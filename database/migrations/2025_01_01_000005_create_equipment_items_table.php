<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')
                  ->constrained('equipments')
                  ->cascadeOnDelete();
            $table->foreignId('room_id')          // Vị trí đang lưu trữ
                  ->constrained('rooms')
                  ->cascadeOnDelete();
            $table->string('specific_code')->unique();
            // Mã cá biệt: TBVL430.1/3, TBVL430.2/3 (thiết bị 1 trong 3)

            $table->enum('status', [
                'available',     // Sẵn sàng cho mượn
                'borrowed',      // Đang được mượn
                'maintenance',   // Đang bảo trì / sửa chữa
                'broken',        // Hỏng (chờ thanh lý)
                'lost'           // Mất
            ])->default('available');

            $table->year('year_acquired')->nullable(); // Năm nhập kho (yêu cầu Mẫu 01)
            $table->text('notes')->nullable();         // Ghi chú thêm về cá thể

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_items');
    }
};
