@extends("user.layouts.master-layouts.auth-plain")

@section('title', 'Forgot Password | Luxorix')

@push("script")
<script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0f172a',
                        secondary: '#1e293b',
                        accent: '#00f2fe',
                        accent2: '#ec4899',
                        accent3: '#4f46e5',
                        dark: '#0f172a',
                        light: '#f8fafc',
                        neon: '#00f2fe',
                        pulse: '#ec4899',
                        warranty: '#4ade80'
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                        rajdhani: ['Rajdhani', 'sans-serif']
                    },
                    animation: {
                        float: 'float 3s ease-in-out infinite',
                        pulse: 'pulse 1.5s infinite',
                        'fade-in': 'fadeIn 0.3s ease-in forwards',
                        'fade-out': 'fadeOut 0.3s ease-out forwards',
                        wave: 'wave 1.2s infinite linear',
                        shield: 'shield 3s ease-in-out infinite'
                    },
                    keyframes: {
                        float: {
                            '0%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-15px)' },
                            '100%': { transform: 'translateY(0px)' }
                        },
                        pulse: {
                            '0%': { transform: 'scale(1)', boxShadow: '0 0 0 0 rgba(236, 72, 153, 0.7)' },
                            '70%': { transform: 'scale(1.05)', boxShadow: '0 0 0 10px rgba(236, 72, 153, 0)' },
                            '100%': { transform: 'scale(1)', boxShadow: '0 0 0 0 rgba(236, 72, 153, 0)' }
                        },
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        fadeOut: {
                            '0%': { opacity: '1', transform: 'translateY(0)' },
                            '100%': { opacity: '0', transform: 'translateY(10px)' }
                        },
                        wave: {
                            '0%': { transform: 'scaleY(0.8)' },
                            '50%': { transform: 'scaleY(1.5)' },
                            '100%': { transform: 'scaleY(0.8)' }
                        },
                        shield: {
                            '0%': { transform: 'translateY(0) rotate(0deg)', boxShadow: '0 0 0 0 rgba(74, 222, 128, 0.7)' },
                            '50%': { transform: 'translateY(-10px) rotate(5deg)', boxShadow: '0 0 20px 5px rgba(74, 222, 128, 0.7)' },
                            '100%': { transform: 'translateY(0) rotate(0deg)', boxShadow: '0 0 0 0 rgba(74, 222, 128, 0.7)' }
                        }
                    }
                }
            }
        }
    </script>
@endpush


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
                <p class="text-slate-400 text-sm">Enter your email to receive a reset link</p>
            </div>
            
            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}" class="px-6 pb-6">
            @csrf

                <div class="form-group mb-5">
                    <label class="form-label block text-slate-300 text-sm font-medium mb-2">Email Address</label>
                    <div class="input-with-icon relative">
                        <i class="fas fa-envelope input-icon absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-500"></i>
                        <input 
                            type="email" 
                            class="form-input w-full py-3 pl-10 pr-4 rounded-lg"
                            placeholder="your.email@example.com"
                            name="email"
                            id="emailInput"
                        >
                    </div>
                    @if (session('status'))
    <div class="text-green-400 text-sm mb-3">
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="text-red-400 text-sm mb-3">
        {{ $errors->first('email') }}
    </div>
@endif

                </div>
                
                <div id="successMessage" class="text-green-400 text-sm mt-2 hidden">
    Check your email for reset link.
</div>
<div id="emailError" class="text-red-400 text-sm mt-2 hidden">
</div>

                
<button 
    type="submit" 
    id="reset-btn"
    class="shine-effect w-full h-12 py-3.5 rounded-lg bg-gradient-to-r from-accent3 to-purple-700 text-white font-semibold shadow-lg shadow-accent3/30 hover:shadow-accent3/40 transition-all duration-300 hover:-translate-y-0.5 mb-4"
>
    <span id="button-text">Send Reset Link</span>
</button>

            </form>
            
            <div class="flex items-center justify-center mt-2 text-sm">
                <a href="{{ route("login") }}" class="text-accent hover:text-accent2 transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Login
                </a>
            </div>
            
            <!-- Footer -->
            <div class="px-6 py-4 border-t border-slate-700/50 text-center text-slate-400 text-xs">
                <p>Need help? 
                    <a href="#" class="text-accent font-medium hover:text-accent2 transition-colors">Contact Support</a>
                </p>
                <div class="mt-2 text-xxs">
                    By using Luxorix, you agree to our 
                    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@push("script")

@vite('resources/js/user/auth/forgot-password.js')

@endpush