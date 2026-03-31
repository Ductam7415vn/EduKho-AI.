<?php

$file = '/Users/ductampro/Downloads/DANH SÁCH CB,GV,NV 8.2025.xlsx';
if (!file_exists($file)) {
    echo "File not found: $file\n";
    exit(1);
}

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

try {
    $spreadsheet = IOFactory::load($file);
    $worksheet = $spreadsheet->getActiveSheet();
    $highestRow = $worksheet->getHighestRow();
    $highestColumn = $worksheet->getHighestColumn();
    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
    
    echo "Total rows: $highestRow\n";
    echo "Columns: A to $highestColumn\n\n";
    
    // Get headers
    echo "Headers:\n";
    $headers = [];
    for ($col = 1; $col <= $highestColumnIndex; $col++) {
        $value = $worksheet->getCellByColumnAndRow($col, 1)->getValue();
        $headers[$col] = $value;
        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
        echo "$colLetter: $value\n";
    }
    
    echo "\n\nAll teachers data:\n";
    echo "=====================================\n";
    
    $teachers = [];
    for ($row = 2; $row <= $highestRow; $row++) {
        $teacher = [];
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
            $teacher[$headers[$col] ?? "Col$col"] = $value;
        }
        
        // Skip empty rows
        if (empty(trim(implode('', $teacher)))) {
            continue;
        }
        
        $teachers[] = $teacher;
        
        echo "\n--- Row $row ---\n";
        foreach ($teacher as $key => $value) {
            if (!empty($value)) {
                echo "$key: $value\n";
            }
        }
    }
    
    echo "\n\nTotal teachers found: " . count($teachers) . "\n";
    
    // Save as JSON for easier processing
    file_put_contents('teachers_data.json', json_encode($teachers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "\nData saved to teachers_data.json\n";
    
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}