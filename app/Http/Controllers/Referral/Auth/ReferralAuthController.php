<?php

namespace App\Http\Controllers\Referral\Auth;



use App\Models\Referral;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReferralResetPasswordMail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException; 
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule; 

class ReferralAuthController extends Controller
{
    public function create()
    {
        return view('referral.auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:referrals,email',
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'country' => 'nullable|string|max:100',
            'type' => 'nullable|in:student,seller',
            'niche' => 'nullable|string|max:255',
            'followers_count' => 'nullable|integer|min:0',
            'social_platform' => 'nullable|string|max:50',
            'social_link' => 'nullable|url|max:255',
            'bio' => 'nullable|string|max:1000',
        ]);

        try {
            $data = $validated;
            $data['password'] = Hash::make($validated['password']);
            $data['status'] = 'pending'; 

            $referral = Referral::create($data);

            if ($request->hasFile('profile_picture')) {
                $path = "uploads/referrals/profile-image/{$referral->id}";
                $fileName = time() . '.' . $request->profile_picture->extension();
                $imagePath = $request->file('profile_picture')->storeAs($path, $fileName, 'public');

                $referral->profile_picture = $imagePath;
                $referral->save();
            }

            $request->session()->put('referral_email', $referral->email);

            return response()->json([
                'message' => 'Application Submitted Successfully!',
                'user_email' => $referral->email,
                'redirect_url' => route('referral.pending') 
            ], 201);

        } catch (\Exception $e) {
            Log::error('Referral Registration Failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'An internal server error occurred. Please try again.'
            ], 500); 
        }
    }

    public function index(Request $request)
    {
        $email = $request->session()->get('referral_email');

        if (!$email) {
            return redirect('/'); 
        }

        return view('referral.auth.pending', ['email' => $email]);
    }

    public function showLoginForm()
    {
        return view('referral.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $remember = $request->has('remember');

        if (Auth::guard('referral')->attempt([
                'email' => $credentials['email'], 
                'password' => $credentials['password'],
                'status' => 'approved' 
            ], $remember)) 
        {
            $request->session()->regenerate(); 
            return redirect()->route('referral.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match or your account is not approved.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('referral')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('referral.login');
    }

    public function showForgotPasswordForm()
    {
        return view('referral.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('referrals')->sendResetLink(
            $request->only('email'),
            function ($user, $token) {
                $resetLink = route('referral.reset-password', $token);
                Mail::to($user->email)->send(new ReferralResetPasswordMail($resetLink));
            }
        );

        return back()->with('status', 'If a matching account was found, a password reset link has been sent to your email.');
    }

    // Show Reset Password Form
    public function showResetForm($token)
    {
        return view('referral.auth.reset-password', ['token' => $token]);
    }

    // Handle Password Reset
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::min(8)], // Uses the Alias at the top
        ]);

        $status = Password::broker('referrals')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
                
                Log::info('Referral password reset for user: ' . $user->email);

            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('referral.login')
                    ->with('status', 'Your password has been reset!');
        }

        return back()
            ->withErrors(['email' => [__($status)]]);
    }

}


    
