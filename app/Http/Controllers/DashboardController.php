<?php

namespace App\Http\Controllers;

use App\Models\BorrowRecord;
use App\Models\Equipment;
use App\Models\EquipmentItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->teacherDashboard();
    }

    /**
     * Admin dashboard with overview statistics
     */
    private function adminDashboard()
    {
        $stats = [
            'total_equipment' => Equipment::count(),
            'total_items' => EquipmentItem::count(),
            'available_items' => EquipmentItem::available()->count(),
            'borrowed_items' => EquipmentItem::borrowed()->count(),
            'pending_approvals' => BorrowRecord::pending()->count(),
            'overdue_records' => BorrowRecord::overdue()->count(),
            'active_teachers' => User::teachers()->active()->count(),
        ];

        $recentBorrows = BorrowRecord::with(['user', 'details.equipmentItem.equipment'])
            ->latest()
            ->take(10)
            ->get();

        $pendingApprovals = BorrowRecord::with(['user', 'details.equipmentItem.equipment'])
            ->pending()
            ->latest()
            ->take(5)
            ->get();

        // Chart data: Monthly borrow trends (last 6 months)
        $monthlyBorrows = $this->getMonthlyBorrowStats(BorrowRecord::query());

        // Chart data: Equipment by category/subject
        $equipmentBySubject = Equipment::select('category_subject', DB::raw('COUNT(*) as count'))
            ->groupBy('category_subject')
            ->get();

        // Chart data: Equipment status distribution
        $statusDistribution = EquipmentItem::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // Chart data: Top borrowed equipment
        $topBorrowed = Equipment::withCount(['items as borrow_count' => function ($query) {
                $query->whereHas('borrowDetails');
            }])
            ->orderByDesc('borrow_count')
            ->take(5)
            ->get();

        $chartData = [
            'monthlyBorrows' => $monthlyBorrows,
            'equipmentBySubject' => $equipmentBySubject,
            'statusDistribution' => $statusDistribution,
            'topBorrowed' => $topBorrowed,
        ];

        return view('dashboard.admin', compact('stats', 'recentBorrows', 'pendingApprovals', 'chartData'));
    }

    /**
     * Teacher dashboard with personal stats
     */
    private function teacherDashboard()
    {
        $user = Auth::user();

        $myBorrows = $user->borrowRecords()
            ->with(['details.equipmentItem.equipment'])
            ->latest()
            ->take(10)
            ->get();

        $activeBorrows = $user->borrowRecords()
            ->active()
            ->with(['details.equipmentItem.equipment'])
            ->get();

        $myPlans = $user->teachingPlans()
            ->with('equipment')
            ->where('planned_date', '>=', now())
            ->orderBy('planned_date')
            ->take(5)
            ->get();

        // Personal stats
        $stats = [
            'total_borrows' => $user->borrowRecords()->count(),
            'active_borrows' => $user->borrowRecords()->active()->count(),
            'returned_borrows' => $user->borrowRecords()->where('status', 'returned')->count(),
            'pending_approvals' => $user->borrowRecords()->pending()->count(),
        ];

        // Monthly borrow chart data (last 6 months)
        $monthlyBorrows = $this->getMonthlyBorrowStats($user->borrowRecords());

        return view('dashboard.teacher', compact('myBorrows', 'activeBorrows', 'myPlans', 'stats', 'monthlyBorrows'));
    }

    /**
     * Build monthly borrow stats with SQL compatible across SQLite/MySQL.
     */
    private function getMonthlyBorrowStats($query)
    {
        $driver = DB::connection()->getDriverName();
        $monthExpr = $driver === 'sqlite'
            ? "CAST(strftime('%m', borrow_date) AS INTEGER)"
            : 'MONTH(borrow_date)';
        $yearExpr = $driver === 'sqlite'
            ? "CAST(strftime('%Y', borrow_date) AS INTEGER)"
            : 'YEAR(borrow_date)';

        return $query
            ->select(
                DB::raw("{$monthExpr} as month"),
                DB::raw("{$yearExpr} as year"),
                DB::raw('COUNT(*) as count')
            )
            ->where('borrow_date', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
    }
}
