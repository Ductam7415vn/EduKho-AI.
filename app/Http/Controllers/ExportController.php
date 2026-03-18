<?php

namespace App\Http\Controllers;

use App\Models\BorrowRecord;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /**
     * Export equipment list to CSV
     */
    public function exportEquipment(Request $request): StreamedResponse
    {
        $filename = 'thiet_bi_' . date('Y-m-d_His') . '.csv';

        return new StreamedResponse(function () use ($request) {
            $handle = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Headers
            fputcsv($handle, [
                'ID',
                'Ma thiet bi',
                'Ten thiet bi',
                'Mon hoc',
                'Khoi lop',
                'Don vi',
                'Gia',
                'Xuat xu',
                'Loai',
                'Muc an ninh',
                'Tong so',
                'San sang',
                'Tags',
            ]);

            // Data
            Equipment::with('items')
                ->orderBy('name')
                ->chunk(100, function ($equipments) use ($handle) {
                    foreach ($equipments as $equipment) {
                        fputcsv($handle, [
                            $equipment->id,
                            $equipment->base_code,
                            $equipment->name,
                            $equipment->category_subject,
                            $equipment->grade_level,
                            $equipment->unit,
                            $equipment->price,
                            $equipment->origin,
                            $equipment->is_digital ? 'Hoc lieu so' : 'Thiet bi vat ly',
                            $equipment->security_level,
                            $equipment->totalCount(),
                            $equipment->availableCount(),
                            $equipment->tags,
                        ]);
                    }
                });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    /**
     * Export borrow records to CSV
     */
    public function exportBorrows(Request $request): StreamedResponse
    {
        $user = Auth::user();
        $filename = 'phieu_muon_' . date('Y-m-d_His') . '.csv';

        return new StreamedResponse(function () use ($request, $user) {
            $handle = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Headers
            fputcsv($handle, [
                'Ma phieu',
                'Nguoi muon',
                'Email',
                'Thiet bi',
                'So luong',
                'Lop',
                'Mon hoc',
                'Ten bai hoc',
                'Tiet',
                'Ngay muon',
                'Han tra',
                'Ngay tra thuc te',
                'Trang thai',
                'Phe duyet',
                'Ghi chu',
            ]);

            // Query based on user role
            $query = $user->isAdmin()
                ? BorrowRecord::query()
                : $user->borrowRecords();

            $query->with(['user', 'details.equipmentItem.equipment'])
                ->orderByDesc('created_at')
                ->chunk(100, function ($records) use ($handle) {
                    foreach ($records as $record) {
                        $equipmentNames = $record->details
                            ->map(fn($d) => $d->equipmentItem->equipment->name)
                            ->implode(', ');

                        $statusLabels = [
                            'active' => 'Dang muon',
                            'returned' => 'Da tra',
                            'overdue' => 'Qua han',
                        ];

                        $approvalLabels = [
                            'pending' => 'Cho duyet',
                            'approved' => 'Da duyet',
                            'auto_approved' => 'Tu dong duyet',
                            'rejected' => 'Tu choi',
                        ];

                        fputcsv($handle, [
                            str_pad($record->id, 6, '0', STR_PAD_LEFT),
                            $record->user->name,
                            $record->user->email,
                            $equipmentNames,
                            $record->details->count(),
                            $record->class_name,
                            $record->subject,
                            $record->lesson_name,
                            $record->period,
                            $record->borrow_date->format('d/m/Y'),
                            $record->expected_return_date->format('d/m/Y'),
                            $record->actual_return_date?->format('d/m/Y') ?? '',
                            $statusLabels[$record->status] ?? $record->status,
                            $approvalLabels[$record->approval_status] ?? $record->approval_status,
                            $record->notes,
                        ]);
                    }
                });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}
