<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    /**
     * Record an audit log entry.
     *
     * @param  string       $action       e.g. 'item.created'
     * @param  string       $module       e.g. 'Inventory'
     * @param  string       $description  Human-readable summary
     * @param  Model|null   $subject      The Eloquent model affected (optional)
     * @param  array|null   $properties   Before/after snapshot data (optional)
     */
    public static function log(
        string $action,
        string $module,
        string $description,
        ?Model $subject = null,
        ?array $properties = null
    ): void {
        try {
            AuditLog::create([
                'user_id'      => Auth::id(),
                'action'       => $action,
                'module'       => $module,
                'description'  => $description,
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id'   => $subject?->getKey(),
                'properties'   => $properties,
                'ip_address'   => Request::ip(),
                'user_agent'   => Request::userAgent(),
            ]);
        } catch (\Exception $e) {
            // Never let audit logging break the main request
            \Log::warning('AuditLogger failed: ' . $e->getMessage());
        }
    }
}
