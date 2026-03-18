<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')
                  ->constrained('equipments')
                  ->cascadeOnDelete();
            $table->foreignId('performed_by')      // Người thực hiện thao tác
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->enum('type', ['increase', 'decrease']);
            // increase = Tăng (Cấp mới / Mua bổ sung / Tiếp nhận)
            // decrease = Giảm (Hỏng / Mất / Thanh lý / Điều chuyển)

            $table->integer('quantity');            // Số lượng tăng/giảm
            $table->text('reason');                 // Lý do tăng/giảm
            $table->string('document_ref')->nullable(); // Số chứng từ kèm theo
            $table->date('action_date');            // Ngày thực hiện

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
