<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')
                  ->nullable()                    // Nullable cho Ban giám hiệu (không thuộc tổ CM)
                  ->constrained('departments')
                  ->nullOnDelete();
            $table->string('name');               // Họ và tên giáo viên
            $table->string('email')->unique();    // Email đăng nhập
            $table->string('phone')->nullable();  // Số điện thoại (hỗ trợ liên hệ)
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'teacher'])->default('teacher');
            // admin = Phó BGH / Cán bộ thiết bị (CBTB)
            // teacher = Giáo viên bộ môn
            $table->boolean('is_active')->default(true); // Tắt tài khoản khi GV chuyển trường
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
