<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentItem;
use App\Models\MaintenanceSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    /**
     * Display maintenance schedules
     */
    public function index(Request $request)
    {
        $query = MaintenanceSchedule::with(['equipmentItem.equipment', 'creator']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $schedules = $query->orderByDesc('scheduled_date')->paginate(20)->withQueryString();

        $stats = [
            'scheduled' => MaintenanceSchedule::scheduled()->count(),
            'in_progress' => MaintenanceSchedule::where('status', 'in_progress')->count(),
            'overdue' => MaintenanceSchedule::overdue()->count(),
            'completed_this_month' => MaintenanceSchedule::where('status', 'completed')
                ->whereMonth('completed_date', now()->month)
                ->count(),
        ];

        return view('admin.maintenance.index', compact('schedules', 'stats'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $equipmentItems = EquipmentItem::with('equipment')
            ->whereIn('status', ['available', 'borrowed'])
            ->orderBy('specific_code')
            ->get();

        return view('admin.maintenance.create', compact('equipmentItems'));
    }

    /**
     * Store a new maintenance schedule
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_item_id' => 'required|exists:equipment_items,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:preventive,corrective,inspection',
            'priority' => 'required|in:low,medium,high,urgent',
            'scheduled_date' => 'required|date|after_or_equal:today',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = 'scheduled';

        MaintenanceSchedule::create($validated);

        return redirect()
            ->route('admin.maintenance.index')
            ->with('success', 'Da len lich bao tri thanh cong.');
    }

    /**
     * Show maintenance schedule details
     */
    public function show(MaintenanceSchedule $maintenance)
    {
        $maintenance->load(['equipmentItem.equipment', 'creator', 'completer']);

        return view('admin.maintenance.show', compact('maintenance'));
    }

    /**
     * Start maintenance
     */
    public function start(MaintenanceSchedule $maintenance)
    {
        if (!$maintenance->isScheduled()) {
            return back()->with('error', 'Lich bao tri nay khong o trang thai cho xu ly.');
        }

        $maintenance->markAsInProgress();

        return back()->with('success', 'Da bat dau quy trinh bao tri.');
    }

    /**
     * Complete maintenance
     */
    public function complete(Request $request, MaintenanceSchedule $maintenance)
    {
        if (!$maintenance->isInProgress()) {
            return back()->with('error', 'Lich bao tri nay chua duoc bat dau.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
            'cost' => 'nullable|numeric|min:0',
        ]);

        $maintenance->markAsCompleted(Auth::user(), $validated['notes'] ?? null, $validated['cost'] ?? null);

        return redirect()
            ->route('admin.maintenance.index')
            ->with('success', 'Da hoan thanh bao tri.');
    }

    /**
     * Cancel maintenance
     */
    public function cancel(MaintenanceSchedule $maintenance)
    {
        if ($maintenance->isCompleted()) {
            return back()->with('error', 'Khong the huy lich bao tri da hoan thanh.');
        }

        $maintenance->update(['status' => 'cancelled']);

        return back()->with('success', 'Da huy lich bao tri.');
    }
}
