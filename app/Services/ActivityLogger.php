<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Log an activity
     */
    public static function log(
        string $action,
        ?Model $subject = null,
        ?array $properties = null
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->getKey(),
            'properties' => $properties,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Log a login event
     */
    public static function logLogin(): ActivityLog
    {
        return self::log('login');
    }

    /**
     * Log a logout event
     */
    public static function logLogout(): ActivityLog
    {
        return self::log('logout');
    }

    /**
     * Log a borrow creation
     */
    public static function logBorrowCreate(Model $borrowRecord): ActivityLog
    {
        return self::log('borrow_create', $borrowRecord, [
            'borrow_date' => $borrowRecord->borrow_date->format('Y-m-d'),
            'class_name' => $borrowRecord->class_name,
        ]);
    }

    /**
     * Log a borrow return
     */
    public static function logBorrowReturn(Model $borrowRecord): ActivityLog
    {
        return self::log('borrow_return', $borrowRecord);
    }

    /**
     * Log a borrow approval
     */
    public static function logBorrowApprove(Model $borrowRecord): ActivityLog
    {
        return self::log('borrow_approve', $borrowRecord);
    }

    /**
     * Log a borrow rejection
     */
    public static function logBorrowReject(Model $borrowRecord, string $reason): ActivityLog
    {
        return self::log('borrow_reject', $borrowRecord, [
            'reason' => $reason,
        ]);
    }
}
