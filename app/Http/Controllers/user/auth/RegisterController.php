<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendingUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Mail\VerifyEmail;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('user.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => 'required|string|min:8',
        ]);

        PendingUser::where('email', $validated['email'])->delete();

        $token  = Str::random(32);
        $hashed = Hash::make($validated['password']);

        DB::transaction(function () use ($validated, $token, $hashed) {
            $pending = PendingUser::create([
                'name'               => $validated['name'],
                'email'              => $validated['email'],
                'password'           => $hashed,
                'verification_token' => $token,
            ]);

            $link = route('custom.verify', [
                'token' => $token,
                'email' => $pending->email,
            ]);

          
            Mail::to($pending->email)->send(new VerifyEmail($link, $pending));

            session()->flash('email',   $pending->email);
            session()->flash('name',    $pending->name);
            session()->flash('message', 'A verification email has been sent. Please check your inbox.');
        });

        return redirect()->route('check.email.page');
    }

    public function resend(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);
    
        $user = PendingUser::where('email', $validated['email'])->first();
    
        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }
    
        $token = Str::random(64);
        $user->verification_token = $token;
        $user->save();
    
        $link = route('custom.verify', [
            'token' => $token,
            'email' => $user->email,
        ]);
    
        Mail::to($user->email)->send(new VerifyEmail($link, $user));
    
        return response()->json(['message' => 'Verification email resent'], 200);
    }

    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'old_email' => 'required|email',
            'new_email' => 'required|email',
        ]);

        if (User::where('email', $validated['new_email'])->exists()) {
            return response()->json([
                'status'  => 'already_verified',
                'message' => 'This email is already verified. Please login instead.',
            ], 409);
        }

        PendingUser::where('email', $validated['new_email'])
                   ->where('email', '!=', $validated['old_email'])
                   ->delete();

        $user = PendingUser::where('email', $validated['old_email'])->first();

        if (!$user) {
            return response()->json(['message' => 'Old email not found'], 404);
        }

        $user->email = $validated['new_email'];
        $user->verification_token = Str::random(64);
        $user->save();

        $link = route('custom.verify', [
            'token' => $user->verification_token,
            'email' => $user->email,
        ]);

        Mail::to($user->email)->send(new VerifyEmail($link, $user));


        return response()->json([
            'status' => 'success',
            'email'  => $user->email,
        ], 200);
    }
} 
