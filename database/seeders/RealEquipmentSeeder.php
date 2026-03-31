<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\EquipmentItem;
use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RealEquipmentSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy phòng
        $rooms = Room::all()->keyBy('name');
        
        // Danh sách thiết bị giáo dục chính từ file kiểm kê
        $equipments = [
            // Thiết bị Ngữ văn
            [
                'name' => 'Video/clip/phim tư liệu về Văn học dân gian Việt Nam',
                'base_code' => 'GD38-0002VN',
                'unit' => 'Bộ',
                'price' => 178600,
                'category_subject' => 'Ngữ văn',
                'grade_level' => '7',
                'security_level' => 'normal',
                'quantity' => 1,
                'room' => 'Kho Tổng',
                'description' => 'Video dạy học về truyện dân gian, độ phân giải HD, thời lượng 3 phút'
            ],
            [
                'name' => 'Video/clip/ phim tư liệu về thơ văn của Chủ tịch Hồ Chí Minh',
                'base_code' => 'GD38-0003VN',
                'unit' => 'Bộ',
                'price' => 178600,
                'category_subject' => 'Ngữ văn',
                'grade_level' => '7',
                'security_level' => 'normal',
                'quantity' => 1,
                'room' => 'Kho Tổng',
                'description' => 'Tư liệu về thời đại, cuộc đời, sự nghiệp sáng tác của Bác Hồ'
            ],
            
            // Thiết bị Toán học
            [
                'name' => 'Bộ thiết bị dạy học hình học trực quan',
                'base_code' => '07DTOHHTQ0021HA',
                'unit' => 'Bộ',
                'price' => 650000,
                'category_subject' => 'Toán học',
                'grade_level' => '7,8',
                'security_level' => 'normal',
                'quantity' => 4,
                'room' => 'Phòng Tin học',
                'description' => 'Gồm hình hộp chữ nhật, hình lập phương, lăng trụ tam giác bằng nhựa trong'
            ],
            [
                'name' => 'Thước thẳng có chia vạch',
                'base_code' => 'TBTO001',
                'unit' => 'Cái',
                'price' => 85000,
                'category_subject' => 'Toán học',
                'grade_level' => 'All',
                'security_level' => 'normal',
                'quantity' => 10,
                'room' => 'Kho Tổng',
                'description' => 'Thước gỗ dài 100cm, chia vạch chính xác'
            ],
            [
                'name' => 'Compa bảng',
                'base_code' => 'TBTO002',
                'unit' => 'Cái',
                'price' => 120000,
                'category_subject' => 'Toán học',
                'grade_level' => 'All',
                'security_level' => 'normal',
                'quantity' => 5,
                'room' => 'Kho Tổng',
                'description' => 'Compa từ tính cho bảng, đường kính 50cm'
            ],
            [
                'name' => 'Eke bảng',
                'base_code' => 'TBTO003',
                'unit' => 'Bộ',
                'price' => 150000,
                'category_subject' => 'Toán học',
                'grade_level' => 'All',
                'security_level' => 'normal',
                'quantity' => 5,
                'room' => 'Kho Tổng',
                'description' => 'Bộ eke 30-60-90 độ và 45-45-90 độ'
            ],
            
            // Thiết bị GDCD
            [
                'name' => 'Tranh về việc học tập tự giác tích cực của HS',
                'base_code' => 'T-GDCD-2-09',
                'unit' => 'Tờ',
                'price' => 95000,
                'category_subject' => 'GDCD',
                'grade_level' => '10,11,12',
                'security_level' => 'normal',
                'quantity' => 1,
                'room' => 'Kho Tổng',
                'description' => 'Tranh (720x1020)mm, in 4 màu trên giấy couché 200g'
            ],
            [
                'name' => 'Tranh về phòng chống bạo lực học đường',
                'base_code' => 'T-GDCD-2-18',
                'unit' => 'Tờ',
                'price' => 95000,
                'category_subject' => 'GDCD',
                'grade_level' => '10,11,12',
                'security_level' => 'normal',
                'quantity' => 1,
                'room' => 'Kho Tổng',
                'description' => 'Sơ đồ kĩ năng ứng phó với bạo lực học đường'
            ],
            
            // Thiết bị Lịch sử
            [
                'name' => 'Lược đồ Đông Nam Á và quốc gia ở Đông Nam Á',
                'base_code' => 'T-LS-2-12',
                'unit' => 'Tờ',
                'price' => 120000,
                'category_subject' => 'Lịch sử',
                'grade_level' => '10,11',
                'security_level' => 'normal',
                'quantity' => 1,
                'room' => 'Kho Tổng',
                'description' => 'Bản đồ kích thước (1000x1400)mm thể hiện các quốc gia Đông Nam Á'
            ],
            
            // Thiết bị Địa lý
            [
                'name' => 'Bản đồ địa lí tự nhiên Việt Nam',
                'base_code' => 'BD-DL-VN-01',
                'unit' => 'Tờ',
                'price' => 250000,
                'category_subject' => 'Địa lý',
                'grade_level' => '10,11,12',
                'security_level' => 'normal',
                'quantity' => 2,
                'room' => 'Kho Tổng',
                'description' => 'Bản đồ (1200x1600)mm, tỉ lệ 1:1.000.000'
            ],
            [
                'name' => 'Bản đồ hành chính Việt Nam',
                'base_code' => 'BD-DL-VN-02',
                'unit' => 'Tờ',
                'price' => 250000,
                'category_subject' => 'Địa lý',
                'grade_level' => '10,11,12',
                'security_level' => 'normal',
                'quantity' => 2,
                'room' => 'Kho Tổng',
                'description' => 'Bản đồ hành chính 63 tỉnh thành'
            ],
            [
                'name' => 'La bàn thực địa',
                'base_code' => 'TBDL003',
                'unit' => 'Cái',
                'price' => 85000,
                'category_subject' => 'Địa lý',
                'grade_level' => '10,11,12',
                'security_level' => 'normal',
                'quantity' => 15,
                'room' => 'Kho Tổng',
                'description' => 'La bàn định hướng dã ngoại'
            ],
            
            // Thiết bị Vật lý
            [
                'name' => 'Bộ thí nghiệm quang học',
                'base_code' => 'TBVL-QH-01',
                'unit' => 'Bộ',
                'price' => 2500000,
                'category_subject' => 'Vật lý',
                'grade_level' => '11,12',
                'security_level' => 'normal',
                'quantity' => 5,
                'room' => 'Phòng TH Vật lý',
                'description' => 'Gồm nguồn laser, gương, thấu kính, màn chắn'
            ],
            [
                'name' => 'Bộ thí nghiệm điện học',
                'base_code' => 'TBVL-DH-01',
                'unit' => 'Bộ',
                'price' => 1850000,
                'category_subject' => 'Vật lý',
                'grade_level' => '11',
                'security_level' => 'normal',
                'quantity' => 8,
                'room' => 'Phòng TH Vật lý',
                'description' => 'Gồm nguồn điện, ampe kế, vôn kế, điện trở, dây nối'
            ],
            [
                'name' => 'Bộ thí nghiệm cơ học',
                'base_code' => 'TBVL-CH-01',
                'unit' => 'Bộ',
                'price' => 1650000,
                'category_subject' => 'Vật lý',
                'grade_level' => '10',
                'security_level' => 'normal',
                'quantity' => 6,
                'room' => 'Phòng TH Vật lý',
                'description' => 'Xe lăn, máng nghiêng, lực kế, ròng rọc'
            ],
            [
                'name' => 'Máy phát tần số âm',
                'base_code' => 'TBVL-AM-01',
                'unit' => 'Cái',
                'price' => 3200000,
                'category_subject' => 'Vật lý',
                'grade_level' => '12',
                'security_level' => 'normal',
                'quantity' => 2,
                'room' => 'Phòng TH Vật lý',
                'description' => 'Dải tần 20Hz-20kHz, công suất 10W'
            ],
            
            // Thiết bị Hóa học
            [
                'name' => 'Bộ dụng cụ thí nghiệm hóa vô cơ',
                'base_code' => 'TBHH-VC-01',
                'unit' => 'Bộ',
                'price' => 950000,
                'category_subject' => 'Hóa học',
                'grade_level' => '10,11',
                'security_level' => 'normal',
                'quantity' => 10,
                'room' => 'Phòng TH Hóa học',
                'description' => 'Ống nghiệm, cốc đong, bình tam giác, đèn cồn'
            ],
            [
                'name' => 'Tủ hút hóa chất',
                'base_code' => 'TBHH-TH-01',
                'unit' => 'Cái',
                'price' => 25000000,
                'category_subject' => 'Hóa học',
                'grade_level' => 'All',
                'security_level' => 'high_security',
                'quantity' => 1,
                'room' => 'Phòng TH Hóa học',
                'description' => 'Tủ hút công suất 1000m3/h, kích thước 1500x750x2400mm'
            ],
            [
                'name' => 'Cân điện tử phân tích',
                'base_code' => 'TBHH-CD-01',
                'unit' => 'Cái',
                'price' => 8500000,
                'category_subject' => 'Hóa học',
                'grade_level' => 'All',
                'security_level' => 'normal',
                'quantity' => 2,
                'room' => 'Phòng TH Hóa học',
                'description' => 'Độ chính xác 0.0001g, max 220g'
            ],
            [
                'name' => 'Bình khí Nitơ',
                'base_code' => 'TBHH-KH-N2',
                'unit' => 'Bình',
                'price' => 850000,
                'category_subject' => 'Hóa học',
                'grade_level' => '11,12',
                'security_level' => 'high_security',
                'quantity' => 2,
                'room' => 'Phòng TH Hóa học',
                'description' => 'Bình 40 lít, áp suất 150 bar'
            ],
            
            // Thiết bị Sinh học
            [
                'name' => 'Kính hiển vi quang học 2 mắt',
                'base_code' => 'TBSH-KHV-01',
                'unit' => 'Cái',
                'price' => 4500000,
                'category_subject' => 'Sinh học',
                'grade_level' => '10,11,12',
                'security_level' => 'normal',
                'quantity' => 15,
                'room' => 'Phòng TH Sinh học',
                'description' => 'Độ phóng đại 40x-1000x, có đèn LED'
            ],
            [
                'name' => 'Bộ tiêu bản sinh học thực vật',
                'base_code' => 'TBSH-TB-TV',
                'unit' => 'Bộ',
                'price' => 1200000,
                'category_subject' => 'Sinh học',
                'grade_level' => '10,11',
                'security_level' => 'normal',
                'quantity' => 5,
                'room' => 'Phòng TH Sinh học',
                'description' => 'Gồm 50 tiêu bản các loại tế bào, mô thực vật'
            ],
            [
                'name' => 'Mô hình ADN',
                'base_code' => 'TBSH-MH-ADN',
                'unit' => 'Cái',
                'price' => 850000,
                'category_subject' => 'Sinh học',
                'grade_level' => '12',
                'security_level' => 'normal',
                'quantity' => 2,
                'room' => 'Phòng TH Sinh học',
                'description' => 'Mô hình xoắn kép ADN, cao 60cm'
            ],
            [
                'name' => 'Mô hình cơ thể người',
                'base_code' => 'TBSH-MH-NT',
                'unit' => 'Cái',
                'price' => 3500000,
                'category_subject' => 'Sinh học',
                'grade_level' => '11',
                'security_level' => 'normal',
                'quantity' => 1,
                'room' => 'Phòng TH Sinh học',
                'description' => 'Mô hình giải phẫu cơ thể người, cao 170cm'
            ],
            
            // Thiết bị Tin học
            [
                'name' => 'Máy tính để bàn học sinh',
                'base_code' => 'TBTI-MT-HS',
                'unit' => 'Bộ',
                'price' => 8500000,
                'category_subject' => 'Tin học',
                'grade_level' => 'All',
                'security_level' => 'normal',
                'quantity' => 25,
                'room' => 'Phòng Tin học',
                'description' => 'Core i3, RAM 8GB, SSD 256GB, màn hình 21.5"'
            ],
            [
                'name' => 'Máy chiếu',
                'base_code' => 'TBTI-MC-01',
                'unit' => 'Cái',
                'price' => 12000000,
                'category_subject' => 'Tin học',
                'grade_level' => 'All',
                'security_level' => 'normal',
                'quantity' => 1,
                'room' => 'Phòng Tin học',
                'description' => '3500 Lumens, Full HD, kết nối HDMI/VGA'
            ],
            [
                'name' => 'Bộ kit Arduino cho học sinh',
                'base_code' => 'TBTI-ARD-01',
                'unit' => 'Bộ',
                'price' => 650000,
                'category_subject' => 'Tin học',
                'grade_level' => '11,12',
                'security_level' => 'normal',
                'quantity' => 15,
                'room' => 'Phòng Tin học',
                'description' => 'Board Arduino Uno, cảm biến, LED, motor'
            ],
            
            // Thiết bị Thể dục
            [
                'name' => 'Bóng đá số 5',
                'base_code' => 'TBTD-BD-05',
                'unit' => 'Quả',
                'price' => 250000,
                'category_subject' => 'Thể dục',
                'grade_level' => 'All',
                'security_level' => 'normal',
                'quantity' => 10,
                'room' => 'Kho QPAN',
                'description' => 'Bóng da PU, tiêu chuẩn thi đấu'
            ],
            [
                'name' => 'Bóng chuyền',
                'base_code' => 'TBTD-BC-01',
                'unit' => 'Quả',
                'price' => 350000,
                'category_subject' => 'Thể dục',
                'grade_level' => 'All',
                'security_level' => 'normal',
                'quantity' => 8,
                'room' => 'Kho QPAN',
                'description' => 'Bóng chuyền Mikasa, tiêu chuẩn thi đấu'
            ],
            [
                'name' => 'Lưới cầu lông',
                'base_code' => 'TBTD-CL-01',
                'unit' => 'Cái',
                'price' => 450000,
                'category_subject' => 'Thể dục',
                'grade_level' => 'All',
                'security_level' => 'normal',
                'quantity' => 4,
                'room' => 'Kho QPAN',
                'description' => 'Lưới tiêu chuẩn, sợi nilon bền'
            ],
            
            // Thiết bị GDQP-AN
            [
                'name' => 'Súng bắn tập AK mô hình',
                'base_code' => 'TBQP-AK-01',
                'unit' => 'Khẩu',
                'price' => 0,
                'category_subject' => 'QPAN',
                'grade_level' => '11,12',
                'security_level' => 'high_security',
                'quantity' => 5,
                'room' => 'Kho QPAN',
                'description' => 'Súng AK-47 hoán cải, đã cắt cò, dùng cho huấn luyện'
            ],
            [
                'name' => 'Lựu đạn tập',
                'base_code' => 'TBQP-LD-01',
                'unit' => 'Quả',
                'price' => 0,
                'category_subject' => 'QPAN',
                'grade_level' => '12',
                'security_level' => 'high_security',
                'quantity' => 10,
                'room' => 'Kho QPAN',
                'description' => 'Mô hình lựu đạn F1 dùng tập ném'
            ],
            [
                'name' => 'Bản đồ địa hình quân sự',
                'base_code' => 'TBQP-BD-01',
                'unit' => 'Tờ',
                'price' => 150000,
                'category_subject' => 'QPAN',
                'grade_level' => '11,12',
                'security_level' => 'normal',
                'quantity' => 5,
                'room' => 'Kho QPAN',
                'description' => 'Bản đồ tỉ lệ 1:25.000'
            ],
            
            // Thiết bị dùng chung
            [
                'name' => 'Máy photocopy đa năng',
                'base_code' => 'TBDC-PC-01',
                'unit' => 'Cái',
                'price' => 45000000,
                'category_subject' => 'Dùng chung',
                'grade_level' => 'All',
                'security_level' => 'normal',
                'quantity' => 1,
                'room' => 'Kho Tổng',
                'description' => 'Photocopy, scan, in mạng, khổ A3'
            ],
            [
                'name' => 'Loa kéo di động',
                'base_code' => 'TBDC-LK-01',
                'unit' => 'Cái',
                'price' => 5500000,
                'category_subject' => 'Dùng chung',
                'grade_level' => 'All',
                'security_level' => 'normal',
                'quantity' => 2,
                'room' => 'Kho Tổng',
                'description' => 'Công suất 300W, 2 micro không dây'
            ],
            [
                'name' => 'Máy chiếu vật thể 3D',
                'base_code' => 'TBDC-3D-01',
                'unit' => 'Cái',
                'price' => 18000000,
                'category_subject' => 'Dùng chung',
                'grade_level' => 'All',
                'security_level' => 'normal',
                'quantity' => 1,
                'room' => 'Kho Tổng',
                'description' => 'Chiếu vật thể thật, zoom quang học 12x'
            ]
        ];

        // Tạo thiết bị và cá thể
        foreach ($equipments as $eq) {
            $room = $rooms->get($eq['room']) ?: $rooms->first();
            
            $equipment = Equipment::create([
                'name' => $eq['name'],
                'base_code' => $eq['base_code'],
                'unit' => $eq['unit'],
                'price' => $eq['price'],
                'category_subject' => $eq['category_subject'],
                'grade_level' => $eq['grade_level'],
                'security_level' => $eq['security_level'],
                'description' => $eq['description'] ?? null,
                'is_digital' => false,
                'file_url' => null,
                'file_type' => null,
                'file_size' => null
            ]);

            // Tạo cá thể vật lý cho thiết bị
            for ($i = 1; $i <= $eq['quantity']; $i++) {
                $status = 'available';
                // 10% thiết bị đang bảo trì
                if ($i == $eq['quantity'] && rand(1, 10) <= 1) {
                    $status = 'maintenance';
                }
                
                EquipmentItem::create([
                    'equipment_id' => $equipment->id,
                    'room_id' => $room->id,
                    'specific_code' => $equipment->base_code . '.' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'status' => $status,
                    'year_acquired' => rand(2020, 2024),
                ]);
            }
        }

        $this->command->info('✅ Đã tạo ' . count($equipments) . ' loại thiết bị từ biểu kiểm kê thực tế');
        $this->command->info('✅ Đã tạo ' . EquipmentItem::count() . ' cá thể thiết bị');
    }
}