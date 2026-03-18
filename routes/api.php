<?php

use App\Http\Controllers\Api\EquipmentApiController;
use App\Http\Controllers\Api\BorrowApiController;
use App\Http\Controllers\Api\AiChatApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Equipment API
    Route::prefix('equipment')->group(function () {
        Route::get('/', [EquipmentApiController::class, 'index']);
        Route::get('/{equipment}', [EquipmentApiController::class, 'show']);
        Route::get('/{equipment}/availability', [EquipmentApiController::class, 'checkAvailability']);
    });

    // Borrow API
    Route::prefix('borrow')->group(function () {
        Route::get('/', [BorrowApiController::class, 'index']);
        Route::post('/', [BorrowApiController::class, 'store']);
        Route::get('/{borrowRecord}', [BorrowApiController::class, 'show']);
        Route::post('/{borrowRecord}/return', [BorrowApiController::class, 'return']);
        Route::get('/calendar/events', [BorrowApiController::class, 'calendarEvents']);
        Route::post('/check-conflict', [BorrowApiController::class, 'checkConflict']);
    });

    // AI Chat API
    Route::prefix('ai')->group(function () {
        Route::post('/chat', [AiChatApiController::class, 'chat']);
    });

    // User info
    Route::get('/user', function () {
        return request()->user();
    });
});
