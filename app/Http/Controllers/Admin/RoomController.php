<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['manager', 'equipmentItems'])
            ->withCount('equipmentItems')
            ->get();

        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $managers = User::where('role', 'admin')->orWhere('role', 'teacher')->get();

        return view('admin.rooms.create', compact('managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'manager_id' => 'nullable|exists:users,id',
            'type' => 'required|in:warehouse,lab',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:0',
        ]);

        Room::create($validated);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Thêm phòng/kho thành công.');
    }

    public function show(Room $room)
    {
        $room->load(['manager', 'equipmentItems.equipment']);

        return view('admin.rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $managers = User::where('role', 'admin')->orWhere('role', 'teacher')->get();

        return view('admin.rooms.edit', compact('room', 'managers'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'manager_id' => 'nullable|exists:users,id',
            'type' => 'required|in:warehouse,lab',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:0',
        ]);

        $room->update($validated);

        return redirect()
            ->route('admin.rooms.show', $room)
            ->with('success', 'Cập nhật phòng/kho thành công.');
    }

    public function destroy(Room $room)
    {
        if ($room->equipmentItems()->exists()) {
            return back()->with('error', 'Không thể xóa phòng đang chứa thiết bị.');
        }

        $room->delete();

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Xóa phòng/kho thành công.');
    }
}
