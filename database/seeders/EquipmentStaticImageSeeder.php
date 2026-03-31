<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipment;

class EquipmentStaticImageSeeder extends Seeder
{
    /**
     * Cập nhật ảnh tĩnh cho thiết bị
     * Giả sử bạn đã đặt các file ảnh trong storage/app/public/equipment/
     */
    private $equipmentImages = [
        // Ngữ văn
        'GD38-0002VN' => 'equipment/video_van_hoc_dan_gian.jpg',
        'GD38-0003VN' => 'equipment/video_tho_van_bac_ho.jpg',
        
        // Toán học
        '07DTOHHTQ0021HA' => 'equipment/bo_hinh_hoc_truc_quan.jpg',
        'TBTO001' => 'equipment/thuoc_thang_chia_vach.jpg',
        'TBTO002' => 'equipment/compa_bang.jpg',
        'TBTO003' => 'equipment/eke_bang.jpg',
        
        // GDCD
        'T-GDCD-2-09' => 'equipment/tranh_hoc_tap_tu_giac.jpg',
        'T-GDCD-2-18' => 'equipment/tranh_phong_chong_bao_luc.jpg',
        
        // Lịch sử
        'T-LS-2-12' => 'equipment/luoc_do_dong_nam_a.jpg',
        
        // Địa lý
        'BD-DL-VN-01' => 'equipment/ban_do_tu_nhien_vn.jpg',
        'BD-DL-VN-02' => 'equipment/ban_do_hanh_chinh_vn.jpg',
        'TBDL003' => 'equipment/la_ban_thuc_dia.jpg',
        
        // Vật lý
        'TBVL-QH-01' => 'equipment/bo_thi_nghiem_quang_hoc.jpg',
        'TBVL-DH-01' => 'equipment/bo_thi_nghiem_dien_hoc.jpg',
        'TBVL-CH-01' => 'equipment/bo_thi_nghiem_co_hoc.jpg',
        'TBVL-AM-01' => 'equipment/may_phat_tan_so_am.jpg',
        
        // Hóa học
        'TBHH-VC-01' => 'equipment/bo_thi_nghiem_hoa_vo_co.jpg',
        'TBHH-TH-01' => 'equipment/tu_hut_hoa_chat.jpg',
        'TBHH-CD-01' => 'equipment/can_dien_tu_phan_tich.jpg',
        'TBHH-KH-N2' => 'equipment/binh_khi_nito.jpg',
        
        // Sinh học
        'TBSH-KHV-01' => 'equipment/kinh_hien_vi_2_mat.jpg',
        'TBSH-TB-TV' => 'equipment/bo_tieu_ban_thuc_vat.jpg',
        'TBSH-MH-ADN' => 'equipment/mo_hinh_adn.jpg',
        'TBSH-MH-NT' => 'equipment/mo_hinh_co_the_nguoi.jpg',
        
        // Tin học
        'TBTI-MT-HS' => 'equipment/may_tinh_hoc_sinh.jpg',
        'TBTI-MC-01' => 'equipment/may_chieu.jpg',
        'TBTI-ARD-01' => 'equipment/bo_kit_arduino.jpg',
        
        // Thể dục
        'TBTD-BD-05' => 'equipment/bong_da_so_5.jpg',
        'TBTD-BC-01' => 'equipment/bong_chuyen.jpg',
        'TBTD-CL-01' => 'equipment/luoi_cau_long.jpg',
        
        // GDQP-AN
        'TBQP-AK-01' => 'equipment/sung_ak_mo_hinh.jpg',
        'TBQP-LD-01' => 'equipment/luu_dan_tap.jpg',
        'TBQP-BD-01' => 'equipment/ban_do_dia_hinh_quan_su.jpg',
        
        // Dùng chung
        'TBDC-PC-01' => 'equipment/may_photocopy.jpg',
        'TBDC-LK-01' => 'equipment/loa_keo_di_dong.jpg',
        'TBDC-3D-01' => 'equipment/may_chieu_3d.jpg',
    ];

    public function run(): void
    {
        foreach ($this->equipmentImages as $code => $imagePath) {
            $equipment = Equipment::where('base_code', $code)->first();
            
            if ($equipment) {
                $equipment->update(['image' => $imagePath]);
                $this->command->info("Updated image for {$equipment->name} - {$code}");
            }
        }
        
        $this->command->info("\nImage update completed!");
        $updated = Equipment::whereNotNull('image')->count();
        $total = Equipment::count();
        $this->command->info("Updated {$updated}/{$total} equipment with images.");
    }
}