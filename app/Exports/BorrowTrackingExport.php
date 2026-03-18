<?php

namespace App\Exports;

use App\Models\BorrowRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BorrowTrackingExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected string $from;
    protected string $to;
    protected ?string $status;

    public function __construct(string $from, string $to, ?string $status = null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->status = $status;
    }

    public function collection()
    {
        $query = BorrowRecord::with(['user.department', 'details.equipmentItem.equipment'])
            ->whereBetween('borrow_date', [$this->from, $this->to]);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->orderBy('borrow_date')->get();
    }

    public function headings(): array
    {
        return [
            'STT',
            'Ngay muon',
            'Giao vien',
            'To chuyen mon',
            'Thiet bi',
            'So luong',
            'Lop',
            'Tiet',
            'Bai day',
            'Han tra',
            'Ngay tra',
            'Trang thai',
            'Tinh trang tra',
        ];
    }

    public function map($record): array
    {
        static $stt = 0;
        $stt++;

        $equipmentNames = $record->details->map(function ($detail) {
            return $detail->equipmentItem->equipment->name;
        })->unique()->implode(', ');

        $conditions = $record->details->map(function ($detail) {
            return $detail->condition_after ?? '-';
        })->implode(', ');

        $statusLabels = [
            'active' => 'Dang muon',
            'returned' => 'Da tra',
            'overdue' => 'Qua han',
        ];

        return [
            $stt,
            $record->borrow_date->format('d/m/Y'),
            $record->user->name,
            $record->user->department?->name ?? '-',
            $equipmentNames,
            $record->details->count(),
            $record->class_name,
            'Tiet ' . $record->period,
            $record->lesson_name ?? '-',
            $record->expected_return_date->format('d/m/Y'),
            $record->actual_return_date?->format('d/m/Y') ?? '-',
            $statusLabels[$record->status] ?? $record->status,
            $conditions,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ],
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            ],
        ];
    }

    public function title(): string
    {
        return 'So theo doi muon tra';
    }
}
