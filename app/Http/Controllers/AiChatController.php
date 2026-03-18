<?php

namespace App\Http\Controllers;

use App\Models\AiChatLog;
use App\Services\AI\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiChatController extends Controller
{
    public function __construct(
        private GeminiService $geminiService
    ) {}

    /**
     * Display AI chat interface
     */
    public function index()
    {
        $recentChats = Auth::user()
            ->aiChatLogs()
            ->latest()
            ->take(10)
            ->get();

        return view('ai.chat', compact('recentChats'));
    }

    /**
     * Send message to AI and process response
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $result = $this->geminiService->processBookingRequest(
            $validated['message'],
            Auth::user()
        );

        if ($result['success']) {
            $data = $result['data'];

            // Handle different intents
            return match ($data['intent']) {
                'create_booking' => $this->handleCreateBooking($request, $data),
                'need_more_info' => $this->handleNeedMoreInfo($data),
                'suggest_alternative' => $this->handleSuggestAlternative($data),
                'query_stock' => $this->handleQueryStock($data),
                'rejected' => $this->handleRejected($data),
                default => response()->json([
                    'success' => false,
                    'message' => 'Không hiểu yêu cầu của bạn.',
                    'show_form' => true,
                ]),
            };
        }

        // Fallback - show manual form
        return response()->json([
            'success' => false,
            'fallback' => true,
            'message' => $result['error'],
            'show_form' => true,
        ]);
    }

    /**
     * Handle AI response: create_booking
     */
    private function handleCreateBooking(Request $request, array $data): \Illuminate\Http\JsonResponse
    {
        // Store pre-fill data in session
        $request->session()->put('ai_prefill', $data['data']);

        return response()->json([
            'success' => true,
            'intent' => 'create_booking',
            'message' => $data['message'],
            'prefill' => $data['data'],
            'requires_approval' => $data['data']['requires_approval'] ?? false,
            'redirect' => route('borrow.create'),
        ]);
    }

    /**
     * Handle AI response: need_more_info
     */
    private function handleNeedMoreInfo(array $data): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'intent' => 'need_more_info',
            'message' => $data['message'],
            'missing_fields' => $data['missing_fields'] ?? [],
        ]);
    }

    /**
     * Handle AI response: suggest_alternative
     */
    private function handleSuggestAlternative(array $data): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'intent' => 'suggest_alternative',
            'message' => $data['message'],
            'requested_equipment' => $data['requested_equipment'] ?? null,
            'alternatives' => $data['alternatives'] ?? [],
        ]);
    }

    /**
     * Handle AI response: query_stock
     */
    private function handleQueryStock(array $data): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'intent' => 'query_stock',
            'message' => $data['message'],
        ]);
    }

    /**
     * Handle AI response: rejected
     */
    private function handleRejected(array $data): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'intent' => 'rejected',
            'message' => $data['message'] ?? 'Yêu cầu không hợp lệ.',
        ]);
    }

    /**
     * Display AI chat history
     */
    public function history()
    {
        $logs = Auth::user()
            ->aiChatLogs()
            ->latest()
            ->paginate(20);

        return view('ai.history', compact('logs'));
    }
}
