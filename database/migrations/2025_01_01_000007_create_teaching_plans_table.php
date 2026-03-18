<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teaching_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')           // Giáo viên đăng ký
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('equipment_id')      // Danh mục thiết bị dự kiến sử dụng
                  ->constrained('equipments')
                  ->cascadeOnDelete();

            // === Thông tin giảng dạy ===
            $table->string('subject');             // Môn dạy
            $table->string('lesson_name');         // Tên bài dạy / tiết dạy
            $table->integer('period');             // Tiết PPCT (Phân phối chương trình)

            // === Thông tin thời gian (ĐÃ BỔ SUNG - khắc phục lỗi schema gốc) ===
            $table->integer('week');               // Tuần học (tuần 1 → 35)
            $table->date('planned_date');           // Ngày cụ thể dự kiến sử dụng
            // Trường này QUAN TRỌNG cho Calendar Booking & Conflict Handling
            // Nếu chỉ có week + period → không xác định được ngày cụ thể

            $table->integer('quantity_needed')->default(1); // Số lượng cần mượn
            $table->text('notes')->nullable();     // Ghi chú thêm

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_plans');
    }
};
