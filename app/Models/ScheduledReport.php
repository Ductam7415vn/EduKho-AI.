<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'report_type',
        'frequency',
        'send_time',
        'day_of_week',
        'day_of_month',
        'filters',
        'recipients',
        'is_active',
        'last_sent_at',
        'next_run_at',
    ];

    protected $casts = [
        'filters' => 'array',
        'recipients' => 'array',
        'is_active' => 'boolean',
        'last_sent_at' => 'datetime',
        'next_run_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDue($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('next_run_at')
                  ->orWhere('next_run_at', '<=', now());
            });
    }

    public function getReportTypeLabelAttribute(): string
    {
        return match ($this->report_type) {
            'equipment_list' => 'Danh sach thiet bi',
            'borrow_tracking' => 'Theo doi muon tra',
            'inventory_summary' => 'Tong hop ton kho',
            'overdue_report' => 'Qua han tra',
            'maintenance_report' => 'Bao tri thiet bi',
            default => $this->report_type,
        };
    }

    public function getFrequencyLabelAttribute(): string
    {
        return match ($this->frequency) {
            'daily' => 'Hang ngay',
            'weekly' => 'Hang tuan',
            'monthly' => 'Hang thang',
            default => $this->frequency,
        };
    }

    public function calculateNextRun(): void
    {
        $now = now();
        $sendTime = $this->send_time;

        switch ($this->frequency) {
            case 'daily':
                $next = $now->copy()->setTimeFromTimeString($sendTime);
                if ($next->lte($now)) {
                    $next->addDay();
                }
                break;

            case 'weekly':
                $dayOfWeek = $this->day_of_week ?? 1; // Default Monday
                $next = $now->copy()->setTimeFromTimeString($sendTime);
                while ($next->dayOfWeek != $dayOfWeek || $next->lte($now)) {
                    $next->addDay();
                }
                break;

            case 'monthly':
                $dayOfMonth = $this->day_of_month ?? 1;
                $next = $now->copy()->day($dayOfMonth)->setTimeFromTimeString($sendTime);
                if ($next->lte($now)) {
                    $next->addMonth()->day($dayOfMonth);
                }
                break;
        }

        $this->update(['next_run_at' => $next]);
    }

    public function markAsSent(): void
    {
        $this->update(['last_sent_at' => now()]);
        $this->calculateNextRun();
    }
}
