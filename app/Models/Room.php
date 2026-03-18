<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'manager_id',
        'type',
        'location',
        'capacity',
    ];

    // ── Relationships ──────────────────────────────────

    /**
     * Người quản lý phòng
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Danh sách cá thể thiết bị đang lưu trữ trong phòng
     */
    public function equipmentItems(): HasMany
    {
        return $this->hasMany(EquipmentItem::class);
    }

    // ── Helper Methods ─────────────────────────────────

    public function isWarehouse(): bool
    {
        return $this->type === 'warehouse';
    }

    public function isLab(): bool
    {
        return $this->type === 'lab';
    }

    /**
     * Đếm số thiết bị đang sẵn sàng trong phòng
     */
    public function availableItemsCount(): int
    {
        return $this->equipmentItems()->where('status', 'available')->count();
    }
}
