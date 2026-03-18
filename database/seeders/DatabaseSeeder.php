<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Equipment;
use App\Models\EquipmentItem;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ══════════════════════════════════════════════
        // 1. TỔ CHUYÊN MÔN
        // ══════════════════════════════════════════════
        $departments = [
            ['name' => 'Tổ Toán - Tin học'],
            ['name' => 'Tổ Vật lý - Công nghệ'],
            ['name' => 'Tổ Hóa học - Sinh học'],
            ['name' => 'Tổ Ngữ văn - Lịch sử - Địa lý'],
            ['name' => 'Tổ Ngoại ngữ'],
            ['name' => 'Tổ Giáo dục Thể chất - QPAN'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        // ══════════════════════════════════════════════
        // 2. TÀI KHOẢN NGƯỜI DÙNG
        // ══════════════════════════════════════════════
        // Admin (Cán bộ thiết bị)
        User::create([
            'name' => 'Nguyễn Văn Quản',
            'email' => 'admin@truong.edu.vn',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department_id' => null,
        ]);

        // Giáo viên mẫu
        $teachers = [
            ['name' => 'Trần Thị Lan', 'email' => 'lan.tran@truong.edu.vn', 'department_id' => 2], // Vật lý
            ['name' => 'Phạm Văn Hùng', 'email' => 'hung.pham@truong.edu.vn', 'department_id' => 3], // Hóa
            ['name' => 'Lê Thị Mai', 'email' => 'mai.le@truong.edu.vn', 'department_id' => 1],     // Toán
            ['name' => 'Ngô Đức Thắng', 'email' => 'thang.ngo@truong.edu.vn', 'department_id' => 6], // QPAN
            ['name' => 'Hoàng Thị Hoa', 'email' => 'hoa.hoang@truong.edu.vn', 'department_id' => 3], // Sinh
        ];

        foreach ($teachers as $teacher) {
            User::create(array_merge($teacher, [
                'password' => Hash::make('password'),
                'role' => 'teacher',
            ]));
        }

        // ══════════════════════════════════════════════
        // 3. PHÒNG HỌC / KHO
        // ══════════════════════════════════════════════
        $rooms = [
            ['name' => 'Kho Tổng', 'type' => 'warehouse', 'manager_id' => 1, 'location' => 'Tầng 1, Dãy A'],
            ['name' => 'Kho QPAN', 'type' => 'warehouse', 'manager_id' => 1, 'location' => 'Tầng 1, Dãy C'],
            ['name' => 'Phòng TH Vật lý', 'type' => 'lab', 'manager_id' => 2, 'location' => 'Tầng 2, Dãy B', 'capacity' => 40],
            ['name' => 'Phòng TH Hóa học', 'type' => 'lab', 'manager_id' => 3, 'location' => 'Tầng 2, Dãy B', 'capacity' => 40],
            ['name' => 'Phòng TH Sinh học', 'type' => 'lab', 'manager_id' => 6, 'location' => 'Tầng 3, Dãy B', 'capacity' => 40],
            ['name' => 'Phòng Tin học', 'type' => 'lab', 'manager_id' => 4, 'location' => 'Tầng 3, Dãy A', 'capacity' => 45],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }

        // ══════════════════════════════════════════════
        // 4. DANH MỤC THIẾT BỊ + CÁ THỂ
        // ══════════════════════════════════════════════
        $equipments = [
            // === VẬT LÝ (Khối 11) ===
            [
                'name' => 'Bộ thực hành Dòng điện trong kim loại',
                'base_code' => 'TBVL430',
                'unit' => 'Bộ',
                'price' => 350000,
                'category_subject' => 'Vật lý',
                'grade_level' => '11',
                'security_level' => 'normal',
                'items_count' => 5,
                'room_id' => 3, // Phòng TH Vật lý
            ],
            [
                'name' => 'Kính hiển vi quang học',
                'base_code' => 'TBSH101',
                'unit' => 'Cái',
                'price' => 2500000,
                'category_subject' => 'Sinh học',
                'grade_level' => '10,11,12',
                'security_level' => 'normal',
                'items_count' => 10,
                'room_id' => 5, // Phòng TH Sinh học
            ],
            [
                'name' => 'Bộ dụng cụ thí nghiệm Quang học',
                'base_code' => 'TBVL310',
                'unit' => 'Bộ',
                'price' => 450000,
                'category_subject' => 'Vật lý',
                'grade_level' => '11,12',
                'security_level' => 'normal',
                'items_count' => 4,
                'room_id' => 3,
            ],
            [
                'name' => 'Máy chiếu đa năng',
                'base_code' => 'TBDC001',
                'unit' => 'Cái',
                'price' => 15000000,
                'category_subject' => 'Dùng chung',
                'grade_level' => 'All',
                'security_level' => 'normal',
                'items_count' => 3,
                'room_id' => 1, // Kho tổng
            ],
            // === HÓA HỌC ===
            [
                'name' => 'Bộ dụng cụ thí nghiệm Hóa đại cương',
                'base_code' => 'TBHH201',
                'unit' => 'Bộ',
                'price' => 280000,
                'category_subject' => 'Hóa học',
                'grade_level' => '10',
                'security_level' => 'normal',
                'items_count' => 6,
                'room_id' => 4, // Phòng TH Hóa học
            ],
            [
                'name' => 'Bộ hóa chất thí nghiệm Axit - Bazơ',
                'base_code' => 'TBHH301',
                'unit' => 'Bộ',
                'price' => 500000,
                'category_subject' => 'Hóa học',
                'grade_level' => '11',
                'security_level' => 'high_security', // ⚠ AN NINH CAO
                'items_count' => 3,
                'room_id' => 4,
            ],
            // === QPAN (Theo HD 590) ===
            [
                'name' => 'Súng AK-47 hoán cải mô hình (cắt cò)',
                'base_code' => 'TBQP001',
                'unit' => 'Khẩu',
                'price' => 0,
                'category_subject' => 'QPAN',
                'grade_level' => '11,12',
                'security_level' => 'high_security', // ⚠ AN NINH CAO
                'items_count' => 5,
                'room_id' => 2, // Kho QPAN
            ],
            [
                'name' => 'Mô hình Lựu đạn tập',
                'base_code' => 'TBQP002',
                'unit' => 'Quả',
                'price' => 0,
                'category_subject' => 'QPAN',
                'grade_level' => '12',
                'security_level' => 'high_security', // ⚠ AN NINH CAO
                'items_count' => 10,
                'room_id' => 2,
            ],
            // === HỌC LIỆU SỐ ===
            [
                'name' => 'Video: Thí nghiệm Quang phổ vạch (HD)',
                'base_code' => 'HLSVL001',
                'unit' => 'File',
                'price' => 0,
                'category_subject' => 'Vật lý',
                'grade_level' => '12',
                'security_level' => 'normal',
                'is_digital' => true,
                'file_url' => '/storage/digital/quang-pho-vach.mp4',
                'file_type' => 'mp4',
                'file_size' => '245MB',
                'items_count' => 0, // Học liệu số không có cá thể vật lý
                'room_id' => null,
            ],
            [
                'name' => 'Phần mềm mô phỏng Mạch điện PhET',
                'base_code' => 'HLSVL002',
                'unit' => 'Phần mềm',
                'price' => 0,
                'category_subject' => 'Vật lý',
                'grade_level' => '11,12',
                'security_level' => 'normal',
                'is_digital' => true,
                'file_url' => 'https://phet.colorado.edu/vi/simulations/circuit-construction-kit-dc',
                'file_type' => 'url',
                'file_size' => null,
                'items_count' => 0,
                'room_id' => null,
            ],
        ];

        foreach ($equipments as $eqData) {
            $itemsCount = $eqData['items_count'];
            $roomId = $eqData['room_id'];
            $isDigital = $eqData['is_digital'] ?? false;

            unset($eqData['items_count'], $eqData['room_id']);

            if (!isset($eqData['is_digital'])) {
                $eqData['is_digital'] = false;
            }

            $equipment = Equipment::create($eqData);

            // Tạo cá thể vật lý
            if (!$isDigital && $itemsCount > 0) {
                for ($i = 1; $i <= $itemsCount; $i++) {
                    EquipmentItem::create([
                        'equipment_id' => $equipment->id,
                        'room_id' => $roomId,
                        'specific_code' => "{$equipment->base_code}.{$i}/{$itemsCount}",
                        'status' => $i <= ($itemsCount - 1) ? 'available' : 'maintenance',
                        // Để 1 cái ở trạng thái bảo trì cho demo
                        'year_acquired' => rand(2020, 2024),
                    ]);
                }
            }
        }

        $this->command->info('✅ Seeded: ' . Department::count() . ' tổ chuyên môn');
        $this->command->info('✅ Seeded: ' . User::count() . ' tài khoản (1 admin + ' . (User::count() - 1) . ' GV)');
        $this->command->info('✅ Seeded: ' . Room::count() . ' phòng/kho');
        $this->command->info('✅ Seeded: ' . Equipment::count() . ' danh mục thiết bị');
        $this->command->info('✅ Seeded: ' . EquipmentItem::count() . ' cá thể vật lý');
        $this->command->info('🎉 Database seeding hoàn tất!');
    }
}
