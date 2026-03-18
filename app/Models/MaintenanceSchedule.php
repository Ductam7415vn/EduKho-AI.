<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceSchedule extends Model
{
    protected $fillable = [
        'equipment_item_id',
        'created_by',
        'completed_by',
        'title',
        'description',
        'type',
        'priority',
        'scheduled_date',
        'completed_date',
        'status',
        'notes',
        'cost',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'completed_date' => 'date',
            'cost' => 'decimal:0',
        ];
    }

    // ── Relationships ──────────────────────────────────

    public function equipmentItem(): BelongsTo
    {
        return $this->belongsTo(EquipmentItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function completer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    // ── Helper Methods ─────────────────────────────────

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isOverdue(): bool
    {
        return $this->status === 'scheduled' && $this->scheduled_date->isPast();
    }

    public function markAsInProgress(): void
    {
        $this->update(['status' => 'in_progress']);
        $this->equipmentItem->update(['status' => 'maintenance']);
    }

    public function markAsCompleted(User $user, ?string $notes = null, ?float $cost = null): void
    {
        $this->update([
            'status' => 'completed',
            'completed_by' => $user->id,
            'completed_date' => now(),
            'notes' => $notes ?? $this->notes,
            'cost' => $cost ?? $this->cost,
        ]);
        $this->equipmentItem->update(['status' => 'available']);
    }

    // ── Scopes ─────────────────────────────────────────

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
                     ->where('scheduled_date', '>=', now())
                     ->orderBy('scheduled_date');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'scheduled')
                     ->where('scheduled_date', '<', now());
    }
}
