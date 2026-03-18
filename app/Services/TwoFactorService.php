<?php

namespace App\Services;

use App\Models\User;

class TwoFactorService
{
    /**
     * Generate a random secret key for TOTP
     */
    public function generateSecret(): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < 16; $i++) {
            $secret .= $chars[random_int(0, 31)];
        }
        return $secret;
    }

    /**
     * Generate a TOTP code for the given secret
     */
    public function generateCode(string $secret): string
    {
        $timeSlice = floor(time() / 30);
        return $this->getCode($secret, $timeSlice);
    }

    /**
     * Verify a TOTP code
     */
    public function verifyCode(string $secret, string $code): bool
    {
        $timeSlice = floor(time() / 30);

        // Check current time slice and ±1 for clock drift
        for ($i = -1; $i <= 1; $i++) {
            if ($this->getCode($secret, $timeSlice + $i) === $code) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate OTP Auth URI for QR code
     */
    public function getQrCodeUri(User $user, string $secret): string
    {
        $issuer = config('app.name', 'QLTHB');
        $label = rawurlencode($issuer) . ':' . rawurlencode($user->email);

        return sprintf(
            'otpauth://totp/%s?secret=%s&issuer=%s&algorithm=SHA1&digits=6&period=30',
            $label,
            $secret,
            rawurlencode($issuer)
        );
    }

    /**
     * Get QR code image URL using Google Charts API
     */
    public function getQrCodeUrl(User $user, string $secret): string
    {
        $uri = $this->getQrCodeUri($user, $secret);
        return 'https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl=' . urlencode($uri);
    }

    /**
     * Calculate TOTP code for a given time slice
     */
    private function getCode(string $secret, int $timeSlice): string
    {
        // Decode base32 secret
        $secretKey = $this->base32Decode($secret);

        // Pack time slice as 64-bit big-endian
        $time = pack('N*', 0) . pack('N*', $timeSlice);

        // Generate HMAC-SHA1
        $hmac = hash_hmac('sha1', $time, $secretKey, true);

        // Extract dynamic offset
        $offset = ord(substr($hmac, -1)) & 0x0F;

        // Extract 4 bytes at offset
        $hashPart = substr($hmac, $offset, 4);

        // Convert to integer (big-endian)
        $value = unpack('N', $hashPart)[1];

        // Apply 31-bit mask
        $value = $value & 0x7FFFFFFF;

        // Generate 6-digit code
        $code = $value % 1000000;

        return str_pad($code, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Decode Base32 string
     */
    private function base32Decode(string $input): string
    {
        $map = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $input = strtoupper($input);
        $input = str_replace('=', '', $input);

        $buffer = 0;
        $bufferSize = 0;
        $result = '';

        for ($i = 0; $i < strlen($input); $i++) {
            $value = strpos($map, $input[$i]);
            if ($value === false) {
                continue;
            }

            $buffer = ($buffer << 5) | $value;
            $bufferSize += 5;

            if ($bufferSize >= 8) {
                $bufferSize -= 8;
                $result .= chr(($buffer >> $bufferSize) & 0xFF);
            }
        }

        return $result;
    }
}
