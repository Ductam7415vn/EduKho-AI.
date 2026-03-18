<?php

namespace App\Http\Controllers;

use App\Models\BorrowTemplate;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowTemplateController extends Controller
{
    /**
     * Display user's borrow templates
     */
    public function index()
    {
        $templates = Auth::user()->borrowTemplates()
            ->with('equipment')
            ->orderBy('name')
            ->get();

        return view('borrow.templates.index', compact('templates'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $equipments = Equipment::physical()
            ->orderBy('name')
            ->get();

        return view('borrow.templates.create', compact('equipments'));
    }

    /**
     * Store a new template
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'equipment_id' => 'required|exists:equipments,id',
            'quantity' => 'required|integer|min:1|max:50',
            'class_name' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:100',
            'lesson_name' => 'nullable|string|max:255',
            'period' => 'nullable|integer|between:1,10',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['user_id'] = Auth::id();

        BorrowTemplate::create($validated);

        return redirect()
            ->route('borrow.templates.index')
            ->with('success', 'Da luu mau phieu muon.');
    }

    /**
     * Delete a template
     */
    public function destroy(BorrowTemplate $template)
    {
        if ($template->user_id !== Auth::id()) {
            abort(403);
        }

        $template->delete();

        return back()->with('success', 'Da xoa mau phieu muon.');
    }

    /**
     * Get template data for AJAX
     */
    public function getData(BorrowTemplate $template)
    {
        if ($template->user_id !== Auth::id()) {
            abort(403);
        }

        return response()->json([
            'equipment_id' => $template->equipment_id,
            'quantity' => $template->quantity,
            'class_name' => $template->class_name,
            'subject' => $template->subject,
            'lesson_name' => $template->lesson_name,
            'period' => $template->period,
            'notes' => $template->notes,
        ]);
    }
}
