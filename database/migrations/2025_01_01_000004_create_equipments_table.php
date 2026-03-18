<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->string('name');                // Tên thiết bị (VD: Kính hiển vi quang học)
            $table->string('base_code')->unique(); // Mã hiệu gốc (VD: TBVL430)
            $table->string('unit');                // Đơn vị tính (Cái, Bộ, Khẩu...)
            $table->decimal('price', 15, 0)->default(0); // Đơn giá (VNĐ, không cần thập phân)
            $table->string('origin')->nullable();  // Nước sản xuất

            // === Phân loại theo Thông tư 37, 38, 39 ===
            $table->string('category_subject');     // Môn học (Vật lý, QPAN, Dùng chung...)
            $table->string('grade_level')->default('All');
            // Khối lớp: "10", "11", "12", "10,11", "11,12", "All"
            // Theo TT 37 (Lớp 10), TT 38 (Lớp 11), TT 39 (Lớp 12)

            // === Phân loại tài sản ===
            $table->boolean('is_digital')->default(false);
            // true = Video/Học liệu số, false = Thiết bị vật lý

            $table->enum('security_level', ['normal', 'high_security'])->default('normal');
            // high_security = Súng AK mô hình, Hóa chất độc hại (Theo HD 590 Quân khu 2)
            // Bắt buộc qua luồng phê duyệt BGH

            $table->boolean('is_fixed_asset')->default(false);
            // true = Gắn cố định tại phòng, không được mang đi (Theo TT 14/2020)

            // === Học liệu số (chỉ dùng khi is_digital = true) ===
            $table->string('file_url')->nullable();     // Link tải/xem trực tuyến
            $table->string('file_type')->nullable();    // Loại file (mp4, pdf, pptx...)
            $table->string('file_size')->nullable();    // Dung lượng file

            // === Thông tin bổ sung cho Mẫu 01 ===
            $table->text('description')->nullable();    // Mô tả chi tiết / Thông số kỹ thuật
            $table->text('tags')->nullable();           // Tags tìm kiếm (phục vụ AI NLP)

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
