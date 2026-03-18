<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrow_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrow_record_id')
                  ->constrained('borrow_records')
                  ->cascadeOnDelete();
            $table->foreignId('equipment_item_id')   // Cá thể thiết bị cụ thể được mượn
                  ->constrained('equipment_items')
                  ->cascadeOnDelete();

            $table->string('condition_before')->default('Bình thường');
            // Tình trạng lúc nhận: "Bình thường", "Có vết xước nhẹ"...

            $table->string('condition_after')->nullable();
            // Tình trạng lúc trả: Giáo viên hoặc Admin điền khi trả đồ
            // Nullable vì chưa trả → chưa có tình trạng

            $table->text('damage_notes')->nullable(); // Ghi chú hư hỏng (nếu có)

            $table->timestamps();

            // Đảm bảo 1 cá thể chỉ xuất hiện 1 lần trong 1 phiếu mượn
            $table->unique(['borrow_record_id', 'equipment_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrow_details');
    }
};
