<?php

namespace App\Http\Controllers;

use App\Models\BorrowDetail;
use App\Models\BorrowRecord;
use App\Models\Equipment;
use App\Models\Reservation;
use App\Notifications\BorrowPendingApproval;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = $user->isAdmin()
            ? Reservation::query()
            : Reservation::where('user_id', $user->id);

        $query->with(['user', 'equipment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('reserved_date', $request->date);
        }

        $reservations = $query->latest()->paginate(20);

        return view('reservations.index', compact('reservations'));
    }

    public function create(Request $request)
    {
        $equipments = Equipment::physical()
            ->with(['items' => fn($q) => $q->available()])
            ->get()
            ->filter(fn($eq) => $eq->items->count() > 0);

        $selectedEquipment = $request->has('equipment')
            ? Equipment::find($request->equipment)
            : null;

        return view('reservations.create', compact('equipments', 'selectedEquipment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'quantity' => 'required|integer|min:1',
            'reserved_date' => 'required|date|after:today',
            'period' => 'nullable|integer|between:1,10',
            'class_name' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:100',
            'lesson_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $equipment = Equipment::findOrFail($validated['equipment_id']);

        // Check if equipment has enough items
        $availableCount = $equipment->items()->available()->count();
        if ($availableCount < $validated['quantity']) {
            return back()
                ->withInput()
                ->with('error', "Chi co {$availableCount} {$equipment->unit} kha dung.");
        }

        // Check for existing reservations on the same date
        $existingReservations = Reservation::where('equipment_id', $validated['equipment_id'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('reserved_date', $validated['reserved_date'])
            ->sum('quantity');

        $totalNeeded = $existingReservations + $validated['quantity'];
        $totalItems = $equipment->items()->count();

        if ($totalNeeded > $totalItems) {
            $remaining = $totalItems - $existingReservations;
            return back()
                ->withInput()
                ->with('error', "Chi con {$remaining} {$equipment->unit} co the dat truoc cho ngay nay.");
        }

        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'equipment_id' => $validated['equipment_id'],
            'quantity' => $validated['quantity'],
            'reserved_date' => $validated['reserved_date'],
            'period' => $validated['period'],
            'class_name' => $validated['class_name'],
            'subject' => $validated['subject'],
            'lesson_name' => $validated['lesson_name'],
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Dat truoc thiet bi thanh cong.');
    }

    public function show(Reservation $reservation)
    {
        $this->authorizeView($reservation);
        $reservation->load(['user', 'equipment', 'borrowRecord']);

        return view('reservations.show', compact('reservation'));
    }

    public function cancel(Reservation $reservation)
    {
        $this->authorizeView($reservation);

        if (!in_array($reservation->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Khong the huy dat truoc nay.');
        }

        $reservation->cancel();

        return back()->with('success', 'Da huy dat truoc.');
    }

    public function confirm(Reservation $reservation)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        if (!$reservation->isPending()) {
            return back()->with('error', 'Dat truoc nay khong o trang thai cho xac nhan.');
        }

        $reservation->confirm();

        return back()->with('success', 'Da xac nhan dat truoc.');
    }

    public function convert(Request $request, Reservation $reservation)
    {
        $this->authorizeView($reservation);

        if (!$reservation->canBeConverted()) {
            return back()->with('error', 'Khong the chuyen doi dat truoc nay. Chi co the chuyen doi vao ngay dat truoc.');
        }

        $equipment = $reservation->equipment;

        // Check availability
        $availableItems = $equipment->items()->available()->take($reservation->quantity)->get();

        if ($availableItems->count() < $reservation->quantity) {
            return back()->with('error', "Chi con {$availableItems->count()} {$equipment->unit} kha dung.");
        }

        $borrowRecord = DB::transaction(function () use ($reservation, $equipment, $availableItems) {
            // Determine approval status
            $approvalStatus = $equipment->isHighSecurity() ? 'pending' : 'auto_approved';

            // Create borrow record
            $borrowRecord = BorrowRecord::create([
                'user_id' => $reservation->user_id,
                'lesson_name' => $reservation->lesson_name,
                'period' => $reservation->period,
                'class_name' => $reservation->class_name,
                'subject' => $reservation->subject,
                'borrow_date' => $reservation->reserved_date,
                'expected_return_date' => $reservation->reserved_date->copy()->addDay(),
                'approval_status' => $approvalStatus,
                'status' => 'active',
                'notes' => $reservation->notes,
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

            // Mark reservation as converted
            $reservation->markAsConverted($borrowRecord);

            return $borrowRecord;
        });

        // Send notification to admins if high-security equipment needs approval
        if ($equipment->isHighSecurity()) {
            $admins = User::where('role', 'admin')->get();
            Notification::send($admins, new BorrowPendingApproval($borrowRecord));
        }

        ActivityLogger::logBorrowCreate($borrowRecord);

        return redirect()
            ->route('borrow.show', $borrowRecord)
            ->with('success', 'Da chuyen doi dat truoc thanh phieu muon.');
    }

    private function authorizeView(Reservation $reservation): void
    {
        $user = Auth::user();

        if (!$user->isAdmin() && $reservation->user_id !== $user->id) {
            abort(403);
        }
    }
}
