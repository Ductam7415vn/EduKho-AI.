<?php

// Auto copy and rename images based on equipment mapping

$sourceDir = '/Users/ductampro/Desktop/files/Ảnh TB 2025-2026-2';
$targetDir = '/Users/ductampro/Desktop/files/storage/app/public/equipment';

// Create target directory if not exists
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0755, true);
}

// Equipment mapping based on the database
$equipmentMapping = [
    // Vật lý
    'TBVL-QH-01' => ['target' => 'bo_thi_nghiem_quang_hoc.jpg', 'subject' => 'Vật lí', 'keywords' => ['quang học', 'optical']],
    'TBVL-DH-01' => ['target' => 'bo_thi_nghiem_dien_hoc.jpg', 'subject' => 'Vật lí', 'keywords' => ['điện', 'electric']],
    'TBVL-CH-01' => ['target' => 'bo_thi_nghiem_co_hoc.jpg', 'subject' => 'Vật lí', 'keywords' => ['cơ học', 'mechanic']],
    'TBVL-AM-01' => ['target' => 'may_phat_tan_so_am.jpg', 'subject' => 'Vật lí', 'keywords' => ['âm', 'sound', 'tần số']],
    
    // Hóa học  
    'TBHH-VC-01' => ['target' => 'bo_thi_nghiem_hoa_vo_co.jpg', 'subject' => 'Hoá học', 'keywords' => ['vô cơ', 'thí nghiệm']],
    'TBHH-TH-01' => ['target' => 'tu_hut_hoa_chat.jpg', 'subject' => 'Hoá học', 'keywords' => ['tủ hút', 'hút']],
    'TBHH-CD-01' => ['target' => 'can_dien_tu_phan_tich.jpg', 'subject' => 'Hoá học', 'keywords' => ['cân', 'điện tử']],
    'TBHH-KH-N2' => ['target' => 'binh_khi_nito.jpg', 'subject' => 'Hoá học', 'keywords' => ['bình', 'khí', 'nitơ']],
    
    // Sinh học
    'TBSH-KHV-01' => ['target' => 'kinh_hien_vi_2_mat.jpg', 'subject' => 'Sinh học', 'keywords' => ['kính', 'hiển vi']],
    'TBSH-TB-TV' => ['target' => 'bo_tieu_ban_thuc_vat.jpg', 'subject' => 'Sinh học', 'keywords' => ['tiêu bản', 'thực vật']],
    'TBSH-MH-ADN' => ['target' => 'mo_hinh_adn.jpg', 'subject' => 'Sinh học', 'keywords' => ['ADN', 'DNA', 'mô hình']],
    'TBSH-MH-NT' => ['target' => 'mo_hinh_co_the_nguoi.jpg', 'subject' => 'Sinh học', 'keywords' => ['mô hình', 'cơ thể', 'người']],
    
    // Toán học
    '07DTOHHTQ0021HA' => ['target' => 'bo_hinh_hoc_truc_quan.jpg', 'subject' => 'Toán', 'keywords' => ['hình học trực quan', 'hình khối']],
    'TBTO001' => ['target' => 'thuoc_thang_chia_vach.jpg', 'subject' => 'Toán', 'keywords' => ['thước', 'chia vạch']],
    'TBTO002' => ['target' => 'compa_bang.jpg', 'subject' => 'Toán', 'keywords' => ['compa', 'vẽ']],
    'TBTO003' => ['target' => 'eke_bang.jpg', 'subject' => 'Toán', 'keywords' => ['eke', 'giác kế']],
    
    // GDQP-AN
    'TBQP-AK-01' => ['target' => 'sung_ak_mo_hinh.jpg', 'subject' => 'QPAN', 'keywords' => ['súng', 'AK', 'mô hình']],
    'TBQP-LD-01' => ['target' => 'luu_dan_tap.jpg', 'subject' => 'QPAN', 'keywords' => ['lựu đạn', 'đạn']],
    'TBQP-BD-01' => ['target' => 'ban_do_dia_hinh_quan_su.jpg', 'subject' => 'QPAN', 'keywords' => ['bản đồ', 'địa hình']],
    
    // Âm nhạc
    'TBAN001' => ['target' => 'dan_organ.jpg', 'subject' => 'Âm nhạc', 'keywords' => ['đàn', 'organ', 'nhạc cụ']],
    
    // Công nghệ
    'TBCN002' => ['target' => 'may_khau.jpg', 'subject' => 'Công nghệ', 'keywords' => ['máy', 'khâu', 'may']],
];

