@extends("user.layouts.master-layouts.auth-plain")



@section('title', 'Check Email | Luxorix')



@push("style")
<style type="text/css">
      
    </style>
@endpush


@section("content")

<div class="grid-bg root">

<div class="max-w-4xl w-full mx-auto">

        
        <!-- Main Card -->
        <div class="holographic-card rounded-2xl overflow-hidden shadow-xl">
            <div class="md:flex">
                <!-- Visual Section -->
                <div class="md:w-2/5 bg-gradient-to-br from-cyan-900/30 to-pink-900/30 p-8 flex flex-col items-center justify-center relative">
                    <div class="absolute top-0 left-0 w-full h-full opacity-20">
                        <div class="pattern-dots pattern-cyan-500 pattern-bg-transparent pattern-opacity-100 pattern-size-4 w-full h-full"></div>
                    </div>
                    
                    <div class="relative z-10 text-center">
                        <div class="floating mb-8 inline-block">
                            <div class="w-48 h-48 rounded-full bg-gradient-to-r from-cyan-500 to-pink-500 flex items-center justify-center p-8 pulse">
                                <i class="fas fa-envelope-open-text text-white text-6xl"></i>
                            </div>
                        </div>
                        
                        <h3 class="text-xl font-bold text-white mb-2">Secure Account Verification</h3>
                        <p class="text-slate-300">Protecting your digital identity</p>
                    </div>
                </div>
                
                <!-- Content Section -->
                <div class="md:w-3/5 p-8">
                    <h2 class="text-3xl font-bold text-white mb-2">Verify Your Email Address</h2>
                    <p class="text-slate-300 mb-6">We've sent a verification link to your email address</p>
                    
                    <div class="bg-slate-800/50 rounded-xl p-4 mb-8 flex items-center">
    <i class="fas fa-envelope text-cyan-400 text-xl mr-3"></i>
    <div>
        <p class="text-slate-400 text-sm">Email sent to:</p>
        <p class="text-white font-medium" id="email-display">{{ session('email') }}</p>
    </div>
</div>
                    <!-- Instructions -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-white mb-3">Next Steps:</h3>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <div class="w-6 h-6 rounded-full bg-cyan-500 flex items-center justify-center text-white text-xs mr-3 mt-1 flex-shrink-0">1</div>
                                <p class="text-slate-300">Check your inbox for an email from <span class="text-cyan-400">Luxorix</span></p>
                            </li>
                            <li class="flex items-start">
                                <div class="w-6 h-6 rounded-full bg-purple-500 flex items-center justify-center text-white text-xs mr-3 mt-1 flex-shrink-0">2</div>
                                <p class="text-slate-300">Click the verification link in the email</p>
                            </li>
                            <li class="flex items-start">
                                <div class="w-6 h-6 rounded-full bg-pink-500 flex items-center justify-center text-white text-xs mr-3 mt-1 flex-shrink-0">3</div>
                                <p class="text-slate-300">Complete your account setup</p>
                            </li>
                        </ul>
                        
                        <div class="mt-4 p-4 bg-slate-800/50 rounded-lg">
                            <p class="text-slate-400 text-sm flex items-start">
                                <i class="fas fa-exclamation-circle text-yellow-400 mr-2 mt-1"></i>
                                Can't find the email? Check your spam folder or promotions tab
                            </p>
                        </div>
                    </div>
                    
                    <!-- Resend & Change Email -->
                    <div class="space-y-4">
                        <div>
                        <form id="resend-form"  action="{{ route('resend.verification') }}" method="POST" >
                        @csrf
                         <input type="hidden" name="email" value="{{ session('email') }}">
                            <button id="resend-btn"  type="button" class="glow-button w-full py-3 px-4 text-white rounded-xl font-medium transition flex items-center justify-center">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Resend Verification Email
                                <span id="countdown" class="countdown hidden">60</span>
                            </button>
                            <p id="resend-message" class="text-green-400 text-sm-green mt-2 text-center hidden">
                                Email resent successfully! Please check your inbox.
                            </p>
                            </form>
                        </div>
                        
                        <div class="pt-4 border-t border-slate-700">
                            <a href="#" id="change-email-trigger" class="text-cyan-400 hover:text-cyan-300 transition flex items-center justify-center">
                                <i class="fas fa-edit mr-2"></i>
                                Change email address
                            </a>
                        </div>
                    </div>
                    
                    <!-- Change Email Form (Hidden Initially) -->
                    <div id="change-email-form" class="mt-6 hidden">
                        <div class="holographic-card p-6 rounded-xl">
                            <h3 class="text-xl font-bold text-white mb-4">Update Your Email</h3>
                            <form id="update-email-form" action="{{ route('update.email') }}" method="POST">
                            @csrf
                            <input type="hidden" name="old_email" value="{{ session('email') }}">
    

                                <div class="mb-4">
                                    <label class="block text-slate-300 text-sm mb-2" for="new-email">
                                        New Email Address
                                    </label>
                                    <input 
                                        type="email" 
                                        id="new-email" 
                                        class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                        placeholder="Enter your correct email"
                                        name="new_email"
                                        required
                                    >
                                    <p class="text-slate-400 text-xs mt-1">We'll send a new verification link to this address</p>
                                </div>
                                
                                <div class="flex gap-3">
                                    <button type="button" id="cancel-change" class="flex-1 py-3 px-4 bg-slate-700 text-white rounded-xl font-medium transition hover:bg-slate-600">
                                        Cancel
                                    </button>
                                    <button type="submit" class="flex-1 glow-button py-3 px-4 text-white rounded-xl font-medium transition">
                                        Update Email
                                    </button>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- loader -->
                    <div id="loader" class="mt-4 hidden text-center">
    <i class="fas fa-spinner fa-spin text-cyan-400 text-2xl"></i>
    <p class="text-slate-400 text-sm mt-2">Updating email, please wait...</p>
</div>

                    <!-- Success Message (Hidden Initially) -->
                    <div id="success-message" class="mt-6 p-4 bg-emerald-900/30 border border-emerald-500/30 rounded-xl text-center hidden">
                        <i class="fas fa-check-circle text-emerald-400 text-3xl mb-3"></i>
                        <h3 class="text-xl font-bold text-white mb-2">Email Updated Successfully!</h3>
                        <p class="text-slate-300">We've sent a new verification link to your updated email address.</p>
                    </div>

                    
                </div>
            </div>
        </div>
        
        <!-- Support Footer -->
        <div class="text-center mt-8">
            <p class="text-slate-500">
                Need help? Contact our support team at 
                <a href="mailto:support.luxorix@gmail.com" class="text-cyan-400 hover:text-cyan-300">support.luxorix@gmail.com</a>
            </p>
            <p class="text-slate-500 mt-1">© 2023 Luxorix Technologies. All rights reserved.</p>
        </div>
    </div>


</div>





@endsection




@push("script")




@vite('resources/js/user/auth/check-email.js')



@endpush




