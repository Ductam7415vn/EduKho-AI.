<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\EquipmentItem;
use App\Models\InventoryLog;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryLog::with(['equipment', 'performer']);

        if ($request->filled('equipment_id')) {
            $query->where('equipment_id', $request->equipment_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->inDateRange($request->from, $request->to);
        }

        $logs = $query->latest()->paginate(20);
        $equipments = Equipment::orderBy('name')->get();

        return view('admin.inventory.index', compact('logs', 'equipments'));
    }

    public function createIncrease()
    {
        $equipments = Equipment::physical()->orderBy('name')->get();
        $rooms = Room::all();

        return view('admin.inventory.increase', compact('equipments', 'rooms'));
    }

    public function createDecrease()
    {
        $equipments = Equipment::physical()
            ->withCount(['items as available_count' => fn($q) => $q->available()])
            ->having('available_count', '>', 0)
            ->orderBy('name')
            ->get();

        return view('admin.inventory.decrease', compact('equipments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'type' => 'required|in:increase,decrease',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:500',
            'document_ref' => 'nullable|string|max:255',
            'action_date' => 'required|date',
            'room_id' => 'required_if:type,increase|nullable|exists:rooms,id',
        ]);

        $equipment = Equipment::findOrFail($validated['equipment_id']);

        DB::transaction(function () use ($validated, $equipment) {
            // Create inventory log
            InventoryLog::create([
                'equipment_id' => $validated['equipment_id'],
                'performed_by' => Auth::id(),
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'reason' => $validated['reason'],
                'document_ref' => $validated['document_ref'],
                'action_date' => $validated['action_date'],
            ]);

            if ($validated['type'] === 'increase') {
                // Create new equipment items
                $existingCount = $equipment->items()->count();

                for ($i = 1; $i <= $validated['quantity']; $i++) {
                    EquipmentItem::create([
                        'equipment_id' => $equipment->id,
                        'room_id' => $validated['room_id'],
                        'specific_code' => "{$equipment->base_code}." . ($existingCount + $i),
                        'status' => 'available',
                        'year_acquired' => now()->year,
                    ]);
                }
            } else {
                // Mark equipment items as lost/disposed
                $itemsToRemove = $equipment->items()
                    ->available()
                    ->take($validated['quantity'])
                    ->get();

                if ($itemsToRemove->count() < $validated['quantity']) {
                    throw new \Exception("Không đủ thiết bị khả dụng để giảm.");
                }

                foreach ($itemsToRemove as $item) {
                    $item->update(['status' => 'lost']); // or create a 'disposed' status
                }
            }
        });

        $message = $validated['type'] === 'increase'
            ? 'Tăng kho thành công.'
            : 'Giảm kho thành công.';

        return redirect()
            ->route('admin.inventory.index')
            ->with('success', $message);
    }
}
