@extends("referral.layouts.plain")

<title>Luxorix | Referral Program</title>

@push("script")
<script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                        secondary: '#6366f1',
                        accent: '#10b981',
                        dark: '#1f2937',
                        light: '#f9fafb'
                    }
                }
            }
        }
    </script>
@endpush


@push("style")
<style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        .body {
            font-family: 'Inter', sans-serif;
        }
        
        .status-card {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .status-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
    </style>
@endpush


@section("content")
<div class="bg-gray-50 body min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4 flex items-center justify-center md:justify-start">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white font-bold">L</div>
                <h1 class="text-xl font-semibold text-gray-800 ml-3">Luxorix Referral Program</h1>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Hero Section -->
            <div class="text-center mb-8">
                <div class="w-24 h-24 mx-auto bg-blue-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-hourglass-half text-4xl text-primary pulse"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-3">Your Application is Under Review</h1>
                <p class="text-gray-600 text-lg">Thank you for applying to our referral program. Our team is currently reviewing your request.</p>
            </div>

            <!-- Status Card -->
            <div class="status-card bg-white rounded-xl p-6 mb-8 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Application Status</h2>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">Pending Review</span>
    </div>
                
                <div class="space-y-4">
                <div class="flex items-center">
    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 mr-4">
        <i class="fas fa-calendar-alt"></i>
    </div>
    <div>
        <p class="text-sm text-gray-500">Submitted On</p>
        <p class="font-medium">{{ $referral->created_at->format('F d, Y') }}</p>
    </div>
</div>

<div class="flex items-center">
    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-500 mr-4">
        <i class="fas fa-envelope"></i>
    </div>
    <div>
        <p class="text-sm text-gray-500">Notification Email</p>
        <p class="font-medium">{{ $referral->email }}</p>
    </div>
</div>



                    
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-600"><i class="fas fa-info-circle text-primary mr-2"></i>You will receive an email notification once your request is approved or declined.</p>
                    </div>
                </div>
            </div>

            <!-- Information Section -->
            <div class="bg-blue-50 rounded-xl p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">What happens next?</h2>
                
                <div class="space-y-4">
                    <div class="flex">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-8 h-8 rounded-full bg-white border border-primary flex items-center justify-center text-primary">
                                <span class="font-bold">1</span>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Review Process</h3>
                            <p class="text-gray-600 text-sm">Our team reviews your details within 24-48 hours.</p>
                        </div>
                    </div>
                    
                    <div class="flex">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-8 h-8 rounded-full bg-white border border-primary flex items-center justify-center text-primary">
                                <span class="font-bold">2</span>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Notification</h3>
                            <p class="text-gray-600 text-sm">You'll receive an email with your referral program status.</p>
                        </div>
                    </div>
                    
                    <div class="flex">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-8 h-8 rounded-full bg-white border border-primary flex items-center justify-center text-primary">
                                <span class="font-bold">3</span>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Getting Started</h3>
                            <p class="text-gray-600 text-sm">Once approved, you can start sharing your referral code  and earning rewards.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support CTA -->
            <div class="text-center">
                <p class="text-gray-600 mb-4">Need assistance with your application?</p>
                <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route("user.contact") }}" class="bg-primary hover:bg-secondary text-white font-medium py-3 px-6 rounded-lg transition duration-200">
                        <i class="fas fa-envelope mr-2"></i>Contact Support
                    </a>
                    <a href="{{ route("user.welcome") }}" class="border border-gray-300 hover:border-primary text-gray-700 hover:text-primary font-medium py-3 px-6 rounded-lg transition duration-200">
                        <i class="fas fa-home mr-2"></i>Return Home
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12 py-6">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-500 text-sm mb-4 md:mb-0">© 2025 Luxorix. All Rights Reserved.</p>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-500 hover:text-primary text-sm">Terms</a>
                    <a href="#" class="text-gray-500 hover:text-primary text-sm">Privacy Policy</a>
                    <a href="#" class="text-gray-500 hover:text-primary text-sm">Help Center</a>
                </div>
            </div>
        </div>
    </footer>
    </div>
@endsection


@push("script")
<script>
        // Simple animation for elements
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.status-card, .pulse');
            
            elements.forEach(element => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(10px)';
                
                setTimeout(() => {
                    element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, 200);
            });
        });
    </script>
@endpush