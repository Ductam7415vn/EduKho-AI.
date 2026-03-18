<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\BorrowDetail;
use App\Models\BorrowRecord;
use App\Models\DamageReport;
use App\Models\Equipment;
use App\Models\EquipmentItem;
use App\Models\EquipmentTransfer;
use App\Models\MaintenanceSchedule;
use App\Models\Room;
use App\Models\ScheduledReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StabilizationPassTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $teacher;
    private Room $warehouse;
    private Room $lab;
    private Equipment $equipment;
    private EquipmentItem $maintenanceItem;
    private EquipmentItem $transferItem;
    private EquipmentTransfer $transfer;
    private MaintenanceSchedule $scheduledMaintenance;
    private ScheduledReport $scheduledReport;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedScenario();
    }

    public function test_admin_can_view_restored_maintenance_and_scheduled_report_pages(): void
    {
        $this->actingAs($this->admin);

        $this->get(route('admin.maintenance.index'))
            ->assertOk()
            ->assertSee('Bao tri thiet bi');

        $this->get(route('admin.scheduled-reports.show', $this->scheduledReport))
            ->assertOk()
            ->assertSee($this->scheduledReport->name)
            ->assertSee('Danh sach nguoi nhan');
    }

    public function test_admin_can_view_restored_transfer_pages(): void
    {
        $this->actingAs($this->admin);

        $this->get(route('admin.transfers.show', $this->transfer))
            ->assertOk()
            ->assertSee($this->transferItem->specific_code)
            ->assertSee('Chi tiet dieu chuyen thiet bi');

        $this->get(route('admin.transfers.item-history', $this->transferItem))
            ->assertOk()
            ->assertSee('Lich su dieu chuyen item')
            ->assertSee($this->lab->name);
    }

    public function test_admin_can_view_restored_audit_pages_on_sqlite(): void
    {
        $this->actingAs($this->admin);

        $this->get(route('admin.audit-reports.inventory'))
            ->assertOk()
            ->assertSee('Kiem ke ton kho');

        $this->get(route('admin.audit-reports.borrow'))
            ->assertOk()
            ->assertSee('Kiem soat muon tra')
            ->assertSee($this->teacher->name);

        $this->get(route('admin.audit-reports.maintenance'))
            ->assertOk()
            ->assertSee('Bao tri va hu hong');

        $this->get(route('admin.audit-reports.activity'))
            ->assertOk()
            ->assertSee('Hoat dong nguoi dung')
            ->assertSee($this->admin->email);
    }

    public function test_equipment_availability_api_returns_expected_payload(): void
    {
        $this->actingAs($this->admin);

        $response = $this->getJson('/api/equipment/' . $this->equipment->id . '/availability?quantity=1');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'equipment_id',
                    'requested_quantity',
                    'available_quantity',
                    'is_available',
                ],
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'equipment_id' => $this->equipment->id,
                    'requested_quantity' => 1,
                    'is_available' => true,
                ],
            ]);
    }

    private function seedScenario(): void
    {
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->teacher = User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@example.com',
            'password' => 'password',
            'role' => 'teacher',
            'is_active' => true,
        ]);

        $this->warehouse = Room::create([
            'name' => 'Kho Tong',
            'type' => 'warehouse',
        ]);

        $this->lab = Room::create([
            'name' => 'Phong Lab',
            'type' => 'lab',
        ]);

        $this->equipment = Equipment::create([
            'name' => 'May chieu',
            'base_code' => 'MC001',
            'unit' => 'Cai',
            'price' => 15000000,
            'category_subject' => 'Dung chung',
            'grade_level' => 'All',
            'low_stock_threshold' => 2,
        ]);

        $this->maintenanceItem = EquipmentItem::create([
            'equipment_id' => $this->equipment->id,
            'room_id' => $this->warehouse->id,
            'specific_code' => 'MC001.1',
            'status' => 'available',
            'year_acquired' => 2024,
        ]);

        $this->transferItem = EquipmentItem::create([
            'equipment_id' => $this->equipment->id,
            'room_id' => $this->lab->id,
            'specific_code' => 'MC001.2',
            'status' => 'available',
            'year_acquired' => 2024,
        ]);

        $this->scheduledMaintenance = MaintenanceSchedule::create([
            'equipment_item_id' => $this->maintenanceItem->id,
            'created_by' => $this->admin->id,
            'title' => 'Bao tri dinh ky may chieu',
            'description' => 'Kiem tra bong den va he thong tan nhiet',
            'type' => 'preventive',
            'priority' => 'high',
            'scheduled_date' => now()->toDateString(),
            'status' => 'scheduled',
        ]);

        MaintenanceSchedule::create([
            'equipment_item_id' => $this->maintenanceItem->id,
            'created_by' => $this->admin->id,
            'completed_by' => $this->admin->id,
            'title' => 'Sua nguon may chieu',
            'type' => 'corrective',
            'priority' => 'medium',
            'scheduled_date' => now()->subDays(3)->toDateString(),
            'completed_date' => now()->subDay()->toDateString(),
            'status' => 'completed',
            'cost' => 500000,
        ]);

        $this->scheduledReport = ScheduledReport::create([
            'user_id' => $this->admin->id,
            'name' => 'Bao cao muon tra hang tuan',
            'report_type' => 'borrow_tracking',
            'frequency' => 'weekly',
            'send_time' => '08:00',
            'day_of_week' => 1,
            'recipients' => ['admin@example.com', 'principal@example.com'],
            'is_active' => true,
            'next_run_at' => now()->addDay(),
        ]);

        $this->transfer = EquipmentTransfer::create([
            'equipment_item_id' => $this->transferItem->id,
            'from_room_id' => $this->warehouse->id,
            'to_room_id' => $this->lab->id,
            'transferred_by' => $this->admin->id,
            'transfer_date' => now()->toDateString(),
            'reason' => 'Phuc vu tiet hoc',
            'notes' => 'Bo tri tai phong lab cho buoi chieu',
        ]);

        $borrowedItem = EquipmentItem::create([
            'equipment_id' => $this->equipment->id,
            'room_id' => $this->warehouse->id,
            'specific_code' => 'MC001.3',
            'status' => 'borrowed',
            'year_acquired' => 2024,
        ]);

        $borrowRecord = BorrowRecord::create([
            'user_id' => $this->teacher->id,
            'lesson_name' => 'Bai 1',
            'period' => 1,
            'class_name' => '10A1',
            'subject' => 'Vat ly',
            'borrow_date' => now()->subDays(2),
            'expected_return_date' => now()->subDay(),
            'approval_status' => 'auto_approved',
            'status' => 'overdue',
        ]);

        BorrowDetail::create([
            'borrow_record_id' => $borrowRecord->id,
            'equipment_item_id' => $borrowedItem->id,
            'condition_before' => 'good',
        ]);

        DamageReport::create([
            'equipment_item_id' => $this->transferItem->id,
            'reported_by' => $this->admin->id,
            'incident_date' => now()->subDay()->toDateString(),
            'severity' => 'minor',
            'description' => 'Truong hop tram nhe o day cap',
            'estimated_cost' => 250000,
            'status' => 'reported',
        ]);

        ActivityLog::create([
            'user_id' => $this->admin->id,
            'action' => 'login',
            'subject_type' => User::class,
            'subject_id' => $this->admin->id,
            'ip_address' => '127.0.0.1',
        ]);

        ActivityLog::create([
            'user_id' => $this->admin->id,
            'action' => 'logout',
            'subject_type' => User::class,
            'subject_id' => $this->admin->id,
            'ip_address' => '127.0.0.1',
        ]);
    }
}
