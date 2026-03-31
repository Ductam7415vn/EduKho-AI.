<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable();
            $table->date('career_start_date')->nullable(); 
            $table->enum('gender', ['Nam', 'Nữ'])->nullable();
            $table->string('ethnicity')->nullable();
            $table->string('specialization')->nullable();
            $table->string('degree')->nullable(); // Trình độ (Thạc sĩ, Đại học, etc)
            $table->boolean('is_party_member')->default(false);
            $table->string('political_theory_cert')->nullable(); // Trình độ CT
            $table->string('position')->nullable(); // Chức vụ
            $table->string('it_certificate')->nullable();
            $table->string('language_certificate')->nullable();
            $table->string('ethnic_language_cert')->nullable();
            $table->string('professional_title_cert')->nullable();
            $table->string('civil_servant_rank')->nullable(); // Hạng viên chức
            // phone column already exists in users table
            $table->text('notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'career_start_date',
                'gender',
                'ethnicity',
                'specialization',
                'degree',
                'is_party_member',
                'political_theory_cert',
                'position',
                'it_certificate',
                'language_certificate',
                'ethnic_language_cert',
                'professional_title_cert',
                'civil_servant_rank',
                'notes'
            ]);
        });
    }
};