@extends("user.layouts.plain")

<title></title>

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
                    <span class="gradient-text">PRIVACY POLICY</span>
                </h1>
                <p class="text-xl text-slate-300 mb-8 max-w-3xl mx-auto">
                    Your privacy matters to us. Learn how we protect your data and ensure your shopping experience is secure.
                </p>
            </div>
        </div>
    </section>

    <!-- Privacy Policy Content -->
    <section class="py-16 section-container">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Quick Links -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24">
                        <div class="holographic p-6 rounded-2xl border border-slate-700">
                            <h3 class="text-xl font-bold mb-4 gradient-text">QUICK LINKS</h3>
                            <ul class="space-y-3">
                                <li><a href="#data-collection" class="text-slate-300 hover:text-cyan-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Data We Collect</a></li>
                                <li><a href="#data-use" class="text-slate-300 hover:text-cyan-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> How We Use Data</a></li>
                                <li><a href="#data-protection" class="text-slate-300 hover:text-cyan-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Data Protection</a></li>
                                <li><a href="#cookies" class="text-slate-300 hover:text-cyan-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Cookies Policy</a></li>
                                <li><a href="#rights" class="text-slate-300 hover:text-cyan-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Your Rights</a></li>
                                <li><a href="#changes" class="text-slate-300 hover:text-cyan-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Policy Changes</a></li>
                            </ul>
                            
                            <div class="mt-8">
                                <h4 class="text-sm font-semibold text-slate-400 mb-2">LAST UPDATED</h4>
                                <p class="text-white">June 27, 2023</p>
                            </div>
                        </div>
                        
                        <div class="mt-6 holographic p-6 rounded-2xl border border-slate-700">
                            <h3 class="text-xl font-bold mb-4 gradient-text">NEED HELP?</h3>
                            <p class="text-slate-300 mb-4">Have questions about our privacy practices?</p>
                            <a href="contact.php" class="inline-block w-full text-center py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-xl font-medium transition border border-slate-600">
                                CONTACT SUPPORT
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Introduction -->
                    <div class="mb-16">
                        <p class="text-slate-300 mb-6">
                            At Luxorix, we are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or make a purchase from us.
                        </p>
                        <p class="text-slate-300">
                            Please read this privacy policy carefully. By accessing or using our website, you agree to be bound by the terms described herein. If you do not agree with our policies and practices, please do not use our website.
                        </p>
                    </div>
                    
                    <!-- Data Collection Section -->
                    <div id="data-collection" class="mb-16">
                        <h2 class="text-3xl font-bold mb-6 gradient-text">1. DATA WE COLLECT</h2>
                        
                        <div class="grid policy-grid gap-6 mb-8">
                            <div class="policy-card bg-slate-800/50 p-6 rounded-xl border border-slate-700">
                                <div class="w-12 h-12 bg-cyan-900/30 border border-cyan-400/30 rounded-lg flex items-center justify-center mb-4">
                                    <i class="fas fa-user text-cyan-400 text-xl"></i>
                                </div>
                                <h3 class="text-xl font-bold mb-2">Personal Information</h3>
                                <p class="text-slate-400">When you create an account or make a purchase, we collect information such as your name, email address, phone number, shipping address, and payment details.</p>
                            </div>
                            
                            <div class="policy-card bg-slate-800/50 p-6 rounded-xl border border-slate-700">
                                <div class="w-12 h-12 bg-purple-900/30 border border-purple-400/30 rounded-lg flex items-center justify-center mb-4">
                                    <i class="fas fa-laptop text-purple-400 text-xl"></i>
                                </div>
                                <h3 class="text-xl font-bold mb-2">Device Information</h3>
                                <p class="text-slate-400">We automatically collect information about the device you use to access our website, including IP address, browser type, operating system, and device identifiers.</p>
                            </div>
                            
                            <div class="policy-card bg-slate-800/50 p-6 rounded-xl border border-slate-700">
                                <div class="w-12 h-12 bg-pink-900/30 border border-pink-400/30 rounded-lg flex items-center justify-center mb-4">
                                    <i class="fas fa-chart-line text-pink-400 text-xl"></i>
                                </div>
                                <h3 class="text-xl font-bold mb-2">Usage Data</h3>
                                <p class="text-slate-400">We collect information about how you interact with our website, including pages visited, products viewed, time spent, and other browsing behavior.</p>
                            </div>
                            
                            <div class="policy-card bg-slate-800/50 p-6 rounded-xl border border-slate-700">
                                <div class="w-12 h-12 bg-emerald-900/30 border border-emerald-400/30 rounded-lg flex items-center justify-center mb-4">
                                    <i class="fas fa-cookie text-emerald-400 text-xl"></i>
                                </div>
                                <h3 class="text-xl font-bold mb-2">Cookies & Tracking</h3>
                                <p class="text-slate-400">We use cookies and similar tracking technologies to enhance your experience and collect data about your browsing activities.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Data Use Section -->
                    <div id="data-use" class="mb-16">
                        <h2 class="text-3xl font-bold mb-6 gradient-text">2. HOW WE USE YOUR DATA</h2>
                        
                        <div class="mb-8">
                            <p class="text-slate-300 mb-6">We use the information we collect for various purposes, including:</p>
                            
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="w-5 h-5 bg-cyan-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <p class="ml-3 text-slate-300">To process and fulfill your orders, including sending order confirmations and shipping notifications</p>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="w-5 h-5 bg-cyan-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <p class="ml-3 text-slate-300">To provide customer support and respond to your inquiries</p>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="w-5 h-5 bg-cyan-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <p class="ml-3 text-slate-300">To personalize your shopping experience and recommend products you might like</p>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="w-5 h-5 bg-cyan-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <p class="ml-3 text-slate-300">To improve our website, products, and services through analytics and research</p>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="w-5 h-5 bg-cyan-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <p class="ml-3 text-slate-300">To detect and prevent fraud, security breaches, and other prohibited or illegal activities</p>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="w-5 h-5 bg-cyan-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <p class="ml-3 text-slate-300">To send you marketing communications (if you've opted in)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Data Protection Section -->
                    <div id="data-protection" class="mb-16">
                        <h2 class="text-3xl font-bold mb-6 gradient-text">3. DATA PROTECTION & SECURITY</h2>
                        
                        <div class="holographic p-6 rounded-2xl border border-slate-700 mb-8">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-slate-700 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-shield-alt text-cyan-400 text-xl"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-xl font-bold mb-2">Our Security Measures</h3>
                                    <p class="text-slate-300 mb-4">We implement appropriate technical and organizational measures to protect your personal data against unauthorized access, alteration, disclosure, or destruction.</p>
                                    <ul class="list-disc list-inside text-slate-400 space-y-2">
                                        <li>SSL encryption for all data transmissions</li>
                                        <li>Secure payment processing with PCI-compliant partners</li>
                                        <li>Regular security audits and vulnerability testing</li>
                                        <li>Limited access to personal data on a need-to-know basis</li>
                                        <li>Employee training on data protection best practices</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-slate-300">
                            While we strive to protect your personal information, no method of transmission over the Internet or electronic storage is 100% secure. We cannot guarantee absolute security but we promise to notify you of any data breaches as required by applicable law.
                        </p>
                    </div>
                    
                    <!-- Cookies Section -->
                    <div id="cookies" class="mb-16">
                        <h2 class="text-3xl font-bold mb-6 gradient-text">4. COOKIES POLICY</h2>
                        
                        <p class="text-slate-300 mb-6">
                            We use cookies and similar tracking technologies to enhance your browsing experience, analyze site traffic, and personalize content. Cookies are small data files stored on your device that help us remember your preferences and understand how you use our site.
                        </p>
                        
                        <div class="overflow-hidden rounded-xl border border-slate-700 mb-6">
                            <table class="min-w-full divide-y divide-slate-700">
                                <thead class="bg-slate-800">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Type</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Purpose</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Examples</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-slate-800/50 divide-y divide-slate-700">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">Essential</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">Required for site functionality</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">Shopping cart, login sessions</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">Performance</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">Improve site performance</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">Google Analytics</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">Functional</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">Remember preferences</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">Language settings</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">Advertising</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">Personalized ads</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">Facebook Pixel</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <p class="text-slate-300">
                            You can control cookies through your browser settings. However, disabling certain cookies may affect the functionality of our website. Our cookie consent banner allows you to manage your preferences.
                        </p>
                    </div>
                    
                    <!-- Your Rights Section -->
                    <div id="rights" class="mb-16">
                        <h2 class="text-3xl font-bold mb-6 gradient-text">5. YOUR RIGHTS</h2>
                        
                        <p class="text-slate-300 mb-6">
                            Depending on your location, you may have certain rights regarding your personal data. These may include:
                        </p>
                        
                        <div class="space-y-4 mb-8">
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <h3 class="text-xl font-bold">Right to Access</h3>
                                    <div class="accordion-icon text-cyan-400">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div class="accordion-content">
                                    <div class="accordion-content-inner text-slate-400">
                                        <p>You can request a copy of the personal data we hold about you.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <h3 class="text-xl font-bold">Right to Rectification</h3>
                                    <div class="accordion-icon text-cyan-400">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div class="accordion-content">
                                    <div class="accordion-content-inner text-slate-400">
                                        <p>You can request correction of inaccurate or incomplete personal data.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <h3 class="text-xl font-bold">Right to Erasure</h3>
                                    <div class="accordion-icon text-cyan-400">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div class="accordion-content">
                                    <div class="accordion-content-inner text-slate-400">
                                        <p>You can request deletion of your personal data under certain circumstances.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <h3 class="text-xl font-bold">Right to Restriction</h3>
                                    <div class="accordion-icon text-cyan-400">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div class="accordion-content">
                                    <div class="accordion-content-inner text-slate-400">
                                        <p>You can request limitation of processing of your personal data.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <h3 class="text-xl font-bold">Right to Object</h3>
                                    <div class="accordion-icon text-cyan-400">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div class="accordion-content">
                                    <div class="accordion-content-inner text-slate-400">
                                        <p>You can object to certain types of processing, such as direct marketing.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <h3 class="text-xl font-bold">Right to Data Portability</h3>
                                    <div class="accordion-icon text-cyan-400">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div class="accordion-content">
                                    <div class="accordion-content-inner text-slate-400">
                                        <p>You can request transfer of your data to another service provider.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-slate-300">
                            To exercise any of these rights, please visit our <a href="contact.php" class="text-cyan-400 hover:underline">Contact Us</a> page. We may need to verify your identity before processing your request.
                        </p>
                    </div>
                    
                    <!-- Policy Changes Section -->
                    <div id="changes" class="mb-16">
                        <h2 class="text-3xl font-bold mb-6 gradient-text">6. POLICY CHANGES</h2>
                        
                        <p class="text-slate-300 mb-6">
                            We may update this Privacy Policy from time to time to reflect changes in our practices or for other operational, legal, or regulatory reasons. When we make changes, we will revise the "Last Updated" date at the top of this page.
                        </p>
                        
                        <div class="holographic p-6 rounded-2xl border border-slate-700">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-bell text-amber-400 text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-xl font-bold mb-2">Notification of Changes</h3>
                                    <p class="text-slate-300">
                                        For material changes to this policy, we will notify you through email (if we have your contact information) or by posting a prominent notice on our website before the changes become effective.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Link Section -->
                    <div class="holographic p-8 rounded-2xl border border-slate-700 text-center">
                        <h3 class="text-2xl font-bold mb-4 gradient-text">QUESTIONS ABOUT OUR PRIVACY POLICY?</h3>
                        <p class="text-slate-300 mb-6">If you have any questions or concerns about our privacy practices, please don't hesitate to reach out to us.</p>
                        <a href="contact.php" class="inline-block px-8 py-3 bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-xl font-bold transition hover:from-cyan-600 hover:to-blue-700">
                            CONTACT US NOW
                        </a>
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