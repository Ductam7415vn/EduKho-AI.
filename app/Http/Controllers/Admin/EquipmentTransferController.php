<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentItem;
use App\Models\EquipmentTransfer;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentTransferController extends Controller
{
    public function index(Request $request)
    {
        $query = EquipmentTransfer::with([
            'equipmentItem.equipment',
            'fromRoom',
            'toRoom',
            'transferredBy',
        ]);

        if ($request->filled('equipment_item_id')) {
            $query->where('equipment_item_id', $request->equipment_item_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('transfer_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('transfer_date', '<=', $request->to_date);
        }

        $transfers = $query->latest('transfer_date')->paginate(20);

        return view('admin.transfers.index', compact('transfers'));
    }

    public function create(Request $request)
    {
        $equipmentItems = EquipmentItem::with(['equipment', 'room'])
            ->whereIn('status', ['available', 'maintenance'])
            ->get();

        $rooms = Room::orderBy('name')->get();

        $selectedItem = $request->has('item')
            ? EquipmentItem::with(['equipment', 'room'])->find($request->item)
            : null;

        return view('admin.transfers.create', compact('equipmentItems', 'rooms', 'selectedItem'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_item_id' => 'required|exists:equipment_items,id',
            'to_room_id' => 'required|exists:rooms,id',
            'transfer_date' => 'required|date|before_or_equal:today',
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $equipmentItem = EquipmentItem::findOrFail($validated['equipment_item_id']);
        $fromRoomId = $equipmentItem->room_id;

        // Don't transfer to same room
        if ($fromRoomId == $validated['to_room_id']) {
            return back()->withInput()->with('error', 'Thiet bi da o phong nay.');
        }

        // Create transfer record
        EquipmentTransfer::create([
            'equipment_item_id' => $validated['equipment_item_id'],
            'from_room_id' => $fromRoomId,
            'to_room_id' => $validated['to_room_id'],
            'transferred_by' => Auth::id(),
            'transfer_date' => $validated['transfer_date'],
            'reason' => $validated['reason'],
            'notes' => $validated['notes'],
        ]);

        // Update equipment item's room
        $equipmentItem->update(['room_id' => $validated['to_room_id']]);

        return redirect()
            ->route('admin.transfers.index')
            ->with('success', 'Da chuyen thiet bi thanh cong.');
    }

    public function show(EquipmentTransfer $transfer)
    {
        $transfer->load([
            'equipmentItem.equipment',
            'fromRoom',
            'toRoom',
            'transferredBy',
        ]);

        return view('admin.transfers.show', compact('transfer'));
    }

    public function itemHistory(EquipmentItem $equipmentItem)
    {
        $equipmentItem->load(['equipment', 'room']);

        $transfers = EquipmentTransfer::with(['fromRoom', 'toRoom', 'transferredBy'])
            ->where('equipment_item_id', $equipmentItem->id)
            ->latest('transfer_date')
            ->get();

        return view('admin.transfers.item-history', compact('equipmentItem', 'transfers'));
    }
}
