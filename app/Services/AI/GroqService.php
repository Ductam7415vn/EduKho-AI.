<?php

/**
 * ============================================================
 * GROQ AI SERVICE - Fallback LLM khi Gemini không khả dụng
 * ============================================================
 *
 * File: app/Services/AI/GroqService.php
 *
 * Groq cung cấp API miễn phí với tốc độ cực nhanh (LPU Inference).
 * Sử dụng các model mở như LLaMA 3, Mixtral qua OpenAI-compatible API.
 */

namespace App\Services\AI;

use App\Models\AiChatLog;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService implements LlmServiceInterface
{
    private string $apiKey;
    private string $model;
    private string $baseUrl;
    private int $timeoutSeconds;

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key');
        $this->model = config('services.groq.model', 'llama-3.3-70b-versatile');
        $this->baseUrl = 'https://api.groq.com/openai/v1';
        $this->timeoutSeconds = config('services.groq.timeout', 15);
    }

    /**
     * Xử lý yêu cầu đặt lịch mượn từ ngôn ngữ tự nhiên
     */
    public function processBookingRequest(string $userMessage, User $teacher): array
    {
        $startTime = microtime(true);

        try {
            $equipments = $this->getAvailableEquipments();
            $rooms = $this->getRooms();

            $systemPrompt = SystemPrompt::generate(
                $equipments,
                $rooms,
                now()->format('Y-m-d'),
                $teacher->name
            );

            $response = $this->callGroqApi($systemPrompt, $userMessage);

            $parsed = $this->parseAiResponse($response);

            $validated = $this->validateParsedResult($parsed);

            $responseTimeMs = (int)((microtime(true) - $startTime) * 1000);
            $this->logInteraction($teacher, $userMessage, $response, $validated, 'success', $responseTimeMs, 'groq');

            return [
                'success' => true,
                'data' => $validated,
                'raw_response' => $response,
                'provider' => 'groq',
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Groq API connection error', ['error' => $e->getMessage()]);
            $this->logInteraction($teacher, $userMessage, $e->getMessage(), null, 'error', null, 'groq');

            return [
                'success' => false,
                'fallback' => true,
                'error' => 'Không thể kết nối đến AI. Vui lòng sử dụng form mượn thủ công.',
                'error_code' => 'CONNECTION_ERROR',
            ];

        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error('Groq API request error', [
                'status' => $e->response?->status(),
                'body' => $e->response?->body(),
            ]);
            $this->logInteraction($teacher, $userMessage, $e->getMessage(), null, 'error', null, 'groq');

            return [
                'success' => false,
                'fallback' => true,
                'error' => 'Trợ lý AI đang tạm thời quá tải. Vui lòng thử lại sau hoặc dùng form thủ công.',
                'error_code' => 'API_ERROR',
            ];

        } catch (AiParseException $e) {
            Log::warning('Groq response parse error', ['response' => $e->rawResponse]);
            $this->logInteraction($teacher, $userMessage, $e->rawResponse, null, 'fallback', null, 'groq');

            return [
                'success' => false,
                'fallback' => true,
                'error' => 'Trợ lý AI chưa hiểu rõ yêu cầu. Vui lòng thử diễn đạt khác hoặc dùng form thủ công.',
                'error_code' => 'PARSE_ERROR',
            ];
        }
    }

    /**
     * Gọi Groq API (OpenAI-compatible format)
     */
    private function callGroqApi(string $systemPrompt, string $userMessage): string
    {
        $response = Http::timeout($this->timeoutSeconds)
            ->withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])
            ->post("{$this->baseUrl}/chat/completions", [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt,
                    ],
                    [
                        'role' => 'user',
                        'content' => $userMessage,
                    ],
                ],
                'temperature' => 0.1,
                'top_p' => 0.8,
                'max_tokens' => 1024,
                'response_format' => ['type' => 'json_object'],
            ]);

        $response->throw();

        $data = $response->json();

        $text = $data['choices'][0]['message']['content'] ?? null;

        if (!$text) {
            throw new AiParseException('Empty response from Groq', json_encode($data));
        }

        return $text;
    }

    /**
     * Parse JSON response từ AI
     */
    private function parseAiResponse(string $response): array
    {
        $cleaned = preg_replace('/```json\s*|\s*```/', '', $response);
        $cleaned = trim($cleaned);

        $parsed = json_decode($cleaned, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new AiParseException('Invalid JSON: ' . json_last_error_msg(), $response);
        }

        if (!isset($parsed['intent'])) {
            throw new AiParseException('Missing "intent" field', $response);
        }

        return $parsed;
    }

    /**
     * Validate kết quả: đảm bảo thiết bị thực sự tồn tại trong DB
     */
    private function validateParsedResult(array $parsed): array
    {
        if ($parsed['intent'] === 'create_booking' && isset($parsed['data']['equipment_id'])) {
            $equipmentId = $parsed['data']['equipment_id'];

            $equipment = Equipment::find($equipmentId);
            if (!$equipment) {
                $equipment = Equipment::where('name', 'LIKE', '%' . ($parsed['data']['equipment_name'] ?? '') . '%')
                    ->first();

                if ($equipment) {
                    $parsed['data']['equipment_id'] = $equipment->id;
                    $parsed['data']['equipment_name'] = $equipment->name;
                } else {
                    $parsed['intent'] = 'suggest_alternative';
                    $parsed['message'] = 'Không tìm thấy thiết bị phù hợp trong kho. Vui lòng kiểm tra lại tên thiết bị.';
                    $parsed['alternatives'] = [];
                }
            }

            if ($equipment && $equipment->isHighSecurity()) {
                $parsed['data']['requires_approval'] = true;
            }
        }

        return $parsed;
    }

    private function getAvailableEquipments(): array
    {
        return Equipment::with(['items' => function ($q) {
            $q->where('status', 'available');
        }])
        ->where('is_digital', false)
        ->get()
        ->map(function ($eq) {
            return [
                'id' => $eq->id,
                'name' => $eq->name,
                'base_code' => $eq->base_code,
                'category_subject' => $eq->category_subject,
                'grade_level' => $eq->grade_level,
                'security_level' => $eq->security_level,
                'available_count' => $eq->items->count(),
            ];
        })
        ->toArray();
    }

    private function getRooms(): array
    {
        return \App\Models\Room::all(['id', 'name', 'type'])->toArray();
    }

    private function logInteraction(
        User $teacher,
        string $userMessage,
        string $aiResponse,
        ?array $parsedResult,
        string $status,
        ?int $responseTimeMs = null,
        string $provider = 'groq'
    ): void {
        try {
            AiChatLog::create([
                'user_id' => $teacher->id,
                'user_message' => $userMessage,
                'ai_response' => $aiResponse,
                'parsed_result' => $parsedResult ? json_encode($parsedResult) : null,
                'status' => $status,
                'response_time_ms' => $responseTimeMs,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log AI interaction', ['error' => $e->getMessage()]);
        }
    }
}
