<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrow_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')            // Giáo viên mượn
                  ->constrained('users')
                  ->cascadeOnDelete();

            // === BỔ SUNG: Liên kết với Kế hoạch giảng dạy ===
            $table->foreignId('teaching_plan_id')
                  ->nullable()                       // Nullable vì có thể mượn không theo kế hoạch
                  ->constrained('teaching_plans')
                  ->nullOnDelete();
            // Trường này cho phép truy vấn: "Phiếu mượn này thuộc kế hoạch nào?"
            // và ngược lại: "Kế hoạch này đã tạo phiếu mượn chưa?"

            // === Thông tin bài dạy ===
            $table->string('lesson_name');          // Tên bài dạy
            $table->integer('period');              // Tiết PPCT
            $table->string('class_name');           // Lớp dạy (10A1, 11B2...)
            $table->string('subject')->nullable();  // Môn dạy

            // === Thời gian mượn/trả ===
            $table->dateTime('borrow_date');        // Ngày giờ bắt đầu mượn
            $table->dateTime('expected_return_date'); // Ngày giờ dự kiến trả
            $table->dateTime('actual_return_date')->nullable(); // Ngày giờ trả thực tế

            // === Trạng thái phê duyệt ===
            $table->enum('approval_status', [
                'auto_approved',   // Thiết bị thường → tự động duyệt
                'pending',         // Thiết bị high_security → chờ BGH duyệt
                'approved',        // BGH đã phê duyệt
                'rejected'         // BGH từ chối
            ])->default('auto_approved');

            // === BỔ SUNG: Audit trail cho phê duyệt ===
            $table->foreignId('approved_by')        // Ai đã duyệt?
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->dateTime('approved_at')->nullable();   // Duyệt lúc nào?
            $table->text('rejection_reason')->nullable();  // Lý do từ chối (nếu rejected)

            // === Trạng thái phiếu mượn ===
            $table->enum('status', [
                'active',          // Đang giữ đồ
                'returned',        // Đã trả xong
                'overdue'          // Quá hạn trả
            ])->default('active');

            $table->text('notes')->nullable();      // Ghi chú

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrow_records');
    }
};
