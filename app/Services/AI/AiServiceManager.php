<?php

/**
 * ============================================================
 * AI SERVICE MANAGER - Tự động chọn LLM provider
 * ============================================================
 *
 * File: app/Services/AI/AiServiceManager.php
 *
 * Logic chọn provider:
 * 1. Nếu GEMINI_API_KEY có giá trị → dùng Gemini
 * 2. Nếu không, nếu GROQ_API_KEY có giá trị → dùng Groq
 * 3. Nếu cả hai đều trống → fallback về form thủ công
 *
 * Khi provider chính gặp lỗi, tự động thử provider còn lại.
 */

namespace App\Services\AI;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class AiServiceManager implements LlmServiceInterface
{
    private ?LlmServiceInterface $primary = null;
    private ?LlmServiceInterface $fallback = null;

    public function __construct()
    {
        $geminiKey = config('services.gemini.api_key');
        $groqKey = config('services.groq.api_key');

        // Xác định provider chính và fallback
        if (!empty($geminiKey) && $geminiKey !== 'your_gemini_api_key_here') {
            $this->primary = new GeminiService();

            if (!empty($groqKey) && $groqKey !== 'your_groq_api_key_here') {
                $this->fallback = new GroqService();
            }
        } elseif (!empty($groqKey) && $groqKey !== 'your_groq_api_key_here') {
            $this->primary = new GroqService();
        }
    }

    /**
     * Xử lý yêu cầu: thử provider chính, nếu lỗi thử fallback
     */
    public function processBookingRequest(string $userMessage, User $teacher): array
    {
        // Không có provider nào được cấu hình
        if (!$this->primary) {
            Log::warning('No AI provider configured. Both GEMINI_API_KEY and GROQ_API_KEY are empty.');

            return [
                'success' => false,
                'fallback' => true,
                'error' => 'Chức năng AI chưa được cấu hình. Vui lòng sử dụng form mượn thủ công.',
                'error_code' => 'NO_PROVIDER',
            ];
        }

        // Thử provider chính
        $result = $this->primary->processBookingRequest($userMessage, $teacher);

        // Nếu thành công hoặc không có fallback → trả kết quả
        if ($result['success'] || !$this->fallback) {
            return $result;
        }

        // Provider chính lỗi + có fallback → thử fallback
        Log::info('Primary AI provider failed, trying fallback', [
            'primary_error' => $result['error_code'] ?? 'unknown',
        ]);

        $fallbackResult = $this->fallback->processBookingRequest($userMessage, $teacher);

        if ($fallbackResult['success']) {
            $fallbackResult['used_fallback'] = true;
        }

        return $fallbackResult;
    }

    /**
     * Lấy tên provider đang hoạt động
     */
    public function getActiveProvider(): string
    {
        if (!$this->primary) {
            return 'none';
        }

        return match (true) {
            $this->primary instanceof GeminiService => 'gemini',
            $this->primary instanceof GroqService => 'groq',
            default => 'unknown',
        };
    }

    /**
     * Kiểm tra có provider nào được cấu hình không
     */
    public function hasProvider(): bool
    {
        return $this->primary !== null;
    }
}
