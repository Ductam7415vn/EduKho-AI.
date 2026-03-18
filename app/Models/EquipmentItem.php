<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipmentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'room_id',
        'specific_code',
        'status',
        'year_acquired',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'year_acquired' => 'integer',
        ];
    }

    // ── Relationships ──────────────────────────────────

    /**
     * Thuộc danh mục thiết bị nào
     */
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * Đang lưu trữ tại phòng nào
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Lịch sử mượn trả của cá thể này
     */
    public function borrowDetails(): HasMany
    {
        return $this->hasMany(BorrowDetail::class);
    }

    // ── Helper Methods ─────────────────────────────────

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isBorrowed(): bool
    {
        return $this->status === 'borrowed';
    }

    /**
     * Đánh dấu đã được mượn
     */
    public function markAsBorrowed(): void
    {
        $this->update(['status' => 'borrowed']);
    }

    /**
     * Đánh dấu đã trả về kho
     */
    public function markAsAvailable(): void
    {
        $this->update(['status' => 'available']);
    }

    // ── Scopes ─────────────────────────────────────────

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeBorrowed($query)
    {
        return $query->where('status', 'borrowed');
    }

    public function scopeInRoom($query, int $roomId)
    {
        return $query->where('room_id', $roomId);
    }
}
