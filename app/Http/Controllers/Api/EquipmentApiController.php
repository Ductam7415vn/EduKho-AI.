<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EquipmentApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Equipment::query();

        if ($request->filled('subject')) {
            $query->bySubject($request->subject);
        }

        if ($request->filled('grade')) {
            $query->byGrade($request->grade);
        }

        $equipment = $query->withCount('items')->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $equipment->items(),
            'meta' => [
                'current_page' => $equipment->currentPage(),
                'last_page' => $equipment->lastPage(),
                'per_page' => $equipment->perPage(),
                'total' => $equipment->total(),
            ],
        ]);
    }

    public function show(Equipment $equipment): JsonResponse
    {
        $equipment->load('items');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $equipment->id,
                'name' => $equipment->name,
                'base_code' => $equipment->base_code,
                'unit' => $equipment->unit,
                'price' => $equipment->price,
                'category_subject' => $equipment->category_subject,
                'grade_level' => $equipment->grade_level,
                'is_digital' => $equipment->is_digital,
                'security_level' => $equipment->security_level,
                'available_count' => $equipment->availableCount(),
                'total_count' => $equipment->totalCount(),
            ],
        ]);
    }

    public function checkAvailability(Equipment $equipment, Request $request): JsonResponse
    {
        return $this->availability($equipment, $request);
    }

    public function availability(Equipment $equipment, Request $request): JsonResponse
    {
        $quantity = $request->get('quantity', 1);
        $availableItems = $equipment->items()->available()->take($quantity)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'equipment_id' => $equipment->id,
                'requested_quantity' => $quantity,
                'available_quantity' => $availableItems->count(),
                'is_available' => $availableItems->count() >= $quantity,
            ],
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        $equipment = Equipment::where('name', 'LIKE', "%{$query}%")
            ->orWhere('base_code', 'LIKE', "%{$query}%")
            ->withCount('items')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'query' => $query,
            'data' => $equipment->map(fn($eq) => [
                'id' => $eq->id,
                'name' => $eq->name,
                'base_code' => $eq->base_code,
                'category_subject' => $eq->category_subject,
            ]),
        ]);
    }
}
