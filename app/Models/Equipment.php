<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipments';

    protected $fillable = [
        'name',
        'base_code',
        'unit',
        'price',
        'purchase_price',
        'purchase_date',
        'useful_life_years',
        'salvage_value',
        'depreciation_method',
        'origin',
        'category_subject',
        'grade_level',
        'is_digital',
        'security_level',
        'is_fixed_asset',
        'file_url',
        'file_type',
        'file_size',
        'description',
        'tags',
        'low_stock_threshold',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:0',
            'purchase_price' => 'decimal:2',
            'salvage_value' => 'decimal:2',
            'purchase_date' => 'date',
            'is_digital' => 'boolean',
            'is_fixed_asset' => 'boolean',
        ];
    }

    // ── Relationships ──────────────────────────────────

    /**
     * Danh sách cá thể vật lý (VD: Kính hiển vi 1, Kính hiển vi 2...)
     */
    public function items(): HasMany
    {
        return $this->hasMany(EquipmentItem::class);
    }

    /**
     * Lịch sử tăng/giảm kho
     */
    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(InventoryLog::class);
    }

    /**
     * Kế hoạch sử dụng thiết bị này
     */
    public function teachingPlans(): HasMany
    {
        return $this->hasMany(TeachingPlan::class);
    }

    // ── Helper Methods ─────────────────────────────────

    public function isHighSecurity(): bool
    {
        return $this->security_level === 'high_security';
    }

    public function isDigital(): bool
    {
        return $this->is_digital === true;
    }

    public function isFixedAsset(): bool
    {
        return $this->is_fixed_asset === true;
    }

    public function isLowStock(): bool
    {
        return !$this->is_digital && $this->availableCount() <= ($this->low_stock_threshold ?? 2);
    }

    public function hasDepreciationInfo(): bool
    {
        return $this->purchase_price && $this->purchase_date;
    }

    public function getDepreciation(): array
    {
        return app(\App\Services\DepreciationService::class)->calculate($this);
    }

    public function getDepreciationSchedule(): array
    {
        return app(\App\Services\DepreciationService::class)->getSchedule($this);
    }

    /**
     * Đếm số cá thể đang sẵn sàng cho mượn
     */
    public function availableCount(): int
    {
        return $this->items()->where('status', 'available')->count();
    }

    /**
     * Tổng số cá thể (tất cả trạng thái)
     */
    public function totalCount(): int
    {
        return $this->items()->count();
    }

    // ── Scopes ─────────────────────────────────────────

    /**
     * Lọc theo môn học
     */
    public function scopeBySubject($query, string $subject)
    {
        return $query->where('category_subject', $subject);
    }

    /**
     * Lọc theo khối lớp (hỗ trợ "All" và "10,11")
     */
    public function scopeByGrade($query, string $grade)
    {
        return $query->where(function ($q) use ($grade) {
            $q->where('grade_level', 'All')
              ->orWhere('grade_level', 'LIKE', "%{$grade}%");
        });
    }

    /**
     * Chỉ thiết bị vật lý (không phải học liệu số)
     */
    public function scopePhysical($query)
    {
        return $query->where('is_digital', false);
    }

    /**
     * Chỉ học liệu số
     */
    public function scopeDigital($query)
    {
        return $query->where('is_digital', true);
    }

    /**
     * Thiết bị an ninh cao (cần phê duyệt BGH)
     */
    public function scopeHighSecurity($query)
    {
        return $query->where('security_level', 'high_security');
    }
}
