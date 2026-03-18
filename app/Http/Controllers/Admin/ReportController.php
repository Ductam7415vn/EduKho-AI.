<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BorrowRecord;
use App\Models\Equipment;
use App\Models\EquipmentItem;
use App\Exports\EquipmentExport;
use App\Exports\BorrowTrackingExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        $stats = [
            'total_equipment_types' => Equipment::count(),
            'total_items' => EquipmentItem::count(),
            'total_borrowed' => EquipmentItem::borrowed()->count(),
            'total_borrows_this_month' => BorrowRecord::whereMonth('created_at', now()->month)->count(),
            'overdue_count' => BorrowRecord::overdue()->count(),
            'high_security_items' => Equipment::highSecurity()->withCount('items')->get()->sum('items_count'),
        ];

        return view('admin.reports.index', compact('stats'));
    }

    public function equipmentList(Request $request)
    {
        $query = Equipment::with(['items.room']);

        if ($request->filled('subject')) {
            $query->bySubject($request->subject);
        }

        $equipments = $query->orderBy('name')->get();

        return view('admin.reports.equipment-list', compact('equipments'));
    }

    public function borrowTracking(Request $request)
    {
        $query = BorrowRecord::with(['user.department', 'details.equipmentItem.equipment']);

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('borrow_date', [$request->from, $request->to]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->latest('borrow_date')->paginate(50);

        return view('admin.reports.borrow-tracking', compact('records'));
    }

    public function exportMau01(Request $request)
    {
        $subject = $request->get('subject');
        $filename = 'Mau01_DanhSachThietBi_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new EquipmentExport($subject), $filename);
    }

    public function exportMau02(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));
        $status = $request->get('status');

        $filename = 'Mau02_SoMuonTra_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new BorrowTrackingExport($from, $to, $status), $filename);
    }
}
