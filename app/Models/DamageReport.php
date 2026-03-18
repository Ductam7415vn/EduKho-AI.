<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DamageReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_item_id',
        'reported_by',
        'borrow_record_id',
        'incident_date',
        'severity',
        'description',
        'cause',
        'estimated_cost',
        'status',
        'resolution_notes',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'resolved_at' => 'datetime',
    ];

    public function equipmentItem(): BelongsTo
    {
        return $this->belongsTo(EquipmentItem::class);
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function borrowRecord(): BelongsTo
    {
        return $this->belongsTo(BorrowRecord::class);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['reported', 'investigating']);
    }

    public function scopeResolved($query)
    {
        return $query->whereIn('status', ['resolved', 'written_off']);
    }

    public function isPending(): bool
    {
        return in_array($this->status, ['reported', 'investigating']);
    }

    public function isResolved(): bool
    {
        return in_array($this->status, ['resolved', 'written_off']);
    }

    public function getSeverityLabelAttribute(): string
    {
        return match ($this->severity) {
            'minor' => 'Nhe',
            'moderate' => 'Trung binh',
            'severe' => 'Nghiem trong',
            'total_loss' => 'Mat/Hong hoan toan',
            default => $this->severity,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'reported' => 'Da bao cao',
            'investigating' => 'Dang dieu tra',
            'resolved' => 'Da xu ly',
            'written_off' => 'Da thanh ly',
            default => $this->status,
        };
    }

    public function markAsInvestigating(): void
    {
        $this->update(['status' => 'investigating']);
    }

    public function resolve(User $resolver, string $notes, string $resolution = 'resolved'): void
    {
        $this->update([
            'status' => $resolution,
            'resolution_notes' => $notes,
            'resolved_by' => $resolver->id,
            'resolved_at' => now(),
        ]);

        // Update equipment item status based on resolution
        if ($resolution === 'written_off') {
            $this->equipmentItem->update(['status' => 'broken']);
        } elseif ($resolution === 'resolved') {
            $this->equipmentItem->update(['status' => 'available']);
        }
    }
}
