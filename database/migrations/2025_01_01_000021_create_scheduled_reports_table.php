<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scheduled_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('report_type', [
                'equipment_list',
                'borrow_tracking',
                'inventory_summary',
                'overdue_report',
                'maintenance_report',
            ]);
            $table->enum('frequency', ['daily', 'weekly', 'monthly']);
            $table->time('send_time')->default('08:00');
            $table->tinyInteger('day_of_week')->nullable(); // 0-6 for weekly
            $table->tinyInteger('day_of_month')->nullable(); // 1-31 for monthly
            $table->json('filters')->nullable();
            $table->json('recipients'); // Array of email addresses
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamp('next_run_at')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'next_run_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheduled_reports');
    }
};
