<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Response;

class QrCodeController extends Controller
{
    /**
     * Generate QR code SVG for equipment
     */
    public function equipment(Equipment $equipment): Response
    {
        $url = route('equipment.show', $equipment);
        $svg = $this->generateQrSvg($url, $equipment->name);

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    /**
     * Generate a simple QR code as SVG
     * Using a basic QR code matrix algorithm
     */
    private function generateQrSvg(string $data, string $title = ''): string
    {
        // Encode data for URL
        $encodedData = urlencode($data);
        $size = 200;

        // Use Google Charts API to generate QR code (simple approach)
        // In production, you might want to use a proper QR code library
        $googleChartUrl = "https://chart.googleapis.com/chart?cht=qr&chs={$size}x{$size}&chl={$encodedData}&choe=UTF-8";

        // Create SVG wrapper with embedded image
        $svg = <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="{$size}" height="{$size}" viewBox="0 0 {$size} {$size}">
    <rect width="100%" height="100%" fill="white"/>
    <image href="{$googleChartUrl}" width="{$size}" height="{$size}"/>
</svg>
SVG;

        return $svg;
    }

    /**
     * Show QR code page for printing
     */
    public function equipmentPrint(Equipment $equipment)
    {
        return view('equipment.qr-print', compact('equipment'));
    }
}
