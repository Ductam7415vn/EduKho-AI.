<?php

namespace App\Exports;

use App\Models\Equipment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EquipmentExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected ?string $subject;

    public function __construct(?string $subject = null)
    {
        $this->subject = $subject;
    }

    public function collection()
    {
        $query = Equipment::with('items')->physical();

        if ($this->subject) {
            $query->bySubject($this->subject);
        }

        return $query->orderBy('category_subject')->orderBy('name')->get();
    }

    public function headings(): array
    {
        return [
            'STT',
            'Ma thiet bi',
            'Ten thiet bi',
            'Don vi',
            'Mon hoc',
            'Khoi lop',
            'Don gia',
            'Tong so',
            'San sang',
            'Dang muon',
            'Bao tri',
            'Hong/Mat',
            'Cap do',
        ];
    }

    public function map($equipment): array
    {
        static $stt = 0;
        $stt++;

        $items = $equipment->items;

        return [
            $stt,
            $equipment->base_code,
            $equipment->name,
            $equipment->unit,
            $equipment->category_subject,
            $equipment->grade_level,
            number_format($equipment->price ?? 0),
            $items->count(),
            $items->where('status', 'available')->count(),
            $items->where('status', 'borrowed')->count(),
            $items->where('status', 'maintenance')->count(),
            $items->whereIn('status', ['broken', 'lost'])->count(),
            $equipment->security_level === 'high_security' ? 'An ninh cao' : 'Binh thuong',
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
        return 'Danh sach thiet bi';
    }
}
