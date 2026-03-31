<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'department_id',
        'name',
        'email',
        'email_verified_at',
        'phone',
        'password',
        'role',
        'is_active',
        'notification_settings',
        'two_factor_secret',
        'two_factor_enabled',
        'two_factor_confirmed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'notification_settings' => 'array',
            'two_factor_enabled' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    /**
     * Default notification settings
     */
    public static function defaultNotificationSettings(): array
    {
        return [
            'email_borrow_approved' => true,
            'email_borrow_rejected' => true,
            'email_borrow_overdue' => true,
            'email_borrow_reminder' => true,
            'email_pending_approval' => true,  // For admins
        ];
    }

    /**
     * Get notification setting with default fallback
     */
    public function getNotificationSetting(string $key): bool
    {
        $settings = $this->notification_settings ?? [];
        $defaults = self::defaultNotificationSettings();

        return $settings[$key] ?? $defaults[$key] ?? true;
    }

    /**
     * Check if user wants to receive a specific email notification
     */
    public function wantsEmailNotification(string $type): bool
    {
        return $this->getNotificationSetting('email_' . $type);
    }

    /**
     * Check if 2FA is enabled and confirmed
     */
    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_enabled && $this->two_factor_confirmed_at !== null;
    }

    // ── Helper Methods ─────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    /**
     * Check if user is principal or vice principal
     */
    public function isSchoolLeader(): bool
    {
        return in_array($this->position, ['Hiệu trưởng', 'Phó Hiệu trưởng']);
    }

    /**
     * Check if user can manage equipment (admin or school leaders)
     */
    public function canManageEquipment(): bool
    {
        return $this->isAdmin() || $this->isSchoolLeader();
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        // Admin has all permissions
        if ($this->isAdmin()) {
            return true;
        }

        // Check role-based permissions
        return \DB::table('role_permissions')
            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->where('role_permissions.role', $this->role)
            ->where('permissions.name', $permission)
            ->exists();
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    // ── Relationships ──────────────────────────────────

    /**
     * Giáo viên thuộc một tổ chuyên môn
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Giáo viên quản lý nhiều phòng
     */
    public function managedRooms(): HasMany
    {
        return $this->hasMany(Room::class, 'manager_id');
    }

    /**
     * Kế hoạch giảng dạy của giáo viên
     */
    public function teachingPlans(): HasMany
    {
        return $this->hasMany(TeachingPlan::class);
    }

    /**
     * Phiếu mượn của giáo viên
     */
    public function borrowRecords(): HasMany
    {
        return $this->hasMany(BorrowRecord::class);
    }

    /**
     * Các phiếu mượn mà admin này đã duyệt
     */
    public function approvedRecords(): HasMany
    {
        return $this->hasMany(BorrowRecord::class, 'approved_by');
    }

    /**
     * Lịch sử tương tác AI
     */
    public function aiChatLogs(): HasMany
    {
        return $this->hasMany(AiChatLog::class);
    }

    /**
     * Các thao tác tăng/giảm kho
     */
    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(InventoryLog::class, 'performed_by');
    }

    /**
     * Check if email is verified
     */
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Mark email as verified
     */
    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => now(),
        ])->save();
    }

    /**
     * Activity logs of the user
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Borrow templates saved by the user
     */
    public function borrowTemplates(): HasMany
    {
        return $this->hasMany(BorrowTemplate::class);
    }

    // ── Scopes ─────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeTeachers($query)
    {
        return $query->where('role', 'teacher');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }
}
