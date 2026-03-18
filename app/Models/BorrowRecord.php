<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BorrowRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'teaching_plan_id',
        'lesson_name',
        'period',
        'class_name',
        'subject',
        'borrow_date',
        'expected_return_date',
        'actual_return_date',
        'approval_status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'borrow_date' => 'datetime',
            'expected_return_date' => 'datetime',
            'actual_return_date' => 'datetime',
            'approved_at' => 'datetime',
            'period' => 'integer',
        ];
    }

    // ── Relationships ──────────────────────────────────

    /**
     * Giáo viên mượn
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Kế hoạch giảng dạy liên kết (nếu có)
     */
    public function teachingPlan(): BelongsTo
    {
        return $this->belongsTo(TeachingPlan::class);
    }

    /**
     * Người phê duyệt (Admin/BGH)
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Chi tiết các cá thể thiết bị trong phiếu mượn
     */
    public function details(): HasMany
    {
        return $this->hasMany(BorrowDetail::class);
    }

    /**
     * Log tương tác AI đã tạo ra phiếu mượn này
     */
    public function aiChatLog(): HasOne
    {
        return $this->hasOne(AiChatLog::class);
    }

    // ── Helper Methods ─────────────────────────────────

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isReturned(): bool
    {
        return $this->status === 'returned';
    }

    public function isOverdue(): bool
    {
        return $this->status === 'overdue';
    }

    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    public function isApproved(): bool
    {
        return in_array($this->approval_status, ['auto_approved', 'approved']);
    }

    /**
     * Phê duyệt phiếu mượn (dành cho Admin)
     */
    public function approve(User $approver): void
    {
        $this->update([
            'approval_status' => 'approved',
            'approved_by' => $approver->id,
            'approved_at' => now(),
        ]);
    }

    /**
     * Từ chối phiếu mượn
     */
    public function reject(User $approver, string $reason): void
    {
        $this->update([
            'approval_status' => 'rejected',
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Hoàn tất trả đồ
     */
    public function markAsReturned(): void
    {
        $this->update([
            'status' => 'returned',
            'actual_return_date' => now(),
        ]);

        // Chỉ đưa về available nếu item vẫn đang ở trạng thái borrowed.
        // Điều này tránh ghi đè các trạng thái đã được xử lý khi trả đồ
        // (ví dụ: maintenance, lost).
        foreach ($this->details as $detail) {
            if ($detail->equipmentItem->status === 'borrowed') {
                $detail->equipmentItem->markAsAvailable();
            }
        }
    }

    // ── Scopes ─────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'active')
                     ->where('expected_return_date', '<', now());
    }

    /**
     * Kiểm tra xung đột: tìm phiếu mượn trùng thời gian
     */
    public function scopeConflictsWith($query, $borrowDate, $returnDate)
    {
        return $query->where('status', 'active')
                     ->whereIn('approval_status', ['auto_approved', 'approved', 'pending'])
                     ->where(function ($q) use ($borrowDate, $returnDate) {
                         $q->whereBetween('borrow_date', [$borrowDate, $returnDate])
                           ->orWhereBetween('expected_return_date', [$borrowDate, $returnDate])
                           ->orWhere(function ($q2) use ($borrowDate, $returnDate) {
                               $q2->where('borrow_date', '<=', $borrowDate)
                                  ->where('expected_return_date', '>=', $returnDate);
                           });
                     });
    }
}
