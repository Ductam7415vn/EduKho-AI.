<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TeachingPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'equipment_id',
        'subject',
        'lesson_name',
        'period',
        'week',
        'planned_date',
        'quantity_needed',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'planned_date' => 'date',
            'week' => 'integer',
            'period' => 'integer',
            'quantity_needed' => 'integer',
        ];
    }

    // ── Relationships ──────────────────────────────────

    /**
     * Giáo viên đăng ký kế hoạch
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Danh mục thiết bị dự kiến sử dụng
     */
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * Phiếu mượn được tạo từ kế hoạch này (nếu có)
     */
    public function borrowRecord(): HasOne
    {
        return $this->hasOne(BorrowRecord::class);
    }

    // ── Helper Methods ─────────────────────────────────

    /**
     * Kế hoạch đã tạo phiếu mượn chưa?
     */
    public function hasBorrowRecord(): bool
    {
        return $this->borrowRecord()->exists();
    }
}
