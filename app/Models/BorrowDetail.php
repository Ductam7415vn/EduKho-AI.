<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrow_record_id',
        'equipment_item_id',
        'condition_before',
        'condition_after',
        'damage_notes',
    ];

    // ── Relationships ──────────────────────────────────

    /**
     * Thuộc phiếu mượn nào
     */
    public function borrowRecord(): BelongsTo
    {
        return $this->belongsTo(BorrowRecord::class);
    }

    /**
     * Cá thể thiết bị cụ thể được mượn
     */
    public function equipmentItem(): BelongsTo
    {
        return $this->belongsTo(EquipmentItem::class);
    }

    // ── Helper Methods ─────────────────────────────────

    /**
     * Thiết bị có bị hư hỏng sau khi sử dụng không?
     */
    public function isDamaged(): bool
    {
        return !empty($this->damage_notes)
            || ($this->condition_after && $this->condition_after !== $this->condition_before);
    }
}
