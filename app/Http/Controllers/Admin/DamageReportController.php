<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DamageReport;
use App\Models\EquipmentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DamageReportController extends Controller
{
    public function index(Request $request)
    {
        $query = DamageReport::with(['equipmentItem.equipment', 'reporter', 'resolver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        $reports = $query->latest()->paginate(20);

        $stats = [
            'pending' => DamageReport::pending()->count(),
            'resolved' => DamageReport::resolved()->count(),
            'total_cost' => DamageReport::whereNotNull('estimated_cost')->sum('estimated_cost'),
        ];

        return view('admin.damage-reports.index', compact('reports', 'stats'));
    }

    public function create(Request $request)
    {
        $equipmentItems = EquipmentItem::with('equipment')
            ->whereIn('status', ['borrowed', 'available', 'maintenance'])
            ->get();

        $selectedItem = $request->has('item')
            ? EquipmentItem::with('equipment')->find($request->item)
            : null;

        return view('admin.damage-reports.create', compact('equipmentItems', 'selectedItem'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_item_id' => 'required|exists:equipment_items,id',
            'incident_date' => 'required|date|before_or_equal:today',
            'severity' => 'required|in:minor,moderate,severe,total_loss',
            'description' => 'required|string|max:1000',
            'cause' => 'nullable|string|max:500',
            'estimated_cost' => 'nullable|numeric|min:0',
            'borrow_record_id' => 'nullable|exists:borrow_records,id',
        ]);

        $report = DamageReport::create([
            ...$validated,
            'reported_by' => Auth::id(),
            'status' => 'reported',
        ]);

        // Update equipment item status to maintenance
        $report->equipmentItem->update(['status' => 'maintenance']);

        return redirect()
            ->route('admin.damage-reports.show', $report)
            ->with('success', 'Da tao bao cao hu hong.');
    }

    public function show(DamageReport $damageReport)
    {
        $damageReport->load([
            'equipmentItem.equipment',
            'reporter',
            'resolver',
            'borrowRecord.user',
        ]);

        return view('admin.damage-reports.show', compact('damageReport'));
    }

    public function investigate(DamageReport $damageReport)
    {
        if (!$damageReport->isPending()) {
            return back()->with('error', 'Bao cao nay da duoc xu ly.');
        }

        $damageReport->markAsInvestigating();

        return back()->with('success', 'Da chuyen trang thai dieu tra.');
    }

    public function resolve(Request $request, DamageReport $damageReport)
    {
        if (!$damageReport->isPending()) {
            return back()->with('error', 'Bao cao nay da duoc xu ly.');
        }

        $validated = $request->validate([
            'resolution_type' => 'required|in:resolved,written_off',
            'resolution_notes' => 'required|string|max:1000',
        ]);

        $damageReport->resolve(
            Auth::user(),
            $validated['resolution_notes'],
            $validated['resolution_type']
        );

        return redirect()
            ->route('admin.damage-reports.index')
            ->with('success', 'Da xu ly bao cao hu hong.');
    }
}
