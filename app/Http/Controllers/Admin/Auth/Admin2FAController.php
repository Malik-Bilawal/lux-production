<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\Admin;
use Illuminate\Http\Request;
use NunoMaduro\Collision\Writer;
use PragmaRX\Google2FA\Google2FA;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;

class Admin2FAController extends Controller
{
    public function showForm()
    {
        if (!session()->has('2fa:admin_id')) {
            return redirect()->route('admin.login');
        }
        return view('admin.auth.2fa');
    }


    public function showSetupForm(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->google2fa_enabled) {
            return redirect()->route('admin.dashboard');
        }

        $google2fa = new Google2FA();

        // Generate secret if not exists
        if (!$admin->google2fa_secret) {
            $admin->google2fa_secret = $google2fa->generateSecretKey();
            $admin->save();
        }

        // Generate QR code URL
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            'YourCompanyName',
            $admin->email,
            $admin->google2fa_secret
        );

        // Optional: render QR code as base64
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrCodeUrl);

        return view('admin.2fa_setup', compact('admin', 'qrCodeSvg'));
    }

    // Verify first OTP and enable 2FA
    public function setup(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $admin = Auth::guard('admin')->user();
        $google2fa = new Google2FA();

        if (!$google2fa->verifyKey($admin->google2fa_secret, $request->otp)) {
            return back()->withErrors(['otp' => 'Invalid code. Try again.']);
        }

        // Enable 2FA
        $admin->google2fa_enabled = true;
        $admin->save();

        return redirect()->route('admin.dashboard')
                         ->with('success', 'Two-Factor Authentication enabled successfully!');
    }

    public function verify(Request $request)
{
    $request->validate(['otp' => 'required|digits:6', 'remember_device' => 'nullable|boolean']);
    $adminId = session('2fa:admin_id');
    $admin = Admin::find($adminId);
    if (!$admin) return redirect()->route('admin.login');

    $google2fa = new Google2FA();
    $otpValid = $google2fa->verifyKey($admin->google2fa_secret, $request->otp);

    // Optional email OTP fallback
    if (!$otpValid) {
        $otpValid = $request->otp == $admin->email_otp &&
                    $admin->email_otp_expires_at &&
                    $admin->email_otp_expires_at->isFuture();
    }

    if (!$otpValid) {
        return back()->withErrors(['otp' => 'Invalid authentication code.']);
    }

    // Login admin
    Auth::guard('admin')->login($admin);
    session()->forget(['2fa:admin_id', '2fa:fingerprint']);

    // Update trusted device if selected
    if ($request->remember_device) {
        $trustedDevices = collect(json_decode($admin->trusted_devices ?? '[]'));
        $trustedDevices->push([
            'fingerprint' => session('2fa:fingerprint'),
            'expires_at' => now()->addDays(30)->toDateTimeString(),
        ]);
        $admin->trusted_devices = $trustedDevices->toJson();
    }

    $admin->update([
        'current_session_id' => session()->getId(),
        'last_login_ip' => request()->ip(),
        'last_device_fingerprint' => session('2fa:fingerprint'),
        'failed_login_attempts' => 0,
        'last_login_at' => now(),
        'email_otp' => null,
        'email_otp_expires_at' => null,
    ]);

    return redirect()->route('admin.dashboard');
}

}

