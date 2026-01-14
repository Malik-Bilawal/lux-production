<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsReferralApproved
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user->referralApplication || $user->referralApplication->status !== 'approved') {
            return redirect()->route('referral.apply')->with('error', 'Your referral application is not approved yet.');
        }

        return $next($request);
    }
}
