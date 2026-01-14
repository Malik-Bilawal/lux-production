@extends('referral.layouts.authplain')

@section('content')
<div class="bg-light h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white rounded-xl shadow-2xl p-8">
        <h2 class="text-2xl font-bold text-dark mb-6 text-center">Referral Partner Login</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form action="{{ route('referral.login.submit') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-dark mb-1">Email</label>
                <input type="email" name="email" id="email" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                       placeholder="Enter your email" required>
                @error('email')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-dark mb-1">Password</label>
                <input type="password" name="password" id="password"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                       placeholder="Enter your password" required>
                @error('password')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between items-center text-sm">
            <input type="checkbox" id="remember_me" name="remember" class="form-checkbox text-primary">
            <label for="remember_me" class="text-dark">Remember Me</label>
                <a href="{{ route('referral.forgot-password') }}" class="text-primary hover:text-primary-dark">Forgot Password?</a>
            </div>

            <button type="submit" 
                    class="w-full bg-primary text-white py-3 rounded-lg font-medium hover:bg-primary-dark transition">
                Login
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-dark">
            Don't have an account? <a href="{{ route("referral.create") }}" class="text-primary hover:text-primary-dark">Register</a>
        </p>
    </div>
</div>
@endsection