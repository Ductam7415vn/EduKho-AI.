<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\TeachingPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeachingPlanController extends Controller
{
    /**
     * Display teaching plans
     */
    public function index()
    {
        $user = Auth::user();

        $plansQuery = $user->isAdmin()
            ? TeachingPlan::query()
            : $user->teachingPlans();

        $plans = $plansQuery
            ->with('equipment')
            ->orderBy('planned_date', 'desc')
            ->paginate(20);

        return view('teaching-plans.index', compact('plans'));
    }

    /**
     * Show form for creating new teaching plan
     */
    public function create()
    {
        $equipments = Equipment::physical()->get();

        return view('teaching-plans.create', compact('equipments'));
    }

    /**
     * Store a new teaching plan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'subject' => 'required|string|max:100',
            'lesson_name' => 'required|string|max:255',
            'period' => 'required|integer|between:1,10',
            'week' => 'required|integer|between:1,52',
            'planned_date' => 'required|date|after_or_equal:today',
            'quantity_needed' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['user_id'] = Auth::id();

        TeachingPlan::create($validated);

        return redirect()
            ->route('teaching-plans.index')
            ->with('success', 'Tạo kế hoạch giảng dạy thành công.');
    }

    /**
     * Show teaching plan details
     */
    public function show(TeachingPlan $teachingPlan)
    {
        $this->authorizePlanAccess($teachingPlan);

        $teachingPlan->load(['equipment', 'borrowRecord']);

        return view('teaching-plans.show', compact('teachingPlan'));
    }

    /**
     * Show form for editing teaching plan
     */
    public function edit(TeachingPlan $teachingPlan)
    {
        $this->authorizePlanAccess($teachingPlan);

        $equipments = Equipment::physical()->get();

        return view('teaching-plans.edit', compact('teachingPlan', 'equipments'));
    }

    /**
     * Update the teaching plan
     */
    public function update(Request $request, TeachingPlan $teachingPlan)
    {
        $this->authorizePlanAccess($teachingPlan);

        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'subject' => 'required|string|max:100',
            'lesson_name' => 'required|string|max:255',
            'period' => 'required|integer|between:1,10',
            'week' => 'required|integer|between:1,52',
            'planned_date' => 'required|date',
            'quantity_needed' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $teachingPlan->update($validated);

        return redirect()
            ->route('teaching-plans.show', $teachingPlan)
            ->with('success', 'Cập nhật kế hoạch thành công.');
    }

    /**
     * Delete the teaching plan
     */
    public function destroy(TeachingPlan $teachingPlan)
    {
        $this->authorizePlanAccess($teachingPlan);

        if ($teachingPlan->hasBorrowRecord()) {
            return back()->with('error', 'Không thể xóa kế hoạch đã có phiếu mượn.');
        }

        $teachingPlan->delete();

        return redirect()
            ->route('teaching-plans.index')
            ->with('success', 'Xóa kế hoạch thành công.');
    }

    /**
     * Authorize access to teaching plan
     */
    private function authorizePlanAccess(TeachingPlan $teachingPlan): void
    {
        if ($teachingPlan->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
    }
}
