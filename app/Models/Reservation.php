<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'equipment_id',
        'quantity',
        'reserved_date',
        'period',
        'class_name',
        'subject',
        'lesson_name',
        'status',
        'borrow_record_id',
        'notes',
        'confirmed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'reserved_date' => 'date',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function borrowRecord(): BelongsTo
    {
        return $this->belongsTo(BorrowRecord::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed'])
                     ->where('reserved_date', '>=', now()->toDateString());
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('reserved_date', $date);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function canBeConverted(): bool
    {
        return in_array($this->status, ['pending', 'confirmed'])
            && $this->reserved_date->isToday();
    }

    public function confirm(): void
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }

    public function cancel(): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
    }

    public function markAsConverted(BorrowRecord $borrowRecord): void
    {
        $this->update([
            'status' => 'converted',
            'borrow_record_id' => $borrowRecord->id,
        ]);
    }
}
