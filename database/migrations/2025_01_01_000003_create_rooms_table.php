<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');               // Tên phòng (VD: Kho Tổng, Phòng TH Hóa học)
            $table->foreignId('manager_id')       // Người quản lý phòng
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->enum('type', ['warehouse', 'lab']);
            // warehouse = Kho chứa thiết bị
            // lab = Phòng thực hành bộ môn
            $table->string('location')->nullable(); // Vị trí (Tầng 2, Dãy B...)
            $table->integer('capacity')->nullable(); // Sức chứa (số chỗ ngồi cho lab)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
