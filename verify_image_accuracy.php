<?php

echo "KIỂM TRA ĐỘ CHÍNH XÁC MAPPING ẢNH\n";
echo "==================================\n\n";

// Danh sách thiết bị và ảnh được gán
$mappings = [
    // Ngữ văn
    'GD38-0002VN' => ['name' => 'Video/clip/phim tư liệu về Văn học dân gian Việt Nam', 'image' => 'video_van_hoc_dan_gian.jpg', 'accuracy' => '⚠️'],
    'GD38-0003VN' => ['name' => 'Video/clip/ phim tư liệu về thơ văn của Chủ tịch Hồ Chí Minh', 'image' => 'video_tho_van_bac_ho.jpg', 'accuracy' => '⚠️'],
    
    // Toán học  
    '07DTOHHTQ0021HA' => ['name' => 'Bộ thiết bị dạy học hình học trực quan', 'image' => 'bo_hinh_hoc_truc_quan.jpg', 'accuracy' => '✅'],
    'TBTO001' => ['name' => 'Thước thẳng có chia vạch', 'image' => 'thuoc_thang_chia_vach.jpg', 'accuracy' => '❓'],
    'TBTO002' => ['name' => 'Compa bảng', 'image' => 'compa_bang.jpg', 'accuracy' => '❓'],
    'TBTO003' => ['name' => 'Eke bảng', 'image' => 'eke_bang.jpg', 'accuracy' => '❓'],
    
    // Vật lý
    'TBVL-QH-01' => ['name' => 'Bộ thí nghiệm quang học', 'image' => 'bo_thi_nghiem_quang_hoc.jpg', 'accuracy' => '❓'],
    'TBVL-DH-01' => ['name' => 'Bộ thí nghiệm điện học', 'image' => 'bo_thi_nghiem_dien_hoc.jpg', 'accuracy' => '❓'],
    'TBVL-CH-01' => ['name' => 'Bộ thí nghiệm cơ học', 'image' => 'bo_thi_nghiem_co_hoc.jpg', 'accuracy' => '❓'],
    'TBVL-AM-01' => ['name' => 'Máy phát tần số âm', 'image' => 'may_phat_tan_so_am.jpg', 'accuracy' => '❓'],
    
    // Hóa học
    'TBHH-VC-01' => ['name' => 'Bộ dụng cụ thí nghiệm hóa vô cơ', 'image' => 'bo_thi_nghiem_hoa_vo_co.jpg', 'accuracy' => '❓'],
    'TBHH-TH-01' => ['name' => 'Tủ hút hóa chất', 'image' => 'tu_hut_hoa_chat.jpg', 'accuracy' => '❓'],
    'TBHH-CD-01' => ['name' => 'Cân điện tử phân tích', 'image' => 'can_dien_tu_phan_tich.jpg', 'accuracy' => '❓'],
    'TBHH-KH-N2' => ['name' => 'Bình khí Nitơ', 'image' => 'binh_khi_nito.jpg', 'accuracy' => '❓'],
];

echo "CHÚ THÍCH:\n";
echo "✅ = Chính xác (tên file khớp với nội dung)\n";
echo "❓ = Có thể đúng (cùng môn học)\n"; 
echo "⚠️ = Không chắc chắn (ảnh chung chung)\n";
echo "❌ = Sai (ảnh không phù hợp)\n\n";

echo "PHÂN TÍCH CHI TIẾT:\n";
echo "-------------------\n\n";

// Phân tích từng môn học
echo "1. VẬT LÝ:\n";
echo "   - Có 4 thiết bị, đã gán 4 ảnh từ thư mục Vật lý\n";
echo "   - Độ chính xác: CÓ THỂ ĐÚNG (cùng môn học nhưng không rõ ảnh cụ thể)\n\n";

echo "2. HÓA HỌC:\n";
echo "   - Có 4 thiết bị, đã gán 4 ảnh từ thư mục Hóa học\n";
echo "   - Độ chính xác: CÓ THỂ ĐÚNG (cùng môn học nhưng không rõ ảnh cụ thể)\n\n";

echo "3. SINH HỌC:\n";
echo "   - Có 4 thiết bị, đã gán 4 ảnh từ thư mục Sinh học\n";
echo "   - Độ chính xác: CÓ THỂ ĐÚNG (cùng môn học nhưng không rõ ảnh cụ thể)\n\n";

echo "4. TOÁN HỌC:\n";
echo "   - Bộ hình học trực quan: CHÍNH XÁC (có file phù hợp trong thư mục)\n";
echo "   - Các thiết bị khác: CÓ THỂ ĐÚNG\n\n";

echo "5. CÁC MÔN KHÁC:\n";
echo "   - Ngữ văn, GDCD, Lịch sử, Địa lý: Dùng ảnh chung từ các môn khác\n";
echo "   - Độ chính xác: THẤP\n\n";

echo "VẤN ĐỀ:\n";
echo "--------\n";
echo "1. Ảnh trong thư mục có tên mã hóa (z7584254290050...) không rõ nội dung\n";
echo "2. Script đã gán ảnh theo thứ tự trong folder, không dựa trên nội dung thực\n";
echo "3. Một số thiết bị (Ngữ văn, GDCD...) không có ảnh riêng nên dùng ảnh từ môn khác\n\n";

echo "ĐỀ XUẤT:\n";
echo "--------\n";
echo "1. Xem lại từng ảnh trong thư mục nguồn để biết nội dung cụ thể\n";
echo "2. Đổi tên file ảnh cho rõ ràng trước khi copy\n";
echo "3. Hoặc chỉnh sửa thủ công mapping trong EquipmentStaticImageSeeder.php\n";
echo "4. Upload ảnh phù hợp cho từng thiết bị qua giao diện admin\n";

// Check actual files
$sourceDir = '/Users/ductampro/Desktop/files/Ảnh TB 2025-2026-2';
echo "\n\nDANH SÁCH FILE ẢNH THỰC TẾ:\n";
echo "----------------------------\n";

$folders = [
    '1. Vật lí',
    '2. Hoá học', 
    '3. Sinh học',
    '4. Toán',
    '5. QPAN',
    '6. Âm nhạc',
    '7. Công nghệ'
];

foreach ($folders as $folder) {
    $path = $sourceDir . '/' . $folder;
    if (is_dir($path)) {
        $files = glob($path . '/*.{jpg,JPG,jpeg,JPEG,png,PNG}', GLOB_BRACE);
        echo "\n$folder: (" . count($files) . " files)\n";
        foreach ($files as $i => $file) {
            if ($i < 3) { // Show first 3 files
                echo "  - " . basename($file) . "\n";
            }
        }
        if (count($files) > 3) {
            echo "  ... và " . (count($files) - 3) . " ảnh khác\n";
        }
    }
}