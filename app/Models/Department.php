<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // ── Relationships ──────────────────────────────────

    /**
     * Một tổ chuyên môn có nhiều giáo viên
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Alias: Lấy danh sách giáo viên (chỉ role = teacher)
     */
    public function teachers(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'teacher');
    }
}
