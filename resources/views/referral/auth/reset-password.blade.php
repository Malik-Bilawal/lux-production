@extends('referral.layouts.authplain')

@section('content')
<div class="h-screen flex items-center justify-center bg-light">
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-2xl">
        <h2 class="text-2xl font-bold text-dark mb-6 text-center">Reset Password</h2>

        <form action="{{ route('referral.reset-password.submit') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <input type="email" name="email" placeholder="Enter your referral email"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent" required>
            </div>

            <div>
                <input type="password" name="password" placeholder="New Password"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent" required>
            </div>

            <div>
                <input type="password" name="password_confirmation" placeholder="Confirm New Password"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent" required>
            </div>

            <button type="submit" style="background-color:#4f46e5;" class="w-full text-white py-3 rounded-lg font-medium">Reset Password</button>
        </form>
    </div>
</div>
@endsection
