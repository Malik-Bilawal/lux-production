<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if ($this->tooManyAttempts($request)) {
            $seconds = RateLimiter::availableIn($this->throttleKey($request));
            return redirect()->back()->with('error', "Too many login attempts. Try again in {$seconds} seconds.");
        }
    
        $credentials = $request->only('email', 'password');
    
        if (Auth::guard('admin')->attempt($credentials)) {
            RateLimiter::clear($this->throttleKey($request));
    
            $admin = Auth::guard('admin')->user();
            $currentFingerprint = hash('sha256', request()->userAgent() . request()->ip());
            $requires2FA = false;
    
            // Check trusted devices
            $trustedDevices = collect(json_decode($admin->trusted_devices ?? '[]'));
            $trustedDevice = $trustedDevices->first(function ($d) use ($currentFingerprint) {
                return $d->fingerprint === $currentFingerprint && now()->lt(\Carbon\Carbon::parse($d->expires_at));
            });
    
            // Conditional 2FA triggers (only if 2FA enabled)
            if ($admin->google2fa_enabled && !$trustedDevice) {
                if ($admin->last_device_fingerprint !== $currentFingerprint) $requires2FA = true;
                if ($admin->last_login_ip !== $request->ip()) $requires2FA = true;
                if ($admin->failed_login_attempts >= 3) $requires2FA = true;
                if ($admin->last_login_at && now()->diffInHours($admin->last_login_at) > 24) $requires2FA = true;
                if ($admin->current_session_id && $admin->current_session_id !== session()->getId()) $requires2FA = true;
            }
    
            // Redirect to 2FA if required
            if ($requires2FA) {
                session([
                    '2fa:admin_id' => $admin->id,
                    '2fa:fingerprint' => $currentFingerprint,
                ]);
                Auth::guard('admin')->logout(); // logout until 2FA verified
                return redirect()->route('admin.2fa.form');
            }
    
            // Successful login without 2FA
            Cache::put('admin-is-online-' . $admin->id, true, now()->addMinutes(5));
            $admin->update([
                'current_session_id' => session()->getId(),
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
                'last_device_fingerprint' => $currentFingerprint,
                'failed_login_attempts' => 0,
            ]);
    
            // Role-based redirect
            if ($admin->role && $admin->role->default_route) {
                return redirect()->route($admin->role->default_route);
            }
    
            return redirect()->route('admin.dashboard');
        }
    
        // Failed login
        RateLimiter::hit($this->throttleKey($request), 300);
        $seconds = RateLimiter::availableIn($this->throttleKey($request));
        return redirect()->back()->with('error', "Invalid credentials! Max attempts in {$seconds} sec.");
    }
    
    // --- Helper methods ---
    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }
    
    protected function tooManyAttempts(Request $request)
    {
        return RateLimiter::tooManyAttempts($this->throttleKey($request), 5); // 5 max attempts
    }
    
    
    
    

    public function logout()
    {
        $admin = Auth::guard('admin')->user();
    
        if ($admin) {
            Cache::forget('admin-is-online-' . $admin->id);
            $admin->update(['last_seen' => now()]);
        }
    
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
    
}
