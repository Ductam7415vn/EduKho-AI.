<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Equipment;

echo "KIỂM TRA TÌNH TRẠNG ẢNH CỦA THIẾT BỊ\n";
echo "=====================================\n\n";

// Get all equipment
$allEquipment = Equipment::orderBy('base_code')->get();
$totalEquipment = $allEquipment->count();

// Equipment with images
$withImages = Equipment::whereNotNull('image')->where('image', '!=', '')->get();
$withImagesCount = $withImages->count();

// Equipment without images  
$withoutImages = Equipment::where(function($query) {
    $query->whereNull('image')->orWhere('image', '');
})->get();
$withoutImagesCount = $withoutImages->count();

echo "TỔNG QUAN:\n";
echo "- Tổng số thiết bị: $totalEquipment\n";
echo "- Đã có ảnh: $withImagesCount (" . round($withImagesCount/$totalEquipment*100, 1) . "%)\n";
echo "- Chưa có ảnh: $withoutImagesCount (" . round($withoutImagesCount/$totalEquipment*100, 1) . "%)\n\n";

// List of expected images based on seeder
$expectedImages = [
    'GD38-0002VN' => 'equipment/video_van_hoc_dan_gian.jpg',
    'GD38-0003VN' => 'equipment/video_tho_van_bac_ho.jpg',
    '07DTOHHTQ0021HA' => 'equipment/bo_hinh_hoc_truc_quan.jpg',
    'TBTO001' => 'equipment/thuoc_thang_chia_vach.jpg',
    'TBTO002' => 'equipment/compa_bang.jpg',
    'TBTO003' => 'equipment/eke_bang.jpg',
    'T-GDCD-2-09' => 'equipment/tranh_hoc_tap_tu_giac.jpg',
    'T-GDCD-2-18' => 'equipment/tranh_phong_chong_bao_luc.jpg',
    'T-LS-2-12' => 'equipment/luoc_do_dong_nam_a.jpg',
    'BD-DL-VN-01' => 'equipment/ban_do_tu_nhien_vn.jpg',
    'BD-DL-VN-02' => 'equipment/ban_do_hanh_chinh_vn.jpg',
    'TBDL003' => 'equipment/la_ban_thuc_dia.jpg',
    'TBVL-QH-01' => 'equipment/bo_thi_nghiem_quang_hoc.jpg',
    'TBVL-DH-01' => 'equipment/bo_thi_nghiem_dien_hoc.jpg',
    'TBVL-CH-01' => 'equipment/bo_thi_nghiem_co_hoc.jpg',
    'TBVL-AM-01' => 'equipment/may_phat_tan_so_am.jpg',
    'TBHH-VC-01' => 'equipment/bo_thi_nghiem_hoa_vo_co.jpg',
    'TBHH-TH-01' => 'equipment/tu_hut_hoa_chat.jpg',
    'TBHH-CD-01' => 'equipment/can_dien_tu_phan_tich.jpg',
    'TBHH-KH-N2' => 'equipment/binh_khi_nito.jpg',
    'TBSH-KHV-01' => 'equipment/kinh_hien_vi_2_mat.jpg',
    'TBSH-TB-TV' => 'equipment/bo_tieu_ban_thuc_vat.jpg',
    'TBSH-MH-ADN' => 'equipment/mo_hinh_adn.jpg',
    'TBSH-MH-NT' => 'equipment/mo_hinh_co_the_nguoi.jpg',
    'TBTI-MT-HS' => 'equipment/may_tinh_hoc_sinh.jpg',
    'TBTI-MC-01' => 'equipment/may_chieu.jpg',
    'TBTI-ARD-01' => 'equipment/bo_kit_arduino.jpg',
    'TBTD-BD-05' => 'equipment/bong_da_so_5.jpg',
    'TBTD-BC-01' => 'equipment/bong_chuyen.jpg',
    'TBTD-CL-01' => 'equipment/luoi_cau_long.jpg',
    'TBQP-AK-01' => 'equipment/sung_ak_mo_hinh.jpg',
    'TBQP-LD-01' => 'equipment/luu_dan_tap.jpg',
    'TBQP-BD-01' => 'equipment/ban_do_dia_hinh_quan_su.jpg',
    'TBDC-PC-01' => 'equipment/may_photocopy.jpg',
    'TBDC-LK-01' => 'equipment/loa_keo_di_dong.jpg',
    'TBDC-3D-01' => 'equipment/may_chieu_3d.jpg',
];

echo "CHI TIẾT THIẾT BỊ CHƯA CÓ ẢNH:\n";
echo "--------------------------------\n";

if ($withoutImagesCount > 0) {
    foreach ($withoutImages as $equipment) {
        echo "❌ {$equipment->base_code} - {$equipment->name}\n";
        
        // Check if this equipment should have an image
        if (isset($expectedImages[$equipment->base_code])) {
            $expectedImage = $expectedImages[$equipment->base_code];
            $fullPath = storage_path('app/public/' . $expectedImage);
            if (file_exists($fullPath)) {
                echo "   → File tồn tại nhưng chưa được cập nhật vào DB: $expectedImage\n";
            } else {
                echo "   → File ảnh không tồn tại: $expectedImage\n";
            }
        } else {
            echo "   → Không có trong danh sách mapping\n";
        }
    }
} else {
    echo "✅ Tất cả thiết bị đã có ảnh!\n";
}

echo "\n";
echo "CHI TIẾT THIẾT BỊ ĐÃ CÓ ẢNH:\n";
echo "-----------------------------\n";

$imageStats = [];
foreach ($withImages as $equipment) {
    $imagePath = storage_path('app/public/' . $equipment->image);
    $exists = file_exists($imagePath);
    
    if (!$exists) {
        echo "⚠️  {$equipment->base_code} - DB có ảnh nhưng file không tồn tại: {$equipment->image}\n";
    } else {
        $imageStats[] = [
            'code' => $equipment->base_code,
            'name' => $equipment->name,
            'image' => $equipment->image,
            'size' => filesize($imagePath)
        ];
    }
}

// Summary by subject
echo "\n";
echo "THỐNG KÊ THEO MÔN HỌC:\n";
echo "---------------------\n";

$subjects = Equipment::select('category_subject')
    ->selectRaw('COUNT(*) as total')
    ->selectRaw('SUM(CASE WHEN image IS NOT NULL AND image != "" THEN 1 ELSE 0 END) as with_image')
    ->groupBy('category_subject')
    ->orderBy('category_subject')
    ->get();

foreach ($subjects as $subject) {
    $percentage = $subject->total > 0 ? round($subject->with_image / $subject->total * 100, 1) : 0;
    echo sprintf("%-20s: %d/%d (%s%%)\n", 
        $subject->category_subject, 
        $subject->with_image, 
        $subject->total,
        $percentage
    );
}

echo "\n";
echo "GỢI Ý:\n";
echo "-------\n";
if ($withoutImagesCount > 0) {
    echo "1. Chạy seeder: php artisan db:seed --class=EquipmentStaticImageSeeder\n";
    echo "2. Nếu vẫn thiếu, kiểm tra file mapping trong seeder\n";
    echo "3. Hoặc thêm ảnh thủ công cho các thiết bị còn lại\n";
} else {
    echo "✅ Hoàn tất! Tất cả thiết bị đã có ảnh.\n";
}