<?php


namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\PendingUser;
use App\Models\User;

class VerifyController extends Controller
{
    public function verify($token, $email)
    {
        $pending = PendingUser::where('email', $email)
                    ->where('verification_token', $token)
                    ->first();

        if (!$pending) {
            return redirect()->route('register.form')->with('error', 'Invalid or expired verification link.');
        }

        $user = User::create([
            'name'     => $pending->name,
            'email'    => $pending->email,
            'password' => $pending->password,
        ]);

        $pending->delete();

        Auth::login($user);

        return redirect()->route('user.welcome')->with('message', 'Email verified successfully!');
    }
}

