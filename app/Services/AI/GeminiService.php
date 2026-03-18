<?php

/**
 * ============================================================
 * GEMINI AI SERVICE - Xử lý giao tiếp với LLM API
 * ============================================================
 *
 * File: app/Services/AI/GeminiService.php
 *
 * Thiết kế theo Interface Pattern để dễ dàng swap sang
 * Claude/OpenAI nếu cần (xem LlmServiceInterface bên dưới)
 */

namespace App\Services\AI;

use App\Models\AiChatLog;
use App\Models\Equipment;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Interface cho phép swap LLM provider mà không sửa logic nghiệp vụ
 */
interface LlmServiceInterface
{
    public function processBookingRequest(string $userMessage, User $teacher): array;
}

class GeminiService implements LlmServiceInterface
{
    private string $apiKey;
    private string $model;
    private string $baseUrl;
    private int $timeoutSeconds;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->model = config('services.gemini.model', 'gemini-1.5-flash');
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta';
        $this->timeoutSeconds = config('services.gemini.timeout', 15);
    }

    /**
     * Xử lý yêu cầu đặt lịch mượn từ ngôn ngữ tự nhiên
     *
     * @param string $userMessage - Câu nhập của giáo viên
     * @param User $teacher - Giáo viên đang chat
     * @return array - Kết quả parse (hoặc fallback signal)
     */
    public function processBookingRequest(string $userMessage, User $teacher): array
    {
        $startTime = microtime(true);

        try {
            // 1. Lấy dữ liệu kho thực tế từ DB
            $equipments = $this->getAvailableEquipments();
            $rooms = $this->getRooms();

            // 2. Tạo System Prompt với dữ liệu kho
            $systemPrompt = SystemPrompt::generate(
                $equipments,
                $rooms,
                now()->format('Y-m-d'),
                $teacher->name
            );

            // 3. Gọi Gemini API
            $response = $this->callGeminiApi($systemPrompt, $userMessage);

            // 4. Parse JSON từ response
            $parsed = $this->parseAiResponse($response);

            // 5. Validate kết quả (chống hallucination)
            $validated = $this->validateParsedResult($parsed);

            // 6. Log kết quả
            $responseTimeMs = (int)((microtime(true) - $startTime) * 1000);
            $this->logInteraction($teacher, $userMessage, $response, $validated, 'success', $responseTimeMs);

            return [
                'success' => true,
                'data' => $validated,
                'raw_response' => $response,
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // === FALLBACK: Lỗi mạng → chuyển về form thủ công ===
            Log::error('Gemini API connection error', ['error' => $e->getMessage()]);
            $this->logInteraction($teacher, $userMessage, $e->getMessage(), null, 'error');

            return [
                'success' => false,
                'fallback' => true,
                'error' => 'Không thể kết nối đến AI. Vui lòng sử dụng form mượn thủ công.',
                'error_code' => 'CONNECTION_ERROR',
            ];

        } catch (\Illuminate\Http\Client\RequestException $e) {
            // === FALLBACK: API trả lỗi (rate limit, 500...) ===
            Log::error('Gemini API request error', [
                'status' => $e->response?->status(),
                'body' => $e->response?->body(),
            ]);
            $this->logInteraction($teacher, $userMessage, $e->getMessage(), null, 'error');

            return [
                'success' => false,
                'fallback' => true,
                'error' => 'Trợ lý AI đang tạm thời quá tải. Vui lòng thử lại sau hoặc dùng form thủ công.',
                'error_code' => 'API_ERROR',
            ];

        } catch (AiParseException $e) {
            // === FALLBACK: AI trả về JSON không hợp lệ ===
            Log::warning('Gemini response parse error', ['response' => $e->rawResponse]);
            $this->logInteraction($teacher, $userMessage, $e->rawResponse, null, 'fallback');

            return [
                'success' => false,
                'fallback' => true,
                'error' => 'Trợ lý AI chưa hiểu rõ yêu cầu. Vui lòng thử diễn đạt khác hoặc dùng form thủ công.',
                'error_code' => 'PARSE_ERROR',
            ];
        }
    }

    /**
     * Gọi Gemini API
     */
    private function callGeminiApi(string $systemPrompt, string $userMessage): string
    {
        $url = "{$this->baseUrl}/models/{$this->model}:generateContent?key={$this->apiKey}";

        $response = Http::timeout($this->timeoutSeconds)
            ->post($url, [
                'system_instruction' => [
                    'parts' => [['text' => $systemPrompt]]
                ],
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [['text' => $userMessage]]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.1,       // Rất thấp → ít sáng tạo, nhiều chính xác
                    'topP' => 0.8,
                    'maxOutputTokens' => 1024,
                    'responseMimeType' => 'application/json', // Bắt buộc trả JSON
                ],
                'safetySettings' => [
                    ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                    ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                    ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                    ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ],
            ]);

        $response->throw(); // Throw exception nếu status != 2xx

        $data = $response->json();

        // Trích xuất text từ response Gemini
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if (!$text) {
            throw new AiParseException('Empty response from Gemini', $response->body());
        }

        return $text;
    }

    /**
     * Parse JSON response từ AI
     */
    private function parseAiResponse(string $response): array
    {
        // Loại bỏ markdown code block nếu có (```json ... ```)
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
     * (Chống AI hallucination)
     */
    private function validateParsedResult(array $parsed): array
    {
        if ($parsed['intent'] === 'create_booking' && isset($parsed['data']['equipment_id'])) {
            $equipmentId = $parsed['data']['equipment_id'];

            // Kiểm tra thiết bị có tồn tại trong DB không
            $equipment = Equipment::find($equipmentId);
            if (!$equipment) {
                // AI bịa ra equipment_id → chuyển sang tìm bằng tên
                $equipment = Equipment::where('name', 'LIKE', '%' . ($parsed['data']['equipment_name'] ?? '') . '%')
                    ->first();

                if ($equipment) {
                    $parsed['data']['equipment_id'] = $equipment->id;
                    $parsed['data']['equipment_name'] = $equipment->name;
                } else {
                    // Không tìm thấy → chuyển thành suggest_alternative
                    $parsed['intent'] = 'suggest_alternative';
                    $parsed['message'] = 'Không tìm thấy thiết bị phù hợp trong kho. Vui lòng kiểm tra lại tên thiết bị.';
                    $parsed['alternatives'] = [];
                }
            }

            // Kiểm tra high_security
            if ($equipment && $equipment->isHighSecurity()) {
                $parsed['data']['requires_approval'] = true;
            }
        }

        return $parsed;
    }

    /**
     * Lấy danh sách thiết bị khả dụng (format cho System Prompt)
     */
    private function getAvailableEquipments(): array
    {
        return Equipment::with(['items' => function ($q) {
            $q->where('status', 'available');
        }])
        ->where('is_digital', false) // Chỉ lấy thiết bị vật lý (học liệu số không cần mượn)
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

    /**
     * Lấy danh sách phòng (format cho System Prompt)
     */
    private function getRooms(): array
    {
        return Room::all(['id', 'name', 'type'])->toArray();
    }

    /**
     * Ghi log tương tác AI vào database
     */
    private function logInteraction(
        User $teacher,
        string $userMessage,
        string $aiResponse,
        ?array $parsedResult,
        string $status,
        ?int $responseTimeMs = null
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

/**
 * Custom Exception cho lỗi parse AI response
 */
class AiParseException extends \RuntimeException
{
    public string $rawResponse;

    public function __construct(string $message, string $rawResponse)
    {
        parent::__construct($message);
        $this->rawResponse = $rawResponse;
    }
}
