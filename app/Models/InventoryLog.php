<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'performed_by',
        'type',
        'quantity',
        'reason',
        'document_ref',
        'action_date',
    ];

    protected function casts(): array
    {
        return [
            'action_date' => 'date',
            'quantity' => 'integer',
        ];
    }

    // ── Relationships ──────────────────────────────────

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    // ── Scopes ─────────────────────────────────────────

    public function scopeIncreases($query)
    {
        return $query->where('type', 'increase');
    }

    public function scopeDecreases($query)
    {
        return $query->where('type', 'decrease');
    }

    public function scopeInDateRange($query, $from, $to)
    {
        return $query->whereBetween('action_date', [$from, $to]);
    }
}
