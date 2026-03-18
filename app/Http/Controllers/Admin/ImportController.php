<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\EquipmentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImportController extends Controller
{
    /**
     * Show import form
     */
    public function showEquipmentForm()
    {
        return view('admin.import.equipment');
    }

    /**
     * Import equipment from CSV
     */
    public function importEquipment(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        // Skip BOM if present
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        // Read header row
        $header = fgetcsv($handle);
        if (!$header) {
            return back()->with('error', 'File CSV trong hoac khong hop le.');
        }

        // Normalize headers
        $header = array_map(function ($h) {
            return strtolower(trim(str_replace([' ', '-'], '_', $h)));
        }, $header);

        // Required columns
        $requiredColumns = ['name', 'base_code', 'unit', 'category_subject', 'grade_level'];
        $missingColumns = array_diff($requiredColumns, $header);

        if (!empty($missingColumns)) {
            fclose($handle);
            return back()->with('error', 'Thieu cot bat buoc: ' . implode(', ', $missingColumns));
        }

        $imported = 0;
        $errors = [];
        $rowNumber = 1;

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;

                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                // Map row to associative array
                $data = array_combine($header, array_pad($row, count($header), null));

                // Validate row
                $validator = Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'base_code' => 'required|string|max:50|unique:equipments,base_code',
                    'unit' => 'required|string|max:50',
                    'category_subject' => 'required|string|max:100',
                    'grade_level' => 'required|string|max:50',
                    'price' => 'nullable|numeric|min:0',
                    'origin' => 'nullable|string|max:255',
                    'security_level' => 'nullable|in:normal,high_security',
                    'quantity' => 'nullable|integer|min:0',
                ]);

                if ($validator->fails()) {
                    $errors[] = "Dong {$rowNumber}: " . implode(', ', $validator->errors()->all());
                    continue;
                }

                // Create equipment
                $equipment = Equipment::create([
                    'name' => $data['name'],
                    'base_code' => $data['base_code'],
                    'unit' => $data['unit'],
                    'category_subject' => $data['category_subject'],
                    'grade_level' => $data['grade_level'],
                    'price' => $data['price'] ?? null,
                    'origin' => $data['origin'] ?? null,
                    'security_level' => $data['security_level'] ?? 'normal',
                    'is_digital' => isset($data['is_digital']) && strtolower($data['is_digital']) === 'true',
                    'is_fixed_asset' => isset($data['is_fixed_asset']) && strtolower($data['is_fixed_asset']) === 'true',
                    'description' => $data['description'] ?? null,
                    'tags' => $data['tags'] ?? null,
                ]);

                // Create equipment items if quantity provided
                $quantity = intval($data['quantity'] ?? 0);
                for ($i = 1; $i <= $quantity; $i++) {
                    EquipmentItem::create([
                        'equipment_id' => $equipment->id,
                        'specific_code' => "{$equipment->base_code}.{$i}",
                        'status' => 'available',
                        'year_acquired' => now()->year,
                    ]);
                }

                $imported++;
            }

            fclose($handle);

            if ($imported === 0 && !empty($errors)) {
                DB::rollBack();
                return back()->with('error', 'Khong import duoc dong nao. Loi: ' . implode('; ', array_slice($errors, 0, 5)));
            }

            DB::commit();

            $message = "Da import thanh cong {$imported} thiet bi.";
            if (!empty($errors)) {
                $message .= ' Co ' . count($errors) . ' loi.';
            }

            return redirect()
                ->route('equipment.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return back()->with('error', 'Loi khi import: ' . $e->getMessage());
        }
    }

    /**
     * Download sample CSV template
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="equipment_template.csv"',
        ];

        $columns = ['name', 'base_code', 'unit', 'category_subject', 'grade_level', 'price', 'origin', 'security_level', 'is_digital', 'is_fixed_asset', 'quantity', 'description', 'tags'];

        $callback = function () use ($columns) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, $columns);
            // Sample row
            fputcsv($handle, [
                'Kinh hien vi',
                'KHV001',
                'Cai',
                'Sinh hoc',
                '10,11,12',
                '5000000',
                'Viet Nam',
                'normal',
                'false',
                'true',
                '5',
                'Kinh hien vi quang hoc',
                'sinh hoc, thuc hanh',
            ]);
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
