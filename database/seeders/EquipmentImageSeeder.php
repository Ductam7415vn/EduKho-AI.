<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class EquipmentImageSeeder extends Seeder
{
    /**
     * Mapping thiết bị với ảnh mẫu
     * Key: mã thiết bị (base_code)
     * Value: tên file ảnh sẽ tạo
     */
    private $equipmentImages = [
        // Vật lý
        'TBVL001' => 'bo_thi_nghiem_co_hoc.jpg',
        'TBVL002' => 'may_do_luc.jpg', 
        'TBVL003' => 'cam_bien_chuyen_dong.jpg',
        'TBVL004' => 'bo_thi_nghiem_dien.jpg',
        'TBVL005' => 'dao_dong_ky.jpg',
        'TBVL006' => 'may_phat_am_tan.jpg',
        
        // Hóa học
        'TBHH001' => 'bo_dung_cu_hoa_chat.jpg',
        'TBHH002' => 'can_dien_tu.jpg',
        'TBHH003' => 'kinh_hien_vi.jpg',
        'TBHH004' => 'may_do_ph.jpg',
        'TBHH005' => 'tu_hut_hoa_chat.jpg',
        
        // Sinh học
        'TBSH001' => 'kinh_hien_vi_sinh_hoc.jpg',
        'TBSH002' => 'mo_hinh_co_the.jpg',
        'TBSH003' => 'mo_hinh_adn.jpg',
        'TBSH004' => 'bo_tieu_ban_hien_vi.jpg',
        
        // Toán học
        'TBTOAN001' => 'bo_hinh_hoc_khong_gian.jpg',
        'TBTOAN002' => 'thuoc_ke_go.jpg',
        'TBTOAN003' => 'compa.jpg',
        'TBTOAN004' => 'may_tinh_casio.jpg',
        
        // Tin học
        'TBTIN001' => 'phong_may_tinh.jpg',
        'TBTIN002' => 'may_chieu.jpg',
        'TBTIN003' => 'man_hinh_tuong_tac.jpg',
        'TBTIN004' => 'bo_thuc_hanh_robot.jpg',
        
        // Ngữ văn
        'TBNV001' => 'sach_giao_khoa.jpg',
        'TBNV002' => 'tranh_minh_hoa.jpg',
        
        // Lịch sử
        'TBLS001' => 'ban_do_lich_su.jpg',
        'TBLS002' => 'sach_tu_lieu.jpg',
        
        // Địa lý
        'TBDL001' => 'qua_dia_cau.jpg',
        'TBDL002' => 'ban_do_viet_nam.jpg',
        'TBDL003' => 'la_ban.jpg',
        
        // Ngoại ngữ
        'TBNN001' => 'may_nghe.jpg',
        'TBNN002' => 'bang_chu_cai.jpg',
        
        // GDCD
        'TBGDCD001' => 'tai_lieu_phap_luat.jpg',
        
        // Thể dục
        'TBTD001' => 'bong_da.jpg',
        'TBTD002' => 'bong_chuyen.jpg',
        'TBTD003' => 'vot_cau_long.jpg',
        'TBTD004' => 'ban_bong_ban.jpg',
        
        // GDQP
        'TBQP001' => 'mo_hinh_sung.jpg',
        'TBQP002' => 'ban_do_chien_thuat.jpg',
        
        // Âm nhạc
        'TBAN001' => 'dan_organ.jpg',
        'TBAN002' => 'trong_bo.jpg',
        
        // Mỹ thuật
        'TBMT001' => 'gia_ve.jpg',
        'TBMT002' => 'mau_ve.jpg',
        
        // Công nghệ
        'TBCN001' => 'bo_dung_cu_co_khi.jpg',
        'TBCN002' => 'may_khau.jpg',
    ];
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo thư mục equipment trong storage nếu chưa có
        Storage::disk('public')->makeDirectory('equipment');
        
        foreach ($this->equipmentImages as $code => $imageName) {
            $equipment = Equipment::where('base_code', $code)->first();
            
            if ($equipment && !$equipment->image) {
                // Tạo placeholder image với màu ngẫu nhiên
                $imagePath = $this->createPlaceholderImage($imageName, $equipment->name);
                
                if ($imagePath) {
                    $equipment->update(['image' => $imagePath]);
                    $this->command->info("Added image for {$equipment->name}");
                }
            }
        }
        
        // Thêm ảnh cho các thiết bị còn lại chưa có ảnh
        $equipmentsWithoutImage = Equipment::whereNull('image')->get();
        
        foreach ($equipmentsWithoutImage as $equipment) {
            $imageName = $this->generateImageName($equipment);
            $imagePath = $this->createPlaceholderImage($imageName, $equipment->name);
            
            if ($imagePath) {
                $equipment->update(['image' => $imagePath]);
                $this->command->info("Added placeholder image for {$equipment->name}");
            }
        }
    }
    
    /**
     * Tạo placeholder image với text
     */
    private function createPlaceholderImage($filename, $text)
    {
        // Tạo image 800x600 với GD library
        $width = 800;
        $height = 600;
        
        // Tạo image resource
        $image = imagecreatetruecolor($width, $height);
        
        // Màu nền ngẫu nhiên
        $bgColors = [
            ['r' => 52, 'g' => 152, 'b' => 219],   // Xanh dương
            ['r' => 46, 'g' => 204, 'b' => 113],   // Xanh lá
            ['r' => 155, 'g' => 89, 'b' => 182],   // Tím
            ['r' => 231, 'g' => 76, 'b' => 60],    // Đỏ
            ['r' => 241, 'g' => 196, 'b' => 15],   // Vàng
            ['r' => 230, 'g' => 126, 'b' => 34],   // Cam
            ['r' => 149, 'g' => 165, 'b' => 166],  // Xám
            ['r' => 52, 'g' => 73, 'b' => 94],     // Xanh đen
        ];
        
        $bgColor = $bgColors[array_rand($bgColors)];
        $background = imagecolorallocate($image, $bgColor['r'], $bgColor['g'], $bgColor['b']);
        $textColor = imagecolorallocate($image, 255, 255, 255);
        
        // Fill background
        imagefilledrectangle($image, 0, 0, $width, $height, $background);
        
        // Add grid pattern
        $gridColor = imagecolorallocatealpha($image, 255, 255, 255, 100);
        for ($i = 0; $i < $width; $i += 50) {
            imageline($image, $i, 0, $i, $height, $gridColor);
        }
        for ($i = 0; $i < $height; $i += 50) {
            imageline($image, 0, $i, $width, $i, $gridColor);
        }
        
        // Add text
        $fontSize = 5; // Built-in font size (1-5)
        $textWidth = imagefontwidth($fontSize) * strlen($text);
        $textHeight = imagefontheight($fontSize);
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2;
        
        // Add shadow
        $shadowColor = imagecolorallocatealpha($image, 0, 0, 0, 50);
        imagestring($image, $fontSize, $x + 2, $y + 2, $text, $shadowColor);
        
        // Add main text
        imagestring($image, $fontSize, $x, $y, $text, $textColor);
        
        // Save image
        $path = 'equipment/' . $filename;
        $fullPath = storage_path('app/public/' . $path);
        
        // Ensure directory exists
        $directory = dirname($fullPath);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
        
        // Save as JPEG
        imagejpeg($image, $fullPath, 90);
        imagedestroy($image);
        
        return $path;
    }
    
    /**
     * Generate image name from equipment
     */
    private function generateImageName($equipment)
    {
        $name = strtolower($equipment->base_code);
        $name = preg_replace('/[^a-z0-9]+/', '_', $name);
        return $name . '_placeholder.jpg';
    }
}