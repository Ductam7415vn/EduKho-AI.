<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipment;

class EquipmentImageUrlSeeder extends Seeder
{
    /**
     * Danh sách URL ảnh mẫu cho từng loại thiết bị
     * Sử dụng placeholder images từ các dịch vụ miễn phí
     */
    private $imageUrls = [
        // Vật lý
        'TBVL001' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=800&h=600&fit=crop', // Lab equipment
        'TBVL002' => 'https://images.unsplash.com/photo-1581093458791-9d42e3c7e117?w=800&h=600&fit=crop', // Force meter
        'TBVL003' => 'https://images.unsplash.com/photo-1636466497217-26a8cbeaf0aa?w=800&h=600&fit=crop', // Sensors
        'TBVL004' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=600&fit=crop', // Electric kit
        'TBVL005' => 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?w=800&h=600&fit=crop', // Oscilloscope
        
        // Hóa học  
        'TBHH001' => 'https://images.unsplash.com/photo-1582719471384-894fbb16e074?w=800&h=600&fit=crop', // Chemistry set
        'TBHH002' => 'https://images.unsplash.com/photo-1576086213369-97a306d36557?w=800&h=600&fit=crop', // Scale
        'TBHH003' => 'https://images.unsplash.com/photo-1530587191325-3db32d826c18?w=800&h=600&fit=crop', // Microscope
        'TBHH004' => 'https://images.unsplash.com/photo-1609952048180-7b35ea6b083b?w=800&h=600&fit=crop', // pH meter
        
        // Sinh học
        'TBSH001' => 'https://images.unsplash.com/photo-1518152006812-edab29b069ac?w=800&h=600&fit=crop', // Bio microscope
        'TBSH002' => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=800&h=600&fit=crop', // Body model
        'TBSH003' => 'https://images.unsplash.com/photo-1628595351029-c2bf17511435?w=800&h=600&fit=crop', // DNA model
        
        // Toán học
        'TBTOAN001' => 'https://images.unsplash.com/photo-1635070041409-e63e783ce3c1?w=800&h=600&fit=crop', // Geometry
        'TBTOAN002' => 'https://images.unsplash.com/photo-1607969391721-9b0a26390c21?w=800&h=600&fit=crop', // Ruler
        'TBTOAN003' => 'https://images.unsplash.com/photo-1611125832047-1d7ad1e8e48f?w=800&h=600&fit=crop', // Compass
        'TBTOAN004' => 'https://images.unsplash.com/photo-1611532736597-de2d4265fba3?w=800&h=600&fit=crop', // Calculator
        
        // Tin học
        'TBTIN001' => 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=800&h=600&fit=crop', // Computer lab
        'TBTIN002' => 'https://images.unsplash.com/photo-1478737270239-2f02b77fc618?w=800&h=600&fit=crop', // Projector
        'TBTIN003' => 'https://images.unsplash.com/photo-1553877522-43269d4ea984?w=800&h=600&fit=crop', // Interactive
        'TBTIN004' => 'https://images.unsplash.com/photo-1561557944-6e7860d1a7eb?w=800&h=600&fit=crop', // Robot kit
        
        // Thể dục
        'TBTD001' => 'https://images.unsplash.com/photo-1551958219-acbc608c6377?w=800&h=600&fit=crop', // Soccer ball
        'TBTD002' => 'https://images.unsplash.com/photo-1547347298-4074fc3086f0?w=800&h=600&fit=crop', // Volleyball
        'TBTD003' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=800&h=600&fit=crop', // Badminton
        'TBTD004' => 'https://images.unsplash.com/photo-1554068865-24cecd4e34b8?w=800&h=600&fit=crop', // Ping pong
        
        // Âm nhạc
        'TBAN001' => 'https://images.unsplash.com/photo-1557425529-b1ae3f04b77e?w=800&h=600&fit=crop', // Keyboard
        'TBAN002' => 'https://images.unsplash.com/photo-1519892300165-cb5542fb47c7?w=800&h=600&fit=crop', // Drums
        
        // Mỹ thuật
        'TBMT001' => 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=800&h=600&fit=crop', // Art easel
        'TBMT002' => 'https://images.unsplash.com/photo-1581592196522-f4738d0b09b6?w=800&h=600&fit=crop', // Paint set
    ];
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cập nhật ảnh cho thiết bị dựa trên URL mapping
        foreach ($this->imageUrls as $code => $url) {
            $equipment = Equipment::where('base_code', $code)->first();
            
            if ($equipment) {
                // Download và lưu ảnh
                $imagePath = $this->downloadAndSaveImage($url, $code);
                
                if ($imagePath) {
                    $equipment->update(['image' => $imagePath]);
                    $this->command->info("Added image for {$equipment->name}");
                }
            }
        }
        
        // Tạo placeholder cho thiết bị còn lại
        $this->command->info("\nAdding placeholder images for remaining equipment...");
        
        $remainingEquipment = Equipment::whereNull('image')
            ->orWhere('image', '')
            ->get();
            
        foreach ($remainingEquipment as $equipment) {
            // Sử dụng placeholder.com để tạo ảnh với text
            $text = urlencode($equipment->base_code);
            $colors = ['3498db', '2ecc71', 'e74c3c', 'f39c12', '9b59b6', '1abc9c', '34495e'];
            $color = $colors[array_rand($colors)];
            
            $url = "https://via.placeholder.com/800x600/{$color}/ffffff?text={$text}";
            $imagePath = $this->downloadAndSaveImage($url, $equipment->base_code);
            
            if ($imagePath) {
                $equipment->update(['image' => $imagePath]);
                $this->command->info("Added placeholder for {$equipment->name}");
            }
        }
    }
    
    /**
     * Download và lưu ảnh vào storage
     */
    private function downloadAndSaveImage($url, $code)
    {
        try {
            $contents = file_get_contents($url);
            if ($contents === false) {
                return null;
            }
            
            $filename = strtolower($code) . '_' . time() . '.jpg';
            $path = 'equipment/' . $filename;
            
            \Storage::disk('public')->put($path, $contents);
            
            return $path;
        } catch (\Exception $e) {
            $this->command->error("Failed to download image for {$code}: " . $e->getMessage());
            return null;
        }
    }
}