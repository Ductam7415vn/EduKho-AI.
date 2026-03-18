<?php

namespace App\Http\Controllers;

use App\Models\BorrowRecord;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Perform global search across equipment and borrow records
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (empty($query)) {
            return view('search.results', [
                'query' => '',
                'equipments' => collect(),
                'borrows' => collect(),
            ]);
        }

        $user = Auth::user();

        // Search equipment by name, code, category, tags
        $equipments = Equipment::query()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('base_code', 'like', "%{$query}%")
                  ->orWhere('category_subject', 'like', "%{$query}%")
                  ->orWhere('tags', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->limit(20)
            ->get();

        // Search borrow records
        $borrowQuery = BorrowRecord::query()
            ->with(['user', 'details.equipmentItem.equipment']);

        // Teachers see only their own borrows; admins see all
        if (!$user->isAdmin()) {
            $borrowQuery->where('user_id', $user->id);
        }

        $borrows = $borrowQuery
            ->where(function ($q) use ($query) {
                // Search by ID
                if (is_numeric($query)) {
                    $q->where('id', $query);
                }

                // Search by lesson name, class, subject
                $q->orWhere('lesson_name', 'like', "%{$query}%")
                  ->orWhere('class_name', 'like', "%{$query}%")
                  ->orWhere('subject', 'like', "%{$query}%")
                  ->orWhere('notes', 'like', "%{$query}%");

                // Search by user name (for admins)
                $q->orWhereHas('user', function ($userQ) use ($query) {
                    $userQ->where('name', 'like', "%{$query}%");
                });
            })
            ->latest()
            ->limit(20)
            ->get();

        return view('search.results', [
            'query' => $query,
            'equipments' => $equipments,
            'borrows' => $borrows,
        ]);
    }
}
