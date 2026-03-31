<?php

// Script đổi tên ảnh thông minh dựa trên nội dung

$sourceDir = '/Users/ductampro/Desktop/files/Ảnh TB 2025-2026-2';
$mappings = [];

// Sau khi xem một số ảnh mẫu, tôi có thể gợi ý mapping như sau:

echo "SCRIPT ĐỔI TÊN ẢNH THIẾT BỊ\n";
echo "============================\n\n";

// Danh sách file cần xem và đổi tên
$filesToCheck = [
    // Vật lý - cần xem từng ảnh
    '1. Vật lí' => [
        'z7584254290050_ddfb17e127a24c52ae9b25f47bfbdb09.jpg' => 'bo_thi_nghiem_co_hoc_VL356.jpg', // Đã xem - thiết bị cơ học
        'z7584254291265_796dec5cf4c1f05ea902f6605fae384c.jpg' => 'dong_ho_van_nang_VLL11.jpg', // Đã xem - đồng hồ điện
        // Các file khác cần xem thêm
    ],
    
    // Hóa học
    '2. Hoá học' => [
        'z7562551793980_24900214640254632ae36100020e91dd.jpg' => 'coc_thuy_tinh_100ml_HH61.jpg', // Đã xem
        // Các file khác cần xem thêm
    ],
    
    // Toán - có tên rõ ràng
    '4. Toán' => [
        'Bộ thiết bị dạy học hình học trực quan (các hình khối trong thực tiễn)- dùng chung.jpg' => 'bo_hinh_hoc_truc_quan.jpg',
        'Bộ thiết bị thống kê và Xắc suất.jpg' => 'bo_thong_ke_xac_suat.jpg',
        'Giác kế -Bộ thiết bị đo khoảng cách và chiều cao- dùng chung.jpg' => 'giac_ke_do_khoang_cach.jpg',
        'Bộ thiết bị để vẽ trên bảng trong dạy học toán- Dùng chung.jpg' => 'bo_dung_cu_ve_bang_toan.jpg',
    ],
];

echo "GỢI Ý: Để đổi tên chính xác, bạn cần:\n\n";

echo "1. XEM TỪNG ẢNH để biết nội dung:\n";
echo "   open \"$sourceDir/1. Vật lí/[tên_file].jpg\"\n\n";

echo "2. ĐỔI TÊN THEO NỘI DUNG:\n";
echo "   - Nếu là bộ thí nghiệm quang học → bo_thi_nghiem_quang_hoc.jpg\n";
echo "   - Nếu là máy phát tần số → may_phat_tan_so_am.jpg\n";
echo "   - Nếu là tủ hút hóa chất → tu_hut_hoa_chat.jpg\n\n";

echo "3. VÍ DỤ LỆNH ĐỔI TÊN:\n";
foreach ($filesToCheck as $folder => $files) {
    foreach ($files as $oldName => $newName) {
        echo "mv \"$sourceDir/$folder/$oldName\" \"$sourceDir/$folder/$newName\"\n";
    }
}

echo "\n4. SAU KHI ĐỔI TÊN XONG, CHẠY LẠI SCRIPT COPY:\n";
echo "   php auto_copy_images.php\n";
echo "   php artisan db:seed --class=EquipmentStaticImageSeeder\n";

// Tạo script bash để dễ thực thi
$bashScript = "#!/bin/bash\n\n";
$bashScript .= "# Script đổi tên ảnh thiết bị\n";
$bashScript .= "# Chạy: bash rename_equipment_images.sh\n\n";

$bashScript .= "cd \"$sourceDir\"\n\n";

// Thêm các lệnh đổi tên đã biết chắc chắn
$bashScript .= "# Toán - tên file đã rõ ràng\n";
$bashScript .= "mv \"4. Toán/Bộ thiết bị dạy học hình học trực quan (các hình khối trong thực tiễn)- dùng chung.jpg\" \"4. Toán/bo_hinh_hoc_truc_quan.jpg\" 2>/dev/null\n";
$bashScript .= "mv \"4. Toán/Bộ thiết bị thống kê và Xắc suất.jpg\" \"4. Toán/bo_thong_ke_xac_suat.jpg\" 2>/dev/null\n";
$bashScript .= "mv \"4. Toán/Giác kế -Bộ thiết bị đo khoảng cách và chiều cao- dùng chung.jpg\" \"4. Toán/giac_ke_do_khoang_cach.jpg\" 2>/dev/null\n\n";

$bashScript .= "# Vật lý - dựa trên ảnh đã xem\n";
$bashScript .= "mv \"1. Vật lí/z7584254290050_ddfb17e127a24c52ae9b25f47bfbdb09.jpg\" \"1. Vật lí/bo_thi_nghiem_co_hoc.jpg\" 2>/dev/null\n";
$bashScript .= "mv \"1. Vật lí/z7584254291265_796dec5cf4c1f05ea902f6605fae384c.jpg\" \"1. Vật lí/dong_ho_van_nang_dien.jpg\" 2>/dev/null\n\n";

$bashScript .= "echo \"Đổi tên hoàn tất!\"\n";
$bashScript .= "echo \"Bước tiếp: Xem thêm các ảnh khác và đổi tên thủ công\"\n";

file_put_contents('/Users/ductampro/Desktop/files/rename_equipment_images.sh', $bashScript);
echo "\nĐã tạo script: rename_equipment_images.sh\n";
echo "Chạy: bash rename_equipment_images.sh\n";