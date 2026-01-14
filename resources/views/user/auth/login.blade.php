@extends("user.layouts.master-layouts.auth-plain")


@section('title', 'Login | Luxorix')






@section("content")

<div class="root">
    <div class="grid-bg h-screen w-full flex items-center justify-center p-4 relative overflow-hidden">
        <!-- Animated background elements -->
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        
        <!-- Login Card -->
        <div class="login-card bg-secondary/80 backdrop-blur-md rounded-2xl border border-accent/20 shadow-2xl shadow-black/30 overflow-hidden w-full max-w-sm compact-mode">
            <!-- Card animation effect -->
            <div class="absolute inset-0 z-0">
                <div class="absolute -inset-32 animate-rotate bg-[conic-gradient(transparent,rgba(0,242,254,0.3),transparent_30%)]"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-primary/80 to-secondary/80"></div>
            </div>
            
            <div class="relative z-10">
                <!-- Header -->
                <div class="login-header text-center px-4 pt-6">
                    <div class="login-logo w-14 h-14 mx-auto rounded-full bg-gradient-to-br from-accent3 to-accent flex items-center justify-center mb-2">
                        <i class="fas fa-crown text-xl text-white"></i>
                    </div>
                    <h1 class="text-xl font-bold mb-1">
                        <span class="gradient-text">Welcome Back</span>
                    </h1>
                    <p class="text-slate-400 text-xs">Sign in to continue your Luxorix experience</p>
                </div>
                
                <!-- Form -->
                <form class="login-form px-4 py-3" method="POST" action="{{ route('login') }}">
    @csrf

                    <!-- Email -->
                    <div class="form-group mb-3">
                        <label class="form-label block text-slate-300 text-xs font-medium mb-1">Email Address</label>
                        <div class="input-with-icon relative">
                            <i class="fas fa-envelope input-icon absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-500 text-sm"></i>
                            <input 
                                type="email" 
                                class="form-input w-full py-2 pl-10 pr-3 rounded-lg text-sm"
                                placeholder="your.email@example.com"
                                name="email"
                            >
                        </div>
                        @error('email')
    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
@enderror

                    </div>
                    
                    <!-- Password -->
                    <div class="form-group mb-4">
                        <label class="form-label block text-slate-300 text-xs font-medium mb-1">Password</label>
                        <div class="input-with-icon relative">
                            <i class="fas fa-lock input-icon absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-500 text-sm"></i>
                            <input 
                                type="password" 
                                id="login-password"
                                class="form-input w-full py-2 pl-10 pr-10 rounded-lg text-sm"
                                placeholder="Enter your password"
                                name="password"
                            >
                            <button 
                                type="button" 
                                class="password-toggle absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-500"
                                onclick="togglePassword('login-password')"
                            >
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        @error('password')
    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
@enderror

                    </div>
                    
                    <div class="flex items-center justify-between mb-4 text-xs">

<!-- Remember Me Checkbox -->
<label class="flex items-center space-x-2 text-gray-600">
    <input type="checkbox" name="remember"
        class="w-4 h-4 text-accent border-gray-300 rounded focus:ring-accent">
    <span class="text-accent hover:text-accent2 transition-colors">Remember Me</span>
</label>

<!-- Forgot Password Link -->
<a href="{{ route('password.email') }}"
    class="text-accent hover:text-accent2 transition-colors">
    Forgot password?
</a>

</div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="form-submit shine-effect w-full py-2.5 rounded-lg bg-gradient-to-r from-accent3 to-purple-700 text-white font-semibold text-sm shadow-lg shadow-accent3/30 hover:shadow-accent3/40 transition-all duration-300 hover:-translate-y-0.5 mb-3"
                    >
                        Sign In
                    </button>
                    
                    <!-- Divider -->
                    <div class="divider flex items-center my-3">
                        <div class="flex-grow h-px bg-gradient-to-r from-transparent via-accent/50 to-transparent"></div>
                        <span class="px-2 text-slate-400 text-xs">or continue with</span>
                        <div class="flex-grow h-px bg-gradient-to-r from-transparent via-accent/50 to-transparent"></div>
                    </div>
                    
                    <!-- Google Sign-in -->
                    <button 
                        type="button" 
                        class="google-signin flex items-center justify-center w-full py-2 rounded-lg bg-primary/50 border border-accent/30 text-slate-300 font-medium text-xs transition-colors duration-300 hover:border-accent mb-4"
                        onclick="signInWithGoogle()"
                    >
                        <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"></path>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path>
                        </svg>
                        Sign in with Google
                    </button>
                </form>
                
                <!-- Footer -->
                <div class="login-footer px-4 py-3 border-t border-slate-700/50 text-center text-slate-400 text-xs">
                    <p>Don't have an account? 
                        <a href="{{ route("register") }}" class="text-accent font-medium hover:text-accent2 transition-colors">Register Now</a>
                    </p>
                    <div class="mt-2 text-xxs">
                        By signing in, you agree to our 
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push("script")
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-auth.js"></script>

@vite('resources/js/user/auth/login.js')
@endpush