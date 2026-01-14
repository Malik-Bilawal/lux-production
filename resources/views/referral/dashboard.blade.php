@extends("referral.layouts.plain")
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<title>Refferral | Luxorix</title>

@push("script")
<script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                        'primary-dark': '#4338ca',
                        secondary: '#10b981',
                        'secondary-dark': '#059669',
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
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fa;
            transition: all 0.3s ease;
        }
        
        .sidebar {
            transition: all 0.3s ease;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 50;
                height: 100vh;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 40;
            }
            
            .overlay.active {
                display: block;
            }
        }
        
        .stats-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .dashboard-content {
            transition: all 0.3s ease;
        }
        
        .nav-item {
            transition: all 0.3s ease;
        }
        
        .nav-item:hover {
            background-color: #eef2ff;
        }
        
        .nav-item.active {
            background-color: #eef2ff;
            color: #4f46e5;
            border-right: 3px solid #4f46e5;
        }
        
        .table-row {
            transition: background-color 0.2s ease;
        }
        
        .table-row:hover {
            background-color: #f9fafb;
        }
        
        .modal {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        
        .modal.hidden {
            opacity: 0;
            transform: scale(0.9);
            pointer-events: none;
        }
        
        .modal.active {
            opacity: 1;
            transform: scale(1);
        }
    </style>
@endpush


@section("content")
<div class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar w-64 bg-white shadow-md overflow-y-auto">
            <div class="p-5 border-b border-gray-200">
                <div class="flex items-center justify-center">
                    <h1 class="text-xl font-bold text-primary">Luxorix Referral</h1>
                </div>
            </div>
            <div class="p-4 border-b border-gray-200 flex items-center space-x-4">
    <div class="relative">
    @if(!empty($referral?->profile_picture))
    <img src="{{ $referral->profile_picture ? asset('storage/'.$referral->profile_picture) : 'https://randomuser.me/api/portraits/men/75.jpg' }}" 
             alt="{{ $referral->name }}" 
             class="w-12 h-12 rounded-full border-2 border-primary p-1">
             @endif
        <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></span>
    </div>
    <div>
        <h2 class="font-semibold text-gray-800">{{ $referral->name }}</h2>
        <p class="text-xs text-gray-500">Referral Partner</p>
    </div>
</div>

            
            <nav class="mt-6">
                <div class="nav-item active py-3 px-6 flex items-center text-primary">
                    <i class="fas fa-chart-pie mr-3"></i>
                    <span>Dashboard</span>
                </div>
                <div class="nav-item py-3 px-6 flex items-center text-gray-600">
                    <i class="fas fa-user mr-3"></i>
                    <span>Profile</span>
                </div>
                <div class="nav-item py-3 px-6 flex items-center text-gray-600">
                    <i class="fas fa-wallet mr-3"></i>
                    <span>Earnings</span>
                </div>
                <div class="nav-item py-3 px-6 flex items-center text-gray-600">
                    <i class="fas fa-users mr-3"></i>
                    <span>Referrals</span>
                </div>
                <div class="nav-item py-3 px-6 flex items-center text-gray-600">
                    <i class="fas fa-cog mr-3"></i>
                    <span>Settings</span>
                </div>
                <div class="nav-item py-3 px-6 flex items-center text-gray-600">
                    <i class="fas fa-question-circle mr-3"></i>
                    <span>Help Center</span>
                </div>
            </nav>
            
            <div class="absolute bottom-0 w-full p-4 border-t border-gray-200">
            <form action="{{ route('referral.logout') }}" method="POST">
    @csrf
    <button type="submit" class="flex items-center text-gray-600 w-full py-2 px-4 rounded-lg hover:bg-gray-100">
        <i class="fas fa-sign-out-alt mr-3"></i>
        <span>Logout</span>
    </button>
</form>

            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center">
                        <button id="menu-toggle" class="text-gray-600 focus:outline-none lg:hidden">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <div class="relative mx-4 lg:mx-0">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-search text-gray-400"></i>
                            </span>
                            <input type="text" class="w-32 sm:w-64 pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Search...">
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <button class="relative p-2 text-gray-600 focus:outline-none">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">3</span>
                        </button>
                        
                        <div class="ml-4 relative">
                            <button id="profile-button" class="relative flex items-center focus:outline-none">
                            @if(!empty($referral?->profile_picture))
                            <img  src="{{ asset('storage/'.$referral->profile_picture) }}" alt="User" class="w-8 h-8 rounded-full">
                                @else
                                <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="Default User" class="w-8 h-8 rounded-full">
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Dashboard Content -->
            <main class="flex-1 overflow-y-auto p-4 md:p-6 dashboard-content">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard Overview</h1>
                    <p class="text-gray-600">Welcome back, {{ $referral->name }} Here's your referral performance summary.</p>
                </div>
                
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="stats-card bg-white rounded-xl shadow-sm p-4 border-l-4 border-primary">
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg bg-indigo-100 text-primary">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-gray-600 font-medium">Total Referrals</h2>
                                <p class="text-2xl font-bold text-gray-800">142</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-xs text-gray-500"><span class="text-green-500"><i class="fas fa-arrow-up"></i> 12%</span> from last month</p>
                        </div>
                    </div>
                    
                    <div class="stats-card bg-white rounded-xl shadow-sm p-4 border-l-4 border-secondary">
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg bg-green-100 text-secondary">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-gray-600 font-medium">Completed</h2>
                                <p class="text-2xl font-bold text-gray-800">86</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-xs text-gray-500"><span class="text-green-500"><i class="fas fa-arrow-up"></i> 8%</span> from last month</p>
                        </div>
                    </div>
                    
                    <div class="stats-card bg-white rounded-xl shadow-sm p-4 border-l-4 border-yellow-500">
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg bg-yellow-100 text-yellow-500">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-gray-600 font-medium">Pending</h2>
                                <p class="text-2xl font-bold text-gray-800">24</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-xs text-gray-500"><span class="text-red-500"><i class="fas fa-arrow-down"></i> 3%</span> from last month</p>
                        </div>
                    </div>
                    
                    <div class="stats-card bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500">
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg bg-purple-100 text-purple-500">
                                <i class="fas fa-dollar-sign text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-gray-600 font-medium">Total Earnings</h2>
                                <p class="text-2xl font-bold text-gray-800">$2,458</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-xs text-gray-500"><span class="text-green-500"><i class="fas fa-arrow-up"></i> 18%</span> from last month</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold text-gray-800">Earnings Overview</h2>
            <select class="px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                <option>Last 7 Days</option>
                <option>Last 30 Days</option>
                <option>Last 3 Months</option>
            </select>
        </div>
        <div class="h-80 relative w-full">
            <canvas id="earningsChart"></canvas>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-6">Your Referral Code</h2>
        
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl p-5 text-center text-white mb-6 shadow-lg">
            <p class="text-sm mb-2 opacity-90">Share this code with your friends</p>
            
            <p class="text-3xl font-mono font-bold tracking-widest mb-5" id="refCodeDisplay">
                {{ $referral->referral_code }}
            </p>
            
            <div class="flex justify-center space-x-3">
                <button onclick="copyCode()" class="bg-white text-indigo-600 hover:bg-gray-50 py-2 px-4 rounded-lg flex items-center font-medium transition shadow-sm">
                    <i class="fas fa-copy mr-2"></i> Copy
                </button>
                <button onclick="shareCode()" class="bg-white/20 hover:bg-white/30 text-white py-2 px-4 rounded-lg flex items-center font-medium transition backdrop-blur-sm border border-white/30">
                    <i class="fas fa-share-alt mr-2"></i> Share
                </button>
            </div>
        </div>
        
        <div class="mt-6">
            <h3 class="font-medium text-gray-700 mb-3">How it works</h3>
            <ul class="space-y-4">
                <li class="flex items-start">
                    <div class="bg-indigo-50 text-indigo-600 p-2 rounded-lg mr-3 mt-1">
                        <i class="fas fa-keyboard text-sm"></i>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Your friend enters your code <strong>{{ $referral->referral_code }}</strong> at checkout.
                    </p>
                </li>
                <li class="flex items-start">
                    <div class="bg-indigo-50 text-indigo-600 p-2 rounded-lg mr-3 mt-1">
                        <i class="fas fa-shopping-cart text-sm"></i>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        They get a discount on their first purchase.
                    </p>
                </li>
                <li class="flex items-start">
                    <div class="bg-indigo-50 text-indigo-600 p-2 rounded-lg mr-3 mt-1">
                        <i class="fas fa-wallet text-sm"></i>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        You earn a <strong>10% commission</strong> instantly.
                    </p>
                </li>
            </ul>
        </div>
    </div>
</div>
                
                <!-- Referral Table -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-bold text-gray-800">Recent Referral Activity</h2>
                        <button class="px-4 py-2 bg-primary text-white rounded-lg text-sm flex items-center">
                            <i class="fas fa-download mr-2"></i> Export CSV
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left border-b border-gray-200">
                                    <th class="pb-3 text-gray-600 text-sm">Customer</th>
                                    <th class="pb-3 text-gray-600 text-sm">Date</th>
                                    <th class="pb-3 text-gray-600 text-sm">Value</th>
                                    <th class="pb-3 text-gray-600 text-sm">Commission</th>
                                    <th class="pb-3 text-gray-600 text-sm">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr class="table-row">
                                    <td class="py-4">
                                        <div class="flex items-center">
                                            <img src="https://randomuser.me/api/portraits/women/23.jpg" alt="Customer" class="w-8 h-8 rounded-full mr-3">
                                            <div>
                                                <p class="font-medium text-gray-800">Sarah Johnson</p>
                                                <p class="text-xs text-gray-500">sarah@example.com</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-gray-600 text-sm">Jun 24, 2023</td>
                                    <td class="py-4 text-gray-600 text-sm">$249.99</td>
                                    <td class="py-4 text-gray-600 text-sm">$37.50</td>
                                    <td class="py-4">
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs">Completed</span>
                                    </td>
                                </tr>
                                <tr class="table-row">
                                    <td class="py-4">
                                        <div class="flex items-center">
                                            <img src="https://randomuser.me/api/portraits/men/42.jpg" alt="Customer" class="w-8 h-8 rounded-full mr-3">
                                            <div>
                                                <p class="font-medium text-gray-800">Michael Chen</p>
                                                <p class="text-xs text-gray-500">michael@example.com</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-gray-600 text-sm">Jun 22, 2023</td>
                                    <td class="py-4 text-gray-600 text-sm">$149.99</td>
                                    <td class="py-4 text-gray-600 text-sm">$22.50</td>
                                    <td class="py-4">
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span>
                                    </td>
                                </tr>
                                <tr class="table-row">
                                    <td class="py-4">
                                        <div class="flex items-center">
                                            <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Customer" class="w-8 h-8 rounded-full mr-3">
                                            <div>
                                                <p class="font-medium text-gray-800">Emma Wilson</p>
                                                <p class="text-xs text-gray-500">emma@example.com</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-gray-600 text-sm">Jun 18, 2023</td>
                                    <td class="py-4 text-gray-600 text-sm">$399.99</td>
                                    <td class="py-4 text-gray-600 text-sm">$60.00</td>
                                    <td class="py-4">
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs">Completed</span>
                                    </td>
                                </tr>
                                <tr class="table-row">
                                    <td class="py-4">
                                        <div class="flex items-center">
                                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Customer" class="w-8 h-8 rounded-full mr-3">
                                            <div>
                                                <p class="font-medium text-gray-800">David Miller</p>
                                                <p class="text-xs text-gray-500">david@example.com</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-gray-600 text-sm">Jun 15, 2023</td>
                                    <td class="py-4 text-gray-600 text-sm">$99.99</td>
                                    <td class="py-4 text-gray-600 text-sm">$15.00</td>
                                    <td class="py-4">
                                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs">Cancelled</span>
                                    </td>
                                </tr>
                                <tr class="table-row">
                                    <td class="py-4">
                                        <div class="flex items-center">
                                            <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Customer" class="w-8 h-8 rounded-full mr-3">
                                            <div>
                                                <p class="font-medium text-gray-800">Jessica Brown</p>
                                                <p class="text-xs text-gray-500">jessica@example.com</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-gray-600 text-sm">Jun 12, 2023</td>
                                    <td class="py-4 text-gray-600 text-sm">$199.99</td>
                                    <td class="py-4 text-gray-600 text-sm">$30.00</td>
                                    <td class="py-4">
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs">Completed</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="flex justify-between items-center mt-6">
                        <p class="text-sm text-gray-600">Showing 5 of 86 referrals</p>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 bg-gray-200 text-gray-600 rounded-lg text-sm">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="px-3 py-1 bg-primary text-white rounded-lg text-sm">1</button>
                            <button class="px-3 py-1 bg-gray-200 text-gray-600 rounded-lg text-sm">2</button>
                            <button class="px-3 py-1 bg-gray-200 text-gray-600 rounded-lg text-sm">3</button>
                            <button class="px-3 py-1 bg-gray-200 text-gray-600 rounded-lg text-sm">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        
        <!-- Mobile Overlay -->
        <div class="overlay"></div>
    </div>
    
    <!-- Edit Profile Modal -->
    <div class="modal hidden fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-screen overflow-y-auto">
            
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-800">Edit Profile</h3>
                    <button class="text-gray-500 close-modal">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <form action="{{ route('referral.updateProfile', $referral->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
            
            <div class="p-6">
            <div class="mb-6 text-center">
    <div class="relative inline-block">
        <!-- Profile Image -->
        @if(!empty($referral?->profile_picture))
        <img id="profilePreview" 
             src="{{ $referral->profile_picture ? asset('storage/'.$referral->profile_picture) : 'https://randomuser.me/api/portraits/men/75.jpg' }}" 
             alt="User" 
             class="w-24 h-24 rounded-full border-4 border-primary cursor-pointer">
@endif
        <!-- Hidden File Input -->
        <input type="file" id="profileImageInput" name="profile_picture" class="hidden" accept="image/*">

        <!-- Camera Button -->
        <button type="button" id="cameraBtn" class="absolute bottom-0 right-0 bg-primary text-white p-2 rounded-full">
            <i class="fas fa-camera text-sm"></i>
        </button>
    </div>
    <p class="mt-2 text-sm text-gray-600">Click on camera or image to change photo</p>
</div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="name">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ $referral->name }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" value="John Doe">
                        </div>
                        
                   
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ $referral->phone }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" value="+1 (555) 123-4567">
                        </div>
                        
                        <div>
    <label for="payment-method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
    <select id="payment-method" name="payment_method" 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
        <option value="Bank Transfer" {{ $referral->payment_method == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
        <option value="PayPal" {{ $referral->payment_method == 'PayPal' ? 'selected' : '' }}>PayPal</option>
        <option value="Easypaisa" {{ $referral->payment_method == 'Easypaisa' ? 'selected' : '' }}>Easypaisa</option>
        <option value="JazzCash" {{ $referral->payment_method == 'JazzCash' ? 'selected' : '' }}>JazzCash</option>
    </select>
</div>

                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="payment-number">Payment Number</label>
                            <input type="text" id="payment-number" name="account_number" value="{{ $referral->account_number }}"  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" value="0333-1234567">
                        </div>
                    </div>
                    
                    <div class="mt-8 flex space-x-3">
                        <button type="button" class="flex-1 px-4 py-3 bg-gray-200 text-gray-800 rounded-lg font-medium close-modal">Cancel</button>
                        <button type="submitt" class="flex-1 px-4 py-3 bg-primary text-white rounded-lg font-medium">Save Changes</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
    </div>
@endsection


@push("script")
  
<script>


        document.addEventListener('DOMContentLoaded', function() {

            const profilePreview = document.getElementById('profilePreview');
    const cameraBtn = document.getElementById('cameraBtn');
    const profileInput = document.getElementById('profileImageInput');

    // Click on image or camera button triggers file input
    profilePreview.addEventListener('click', () => profileInput.click());
    cameraBtn.addEventListener('click', () => profileInput.click());

    // Live preview after selecting image
    profileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profilePreview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
            // Toggle mobile menu
            const menuToggle = document.getElementById('menu-toggle');
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.overlay');
            
            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });
            
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
            
            // Close modal
            const modal = document.querySelector('.modal');
            const closeModalButtons = document.querySelectorAll('.close-modal');
            const profileButton = document.getElementById('profile-button');

            profileButton.addEventListener('click', function() {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.add('active');
                }, 10);
            });
            
            closeModalButtons.forEach(button => {
                button.addEventListener('click', function() {
                    modal.classList.remove('active');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                    }, 300);
                });
            });
            
            // Open modal (for demo purposes, would be triggered by edit button)
            setTimeout(() => {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.add('active');
                }, 10);
            }, 1000);
            
            function copyCode() {
        // Get the code text (trim removes whitespace)
        const code = document.getElementById('refCodeDisplay').innerText.trim();
        navigator.clipboard.writeText(code);
        
        // Simple feedback (You can use a toast notification here if you have one)
        alert('Referral code ' + code + ' copied to clipboard!');
    }

    // --- 2. SHARE FUNCTION (Mobile Native Share) ---
    function shareCode() {
        const code = document.getElementById('refCodeDisplay').innerText.trim();
        
        if (navigator.share) {
            navigator.share({
                title: 'Join Luxorix!',
                text: 'Use my code ' + code + ' to get a discount on Luxorix!',
                url: '{{ url("/") }}?ref=' + code
            }).catch(console.error);
        } else {
            // Fallback for desktop browsers
            copyCode();
        }
    }

    // --- 3. CHART CONFIGURATION ---
    const ctx = document.getElementById('earningsChart').getContext('2d');
    
    // Create a gradient for the chart line
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(79, 70, 229, 0.2)'); // Indigo color
    gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            // Mock Data (You will pass real PHP data here later)
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Earnings ($)',
                data: [12, 19, 3, 5, 2, 3, 15], 
                borderColor: '#4F46E5', // Tailwind Indigo-600
                backgroundColor: gradient,
                borderWidth: 2,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#4F46E5',
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4 // Makes the line curved (smooth)
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Important for CSS sizing
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1F2937',
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [2, 4], color: '#E5E7EB' },
                    ticks: { callback: function(value) { return '$' + value; } }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
            
            // Update stats cards with animation
            const statsCards = document.querySelectorAll('.stats-card');
            
            statsCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });
    </script>
@endpush