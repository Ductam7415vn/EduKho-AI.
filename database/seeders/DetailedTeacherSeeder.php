<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use App\Models\BorrowRecord;
use App\Models\BorrowDetail;
use App\Models\Equipment;
use App\Models\EquipmentItem;
use App\Models\TeachingPlan;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\DamageReport;
use App\Models\ActivityLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DetailedTeacherSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy các tổ chuyên môn
        $departments = Department::all();

        // Danh sách giáo viên chi tiết cho mỗi tổ
        $teachersData = [
            // Tổ Toán - Tin học
            [
                ['name' => 'Nguyễn Văn An', 'email' => 'an.nguyen@truong.edu.vn', 'department' => 'Tổ Toán - Tin học'],
                ['name' => 'Trần Thị Bình', 'email' => 'binh.tran@truong.edu.vn', 'department' => 'Tổ Toán - Tin học'],
                ['name' => 'Lê Hoàng Cường', 'email' => 'cuong.le@truong.edu.vn', 'department' => 'Tổ Toán - Tin học'],
                ['name' => 'Phạm Thị Dung', 'email' => 'dung.pham@truong.edu.vn', 'department' => 'Tổ Toán - Tin học'],
            ],
            // Tổ Vật lý - Công nghệ
            [
                ['name' => 'Hoàng Văn Em', 'email' => 'em.hoang@truong.edu.vn', 'department' => 'Tổ Vật lý - Công nghệ'],
                ['name' => 'Vũ Thị Phương', 'email' => 'phuong.vu@truong.edu.vn', 'department' => 'Tổ Vật lý - Công nghệ'],
                ['name' => 'Đặng Minh Giang', 'email' => 'giang.dang@truong.edu.vn', 'department' => 'Tổ Vật lý - Công nghệ'],
                ['name' => 'Ngô Thị Hằng', 'email' => 'hang.ngo@truong.edu.vn', 'department' => 'Tổ Vật lý - Công nghệ'],
            ],
            // Tổ Hóa học - Sinh học
            [
                ['name' => 'Bùi Văn Kiên', 'email' => 'kien.bui@truong.edu.vn', 'department' => 'Tổ Hóa học - Sinh học'],
                ['name' => 'Đỗ Thị Lan', 'email' => 'lan.do@truong.edu.vn', 'department' => 'Tổ Hóa học - Sinh học'],
                ['name' => 'Lý Hoàng Minh', 'email' => 'minh.ly@truong.edu.vn', 'department' => 'Tổ Hóa học - Sinh học'],
                ['name' => 'Cao Thị Nga', 'email' => 'nga.cao@truong.edu.vn', 'department' => 'Tổ Hóa học - Sinh học'],
            ],
            // Tổ Ngữ văn - Lịch sử - Địa lý
            [
                ['name' => 'Mai Văn Oanh', 'email' => 'oanh.mai@truong.edu.vn', 'department' => 'Tổ Ngữ văn - Lịch sử - Địa lý'],
                ['name' => 'Trương Thị Phúc', 'email' => 'phuc.truong@truong.edu.vn', 'department' => 'Tổ Ngữ văn - Lịch sử - Địa lý'],
                ['name' => 'Đinh Văn Quang', 'email' => 'quang.dinh@truong.edu.vn', 'department' => 'Tổ Ngữ văn - Lịch sử - Địa lý'],
                ['name' => 'Phan Thị Ráng', 'email' => 'rang.phan@truong.edu.vn', 'department' => 'Tổ Ngữ văn - Lịch sử - Địa lý'],
            ],
            // Tổ Ngoại ngữ
            [
                ['name' => 'Võ Văn Sơn', 'email' => 'son.vo@truong.edu.vn', 'department' => 'Tổ Ngoại ngữ'],
                ['name' => 'Hồ Thị Tâm', 'email' => 'tam.ho@truong.edu.vn', 'department' => 'Tổ Ngoại ngữ'],
                ['name' => 'Dương Văn Ưu', 'email' => 'uu.duong@truong.edu.vn', 'department' => 'Tổ Ngoại ngữ'],
                ['name' => 'Lâm Thị Vân', 'email' => 'van.lam@truong.edu.vn', 'department' => 'Tổ Ngoại ngữ'],
            ],
            // Tổ Giáo dục Thể chất - QPAN
            [
                ['name' => 'Nguyễn Văn Xuân', 'email' => 'xuan.nguyen@truong.edu.vn', 'department' => 'Tổ Giáo dục Thể chất - QPAN'],
                ['name' => 'Trần Thị Yến', 'email' => 'yen.tran@truong.edu.vn', 'department' => 'Tổ Giáo dục Thể chất - QPAN'],
                ['name' => 'Lê Văn Dũng', 'email' => 'dung.le@truong.edu.vn', 'department' => 'Tổ Giáo dục Thể chất - QPAN'],
                ['name' => 'Phạm Thị Ánh', 'email' => 'anh.pham@truong.edu.vn', 'department' => 'Tổ Giáo dục Thể chất - QPAN'],
            ],
        ];

        // Tạo giáo viên
        $createdTeachers = [];
        foreach ($teachersData as $deptTeachers) {
            foreach ($deptTeachers as $teacher) {
                $dept = $departments->where('name', $teacher['department'])->first();
                $user = User::create([
                    'name' => $teacher['name'],
                    'email' => $teacher['email'],
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                    'department_id' => $dept ? $dept->id : null,
                    'email_verified_at' => now(),
                ]);
                $createdTeachers[] = $user;
            }
        }

        // Lấy danh sách thiết bị và phòng
        $equipments = Equipment::where('is_digital', false)->get();
        $rooms = Room::where('type', 'lab')->get();
        $allRooms = Room::all();

        // Tạo dữ liệu mượn trả cho mỗi giáo viên
        foreach ($createdTeachers as $teacher) {
            // Tạo 3-5 đơn mượn cho mỗi giáo viên
            $borrowCount = rand(3, 5);
            
            for ($i = 0; $i < $borrowCount; $i++) {
                $borrowDate = Carbon::now()->subDays(rand(1, 60));
                $returnDate = $borrowDate->copy()->addDays(rand(1, 14));
                
                // Xác định trạng thái đơn mượn
                $statuses = ['pending', 'approved', 'borrowed', 'returned', 'rejected'];
                $status = $statuses[array_rand($statuses)];
                
                if ($status == 'returned') {
                    $actualReturnDate = $returnDate->copy()->subDays(rand(0, 3));
                } else if ($status == 'borrowed' && $returnDate->isPast()) {
                    // Đơn quá hạn
                    $actualReturnDate = null;
                } else {
                    $actualReturnDate = null;
                }

                $borrowRecord = BorrowRecord::create([
                    'user_id' => $teacher->id,
                    'purpose' => $this->getRandomPurpose($teacher->department->name ?? ''),
                    'notes' => $this->getRandomNotes(),
                    'borrow_date' => $borrowDate,
                    'return_date' => $returnDate,
                    'actual_return_date' => $actualReturnDate,
                    'status' => $status,
                    'approved_by' => in_array($status, ['approved', 'borrowed', 'returned']) ? 1 : null,
                    'approved_at' => in_array($status, ['approved', 'borrowed', 'returned']) ? $borrowDate->copy()->addHours(rand(1, 4)) : null,
                    'rejection_reason' => $status == 'rejected' ? $this->getRandomRejectionReason() : null,
                ]);

                // Tạo chi tiết mượn (1-3 thiết bị)
                $equipmentCount = rand(1, 3);
                $selectedEquipments = $equipments->random(min($equipmentCount, $equipments->count()));
                
                foreach ($selectedEquipments as $equipment) {
                    $items = $equipment->items()->where('status', 'available')->limit(rand(1, 2))->get();
                    
                    foreach ($items as $item) {
                        BorrowDetail::create([
                            'borrow_id' => $borrowRecord->id,
                            'item_id' => $item->id,
                            'quantity' => 1,
                            'condition_before' => 'Tốt',
                            'condition_after' => $status == 'returned' ? $this->getRandomCondition() : null,
                            'damages' => $status == 'returned' && rand(0, 10) > 8 ? 'Trầy xước nhẹ' : null,
                        ]);
                    }
                }

                // Tạo activity log
                ActivityLog::create([
                    'user_id' => $teacher->id,
                    'action' => 'created',
                    'model_type' => 'BorrowRecord',
                    'model_id' => $borrowRecord->id,
                    'description' => "Tạo đơn mượn #{$borrowRecord->id}",
                    'created_at' => $borrowDate,
                ]);
            }

            // Tạo kế hoạch giảng dạy (2-4 kế hoạch)
            $planCount = rand(2, 4);
            for ($i = 0; $i < $planCount; $i++) {
                $startDate = Carbon::now()->addDays(rand(1, 30));
                
                TeachingPlan::create([
                    'user_id' => $teacher->id,
                    'title' => $this->getRandomLessonTitle($teacher->department->name ?? ''),
                    'subject' => $this->getSubjectFromDepartment($teacher->department->name ?? ''),
                    'grade' => rand(10, 12),
                    'start_date' => $startDate,
                    'end_date' => $startDate->copy()->addDays(rand(1, 5)),
                    'equipment_list' => $this->getRandomEquipmentList($teacher->department->name ?? ''),
                    'notes' => $this->getRandomTeachingNotes(),
                ]);
            }

            // Tạo đặt phòng (1-3 lần)
            $reservationCount = rand(1, 3);
            for ($i = 0; $i < $reservationCount; $i++) {
                $date = Carbon::now()->addDays(rand(1, 14));
                $startTime = rand(7, 14) . ':00:00';
                $endTime = (intval($startTime) + rand(1, 2)) . ':00:00';
                
                Reservation::create([
                    'user_id' => $teacher->id,
                    'room_id' => $rooms->random()->id,
                    'date' => $date,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'purpose' => $this->getRandomReservationPurpose($teacher->department->name ?? ''),
                    'participant_count' => rand(20, 45),
                    'equipment_needed' => $this->getRandomEquipmentNeeded(),
                    'status' => ['pending', 'approved', 'cancelled'][rand(0, 2)],
                    'notes' => rand(0, 1) ? 'Cần chuẩn bị thêm bàn thí nghiệm' : null,
                ]);
            }

            // Tạo báo cáo hư hỏng (10% giáo viên)
            if (rand(1, 10) <= 1) {
                $equipment = $equipments->random();
                $item = $equipment->items()->first();
                
                if ($item) {
                    DamageReport::create([
                        'item_id' => $item->id,
                        'reported_by' => $teacher->id,
                        'damage_date' => Carbon::now()->subDays(rand(1, 30)),
                        'description' => $this->getRandomDamageDescription(),
                        'severity' => ['minor', 'moderate', 'severe'][rand(0, 2)],
                        'repair_status' => ['pending', 'in_progress', 'completed'][rand(0, 2)],
                        'repair_cost' => rand(100000, 2000000),
                        'repair_notes' => 'Đã liên hệ đơn vị sửa chữa',
                    ]);
                }
            }
        }

        $this->command->info('✅ Đã tạo ' . count($createdTeachers) . ' giáo viên với dữ liệu chi tiết');
        $this->command->info('✅ Đã tạo ' . BorrowRecord::count() . ' đơn mượn thiết bị');
        $this->command->info('✅ Đã tạo ' . TeachingPlan::count() . ' kế hoạch giảng dạy');
        $this->command->info('✅ Đã tạo ' . Reservation::count() . ' đơn đặt phòng');
        $this->command->info('✅ Đã tạo ' . DamageReport::count() . ' báo cáo hư hỏng');
    }

    private function getRandomPurpose($department)
    {
        $purposes = [
            'Tổ Vật lý - Công nghệ' => [
                'Thí nghiệm chương Điện học lớp 11',
                'Thực hành đo điện trở và công suất',
                'Minh họa hiện tượng cảm ứng điện từ',
                'Thí nghiệm quang học - Giao thoa ánh sáng',
            ],
            'Tổ Hóa học - Sinh học' => [
                'Thí nghiệm phản ứng oxi hóa - khử',
                'Quan sát tế bào thực vật dưới kính hiển vi',
                'Thực hành điều chế và nhận biết khí',
                'Nghiên cứu quá trình quang hợp',
            ],
            'Tổ Toán - Tin học' => [
                'Dạy thực hành lập trình Python',
                'Minh họa thuật toán sắp xếp',
                'Thực hành Excel nâng cao',
                'Dạy thiết kế website cơ bản',
            ],
            'default' => [
                'Phục vụ giảng dạy',
                'Tổ chức hoạt động ngoại khóa',
                'Chuẩn bị cho kỳ thi thực hành',
                'Hoạt động trải nghiệm sáng tạo',
            ],
        ];

        $deptPurposes = $purposes[$department] ?? $purposes['default'];
        return $deptPurposes[array_rand($deptPurposes)];
    }

    private function getRandomNotes()
    {
        $notes = [
            'Cần thêm dây nối',
            'Yêu cầu kiểm tra trước khi sử dụng',
            'Sử dụng cho tiết 3-4',
            'Kết hợp với thiết bị khác',
            null,
            null, // 1/3 không có ghi chú
        ];
        return $notes[array_rand($notes)];
    }

    private function getRandomRejectionReason()
    {
        $reasons = [
            'Thiết bị đang được sử dụng cho lớp khác',
            'Thiết bị đang trong quá trình bảo trì',
            'Không đủ số lượng theo yêu cầu',
            'Chưa hoàn thành đơn mượn trước đó',
        ];
        return $reasons[array_rand($reasons)];
    }

    private function getRandomCondition()
    {
        $conditions = [
            'Tốt',
            'Tốt',
            'Tốt', // 60% tốt
            'Bình thường',
            'Bình thường', // 40% bình thường
        ];
        return $conditions[array_rand($conditions)];
    }

    private function getRandomLessonTitle($department)
    {
        $titles = [
            'Tổ Vật lý - Công nghệ' => [
                'Bài 15: Dòng điện trong chất điện phân',
                'Bài 20: Lực từ - Cảm ứng từ',
                'Bài 25: Giao thoa ánh sáng',
                'Bài 30: Hiện tượng quang điện',
            ],
            'Tổ Hóa học - Sinh học' => [
                'Bài 10: Phản ứng oxi hóa - khử',
                'Bài 12: Tế bào và các bào quan',
                'Bài 18: Quá trình quang hợp',
                'Bài 22: Di truyền học Mendel',
            ],
            'default' => [
                'Bài giảng thực hành',
                'Ôn tập chương 3',
                'Kiểm tra thực hành',
                'Hoạt động trải nghiệm',
            ],
        ];

        $deptTitles = $titles[$department] ?? $titles['default'];
        return $deptTitles[array_rand($deptTitles)];
    }

    private function getSubjectFromDepartment($department)
    {
        $subjects = [
            'Tổ Toán - Tin học' => ['Toán', 'Tin học'],
            'Tổ Vật lý - Công nghệ' => ['Vật lý', 'Công nghệ'],
            'Tổ Hóa học - Sinh học' => ['Hóa học', 'Sinh học'],
            'Tổ Ngữ văn - Lịch sử - Địa lý' => ['Ngữ văn', 'Lịch sử', 'Địa lý'],
            'Tổ Ngoại ngữ' => ['Tiếng Anh', 'Tiếng Pháp'],
            'Tổ Giáo dục Thể chất - QPAN' => ['Thể dục', 'GDQP-AN'],
        ];

        $deptSubjects = $subjects[$department] ?? ['Khác'];
        return $deptSubjects[array_rand($deptSubjects)];
    }

    private function getRandomEquipmentList($department)
    {
        $equipmentLists = [
            'Tổ Vật lý - Công nghệ' => [
                'Bộ thí nghiệm điện, Ampe kế, Vôn kế, Biến trở',
                'Bộ thí nghiệm quang học, Nguồn laser, Màn chắn',
                'Nam châm, Cuộn dây, Lõi sắt từ, Điện kế',
            ],
            'Tổ Hóa học - Sinh học' => [
                'Ống nghiệm, Cốc đong, Hóa chất (HCl, NaOH)',
                'Kính hiển vi, Lam kính, Thuốc nhuộm',
                'Bình tam giác, Ống đong, Giấy quỳ',
            ],
            'default' => [
                'Máy chiếu, Loa, Micro',
                'Bảng tương tác, Máy tính',
            ],
        ];

        $deptLists = $equipmentLists[$department] ?? $equipmentLists['default'];
        return $deptLists[array_rand($deptLists)];
    }

    private function getRandomTeachingNotes()
    {
        $notes = [
            'Chuẩn bị thêm phiếu học tập',
            'Chia nhóm thực hành 4-5 học sinh',
            'Kiểm tra an toàn trước khi thí nghiệm',
            'Cần trợ giảng hỗ trợ',
            null,
        ];
        return $notes[array_rand($notes)];
    }

    private function getRandomReservationPurpose($department)
    {
        $purposes = [
            'Tổ Vật lý - Công nghệ' => [
                'Thí nghiệm vật lý nâng cao',
                'Ôn thi học sinh giỏi',
                'CLB Robotics',
            ],
            'Tổ Hóa học - Sinh học' => [
                'Thực hành hóa học',
                'Quan sát mẫu vật sinh học',
                'Chuẩn bị thi thực hành',
            ],
            'default' => [
                'Dạy bù cho học sinh',
                'Họp nhóm chuyên môn',
                'Hoạt động ngoại khóa',
            ],
        ];

        $deptPurposes = $purposes[$department] ?? $purposes['default'];
        return $deptPurposes[array_rand($deptPurposes)];
    }

    private function getRandomEquipmentNeeded()
    {
        $equipment = [
            'Máy chiếu, Loa, Micro không dây',
            'Máy chiếu, Bảng tương tác',
            'Hệ thống âm thanh, Máy chiếu',
            'Máy tính, Máy chiếu',
            null,
        ];
        return $equipment[array_rand($equipment)];
    }

    private function getRandomDamageDescription()
    {
        $descriptions = [
            'Màn hình bị xước, cần thay thế',
            'Dây nguồn bị đứt, không hoạt động',
            'Nút bấm bị kẹt, khó sử dụng',
            'Pin không giữ được điện',
            'Ống kính bị mờ, cần vệ sinh chuyên sâu',
            'Bo mạch bị hỏng do nhiệt độ cao',
        ];
        return $descriptions[array_rand($descriptions)];
    }
}