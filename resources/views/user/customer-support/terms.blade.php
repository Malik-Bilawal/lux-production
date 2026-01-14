@extends("user.layouts.plain")

<title>Luxorix | Terms of Service</title>

@push("script")
@endpush


@push("style")
<style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
            overflow-x: hidden;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        }
        
        .glow {
            text-shadow: 0 0 10px rgba(79, 70, 229, 0.7), 0 0 20px rgba(79, 70, 229, 0.5);
        }
        
        .neon-border {
            border: 2px solid rgba(0, 242, 254, 0.3);
            box-shadow: inset 0 0 10px rgba(0, 242, 254, 0.2), 0 0 20px rgba(0, 242, 254, 0.1);
        }
        
        .neon-text {
            text-shadow: 0 0 5px #00f2fe, 0 0 10px #00f2fe;
        }
        
        .gradient-text {
            background: linear-gradient(90deg, #00f2fe 0%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .holographic {
            background: linear-gradient(45deg, 
                rgba(16, 185, 129, 0.1) 0%, 
                rgba(79, 70, 229, 0.1) 25%, 
                rgba(236, 72, 153, 0.1) 50%, 
                rgba(245, 158, 11, 0.1) 75%, 
                rgba(0, 242, 254, 0.1) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .shine-effect {
            position: relative;
            overflow: hidden;
        }
        
        .shine-effect::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -60%;
            width: 20%;
            height: 200%;
            background: rgba(255, 255, 255, 0.13);
            background: linear-gradient(
                to right, 
                rgba(255, 255, 255, 0.13) 0%,
                rgba(255, 255, 255, 0.13) 77%,
                rgba(255, 255, 255, 0.5) 92%,
                rgba(255, 255, 255, 0.0) 100%
            );
            transform: rotate(30deg);
            transition: all 0.7s ease;
        }
        
        .shine-effect:hover::after {
            left: 120%;
            transition: all 0.7s ease;
        }
        
        .grid-bg {
            background-image: 
                linear-gradient(rgba(255,255,255,0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 30px 30px;
        }
        
        .cyber-button {
            background: linear-gradient(90deg, #4f46e5, #7c3aed);
            border: 1px solid rgba(0, 242, 254, 0.5);
            box-shadow: 0 0 15px rgba(79, 70, 229, 0.5);
            transition: all 0.3s ease;
        }
        
        .cyber-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.7);
            background: linear-gradient(90deg, #7c3aed, #4f46e5);
        }
        
        .policy-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border-left: 3px solid transparent;
        }
        
        .policy-card:hover {
            transform: translateY(-5px);
            border-left: 3px solid #00f2fe;
            box-shadow: 0 10px 25px -5px rgba(0, 242, 254, 0.15);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #0f172a;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #4f46e5;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #6366f1;
        }
        
        /* Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.6; }
        }
        
        @keyframes ping {
            0% { transform: scale(1); opacity: 1; }
            75%, 100% { transform: scale(2); opacity: 0; }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        .animate-ping {
            animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .policy-grid {
                grid-template-columns: 1fr;
            }
            
            .section-container {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }
        
        /* Accordion styles */
        .accordion-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .accordion-header {
            padding: 1.5rem 0;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        
        .accordion-content-inner {
            padding-bottom: 1.5rem;
        }
        
        .accordion-item.active .accordion-content {
            max-height: 1000px;
            transition: max-height 0.5s ease-in;
        }
        
        .accordion-icon {
            transition: transform 0.3s ease;
        }
        
        .accordion-item.active .accordion-icon {
            transform: rotate(180deg);
        }
        
        /* Terms-specific styles */
        .terms-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-right: 0.5rem;
        }
        
        .highlight-box {
            background: rgba(79, 70, 229, 0.15);
            border-left: 3px solid #4f46e5;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin: 1.5rem 0;
        }
        
        .steps-container {
            counter-reset: step-counter;
        }
        
        .step-item {
            position: relative;
            padding-left: 3rem;
            margin-bottom: 2rem;
        }
        
        .step-item:before {
            counter-increment: step-counter;
            content: counter(step-counter);
            position: absolute;
            left: 0;
            top: 0;
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            background: #4f46e5;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
    </style>

@endpush


@section("content")
<div class="grid-bg">
    <!-- Hero Section -->
    <section class="hero-gradient pt-32 pb-20 relative overflow-hidden">
        <!-- Animated background elements -->
        <div class="absolute inset-0 z-0">
            <div class="absolute top-20 right-0 w-96 h-96 bg-purple-600 rounded-full mix-blend-soft-light filter blur-3xl opacity-30 animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-cyan-500 rounded-full mix-blend-soft-light filter blur-3xl opacity-30 animate-ping" style="animation-duration: 3s;"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    <span class="gradient-text">TERMS OF SERVICE</span>
                </h1>
                <p class="text-xl text-slate-300 mb-8 max-w-3xl mx-auto">
                    Understand the rules and guidelines that govern your use of Luxorix services and products.
                </p>
                <div class="inline-flex items-center bg-slate-800/50 border border-slate-700 rounded-full px-6 py-2">
                    <span class="text-slate-400 mr-2">Last updated:</span>
                    <span class="text-white font-medium">June 27, 2023</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Terms of Service Content -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Quick Links -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24">
                        <div class="holographic p-6 rounded-2xl border border-slate-700">
                            <h3 class="text-xl font-bold mb-4 gradient-text">QUICK NAVIGATION</h3>
                            <ul class="space-y-3">
                                <li><a href="#introduction" class="text-slate-300 hover:text-cyan-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Introduction</a></li>
                                <li><a href="#account" class="text-slate-300 hover:text-cyan-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Account Registration</a></li>
                                <li><a href="#orders" class="text-slate-300 hover:text-cyan-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Orders & Payments</a></li>
                                <li><a href="#shipping" class="text-slate-300 hover:text-cyan-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Shipping & Delivery</a></li>
                                <li><a href="#returns" class="text-slate-300 hover:text-cyan-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Returns & Refunds</a></li>
                                <li><a href="#intellectual" class="text-slate-300 hover:text-cyan-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Intellectual Property</a></li>
                                <li><a href="#conduct" class="text-slate-300 hover:text-cyan-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> User Conduct</a></li>
                                <li><a href="#liability" class="text-slate-300 hover:text-cyan-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Limitation of Liability</a></li>
                                <li><a href="#changes" class="text-slate-300 hover:text-cyan-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Changes to Terms</a></li>
                            </ul>
                        </div>
                        
                        <div class="mt-6 holographic p-6 rounded-2xl border border-slate-700">
                            <h3 class="text-xl font-bold mb-4 gradient-text">ACCEPTANCE REQUIRED</h3>
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 mr-3">
                                    <i class="fas fa-exclamation-triangle text-amber-400 text-xl"></i>
                                </div>
                                <p class="text-slate-300">By using our services, you agree to these terms.</p>
                            </div>
                            <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-4">
                                <p class="text-sm text-slate-400">Your continued access or use of the Service after the effective date of the revised Terms constitutes your acceptance of them.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Introduction -->
                    <div id="introduction" class="mb-16">
                        <h2 class="text-3xl font-bold mb-6 gradient-text">1. INTRODUCTION</h2>
                        
                        <div class="holographic p-6 rounded-2xl border border-slate-700 mb-8">
                            <p class="text-slate-300 mb-4">
                                Welcome to <span class="text-cyan-400 font-bold">Luxorix</span>! These Terms of Service ("Terms") govern your access to and use of our website, products, and services ("Services"). By accessing or using our Services, you agree to be bound by these Terms and our Privacy Policy.
                            </p>
                            <p class="text-slate-300">
                                If you do not agree to all of these Terms, do not access or use our Services. We may modify these Terms at any time, and such modifications will be effective when posted.
                            </p>
                        </div>
                        
                        <h3 class="text-xl font-bold mb-4 text-slate-200">Key Definitions</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                            <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-4">
                                <div class="flex items-center mb-2">
                                    <div class="w-8 h-8 bg-cyan-900/30 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-cyan-400"></i>
                                    </div>
                                    <h4 class="font-bold text-slate-200">User</h4>
                                </div>
                                <p class="text-slate-400 text-sm">Anyone who accesses or uses our Services.</p>
                            </div>
                            <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-4">
                                <div class="flex items-center mb-2">
                                    <div class="w-8 h-8 bg-purple-900/30 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-shopping-cart text-purple-400"></i>
                                    </div>
                                    <h4 class="font-bold text-slate-200">Services</h4>
                                </div>
                                <p class="text-slate-400 text-sm">Our website, products, and all related services.</p>
                            </div>
                            <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-4">
                                <div class="flex items-center mb-2">
                                    <div class="w-8 h-8 bg-emerald-900/30 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-box text-emerald-400"></i>
                                    </div>
                                    <h4 class="font-bold text-slate-200">Products</h4>
                                </div>
                                <p class="text-slate-400 text-sm">Physical or digital goods available for purchase.</p>
                            </div>
                            <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-4">
                                <div class="flex items-center mb-2">
                                    <div class="w-8 h-8 bg-pink-900/30 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-file-contract text-pink-400"></i>
                                    </div>
                                    <h4 class="font-bold text-slate-200">Agreement</h4>
                                </div>
                                <p class="text-slate-400 text-sm">These Terms and any related policies.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Account Registration -->
                    <div id="account" class="mb-16">
                        <h2 class="text-3xl font-bold mb-6 gradient-text">2. ACCOUNT REGISTRATION</h2>
                        
                        <div class="steps-container mb-8">
                            <div class="step-item">
                                <h3 class="text-xl font-bold mb-2 text-slate-200">Eligibility</h3>
                                <p class="text-slate-300">To create an account, you must be at least 12 years old and provide accurate and complete information.</p>
                            </div>
                            
                            <div class="step-item">
                                <h3 class="text-xl font-bold mb-2 text-slate-200">Account Security</h3>
                                <p class="text-slate-300">You are responsible for maintaining the confidentiality of your account credentials and for all activities under your account.</p>
                            </div>
                            
                            <div class="step-item">
                                <h3 class="text-xl font-bold mb-2 text-slate-200">Account Termination</h3>
                                <p class="text-slate-300">We reserve the right to suspend or terminate accounts that violate these Terms or engage in prohibited activities.</p>
                            </div>
                        </div>
                        
                        <div class="highlight-box">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1 mr-3">
                                    <i class="fas fa-exclamation-circle text-amber-400 text-xl"></i>
                                </div>
                                <p class="text-slate-300">
                                    <span class="font-bold">Important:</span> You may not create multiple accounts, use false information, or create accounts for unauthorized purposes. We may require verification for account creation.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Orders & Payments -->
                    <div id="orders" class="mb-16">
                        <h2 class="text-3xl font-bold mb-6 gradient-text">3. ORDERS & PAYMENTS</h2>
                        
                        <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 mb-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="text-xl font-bold mb-3 text-slate-200">Order Placement</h3>
                                    <ul class="list-disc list-inside text-slate-400 space-y-2">
                                        <li>All orders are subject to product availability</li>
                                        <li>We reserve the right to refuse or cancel any order</li>
                                        <li>Order confirmation does not signify acceptance</li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold mb-3 text-slate-200">Payment Methods</h3>
                                    <ul class="list-disc list-inside text-slate-400 space-y-2">
                                        <li>Credit/Debit Cards (Visa, Mastercard, Amex)</li>
                                        <li>PayPal</li>
                                        <li>Cryptocurrency (BTC, ETH)</li>
                                        <li>Other payment methods as listed at checkout</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6 border border-slate-700 rounded-2xl">
                            <h3 class="text-xl font-bold mb-4 text-slate-200">Pricing Information</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-700">
                                    <thead class="bg-slate-800">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Fee Type</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Amount</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-700">
                                        <tr>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-white">Sales Tax</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-400">Based on location</td>
                                            <td class="px-4 py-4 text-sm text-slate-400">Calculated at checkout</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-white">Shipping Fees</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-400">Varies by method</td>
                                            <td class="px-4 py-4 text-sm text-slate-400">See shipping section</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-white">Currency Conversion</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-400">0.5-3%</td>
                                            <td class="px-4 py-4 text-sm text-slate-400">Set by payment processor</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shipping & Delivery -->
                    <div id="shipping" class="mb-16">
                        <h2 class="text-3xl font-bold mb-6 gradient-text">4. SHIPPING & DELIVERY</h2>
                        
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 mr-4">
                                    <i class="fas fa-shipping-fast text-cyan-400 text-3xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-200">Shipping Methods & Timeframes</h3>
                                    <p class="text-slate-400">We offer several shipping options with varying delivery speeds:</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                                <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-5 text-center">
                                    <div class="w-14 h-14 bg-cyan-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-bolt text-cyan-400 text-xl"></i>
                                    </div>
                                    <h4 class="font-bold text-lg mb-2 text-slate-200">Express Shipping</h4>
                                    <p class="text-cyan-400 font-bold mb-2">1-3 Business Days</p>
                                    <p class="text-slate-400 text-sm">Guaranteed delivery</p>
                                </div>
                                <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-5 text-center">
                                    <div class="w-14 h-14 bg-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-truck text-purple-400 text-xl"></i>
                                    </div>
                                    <h4 class="font-bold text-lg mb-2 text-slate-200">Standard Shipping</h4>
                                    <p class="text-purple-400 font-bold mb-2">5-7 Business Days</p>
                                    <p class="text-slate-400 text-sm">Most popular option</p>
                                </div>
                                <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-5 text-center">
                                    <div class="w-14 h-14 bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-globe-americas text-emerald-400 text-xl"></i>
                                    </div>
                                    <h4 class="font-bold text-lg mb-2 text-slate-200">International</h4>
                                    <p class="text-emerald-400 font-bold mb-2">7-14 Business Days</p>
                                    <p class="text-slate-400 text-sm">Customs fees may apply</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="holographic p-6 rounded-2xl border border-slate-700">
                            <h3 class="text-xl font-bold mb-4 text-slate-200">Delivery Expectations</h3>
                            <ul class="list-disc list-inside text-slate-400 space-y-2">
                                <li>Delivery times are estimates, not guarantees</li>
                                <li>We are not responsible for delays beyond our control</li>
                                <li>Signature may be required for delivery</li>
                                <li>You are responsible for providing accurate shipping information</li>
                                <li>Risk of loss passes to you upon delivery</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Returns & Refunds -->
                    <div id="returns" class="mb-16">
                        <h2 class="text-3xl font-bold mb-6 gradient-text">5. RETURNS & REFUNDS</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6">
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 bg-pink-900/30 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-undo text-pink-400 text-xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-200">Return Policy</h3>
                                </div>
                                <p class="text-slate-400 mb-4">Most unopened items in new condition can be returned within <span class="text-cyan-400 font-bold">30 days</span> of delivery for a full refund.</p>
                                <ul class="list-disc list-inside text-slate-400 space-y-2">
                                    <li>Items must be in original packaging</li>
                                    <li>Proof of purchase required</li>
                                    <li>Some items are non-returnable</li>
                                </ul>
                            </div>
                            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6">
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 bg-amber-900/30 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-money-bill-wave text-amber-400 text-xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-200">Refund Process</h3>
                                </div>
                                <p class="text-slate-400 mb-4">Refunds are processed within <span class="text-cyan-400 font-bold">7 business days</span> after we receive your return.</p>
                                <ul class="list-disc list-inside text-slate-400 space-y-2">
                                    <li>Original payment method will be credited</li>
                                    <li>Shipping costs are non-refundable</li>
                                    <li>Restocking fees may apply to certain items</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="p-6 border border-slate-700 rounded-2xl">
                            <h3 class="text-xl font-bold mb-4 text-slate-200">How to Initiate a Return</h3>
                            <div class="steps-container">
                                <div class="step-item">
                                    <p class="text-slate-300">Contact our support team within 30 days of delivery</p>
                                </div>
                                <div class="step-item">
                                    <p class="text-slate-300">Provide your order number and reason for return</p>
                                </div>
                                <div class="step-item">
                                    <p class="text-slate-300">We'll email you a return authorization and shipping label</p>
                                </div>
                                <div class="step-item">
                                    <p class="text-slate-300">Package your item securely and ship it back to us</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Intellectual Property -->
                    <div id="intellectual" class="mb-16">
                        <h2 class="text-3xl font-bold mb-6 gradient-text">6. INTELLECTUAL PROPERTY</h2>
                        
                        <div class="holographic p-6 rounded-2xl border border-slate-700 mb-8">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-12 h-12 bg-slate-700 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-copyright text-cyan-400 text-xl"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-xl font-bold mb-2 text-slate-200">Ownership Rights</h3>
                                    <p class="text-slate-300 mb-4">All content on our Services, including text, graphics, logos, images, and software, is the property of Luxorix or its licensors and is protected by intellectual property laws.</p>
                                    <p class="text-slate-300">
                                        You may not reproduce, distribute, modify, create derivative works of, publicly display, or in any way exploit any of the content without our express written permission.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6">
                            <h3 class="text-xl font-bold mb-4 text-slate-200">User-Generated Content</h3>
                            <p class="text-slate-400 mb-4">By submitting content (reviews, comments, etc.), you grant Luxorix a worldwide, non-exclusive, royalty-free license to use, reproduce, and display such content.</p>
                            <div class="flex items-center">
                                <div class="flex-shrink-0 mr-3">
                                    <i class="fas fa-lightbulb text-amber-400 text-xl"></i>
                                </div>
                                <p class="text-slate-400">You represent that you own or have permission to use any content you submit.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Conduct -->
                    <div id="conduct" class="mb-16">
                        <h2 class="text-3xl font-bold mb-6 gradient-text">7. USER CONDUCT</h2>
                        
                        <div class="mb-8">
                            <p class="text-slate-300 mb-6">When using our Services, you agree not to:</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-start bg-slate-800/50 border border-slate-700 rounded-xl p-4">
                                    <div class="flex-shrink-0 mt-1 mr-3 text-red-400">
                                        <i class="fas fa-ban"></i>
                                    </div>
                                    <p class="text-slate-300">Violate any laws or regulations</p>
                                </div>
                                <div class="flex items-start bg-slate-800/50 border border-slate-700 rounded-xl p-4">
                                    <div class="flex-shrink-0 mt-1 mr-3 text-red-400">
                                        <i class="fas fa-ban"></i>
                                    </div>
                                    <p class="text-slate-300">Infringe on intellectual property rights</p>
                                </div>
                                <div class="flex items-start bg-slate-800/50 border border-slate-700 rounded-xl p-4">
                                    <div class="flex-shrink-0 mt-1 mr-3 text-red-400">
                                        <i class="fas fa-ban"></i>
                                    </div>
                                    <p class="text-slate-300">Harass, abuse, or harm others</p>
                                </div>
                                <div class="flex items-start bg-slate-800/50 border border-slate-700 rounded-xl p-4">
                                    <div class="flex-shrink-0 mt-1 mr-3 text-red-400">
                                        <i class="fas fa-ban"></i>
                                    </div>
                                    <p class="text-slate-300">Transmit viruses or malicious code</p>
                                </div>
                                <div class="flex items-start bg-slate-800/50 border border-slate-700 rounded-xl p-4">
                                    <div class="flex-shrink-0 mt-1 mr-3 text-red-400">
                                        <i class="fas fa-ban"></i>
                                    </div>
                                    <p class="text-slate-300">Attempt unauthorized access to our systems</p>
                                </div>
                                <div class="flex items-start bg-slate-800/50 border border-slate-700 rounded-xl p-4">
                                    <div class="flex-shrink-0 mt-1 mr-3 text-red-400">
                                        <i class="fas fa-ban"></i>
                                    </div>
                                    <p class="text-slate-300">Engage in fraudulent activities</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="holographic p-6 rounded-2xl border border-slate-700">
                            <h3 class="text-xl font-bold mb-4 text-slate-200">Consequences of Violations</h3>
                            <p class="text-slate-400 mb-4">Violation of these conduct rules may result in:</p>
                            <ul class="list-disc list-inside text-slate-400 space-y-2">
                                <li>Immediate termination of your account</li>
                                <li>Legal action and prosecution</li>
                                <li>Cancellation of pending orders</li>
                                <li>Forfeiture of any funds or credits</li>
                                <li>Permanent ban from our services</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Limitation of Liability -->
                    <div id="liability" class="mb-16">
                        <h2 class="text-3xl font-bold mb-6 gradient-text">8. LIMITATION OF LIABILITY</h2>
                        
                        <div class="highlight-box">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1 mr-3">
                                    <i class="fas fa-gavel text-amber-400 text-xl"></i>
                                </div>
                                <p class="text-slate-300">
                                    <span class="font-bold">Important Legal Notice:</span> To the maximum extent permitted by law, Luxorix shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your access to or use of our Services.
                                </p>
                            </div>
                        </div>
                        
                        <div class="mb-8">
                            <h3 class="text-xl font-bold mb-4 text-slate-200">Scope of Liability</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-5">
                                    <div class="flex items-center mb-3">
                                        <div class="w-10 h-10 bg-red-900/30 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-times text-red-400"></i>
                                        </div>
                                        <h4 class="font-bold text-slate-200">What We Don't Cover</h4>
                                    </div>
                                    <ul class="list-disc list-inside text-slate-400 space-y-2">
                                        <li>Loss of profits or revenue</li>
                                        <li>Data loss or corruption</li>
                                        <li>Indirect or consequential damages</li>
                                        <li>Incidental damages</li>
                                    </ul>
                                </div>
                                <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-5">
                                    <div class="flex items-center mb-3">
                                        <div class="w-10 h-10 bg-green-900/30 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-check text-green-400"></i>
                                        </div>
                                        <h4 class="font-bold text-slate-200">What We Cover</h4>
                                    </div>
                                    <ul class="list-disc list-inside text-slate-400 space-y-2">
                                        <li>Direct physical product defects</li>
                                        <li>Failure to deliver paid products</li>
                                        <li>Violation of consumer protection laws</li>
                                        <li>Gross negligence or willful misconduct</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-slate-300">
                            Our total liability for any claims related to the Services will not exceed the amount you paid us for the products or services in the 12 months preceding the claim.
                        </p>
                    </div>
                    
                    <!-- Changes to Terms -->
                    <div id="changes" class="mb-16">
                        <h2 class="text-3xl font-bold mb-6 gradient-text">9. CHANGES TO TERMS</h2>
                        
                        <div class="holographic p-6 rounded-2xl border border-slate-700 mb-8">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-sync-alt text-cyan-400 text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-xl font-bold mb-2 text-slate-200">Modification Rights</h3>
                                    <p class="text-slate-300">
                                        We reserve the right to modify these Terms at any time. When we make changes, we will update the "Last Updated" date at the top of this page.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6">
                            <h3 class="text-xl font-bold mb-4 text-slate-200">Your Acceptance</h3>
                            <p class="text-slate-400 mb-4">
                                Your continued use of our Services after any changes to these Terms constitutes your acceptance of the new Terms. If you do not agree to the changes, you must discontinue using our Services.
                            </p>
                            <div class="flex items-center">
                                <div class="flex-shrink-0 mr-3">
                                    <i class="fas fa-bell text-amber-400 text-xl"></i>
                                </div>
                                <p class="text-slate-400">We will notify you of significant changes through email or prominent website notice.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Acceptance Section -->
                    <div class="holographic p-8 rounded-2xl border border-slate-700 text-center mb-16">
                        <h3 class="text-2xl font-bold mb-4 gradient-text">ACCEPTANCE OF TERMS</h3>
                        <p class="text-slate-300 mb-6">By using Luxorix services, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service.</p>
                        <div class="inline-flex items-center bg-gradient-to-r from-cyan-500 to-blue-600 px-6 py-3 rounded-xl">
                            <i class="fas fa-signature text-white text-xl mr-3"></i>
                            <span class="text-white font-bold">I AGREE TO THESE TERMS</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
@endsection



@push("script")
<script>
        // Accordion functionality
        document.addEventListener('DOMContentLoaded', function() {
            const accordionItems = document.querySelectorAll('.accordion-item');
            
            accordionItems.forEach(item => {
                const header = item.querySelector('.accordion-header');
                
                header.addEventListener('click', () => {
                    // Close all other items
                    accordionItems.forEach(otherItem => {
                        if (otherItem !== item) {
                            otherItem.classList.remove('active');
                        }
                    });
                    
                    // Toggle current item
                    item.classList.toggle('active');
                });
            });
            
            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>
@endpush