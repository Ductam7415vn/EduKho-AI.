<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BorrowRecord;
use App\Models\DamageReport;
use App\Models\Equipment;
use App\Models\EquipmentItem;
use App\Models\EquipmentTransfer;
use App\Models\InventoryLog;
use App\Models\MaintenanceSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditReportController extends Controller
{
    public function index()
    {
        return view('admin.audit-reports.index');
    }

    /**
     * Equipment inventory audit report
     */
    public function inventoryAudit(Request $request)
    {
        $validated = $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $fromDate = $validated['from_date'] ?? now()->startOfYear();
        $toDate = $validated['to_date'] ?? now();

        // Equipment statistics
        $equipmentStats = [
            'total_types' => Equipment::count(),
            'total_items' => EquipmentItem::count(),
            'available' => EquipmentItem::where('status', 'available')->count(),
            'borrowed' => EquipmentItem::where('status', 'borrowed')->count(),
            'maintenance' => EquipmentItem::where('status', 'maintenance')->count(),
            'broken' => EquipmentItem::where('status', 'broken')->count(),
            'lost' => EquipmentItem::where('status', 'lost')->count(),
        ];

        // Calculate total value
        $totalValue = Equipment::sum(DB::raw('COALESCE(price, 0) * (SELECT COUNT(*) FROM equipment_items WHERE equipment_items.equipment_id = equipments.id)'));

        // Inventory changes in period
        $inventoryChanges = InventoryLog::whereBetween('action_date', [$fromDate, $toDate])
            ->selectRaw('type, SUM(quantity) as total')
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        // High-value items (top 10)
        $highValueItems = Equipment::orderByDesc('price')
            ->limit(10)
            ->get();

        // Low stock items
        $lowStockItems = Equipment::where('is_digital', false)
            ->get()
            ->filter(fn($e) => $e->isLowStock())
            ->take(10);

        return view('admin.audit-reports.inventory', compact(
            'equipmentStats',
            'totalValue',
            'inventoryChanges',
            'highValueItems',
            'lowStockItems',
            'fromDate',
            'toDate'
        ));
    }

    /**
     * Borrow activity audit report
     */
    public function borrowAudit(Request $request)
    {
        $validated = $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $fromDate = $validated['from_date'] ?? now()->startOfYear();
        $toDate = $validated['to_date'] ?? now();

        // Borrow statistics
        $borrowStats = BorrowRecord::whereBetween('borrow_date', [$fromDate, $toDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Top borrowers
        $topBorrowers = User::whereHas('borrowRecords', function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('borrow_date', [$fromDate, $toDate]);
            })
            ->withCount(['borrowRecords' => function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('borrow_date', [$fromDate, $toDate]);
            }])
            ->orderByDesc('borrow_records_count')
            ->limit(10)
            ->get();

        // Overdue records
        $overdueRecords = BorrowRecord::where('status', 'overdue')
            ->with(['user', 'details.equipmentItem.equipment'])
            ->get();

        $monthlyTrend = $this->buildMonthlyTrend($fromDate, $toDate);

        return view('admin.audit-reports.borrow', compact(
            'borrowStats',
            'topBorrowers',
            'overdueRecords',
            'monthlyTrend',
            'fromDate',
            'toDate'
        ));
    }

    /**
     * Maintenance audit report
     */
    public function maintenanceAudit(Request $request)
    {
        $validated = $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $fromDate = $validated['from_date'] ?? now()->startOfYear();
        $toDate = $validated['to_date'] ?? now();

        // Maintenance statistics
        $maintenanceStats = MaintenanceSchedule::whereBetween('scheduled_date', [$fromDate, $toDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Total maintenance cost
        $totalCost = MaintenanceSchedule::whereBetween('scheduled_date', [$fromDate, $toDate])
            ->sum('actual_cost');

        // Damage reports
        $damageStats = DamageReport::whereBetween('incident_date', [$fromDate, $toDate])
            ->selectRaw('severity, COUNT(*) as count')
            ->groupBy('severity')
            ->get()
            ->keyBy('severity');

        $totalDamageCost = DamageReport::whereBetween('incident_date', [$fromDate, $toDate])
            ->sum('estimated_cost');

        return view('admin.audit-reports.maintenance', compact(
            'maintenanceStats',
            'totalCost',
            'damageStats',
            'totalDamageCost',
            'fromDate',
            'toDate'
        ));
    }

    /**
     * User activity audit report
     */
    public function activityAudit(Request $request)
    {
        $validated = $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $fromDate = $validated['from_date'] ?? now()->startOfMonth();
        $toDate = $validated['to_date'] ?? now();

        // Activity by action type
        $activityByAction = ActivityLog::whereBetween('created_at', [$fromDate, $toDate])
            ->selectRaw('action, COUNT(*) as count')
            ->groupBy('action')
            ->orderByDesc('count')
            ->get();

        // Most active users
        $mostActiveUsers = User::whereHas('activityLogs', function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->withCount(['activityLogs' => function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('created_at', [$fromDate, $toDate]);
            }])
            ->orderByDesc('activity_logs_count')
            ->limit(10)
            ->get();

        // Login attempts
        $loginAttempts = ActivityLog::whereBetween('created_at', [$fromDate, $toDate])
            ->where('action', 'login')
            ->count();

        // Failed logins (if tracked)
        $logoutCount = ActivityLog::whereBetween('created_at', [$fromDate, $toDate])
            ->where('action', 'logout')
            ->count();

        return view('admin.audit-reports.activity', compact(
            'activityByAction',
            'mostActiveUsers',
            'loginAttempts',
            'logoutCount',
            'fromDate',
            'toDate'
        ));
    }

    /**
     * Export audit report as CSV
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'inventory');
        $fromDate = $request->get('from_date', now()->startOfYear()->format('Y-m-d'));
        $toDate = $request->get('to_date', now()->format('Y-m-d'));

        $filename = "audit-report-{$type}-{$fromDate}-to-{$toDate}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($type, $fromDate, $toDate) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            switch ($type) {
                case 'inventory':
                    $this->exportInventoryAudit($handle, $fromDate, $toDate);
                    break;
                case 'borrow':
                    $this->exportBorrowAudit($handle, $fromDate, $toDate);
                    break;
                case 'maintenance':
                    $this->exportMaintenanceAudit($handle, $fromDate, $toDate);
                    break;
                case 'activity':
                    $this->exportActivityAudit($handle, $fromDate, $toDate);
                    break;
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportInventoryAudit($handle, $fromDate, $toDate)
    {
        fputcsv($handle, ['BAO CAO KIEM KE THIET BI']);
        fputcsv($handle, ["Tu ngay: {$fromDate}", "Den ngay: {$toDate}"]);
        fputcsv($handle, []);

        fputcsv($handle, ['Ma thiet bi', 'Ten thiet bi', 'Don vi', 'Tong so', 'San sang', 'Dang muon', 'Bao tri', 'Hong/Mat', 'Gia tri']);

        Equipment::with('items')->chunk(100, function ($equipments) use ($handle) {
            foreach ($equipments as $eq) {
                $items = $eq->items;
                fputcsv($handle, [
                    $eq->base_code,
                    $eq->name,
                    $eq->unit,
                    $items->count(),
                    $items->where('status', 'available')->count(),
                    $items->where('status', 'borrowed')->count(),
                    $items->where('status', 'maintenance')->count(),
                    $items->whereIn('status', ['broken', 'lost'])->count(),
                    number_format($eq->price ?? 0),
                ]);
            }
        });
    }

    private function exportBorrowAudit($handle, $fromDate, $toDate)
    {
        fputcsv($handle, ['BAO CAO MUON TRA']);
        fputcsv($handle, ["Tu ngay: {$fromDate}", "Den ngay: {$toDate}"]);
        fputcsv($handle, []);

        fputcsv($handle, ['Ma phieu', 'Nguoi muon', 'Ngay muon', 'Ngay tra du kien', 'Ngay tra thuc te', 'Trang thai', 'Thiet bi']);

        BorrowRecord::whereBetween('borrow_date', [$fromDate, $toDate])
            ->with(['user', 'details.equipmentItem.equipment'])
            ->chunk(100, function ($records) use ($handle) {
                foreach ($records as $record) {
                    $equipmentNames = $record->details->map(fn($d) => $d->equipmentItem->equipment->name)->join(', ');
                    fputcsv($handle, [
                        $record->id,
                        $record->user->name,
                        $record->borrow_date->format('d/m/Y'),
                        $record->expected_return_date->format('d/m/Y'),
                        $record->actual_return_date?->format('d/m/Y') ?? '',
                        $record->status,
                        $equipmentNames,
                    ]);
                }
            });
    }

    private function exportActivityAudit($handle, $fromDate, $toDate)
    {
        fputcsv($handle, ['BAO CAO HOAT DONG']);
        fputcsv($handle, ["Tu ngay: {$fromDate}", "Den ngay: {$toDate}"]);
        fputcsv($handle, []);

        fputcsv($handle, ['Thoi gian', 'Nguoi dung', 'Hanh dong', 'Doi tuong', 'IP']);

        ActivityLog::whereBetween('created_at', [$fromDate, $toDate])
            ->with('user')
            ->orderBy('created_at')
            ->chunk(100, function ($logs) use ($handle) {
                foreach ($logs as $log) {
                    fputcsv($handle, [
                        $log->created_at->format('d/m/Y H:i:s'),
                        $log->user?->name ?? 'System',
                        $log->action,
                        $log->subject_type ? class_basename($log->subject_type) . ' #' . $log->subject_id : '',
                        $log->ip_address,
                    ]);
                }
            });
    }

    private function exportMaintenanceAudit($handle, $fromDate, $toDate)
    {
        fputcsv($handle, ['BAO CAO BAO TRI']);
        fputcsv($handle, ["Tu ngay: {$fromDate}", "Den ngay: {$toDate}"]);
        fputcsv($handle, []);

        fputcsv($handle, ['Lich bao tri']);
        fputcsv($handle, ['Tieu de', 'Loai', 'Uu tien', 'Ngay du kien', 'Trang thai', 'Chi phi']);
        MaintenanceSchedule::whereBetween('scheduled_date', [$fromDate, $toDate])
            ->orderBy('scheduled_date')
            ->chunk(100, function ($schedules) use ($handle) {
                foreach ($schedules as $schedule) {
                    fputcsv($handle, [
                        $schedule->title,
                        $schedule->type,
                        $schedule->priority,
                        $schedule->scheduled_date->format('d/m/Y'),
                        $schedule->status,
                        $schedule->cost ? number_format($schedule->cost) : '',
                    ]);
                }
            });

        fputcsv($handle, []);
        fputcsv($handle, ['Bao cao hu hong']);
        fputcsv($handle, ['Ngay su co', 'Muc do', 'Trang thai', 'Chi phi uoc tinh', 'Mo ta']);
        DamageReport::whereBetween('incident_date', [$fromDate, $toDate])
            ->orderBy('incident_date')
            ->chunk(100, function ($reports) use ($handle) {
                foreach ($reports as $report) {
                    fputcsv($handle, [
                        $report->incident_date->format('d/m/Y'),
                        $report->severity,
                        $report->status,
                        $report->estimated_cost ? number_format($report->estimated_cost, 2) : '',
                        $report->description,
                    ]);
                }
            });
    }

    private function buildMonthlyTrend($fromDate, $toDate)
    {
        $driver = DB::connection()->getDriverName();
        $monthExpr = $driver === 'sqlite'
            ? "CAST(strftime('%m', borrow_date) AS INTEGER)"
            : 'MONTH(borrow_date)';
        $yearExpr = $driver === 'sqlite'
            ? "CAST(strftime('%Y', borrow_date) AS INTEGER)"
            : 'YEAR(borrow_date)';

        return BorrowRecord::whereBetween('borrow_date', [$fromDate, $toDate])
            ->selectRaw("{$yearExpr} as year, {$monthExpr} as month, COUNT(*) as count")
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($row) {
                $row->label = sprintf('%04d-%02d', $row->year, $row->month);

                return $row;
            });
    }
}
