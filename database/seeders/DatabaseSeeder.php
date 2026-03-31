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
        $this->call(PermissionSeeder::class);

        // ══════════════════════════════════════════════
        // 1. TỔ CHUYÊN MÔN
        // ══════════════════════════════════════════════
        $departments = [
            ['name' => 'KHTN'], // Khoa học tự nhiên
            ['name' => 'T-A-TI-TC-QP'], // Toán-Anh-Tin-Thể chất-QPAN
            ['name' => 'KHXH'], // Khoa học xã hội  
            ['name' => 'VĂN PHÒNG'], // Văn phòng
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        // ══════════════════════════════════════════════
        // 2. TÀI KHOẢN NGƯỜI DÙNG
        // ══════════════════════════════════════════════
        // Tạo nhân sự thực tế từ danh sách 41 người (bao gồm 3 admin)
        $this->call(RealStaffSeeder::class);

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
        // Tạo thiết bị từ biểu kiểm kê thực tế
        $this->call(RealEquipmentSeeder::class);

        $this->command->info('✅ Seeded: ' . Department::count() . ' tổ chuyên môn');
        $this->command->info('✅ Seeded: ' . User::count() . ' tài khoản (1 admin + ' . (User::count() - 1) . ' GV)');
        $this->command->info('✅ Seeded: ' . Room::count() . ' phòng/kho');
        $this->command->info('✅ Seeded: ' . Equipment::count() . ' danh mục thiết bị');
        $this->command->info('✅ Seeded: ' . EquipmentItem::count() . ' cá thể vật lý');
        $this->command->info('🎉 Database seeding hoàn tất!');
    }
}
