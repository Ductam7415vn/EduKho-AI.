<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BorrowRecord;
use App\Models\BorrowDetail;
use App\Models\Equipment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BorrowApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();

        $query = $user->isAdmin()
            ? BorrowRecord::query()
            : $user->borrowRecords();

        $query->with(['user', 'details.equipmentItem.equipment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $borrows = $query->latest()->paginate(20);

        return response()->json($borrows);
    }

    public function store(Request $request): JsonResponse
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
        $availableItems = $equipment->items()->available()->take($validated['quantity'])->get();

        if ($availableItems->count() < $validated['quantity']) {
            return response()->json([
                'message' => "Chi con {$availableItems->count()} {$equipment->unit} kha dung.",
            ], 422);
        }

        $borrowRecord = DB::transaction(function () use ($validated, $availableItems, $equipment) {
            $approvalStatus = $equipment->isHighSecurity() ? 'pending' : 'auto_approved';

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

        return response()->json([
            'message' => 'Dang ky muon thanh cong.',
            'data' => $borrowRecord->load('details.equipmentItem.equipment'),
        ], 201);
    }

    public function show(BorrowRecord $borrowRecord): JsonResponse
    {
        $user = Auth::user();

        if (!$user->isAdmin() && $borrowRecord->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $borrowRecord->load([
            'user.department',
            'details.equipmentItem.equipment',
            'details.equipmentItem.room',
            'approver',
        ]);

        return response()->json(['data' => $borrowRecord]);
    }

    public function return(Request $request, BorrowRecord $borrowRecord): JsonResponse
    {
        $user = Auth::user();

        if (!$user->isAdmin() && $borrowRecord->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!$borrowRecord->isActive()) {
            return response()->json(['message' => 'Phieu muon nay khong the tra.'], 422);
        }

        $validated = $request->validate([
            'conditions' => 'required|array',
            'conditions.*' => 'required|in:good,damaged,lost',
            'damage_notes' => 'nullable|array',
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

                $newStatus = match ($condition) {
                    'damaged' => 'maintenance',
                    'lost' => 'lost',
                    default => 'available',
                };

                $detail->equipmentItem->update(['status' => $newStatus]);
            }

            $borrowRecord->markAsReturned();
        });

        return response()->json(['message' => 'Tra thiet bi thanh cong.']);
    }

    public function calendarEvents(Request $request): JsonResponse
    {
        $start = $request->get('start', now()->startOfMonth()->format('Y-m-d'));
        $end = $request->get('end', now()->endOfMonth()->format('Y-m-d'));

        $borrows = BorrowRecord::with(['user', 'details.equipmentItem.equipment'])
            ->whereBetween('borrow_date', [$start, $end])
            ->whereIn('approval_status', ['auto_approved', 'approved', 'pending'])
            ->get();

        $events = $borrows->map(function ($borrow) {
            $equipmentNames = $borrow->details->map(fn($d) => $d->equipmentItem->equipment->name)->join(', ');

            return [
                'id' => $borrow->id,
                'title' => "{$borrow->user->name} - {$equipmentNames}",
                'start' => $borrow->borrow_date->format('Y-m-d'),
                'end' => $borrow->expected_return_date->format('Y-m-d'),
                'color' => match ($borrow->approval_status) {
                    'pending' => '#F59E0B',
                    default => '#3B82F6',
                },
                'extendedProps' => [
                    'period' => $borrow->period,
                    'class_name' => $borrow->class_name,
                    'status' => $borrow->status,
                ],
            ];
        });

        return response()->json($events);
    }

    public function checkConflict(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'borrow_date' => 'required|date',
            'expected_return_date' => 'required|date|after:borrow_date',
            'quantity' => 'required|integer|min:1',
        ]);

        $equipment = Equipment::findOrFail($validated['equipment_id']);

        $conflictingRecords = BorrowRecord::conflictsWith(
            $validated['borrow_date'],
            $validated['expected_return_date']
        )->whereHas('details.equipmentItem', function ($q) use ($equipment) {
            $q->where('equipment_id', $equipment->id);
        })->count();

        $availableCount = $equipment->availableCount();
        $hasConflict = ($availableCount - $conflictingRecords) < $validated['quantity'];

        return response()->json([
            'has_conflict' => $hasConflict,
            'available_count' => $availableCount,
            'conflicting_records' => $conflictingRecords,
        ]);
    }
}
