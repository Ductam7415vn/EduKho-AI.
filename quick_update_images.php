<?php

// Script cập nhật nhanh ảnh vào database

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Equipment;
use Illuminate\Support\Facades\Storage;

echo "CẬP NHẬT ẢNH CHO THIẾT BỊ\n";
echo "==========================\n\n";

// Danh sách mapping
$imageMapping = [
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

$updated = 0;
$notFound = 0;
$noImage = 0;

foreach ($imageMapping as $code => $imagePath) {
    $equipment = Equipment::where('base_code', $code)->first();
    
    if (!$equipment) {
        echo "❌ Không tìm thấy thiết bị: $code\n";
        $notFound++;
        continue;
    }
    
    // Kiểm tra file tồn tại
    $fullPath = storage_path('app/public/' . $imagePath);
    if (!file_exists($fullPath)) {
        echo "⚠️  File không tồn tại: $imagePath cho thiết bị $code\n";
        $noImage++;
        continue;
    }
    
    // Cập nhật database
    $equipment->image = $imagePath;
    $equipment->save();
    echo "✅ Đã cập nhật: $code - {$equipment->name}\n";
    $updated++;
}

echo "\n";
echo "KẾT QUẢ:\n";
echo "========\n";
echo "✅ Cập nhật thành công: $updated thiết bị\n";
echo "❌ Không tìm thấy thiết bị: $notFound\n";
echo "⚠️  File ảnh không tồn tại: $noImage\n";

// Thống kê tổng quan
$totalEquipment = Equipment::count();
$equipmentWithImages = Equipment::whereNotNull('image')->where('image', '!=', '')->count();

echo "\n";
echo "TỔNG QUAN:\n";
echo "==========\n";
echo "Tổng thiết bị: $totalEquipment\n";
echo "Có ảnh: $equipmentWithImages (" . round($equipmentWithImages/$totalEquipment*100, 1) . "%)\n";
echo "Chưa có ảnh: " . ($totalEquipment - $equipmentWithImages) . "\n";