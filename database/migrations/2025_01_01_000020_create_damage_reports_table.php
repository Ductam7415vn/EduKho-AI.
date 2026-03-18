<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('damage_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('borrow_record_id')->nullable()->constrained()->onDelete('set null');
            $table->date('incident_date');
            $table->enum('severity', ['minor', 'moderate', 'severe', 'total_loss'])->default('minor');
            $table->text('description');
            $table->text('cause')->nullable();
            $table->decimal('estimated_cost', 15, 2)->nullable();
            $table->enum('status', ['reported', 'investigating', 'resolved', 'written_off'])->default('reported');
            $table->text('resolution_notes')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'severity']);
            $table->index('equipment_item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('damage_reports');
    }
};
