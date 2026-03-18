<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AI\GeminiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiChatApiController extends Controller
{
    public function __construct(
        private GeminiService $geminiService
    ) {}

    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $result = $this->geminiService->processBookingRequest(
            $validated['message'],
            Auth::user()
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'data' => $result['data'],
            ]);
        }

        return response()->json([
            'success' => false,
            'fallback' => $result['fallback'] ?? false,
            'error' => $result['error'],
            'error_code' => $result['error_code'] ?? 'UNKNOWN',
        ], 422);
    }
}
