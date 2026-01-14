<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function showLoginForm()
    {
        return view('user.auth.login'); 
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    
        $remember = $request->has('remember');
    
        if (Auth::attempt($request->only('email', 'password'), $remember)) {
            $user = Auth::user();
    
            if ($user->status === 'blocked') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is blocked. Please contact support.',
                ])->withInput();
            }
    
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
    
        return back()->withErrors([
            'email' => 'Invalid credentials or account not verified.',
        ])->withInput();
    }
    


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'You have been logged out!');
    }
}

