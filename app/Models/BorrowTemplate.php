<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowTemplate extends Model
{
    protected $fillable = [
        'user_id',
        'equipment_id',
        'name',
        'quantity',
        'class_name',
        'subject',
        'lesson_name',
        'period',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'period' => 'integer',
        ];
    }

    // ── Relationships ──────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }
}