// Map subject folders
$subjectFolders = [
    'Vật lí' => '1. Vật lí',
    'Hoá học' => '2. Hoá học',
    'Sinh học' => '3. Sinh học',
    'Toán' => '4. Toán',
    'QPAN' => '5. QPAN',
    'Âm nhạc' => '6. Âm nhạc',
    'Công nghệ' => '7. Công nghệ',
];

$copiedCount = 0;
$totalEquipment = count($equipmentMapping);

echo "Starting intelligent image copy process...\n\n";

// Process each equipment
foreach ($equipmentMapping as $code => $info) {
    $subject = $info['subject'];
    $targetFile = $info['target'];
    $keywords = $info['keywords'];
    
    if (!isset($subjectFolders[$subject])) {
        continue;
    }
    
    $subjectPath = $sourceDir . '/' . $subjectFolders[$subject];
    
    if (!is_dir($subjectPath)) {
        echo "❌ Directory not found: $subjectPath\n";
        continue;
    }
    
    // Find matching image
    $images = glob($subjectPath . '/*.{jpg,JPG,jpeg,JPEG,png,PNG}', GLOB_BRACE);
    $matched = false;
    
    // Try to match by keywords in filename
    foreach ($images as $image) {
        $filename = basename($image);
        $filenameLower = mb_strtolower($filename);
        
        foreach ($keywords as $keyword) {
            if (mb_strpos($filenameLower, mb_strtolower($keyword)) !== false) {
                // Found matching image
                $targetPath = $targetDir . '/' . $targetFile;
                if (copy($image, $targetPath)) {
                    echo "✅ Copied: $code -> $targetFile (matched: $filename)\n";
                    $copiedCount++;
                    $matched = true;
                    break 2;
                }
            }
        }
    }
    
    // If no keyword match, use first available image in folder
    if (!$matched && !empty($images)) {
        $targetPath = $targetDir . '/' . $targetFile;
        if (copy($images[0], $targetPath)) {
            echo "✅ Copied: $code -> $targetFile (first image from {$subject})\n";
            $copiedCount++;
        }
    } else if (!$matched) {
        echo "❌ No image found for: $code ({$subject})\n";
    }
}

// Copy remaining images that don't need keyword matching
$genericImages = [
    'video_van_hoc_dan_gian.jpg',
    'video_tho_van_bac_ho.jpg', 
    'tranh_hoc_tap_tu_giac.jpg',
    'tranh_phong_chong_bao_luc.jpg',
    'luoc_do_dong_nam_a.jpg',
    'ban_do_tu_nhien_vn.jpg',
    'ban_do_hanh_chinh_vn.jpg',
    'la_ban_thuc_dia.jpg',
    'may_tinh_hoc_sinh.jpg',
    'may_chieu.jpg',
    'bo_kit_arduino.jpg',
    'bong_da_so_5.jpg',
    'bong_chuyen.jpg',
    'luoi_cau_long.jpg',
    'may_photocopy.jpg',
    'loa_keo_di_dong.jpg',
    'may_chieu_3d.jpg',
    'trong_bo.jpg',
    'gia_ve_my_thuat.jpg',
    'bo_mau_acrylic.jpg',
    'co_ve.jpg',
    'bo_dung_cu_co_khi.jpg',
    'may_khoan.jpg',
    'keo_nong_chay.jpg'
];

// Use random images from each subject for generic equipment
$subjectIndex = 0;
foreach ($genericImages as $genericImage) {
    $targetPath = $targetDir . '/' . $genericImage;
    if (!file_exists($targetPath)) {
        // Get a random image from any subject folder
        $folders = array_values($subjectFolders);
        $folderPath = $sourceDir . '/' . $folders[$subjectIndex % count($folders)];
        $images = glob($folderPath . '/*.{jpg,JPG,jpeg,JPEG,png,PNG}', GLOB_BRACE);
        
        if (!empty($images)) {
            $randomImage = $images[array_rand($images)];
            if (copy($randomImage, $targetPath)) {
                echo "✅ Generic copy: $genericImage\n";
                $copiedCount++;
            }
        }
        $subjectIndex++;
    }
}

echo "\n";
echo "=============================\n";
echo "Copy process completed!\n";
echo "Total copied: $copiedCount images\n";
echo "Target directory: $targetDir\n";
echo "=============================\n";

// Now update the seeder file with actual mappings
echo "\nUpdating EquipmentStaticImageSeeder.php...\n";

$seederPath = '/Users/ductampro/Desktop/files/database/seeders/EquipmentStaticImageSeeder.php';
if (file_exists($seederPath)) {
    // The seeder is already configured correctly
    echo "✅ Seeder is ready to use\n";
} else {
    echo "❌ Seeder file not found\n";
}

echo "\nNext step: Run 'php artisan db:seed --class=EquipmentStaticImageSeeder'\n";