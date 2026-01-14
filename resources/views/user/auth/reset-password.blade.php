@extends("user.layouts.master-layouts.auth-plain")

@section('title', ' Reset Password | Luxorix')



@section("content")

<div class="grid-bg h-screen w-full flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Animated background elements -->
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    
    <!-- Password Reset Card -->
    <div class="password-reset-card rounded-2xl overflow-hidden w-full max-w-md compact-mode relative">
        <!-- Card animation effect -->
        <div class="absolute inset-0 z-0">
            <div class="absolute -inset-32 animate-rotate bg-[conic-gradient(transparent,rgba(0,242,254,0.3),transparent_30%)]"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-primary/80 to-secondary/80"></div>
        </div>
        
        <div class="relative z-10">
            <!-- Header -->
            <div class="px-6 pt-8 pb-6 text-center">
                <div class="reset-logo w-16 h-16 mx-auto rounded-full bg-gradient-to-br from-accent3 to-accent flex items-center justify-center mb-3">
                    <i class="fas fa-shield-alt shield-icon text-2xl text-white"></i>
                </div>
                <h1 class="text-2xl font-bold mb-2">
                    <span class="gradient-text">Reset Your Password</span>
                </h1>
                <p class="text-slate-400 text-sm">Create a new secure password for your account</p>
            </div>
            
            <!-- Form -->

            <form method="POST" action="{{ route('password.update') }}">
    @csrf

    <!-- Hidden Email -->
    <input type="hidden" name="email" value="{{ request()->input('email') }}">
    <input type="hidden" name="token" value="{{ request()->route('token') }}">


            <div class="px-6 pb-6">
                <!-- New Password -->
                <div class="form-group mb-4">
                    <label class="form-label block text-slate-300 text-sm font-medium mb-2">New Password</label>
                    <div class="input-with-icon relative">
                        <i class="fas fa-lock input-icon absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-500"></i>
                        <input 
                            type="password" 
                            id="new-password"
                            class="form-input w-full py-3 pl-10 pr-10 rounded-lg"
                            placeholder="Enter new password"
                            name="password"
                        >
                        <button 
                            type="button" 
                            class="password-toggle absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-500"
                            onclick="togglePassword('new-password')"
                        >
                            <i class="fas fa-eye text-sm"></i>
                        </button>
                    </div>

                
                <!-- Confirm Password -->
                <div class="form-group mb-5">
                    <label class="form-label block text-slate-300 text-sm font-medium mb-2">Confirm Password</label>
                    <div class="input-with-icon relative">
                        <i class="fas fa-lock input-icon absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-500"></i>
                        <input 
                            type="password" 
                            id="confirm-password"
                            class="form-input w-full py-3 pl-10 pr-10 rounded-lg"
                            placeholder="Confirm new password"
                            name="password_confirmation"
                        >
                        <button 
                            type="button" 
                            class="password-toggle absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-500"
                            onclick="togglePassword('confirm-password')"
                        >
                            <i class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                    <div id="password-match" class="mt-1 text-xs hidden">
                        <i class="fas fa-check-circle text-green-400 mr-1"></i>
                        <span>Passwords match</span>
                    </div>
                </div>
                
               
                
                <button 
                    id="reset-button"
                    class="shine-effect w-full py-3.5 rounded-lg bg-gradient-to-r from-accent3 to-purple-700 text-white font-semibold shadow-lg shadow-accent3/30 hover:shadow-accent3/40 transition-all duration-300 hover:-translate-y-0.5 mb-4"
                >
                    Reset Password
                </button>
                

                
                <div class="flex items-center justify-center mt-4 text-sm">
                    <a href="href="{{ route('login') }} class="text-accent hover:text-accent2 transition-colors flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Login
                    </a>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="px-6 py-4 border-t border-slate-700/50 text-center text-slate-400 text-xs">
                <p>Need help? 
                    <a href="#" class="text-accent font-medium hover:text-accent2 transition-colors">Contact Support</a>
                </p>
                <div class="mt-2 text-xxs">
                    By using Luxorix, you agree to our 
                    <a href="{{ route("privacy") }}" class="text-accent hover:underline">Terms</a> and 
                    <a href="{{ route("terms") }}" class="text-accent hover:underline">Privacy</a>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection


@push("script")
@vite('resources/js/user/auth/reset-password.js')
@endpush