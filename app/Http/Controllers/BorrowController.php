<?php

namespace App\Http\Controllers;

use App\Models\BorrowRecord;
use App\Models\BorrowDetail;
use App\Models\Equipment;
use App\Models\EquipmentItem;
use App\Models\User;
use App\Notifications\BorrowApproved;
use App\Notifications\BorrowRejected;
use App\Notifications\BorrowPendingApproval;
use App\Services\ActivityLogger;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class BorrowController extends Controller
{
    /**
     * Display borrow records for current user
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = $user->isAdmin()
            ? BorrowRecord::query()
            : $user->borrowRecords();

        $query->with(['user', 'details.equipmentItem.equipment', 'approver']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by approval status
        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        $borrows = $query->latest()->paginate(20);

        return view('borrow.index', compact('borrows'));
    }

    /**
     * Show borrow form
     */
    public function create(Request $request)
    {
        $equipments = Equipment::physical()
            ->with(['items' => fn($q) => $q->available()])
            ->get()
            ->filter(fn($eq) => $eq->items->count() > 0);

        // Pre-fill from AI chat if available
        $prefill = $request->session()->get('ai_prefill', []);

        return view('borrow.create', compact('equipments', 'prefill'));
    }

    /**
     * Store a new borrow record
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'quantity' => 'required|integer|min:1',
            'borrow_date' => 'required|date|after_or_equal:today',
            'expected_return_date' => 'required|date|after:borrow_date',
            'period' => 'required|integer|between:1,10',
            'class_name' => 'required|string|max:50',
            'subject' => 'required|string|max:100',
            'lesson_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $equipment = Equipment::findOrFail($validated['equipment_id']);

        // Check availability
        $availableItems = $equipment->items()->available()->take($validated['quantity'])->get();

        if ($availableItems->count() < $validated['quantity']) {
            return back()
                ->withInput()
                ->with('error', "Chỉ còn {$availableItems->count()} {$equipment->unit} khả dụng.");
        }

        // Check for conflicts
        $hasConflict = BorrowRecord::conflictsWith(
            $validated['borrow_date'],
            $validated['expected_return_date']
        )->whereHas('details', function ($q) use ($availableItems) {
            $q->whereIn('equipment_item_id', $availableItems->pluck('id'));
        })->exists();

        if ($hasConflict) {
            return back()
                ->withInput()
                ->with('error', 'Thiết bị đã được đăng ký mượn trong khoảng thời gian này.');
        }

        $borrowRecord = DB::transaction(function () use ($validated, $availableItems, $equipment) {
            // Determine approval status
            $approvalStatus = $equipment->isHighSecurity() ? 'pending' : 'auto_approved';

            // Create borrow record
            $borrowRecord = BorrowRecord::create([
                'user_id' => Auth::id(),
                'lesson_name' => $validated['lesson_name'],
                'period' => $validated['period'],
                'class_name' => $validated['class_name'],
                'subject' => $validated['subject'],
                'borrow_date' => $validated['borrow_date'],
                'expected_return_date' => $validated['expected_return_date'],
                'approval_status' => $approvalStatus,
                'status' => 'active',
                'notes' => $validated['notes'],
            ]);

            // Create borrow details and mark items as borrowed
            foreach ($availableItems as $item) {
                BorrowDetail::create([
                    'borrow_record_id' => $borrowRecord->id,
                    'equipment_item_id' => $item->id,
                    'condition_before' => 'good',
                ]);

                $item->markAsBorrowed();
            }

            return $borrowRecord;
        });

        // Send notification to admins if high-security equipment needs approval
        if ($equipment->isHighSecurity()) {
            $admins = User::where('role', 'admin')->get();
            Notification::send($admins, new BorrowPendingApproval($borrowRecord));
        }

        // Log activity
        ActivityLogger::logBorrowCreate($borrowRecord);

        // Clear AI prefill if any
        $request->session()->forget('ai_prefill');

        $message = $equipment->isHighSecurity()
            ? 'Đăng ký mượn thành công. Phiếu mượn đang chờ phê duyệt từ Ban Giám Hiệu.'
            : 'Đăng ký mượn thành công.';

        return redirect()
            ->route('borrow.index')
            ->with('success', $message);
    }

    /**
     * Show borrow record details
     */
    public function show(BorrowRecord $borrowRecord)
    {
        $this->authorizeView($borrowRecord);

        $borrowRecord->load([
            'user.department',
            'details.equipmentItem.equipment',
            'details.equipmentItem.room',
            'approver',
            'aiChatLog',
        ]);

        return view('borrow.show', compact('borrowRecord'));
    }

    /**
     * Print borrow record as PDF
     */
    public function printPdf(BorrowRecord $borrowRecord)
    {
        $this->authorizeView($borrowRecord);

        $borrowRecord->load([
            'user.department',
            'details.equipmentItem.equipment',
            'approver',
        ]);

        $pdf = Pdf::loadView('pdf.borrow-record', compact('borrowRecord'));
        $pdf->setPaper('A4', 'portrait');

        $filename = 'PhieuMuon_' . str_pad($borrowRecord->id, 6, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Return borrowed items
     */
    public function return(Request $request, BorrowRecord $borrowRecord)
    {
        $this->authorizeView($borrowRecord);

        if (!$borrowRecord->isActive()) {
            return back()->with('error', 'Phiếu mượn này đã được trả hoặc không hợp lệ.');
        }

        $validated = $request->validate([
            'conditions' => 'required|array',
            'conditions.*' => 'required|in:good,damaged,lost',
            'damage_notes' => 'nullable|array',
            'damage_notes.*' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($borrowRecord, $validated) {
            foreach ($borrowRecord->details as $detail) {
                $itemId = $detail->equipment_item_id;
                $condition = $validated['conditions'][$itemId] ?? 'good';
                $notes = $validated['damage_notes'][$itemId] ?? null;

                $detail->update([
                    'condition_after' => $condition,
                    'damage_notes' => $notes,
                ]);

                // Update item status based on return condition
                $newStatus = match ($condition) {
                    'damaged' => 'maintenance',
                    'lost' => 'lost',
                    default => 'available',
                };

                $detail->equipmentItem->update(['status' => $newStatus]);
            }

            $borrowRecord->markAsReturned();
        });

        ActivityLogger::logBorrowReturn($borrowRecord);

        return redirect()
            ->route('borrow.show', $borrowRecord)
            ->with('success', 'Trả thiết bị thành công.');
    }

    /**
     * Display calendar view
     */
    public function calendar()
    {
        return view('borrow.calendar');
    }

    /**
     * Display pending approvals (Admin only)
     */
    public function pendingApprovals()
    {
        $pending = BorrowRecord::pending()
            ->with(['user.department', 'details.equipmentItem.equipment'])
            ->latest()
            ->paginate(20);

        return view('admin.approvals.index', compact('pending'));
    }

    /**
     * Approve a borrow record (Admin only)
     */
    public function approve(BorrowRecord $borrowRecord)
    {
        if (!$borrowRecord->isPending()) {
            return back()->with('error', 'Phiếu mượn này không ở trạng thái chờ duyệt.');
        }

        $borrowRecord->approve(Auth::user());

        ActivityLogger::logBorrowApprove($borrowRecord);

        // Send notification to teacher
        $borrowRecord->user->notify(new BorrowApproved($borrowRecord));

        return back()->with('success', 'Phê duyệt phiếu mượn thành công.');
    }

    /**
     * Reject a borrow record (Admin only)
     */
    public function reject(Request $request, BorrowRecord $borrowRecord)
    {
        if (!$borrowRecord->isPending()) {
            return back()->with('error', 'Phiếu mượn này không ở trạng thái chờ duyệt.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $borrowRecord->reject(Auth::user(), $validated['rejection_reason']);

        ActivityLogger::logBorrowReject($borrowRecord, $validated['rejection_reason']);

        // Release the equipment items
        foreach ($borrowRecord->details as $detail) {
            $detail->equipmentItem->markAsAvailable();
        }

        // Send notification to teacher
        $borrowRecord->user->notify(new BorrowRejected($borrowRecord));

        return back()->with('success', 'Từ chối phiếu mượn thành công.');
    }

    /**
     * Bulk approve multiple borrow records (Admin only)
     */
    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:borrow_records,id',
        ]);

        $approved = 0;
        $skipped = 0;

        foreach ($validated['ids'] as $id) {
            $borrowRecord = BorrowRecord::find($id);

            if (!$borrowRecord || !$borrowRecord->isPending()) {
                $skipped++;
                continue;
            }

            $borrowRecord->approve(Auth::user());
            ActivityLogger::logBorrowApprove($borrowRecord);
            $borrowRecord->user->notify(new BorrowApproved($borrowRecord));
            $approved++;
        }

        $message = "Đã phê duyệt {$approved} phiếu mượn.";
        if ($skipped > 0) {
            $message .= " Bỏ qua {$skipped} phiếu không hợp lệ.";
        }

        return back()->with('success', $message);
    }

    /**
     * Bulk reject multiple borrow records (Admin only)
     */
    public function bulkReject(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:borrow_records,id',
            'rejection_reason' => 'required|string|max:500',
        ]);

        $rejected = 0;
        $skipped = 0;

        foreach ($validated['ids'] as $id) {
            $borrowRecord = BorrowRecord::find($id);

            if (!$borrowRecord || !$borrowRecord->isPending()) {
                $skipped++;
                continue;
            }

            $borrowRecord->reject(Auth::user(), $validated['rejection_reason']);
            ActivityLogger::logBorrowReject($borrowRecord, $validated['rejection_reason']);

            // Release the equipment items
            foreach ($borrowRecord->details as $detail) {
                $detail->equipmentItem->markAsAvailable();
            }

            $borrowRecord->user->notify(new BorrowRejected($borrowRecord));
            $rejected++;
        }

        $message = "Đã từ chối {$rejected} phiếu mượn.";
        if ($skipped > 0) {
            $message .= " Bỏ qua {$skipped} phiếu không hợp lệ.";
        }

        return back()->with('success', $message);
    }

    /**
     * Check if current user can view the borrow record
     */
    private function authorizeView(BorrowRecord $borrowRecord): void
    {
        $user = Auth::user();

        if (!$user->isAdmin() && $borrowRecord->user_id !== $user->id) {
            abort(403);
        }
    }
}
