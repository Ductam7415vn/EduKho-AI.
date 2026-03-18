<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\TeachingPlanController;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\BorrowTemplateController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\ICalController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AuditReportController;
use App\Http\Controllers\Admin\DamageReportController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EquipmentTransferController;
use App\Http\Controllers\Admin\ImpersonationController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\ScheduledReportController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

    // Password reset
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email')->middleware('throttle:password-reset');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update')->middleware('throttle:password-reset');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Two-factor authentication challenge (during login)
Route::get('/two-factor-challenge', [TwoFactorController::class, 'challenge'])->name('two-factor.challenge');
Route::post('/two-factor-challenge', [TwoFactorController::class, 'verify'])->name('two-factor.verify');

// Authenticated routes
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::get('/password', [ProfileController::class, 'showChangePassword'])->name('password');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::get('/notifications', [ProfileController::class, 'showNotifications'])->name('notifications');
        Route::put('/notifications', [ProfileController::class, 'updateNotifications'])->name('notifications.update');
        Route::get('/two-factor', [TwoFactorController::class, 'show'])->name('two-factor');
        Route::get('/two-factor/enable', [TwoFactorController::class, 'enable'])->name('two-factor.enable');
        Route::post('/two-factor/confirm', [TwoFactorController::class, 'confirm'])->name('two-factor.confirm');
        Route::delete('/two-factor', [TwoFactorController::class, 'disable'])->name('two-factor.disable');
    });

    // Global Search
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    // Export functionality
    Route::prefix('export')->name('export.')->group(function () {
        Route::get('/equipment', [ExportController::class, 'exportEquipment'])->name('equipment');
        Route::get('/borrows', [ExportController::class, 'exportBorrows'])->name('borrows');
    });

    // iCal Export
    Route::prefix('ical')->name('ical.')->group(function () {
        Route::get('/borrows', [ICalController::class, 'exportBorrows'])->name('borrows');
        Route::get('/reservations', [ICalController::class, 'exportReservations'])->name('reservations');
        Route::get('/all', [ICalController::class, 'exportAll'])->name('all');
    });

    // Equipment catalog (view only for teachers)
    Route::get('/equipment', [EquipmentController::class, 'index'])->name('equipment.index');
    Route::get('/equipment/{equipment}', [EquipmentController::class, 'show'])->name('equipment.show');
    Route::get('/equipment/{equipment}/history', [EquipmentController::class, 'history'])->name('equipment.history');
    Route::get('/equipment/{equipment}/qr', [QrCodeController::class, 'equipment'])->name('equipment.qr');
    Route::get('/equipment/{equipment}/qr/print', [QrCodeController::class, 'equipmentPrint'])->name('equipment.qr.print');

    // Borrowing system
    Route::prefix('borrow')->name('borrow.')->group(function () {
        Route::get('/', [BorrowController::class, 'index'])->name('index');
        Route::get('/create', [BorrowController::class, 'create'])->name('create');
        Route::post('/', [BorrowController::class, 'store'])->name('store');
        Route::get('/calendar', [BorrowController::class, 'calendar'])->name('calendar');
        Route::get('/{borrowRecord}', [BorrowController::class, 'show'])->name('show');
        Route::get('/{borrowRecord}/print', [BorrowController::class, 'printPdf'])->name('print');
        Route::post('/{borrowRecord}/return', [BorrowController::class, 'return'])->name('return');

        // Borrow templates
        Route::prefix('templates')->name('templates.')->group(function () {
            Route::get('/', [BorrowTemplateController::class, 'index'])->name('index');
            Route::get('/create', [BorrowTemplateController::class, 'create'])->name('create');
            Route::post('/', [BorrowTemplateController::class, 'store'])->name('store');
            Route::delete('/{template}', [BorrowTemplateController::class, 'destroy'])->name('destroy');
            Route::get('/{template}/data', [BorrowTemplateController::class, 'getData'])->name('data');
        });
    });

    // Teaching plans
    Route::resource('teaching-plans', TeachingPlanController::class);

    // Equipment Reservations
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('index');
        Route::get('/create', [ReservationController::class, 'create'])->name('create');
        Route::post('/', [ReservationController::class, 'store'])->name('store');
        Route::get('/{reservation}', [ReservationController::class, 'show'])->name('show');
        Route::delete('/{reservation}', [ReservationController::class, 'cancel'])->name('cancel');
        Route::post('/{reservation}/confirm', [ReservationController::class, 'confirm'])->name('confirm')->middleware('admin');
        Route::post('/{reservation}/convert', [ReservationController::class, 'convert'])->name('convert');
    });

    // AI Chat assistant
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('/chat', [AiChatController::class, 'index'])->name('chat');
        Route::post('/chat', [AiChatController::class, 'send'])->name('send');
        Route::get('/history', [AiChatController::class, 'history'])->name('history');
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
    });

    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {

        // Equipment management (full CRUD)
        Route::resource('equipment', EquipmentController::class)->except(['index', 'show']);

        // Room/Warehouse management
        Route::resource('rooms', RoomController::class);

        // User management
        Route::resource('users', UserController::class);

        // Department management
        Route::resource('departments', DepartmentController::class);

        // Impersonation
        Route::post('/impersonate/{user}', [ImpersonationController::class, 'start'])->name('impersonate.start');
        Route::post('/impersonate/stop', [ImpersonationController::class, 'stop'])->name('impersonate.stop')->withoutMiddleware('admin');

        // Inventory logs
        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('/', [InventoryController::class, 'index'])->name('index');
            Route::get('/increase', [InventoryController::class, 'createIncrease'])->name('increase');
            Route::get('/decrease', [InventoryController::class, 'createDecrease'])->name('decrease');
            Route::post('/', [InventoryController::class, 'store'])->name('store');
        });

        // Approval workflow
        Route::prefix('approvals')->name('approvals.')->group(function () {
            Route::get('/', [BorrowController::class, 'pendingApprovals'])->name('index');
            Route::post('/{borrowRecord}/approve', [BorrowController::class, 'approve'])->name('approve');
            Route::post('/{borrowRecord}/reject', [BorrowController::class, 'reject'])->name('reject');
            Route::post('/bulk-approve', [BorrowController::class, 'bulkApprove'])->name('bulk-approve');
            Route::post('/bulk-reject', [BorrowController::class, 'bulkReject'])->name('bulk-reject');
        });

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/equipment-list', [ReportController::class, 'equipmentList'])->name('equipment-list');
            Route::get('/borrow-tracking', [ReportController::class, 'borrowTracking'])->name('borrow-tracking');
            Route::get('/export/mau01', [ReportController::class, 'exportMau01'])->name('export.mau01');
            Route::get('/export/mau02', [ReportController::class, 'exportMau02'])->name('export.mau02');
        });

        // Activity Logs
        Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
            Route::get('/', [ActivityLogController::class, 'index'])->name('index');
            Route::get('/{activityLog}', [ActivityLogController::class, 'show'])->name('show');
        });

        // Maintenance Schedules
        Route::prefix('maintenance')->name('maintenance.')->group(function () {
            Route::get('/', [MaintenanceController::class, 'index'])->name('index');
            Route::get('/create', [MaintenanceController::class, 'create'])->name('create');
            Route::post('/', [MaintenanceController::class, 'store'])->name('store');
            Route::get('/{maintenance}', [MaintenanceController::class, 'show'])->name('show');
            Route::post('/{maintenance}/start', [MaintenanceController::class, 'start'])->name('start');
            Route::post('/{maintenance}/complete', [MaintenanceController::class, 'complete'])->name('complete');
            Route::delete('/{maintenance}', [MaintenanceController::class, 'cancel'])->name('cancel');
        });

        // Import Equipment
        Route::prefix('import')->name('import.')->group(function () {
            Route::get('/equipment', [ImportController::class, 'showEquipmentForm'])->name('equipment.form');
            Route::post('/equipment', [ImportController::class, 'importEquipment'])->name('equipment');
            Route::get('/equipment/template', [ImportController::class, 'downloadTemplate'])->name('equipment.template');
        });

        // Damage Reports
        Route::prefix('damage-reports')->name('damage-reports.')->group(function () {
            Route::get('/', [DamageReportController::class, 'index'])->name('index');
            Route::get('/create', [DamageReportController::class, 'create'])->name('create');
            Route::post('/', [DamageReportController::class, 'store'])->name('store');
            Route::get('/{damageReport}', [DamageReportController::class, 'show'])->name('show');
            Route::post('/{damageReport}/investigate', [DamageReportController::class, 'investigate'])->name('investigate');
            Route::post('/{damageReport}/resolve', [DamageReportController::class, 'resolve'])->name('resolve');
        });

        // Scheduled Reports
        Route::prefix('scheduled-reports')->name('scheduled-reports.')->group(function () {
            Route::get('/', [ScheduledReportController::class, 'index'])->name('index');
            Route::get('/create', [ScheduledReportController::class, 'create'])->name('create');
            Route::post('/', [ScheduledReportController::class, 'store'])->name('store');
            Route::get('/{scheduledReport}', [ScheduledReportController::class, 'show'])->name('show');
            Route::get('/{scheduledReport}/edit', [ScheduledReportController::class, 'edit'])->name('edit');
            Route::put('/{scheduledReport}', [ScheduledReportController::class, 'update'])->name('update');
            Route::delete('/{scheduledReport}', [ScheduledReportController::class, 'destroy'])->name('destroy');
            Route::post('/{scheduledReport}/toggle', [ScheduledReportController::class, 'toggle'])->name('toggle');
        });

        // Equipment Transfers
        Route::prefix('transfers')->name('transfers.')->group(function () {
            Route::get('/', [EquipmentTransferController::class, 'index'])->name('index');
            Route::get('/create', [EquipmentTransferController::class, 'create'])->name('create');
            Route::post('/', [EquipmentTransferController::class, 'store'])->name('store');
            Route::get('/{transfer}', [EquipmentTransferController::class, 'show'])->name('show');
            Route::get('/item/{equipmentItem}/history', [EquipmentTransferController::class, 'itemHistory'])->name('item-history');
        });

        // Audit Reports
        Route::prefix('audit-reports')->name('audit-reports.')->group(function () {
            Route::get('/', [AuditReportController::class, 'index'])->name('index');
            Route::get('/inventory', [AuditReportController::class, 'inventoryAudit'])->name('inventory');
            Route::get('/borrow', [AuditReportController::class, 'borrowAudit'])->name('borrow');
            Route::get('/maintenance', [AuditReportController::class, 'maintenanceAudit'])->name('maintenance');
            Route::get('/activity', [AuditReportController::class, 'activityAudit'])->name('activity');
            Route::get('/export', [AuditReportController::class, 'export'])->name('export');
        });
    });
});
