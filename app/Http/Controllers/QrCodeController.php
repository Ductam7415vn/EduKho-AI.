<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Response;

class QrCodeController extends Controller
{
    /**
     * Generate QR code for equipment
     */
    public function equipment(Equipment $equipment): Response
    {
        $url = route('equipment.show', $equipment);
        
        // Get QR code image directly from API
        $size = 220;
        $encodedData = urlencode($url);
        $qrApiUrl = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$encodedData}&format=png";
        
        // Try to fetch the QR code image
        $context = stream_context_create([
            'http' => [
                'timeout' => 5,
                'ignore_errors' => true,
            ]
        ]);
        
        $qrImage = @file_get_contents($qrApiUrl, false, $context);
        
        if ($qrImage === false || empty($qrImage)) {
            // Fallback to SVG if API fails
            $svg = $this->generateQrSvg($url, $equipment->name);
            return response($svg, 200, [
                'Content-Type' => 'image/svg+xml',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }
        
        return response($qrImage, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    /**
     * Generate a simple QR code as SVG
     * Using qr-server.com API which is free and reliable
     */
    private function generateQrSvg(string $data, string $title = ''): string
    {
        // Encode data for URL
        $encodedData = urlencode($data);
        $size = 200;

        // Use qr-server.com API which is more reliable than Google Charts
        $qrApiUrl = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$encodedData}";

        // Create SVG wrapper with embedded image
        $svg = <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="{$size}" height="{$size}" viewBox="0 0 {$size} {$size}">
    <rect width="100%" height="100%" fill="white"/>
    <image href="{$qrApiUrl}" width="{$size}" height="{$size}"/>
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
