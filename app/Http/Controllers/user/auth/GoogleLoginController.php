<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Kreait\Firebase\Factory;

class GoogleLoginController extends Controller
{
    public function handleGoogleLogin(Request $request)
    {
        $token = $request->token;

        $firebase = (new Factory)->withServiceAccount(base_path('firebase.json'));
        $auth = $firebase->createAuth();

        try {
            $verifiedIdToken = $auth->verifyIdToken($token);
            $uid = $verifiedIdToken->claims()->get('sub');
            $firebaseUser = $auth->getUser($uid);

            $user = User::updateOrCreate(
                ['email' => $firebaseUser->email],
                [
                    'name' => $firebaseUser->displayName ?? $firebaseUser->email,
                    'password' => bcrypt('firebase_dummy'), 
                ]
            );

            Auth::login($user);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
