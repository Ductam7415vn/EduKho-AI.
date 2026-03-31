<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BorrowDetail;
use App\Models\Equipment;
use App\Models\EquipmentItem;
use App\Models\MaintenanceSchedule;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EquipmentController extends Controller
{
    /**
     * Display equipment catalog
     */
    public function index(Request $request)
    {
        $query = Equipment::with('items');

        // Filter by subject
        if ($request->filled('subject')) {
            $query->bySubject($request->subject);
        }

        // Filter by grade
        if ($request->filled('grade')) {
            $query->byGrade($request->grade);
        }

        // Filter by type (physical/digital)
        if ($request->type === 'physical') {
            $query->physical();
        } elseif ($request->type === 'digital') {
            $query->digital();
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $equipments = $query->paginate(20);

        $subjects = Equipment::distinct()->pluck('category_subject');

        return view('equipment.index', compact('equipments', 'subjects'));
    }

    /**
     * Display single equipment details
     */
    public function show(Equipment $equipment)
    {
        $equipment->load(['items.room', 'inventoryLogs.performer']);

        return view('equipment.show', compact('equipment'));
    }

    /**
     * Show form for creating new equipment (Admin only)
     */
    public function create()
    {
        $rooms = Room::all();

        return view('equipment.create', compact('rooms'));
    }

    /**
     * Store new equipment (Admin only)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'base_code' => 'required|string|max:50|unique:equipments',
            'unit' => 'required|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'origin' => 'nullable|string|max:255',
            'category_subject' => 'required|string|max:100',
            'grade_level' => 'required|string|max:50',
            'is_digital' => 'boolean',
            'security_level' => 'required|in:normal,high_security',
            'is_fixed_asset' => 'boolean',
            'file_url' => 'nullable|url',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'initial_quantity' => 'nullable|integer|min:0',
            'room_id' => 'nullable|exists:rooms,id',
        ]);

        $validated['is_digital'] = $request->boolean('is_digital');
        $validated['is_fixed_asset'] = $request->boolean('is_fixed_asset');

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('equipment', 'public');
            $validated['image'] = $imagePath;
        }

        $equipment = Equipment::create($validated);

        // Create initial equipment items if quantity provided
        if ($request->filled('initial_quantity') && !$validated['is_digital']) {
            for ($i = 1; $i <= $request->initial_quantity; $i++) {
                EquipmentItem::create([
                    'equipment_id' => $equipment->id,
                    'room_id' => $request->room_id,
                    'specific_code' => "{$equipment->base_code}.{$i}",
                    'status' => 'available',
                    'year_acquired' => now()->year,
                ]);
            }
        }

        return redirect()
            ->route('equipment.show', $equipment)
            ->with('success', 'Thêm thiết bị thành công.');
    }

    /**
     * Show form for editing equipment (Admin only)
     */
    public function edit(Equipment $equipment)
    {
        $rooms = Room::all();

        return view('equipment.edit', compact('equipment', 'rooms'));
    }

    /**
     * Update equipment (Admin only)
     */
    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'base_code' => 'required|string|max:50|unique:equipments,base_code,' . $equipment->id,
            'unit' => 'required|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'origin' => 'nullable|string|max:255',
            'category_subject' => 'required|string|max:100',
            'grade_level' => 'required|string|max:50',
            'is_digital' => 'boolean',
            'security_level' => 'required|in:normal,high_security',
            'is_fixed_asset' => 'boolean',
            'file_url' => 'nullable|url',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['is_digital'] = $request->boolean('is_digital');
        $validated['is_fixed_asset'] = $request->boolean('is_fixed_asset');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($equipment->image && Storage::disk('public')->exists($equipment->image)) {
                Storage::disk('public')->delete($equipment->image);
            }
            
            $imagePath = $request->file('image')->store('equipment', 'public');
            $validated['image'] = $imagePath;
        }

        $equipment->update($validated);

        return redirect()
            ->route('equipment.show', $equipment)
            ->with('success', 'Cập nhật thiết bị thành công.');
    }

    /**
     * Delete equipment (Admin only)
     */
    public function destroy(Equipment $equipment)
    {
        // Check if any items are currently borrowed
        if ($equipment->items()->borrowed()->exists()) {
            return back()->with('error', 'Không thể xóa thiết bị đang được mượn.');
        }

        $equipment->delete();

        return redirect()
            ->route('equipment.index')
            ->with('success', 'Xóa thiết bị thành công.');
    }

    /**
     * Display equipment history/timeline
     */
    public function history(Equipment $equipment)
    {
        $equipment->load('items');

        // Collect all events into a timeline
        $timeline = collect();

        // Add borrow history
        $borrowDetails = BorrowDetail::whereHas('equipmentItem', function ($q) use ($equipment) {
            $q->where('equipment_id', $equipment->id);
        })->with(['borrowRecord.user', 'equipmentItem'])->get();

        foreach ($borrowDetails as $detail) {
            $timeline->push([
                'type' => 'borrow',
                'date' => $detail->borrowRecord->borrow_date,
                'title' => 'Muon thiet bi',
                'description' => "Muon boi {$detail->borrowRecord->user->name} - Lop {$detail->borrowRecord->class_name}",
                'status' => $detail->borrowRecord->status,
                'icon' => 'clipboard',
                'color' => 'blue',
            ]);

            if ($detail->borrowRecord->actual_return_date) {
                $timeline->push([
                    'type' => 'return',
                    'date' => $detail->borrowRecord->actual_return_date,
                    'title' => 'Tra thiet bi',
                    'description' => "Tra boi {$detail->borrowRecord->user->name}" .
                        ($detail->condition_after !== 'good' ? " - Tinh trang: {$detail->condition_after}" : ''),
                    'icon' => 'check-circle',
                    'color' => 'green',
                ]);
            }
        }

        // Add inventory logs
        foreach ($equipment->inventoryLogs as $log) {
            $timeline->push([
                'type' => 'inventory',
                'date' => $log->action_date,
                'title' => $log->type === 'increase' ? 'Tang kho' : 'Giam kho',
                'description' => "{$log->quantity} {$equipment->unit} - {$log->reason}",
                'icon' => $log->type === 'increase' ? 'plus-circle' : 'minus-circle',
                'color' => $log->type === 'increase' ? 'green' : 'red',
            ]);
        }

        // Add maintenance history
        $maintenances = MaintenanceSchedule::whereHas('equipmentItem', function ($q) use ($equipment) {
            $q->where('equipment_id', $equipment->id);
        })->with('equipmentItem')->get();

        foreach ($maintenances as $maintenance) {
            $timeline->push([
                'type' => 'maintenance',
                'date' => $maintenance->completed_date ?? $maintenance->scheduled_date,
                'title' => 'Bao tri: ' . $maintenance->title,
                'description' => "Ca the: {$maintenance->equipmentItem->specific_code} - {$maintenance->status}",
                'icon' => 'cog',
                'color' => $maintenance->status === 'completed' ? 'green' : 'yellow',
            ]);
        }

        // Sort by date descending
        $timeline = $timeline->sortByDesc('date')->values();

        return view('equipment.history', compact('equipment', 'timeline'));
    }
}
