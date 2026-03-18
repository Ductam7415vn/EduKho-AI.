<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // === BẢNG MỚI: Ghi log mọi tương tác với AI ===
        // Phục vụ: Debug hallucination, audit bảo mật, thống kê sử dụng AI
        Schema::create('ai_chat_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->text('user_message');           // Câu nhập của giáo viên
            $table->text('ai_response');            // Phản hồi thô từ AI (JSON)
            $table->text('parsed_result')->nullable(); // Kết quả sau khi parse (JSON)

            $table->enum('status', [
                'success',          // AI trả kết quả đúng, đã tạo phiếu mượn
                'fallback',         // AI không hiểu → chuyển về form thủ công
                'error',            // Lỗi API (timeout, rate limit...)
                'rejected'          // Bị chặn bởi System Prompt (prompt injection, off-topic)
            ]);

            $table->foreignId('borrow_record_id')  // Liên kết đến phiếu mượn (nếu tạo thành công)
                  ->nullable()
                  ->constrained('borrow_records')
                  ->nullOnDelete();

            $table->integer('response_time_ms')->nullable(); // Thời gian phản hồi (ms)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_chat_logs');
    }
};
