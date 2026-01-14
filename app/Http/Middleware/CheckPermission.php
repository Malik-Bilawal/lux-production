<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        $admin = Auth::guard('admin')->user();
    
        if (!$admin) {
            return redirect()->route('admin.login');
        }
    
        Cache::put('admin-is-online-' . $admin->id, true, now()->addMinutes(5));
    
        if ($admin->isFillable('last_seen')) {
            $admin->update(['last_seen' => now()]);
        }
    
        if ($admin->role && $admin->role->slug === 'super_admin') {
            return $next($request);
        }
    
        if (!$admin->hasPermission($permission)) {
            abort(403, 'Unauthorized action.');
        }
    
        return $next($request);
    }
    
    

}
