<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // === Bảng thông báo (phục vụ Approval Workflow) ===
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');           // Polymorphic (user_id + user_type)
            $table->text('data');                   // JSON data (nội dung thông báo)
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // === Indexes tối ưu truy vấn ===
        // Index cho Calendar Booking: tìm phiếu mượn theo ngày + trạng thái
        Schema::table('borrow_records', function (Blueprint $table) {
            $table->index(['borrow_date', 'status']);
            $table->index(['approval_status']);
            $table->index(['user_id', 'status']);
        });

        // Index cho Conflict Handling: kiểm tra thiết bị đã được đặt chưa
        Schema::table('equipment_items', function (Blueprint $table) {
            $table->index(['equipment_id', 'status']);
            $table->index(['room_id', 'status']);
        });

        // Index cho tìm kiếm thiết bị (AI NLP + Filter)
        Schema::table('equipments', function (Blueprint $table) {
            $table->index(['category_subject', 'grade_level']);
            $table->index(['security_level']);
            $table->index(['is_digital']);
        });

        // Index cho Teaching Plans
        Schema::table('teaching_plans', function (Blueprint $table) {
            $table->index(['user_id', 'planned_date']);
            $table->index(['planned_date', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');

        Schema::table('borrow_records', function (Blueprint $table) {
            $table->dropIndex(['borrow_date', 'status']);
            $table->dropIndex(['approval_status']);
            $table->dropIndex(['user_id', 'status']);
        });

        Schema::table('equipment_items', function (Blueprint $table) {
            $table->dropIndex(['equipment_id', 'status']);
            $table->dropIndex(['room_id', 'status']);
        });

        Schema::table('equipments', function (Blueprint $table) {
            $table->dropIndex(['category_subject', 'grade_level']);
            $table->dropIndex(['security_level']);
            $table->dropIndex(['is_digital']);
        });

        Schema::table('teaching_plans', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'planned_date']);
            $table->dropIndex(['planned_date', 'period']);
        });
    }
};
