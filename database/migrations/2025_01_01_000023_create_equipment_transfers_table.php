<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('from_room_id')->nullable()->constrained('rooms')->onDelete('set null');
            $table->foreignId('to_room_id')->nullable()->constrained('rooms')->onDelete('set null');
            $table->foreignId('transferred_by')->constrained('users')->onDelete('cascade');
            $table->date('transfer_date');
            $table->string('reason', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['equipment_item_id', 'transfer_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_transfers');
    }
};
