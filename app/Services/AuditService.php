<?php
namespace App\Services;

use function Spatie\Activitylog\activity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Small wrapper around spatie/activitylog to keep usage consistent.
 */
class AuditService
{
    /**
     * Log an activity.
     *
     * @param string $message 
     * @param Model|null $subject 
     * @param array $properties 
     * @param string|null $logName 
     * @return \Spatie\Activitylog\Models\Activity
     */
    public static function log(string $message, ?Model $subject = null, array $properties = [], ?string $logName = null)
    {
        // support admin guard if you have separate guard e.g. 'admin'
        $user = Auth::guard('admin')->user() ?? Auth::user() ?? null;

        // safe request properties (if not available, skip)
        $req = null;
        try {
            $req = request();
        } catch (\Throwable $e) {
            $req = null;
        }

        $baseProps = [
            'ip' => $req ? $req->ip() : null,
            'user_agent' => $req ? $req->userAgent() : null,
            'route' => $req && $req->route() ? $req->route()->getName() : null,
        ];

        $act = activity($logName ?? 'default')
            ->causedBy($user)
            ->withProperties(array_merge($baseProps, $properties));

        if ($subject) {
            $act = $act->performedOn($subject);
        }

        return $act->log($message);
    }
}
