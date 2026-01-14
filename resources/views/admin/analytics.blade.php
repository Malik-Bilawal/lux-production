@extends("admin.layouts.master-layouts.plain")

<title>Analytics Report | Luxorix</title>

@push("script")
<script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#10B981',
                        dark: '#1F2937',
                        light: '#F9FAFB'
                    }
                }
            }
        }
    </script>
@endpush


@push("style")
<style>
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .progress-bar {
            transition: width 0.5s ease-in-out;
        }
        .real-time-pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        .stats-change {
            transition: all 0.3s ease;
        }
        .stats-change:hover {
            transform: scale(1.05);
        }
        .analytics-chart {
            transition: opacity 0.5s ease;
        }
        .tab-button {
            transition: all 0.2s ease;
        }
        .tab-button.active {
            background-color: #3B82F6;
            color: white;
        }
        .map-point {
            transition: all 0.3s ease;
        }
        .map-point:hover {
            transform: scale(1.2);
            z-index: 10;
        }
    </style>
@endpush


@section("content")

<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
      <aside class="w-64 bg-white shadow h-screen fixed top-0 left-0">
      @include("admin.layouts.partials.sidebar")
    </aside>


    <!-- Main Content -->
    <div class="ml-64 flex-1 overflow-y-auto bg-gradient-to-br from-gray-50 to-gray-100 p-6">
            <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="flex justify-between items-center py-4 px-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Analytics Dashboard</h2>
                    <p class="text-sm text-gray-500">Real-time insights and performance metrics</p>
                </div>
                <div class="flex items-center">
                    <div class="relative mr-4">
                        <select class="border rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-48">
                            <option>Last 24 Hours</option>
                            <option>Last 7 Days</option>
                            <option selected>Last 30 Days</option>
                            <option>Last 90 Days</option>
                            <option>This Year</option>
                        </select>
                        <i class="fas fa-calendar absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center action-btn">
                        <i class="fas fa-sync-alt mr-2 real-time-pulse"></i>
                        Live Mode
                    </button>
                </div>
                @include("admin.components.dark-mode.dark-toggle")

            </div>
        </header>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto p-6">
            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Live Visitors -->
                <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-blue-500 card-hover">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Live Visitors</p>
                            <p id="liveVisitors" class="text-2xl font-bold">{{ $liveVisitors }}</p>
                            </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-users text-blue-500"></i>
                        </div>
                    </div>
                    <p class="text-xs text-green-500 mt-2 stats-change"><i class="fas fa-arrow-up mr-1"></i>18% from yesterday</p>
                </div>
                
                <!-- Total Pageviews -->
                <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-purple-500 card-hover">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Total Pageviews</p>
                            <p id="totalPageviews" class="text-2xl font-bold">{{ number_format($totalPageviews) }}</p>
                            </div>
                        <div class="bg-purple-100 p-3 rounded-full">
                            <i class="fas fa-eye text-purple-500"></i>
                        </div>
                    </div>
                    <p class="text-xs text-green-500 mt-2 stats-change"><i class="fas fa-arrow-up mr-1"></i>12% from last week</p>
                </div>
                
                <!-- Conversion Rate -->
                <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-green-500 card-hover">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Conversion Rate</p>
                            <p id="conversionRate" class="text-2xl font-bold">{{ $conversionRate }}%</p>
                            </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-shopping-cart text-green-500"></i>
                        </div>
                    </div>
                    <p class="text-xs text-green-500 mt-2 stats-change"><i class="fas fa-arrow-up mr-1"></i>2% from last month</p>
                </div>
                
                <!-- Avg. Session Duration -->
                <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-yellow-500 card-hover">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Avg. Session Duration</p>
                            <p id="avgSessionDuration" class="text-2xl font-bold">
    {{ gmdate("i\m s\s", $avgSessionDuration) }}
</p>                            </div>
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <i class="fas fa-clock text-yellow-500"></i>
                        </div>
                    </div>
                    <p class="text-xs text-red-500 mt-2 stats-change"><i class="fas fa-arrow-down mr-1"></i>5% from last week</p>
                </div>
            </div>

            <!-- Charts Row 1 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Traffic Overview Chart -->
                <div class="bg-white p-5 rounded-xl shadow-sm card-hover">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="font-semibold text-gray-800">Traffic Overview</h3>
                        <div class="flex space-x-2">
                            <button class="tab-button px-3 py-1 text-xs rounded-md active">Visitors</button>
                            <button class="tab-button px-3 py-1 text-xs rounded-md">Sessions</button>
                            <button class="tab-button px-3 py-1 text-xs rounded-md">Pageviews</button>
                        </div>
                    </div>
                    <div class="h-72">
                        <canvas id="trafficChart"></canvas>
                    </div>
                </div>
                
                <!-- Conversion Funnel -->
                <div class="bg-white p-5 rounded-xl shadow-sm card-hover">
                    <h3 class="font-semibold text-gray-800 mb-5">Conversion Funnel</h3>
                    <div class="space-y-5">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span>Visitors</span>
                                <span>24,850 (100%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span>Added to Cart</span>
                                <span>3,728 (15%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 15%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span>Reached Checkout</span>
                                <span>1,491 (6%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 6%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span>Completed Purchase</span>
                                <span>795 (3.2%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 3.2%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row 2 -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Traffic Sources -->
                <div class="bg-white p-5 rounded-xl shadow-sm card-hover">
                    <h3 class="font-semibold text-gray-800 mb-5">Traffic Sources</h3>
                    <div class="h-64">
                        <canvas id="trafficSourceChart"></canvas>
                    </div>
                </div>
                
                <!-- Device Breakdown -->
                <div class="bg-white p-5 rounded-xl shadow-sm card-hover">
                    <h3 class="font-semibold text-gray-800 mb-5">Device Breakdown</h3>
                    <div class="h-64">
                        <canvas id="deviceChart"></canvas>
                    </div>
                </div>
                
                <!-- Top Locations -->
                <div class="bg-white p-5 rounded-xl shadow-sm card-hover">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="font-semibold text-gray-800">Visitors by Location</h3>
                        <button class="text-xs text-blue-500">View Full Report</button>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="mr-2 text-sm">🇺🇸</span>
                                <span class="text-sm">United States</span>
                            </div>
                            <div class="text-sm font-medium">8,452 (34%)</div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="mr-2 text-sm">🇬🇧</span>
                                <span class="text-sm">United Kingdom</span>
                            </div>
                            <div class="text-sm font-medium">3,781 (15.2%)</div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="mr-2 text-sm">🇨🇦</span>
                                <span class="text-sm">Canada</span>
                            </div>
                            <div class="text-sm font-medium">2,543 (10.2%)</div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="mr-2 text-sm">🇦🇺</span>
                                <span class="text-sm">Australia</span>
                            </div>
                            <div class="text-sm font-medium">1,874 (7.5%)</div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="mr-2 text-sm">🇩🇪</span>
                                <span class="text-sm">Germany</span>
                            </div>
                            <div class="text-sm font-medium">1,327 (5.3%)</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Pages & Real-Time Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Top Pages -->
                <div class="bg-white p-5 rounded-xl shadow-sm card-hover">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="font-semibold text-gray-800">Top Pages</h3>
                        <button class="text-xs text-blue-500">View All</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-xs text-gray-500 uppercase border-b">
                                    <th class="pb-2">Page</th>
                                    <th class="pb-2">Visitors</th>
                                    <th class="pb-2">Bounce Rate</th>
                                    <th class="pb-2 text-right">Avg. Time</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr>
                                    <td class="py-3 text-sm">Homepage</td>
                                    <td class="py-3 text-sm">8,452</td>
                                    <td class="py-3 text-sm">42%</td>
                                    <td class="py-3 text-sm text-right">2m 15s</td>
                                </tr>
                                <tr>
                                    <td class="py-3 text-sm">Product Catalog</td>
                                    <td class="py-3 text-sm">5,781</td>
                                    <td class="py-3 text-sm">38%</td>
                                    <td class="py-3 text-sm text-right">3m 42s</td>
                                </tr>
                                <tr>
                                    <td class="py-3 text-sm">Product Details - Wireless Headphones</td>
                                    <td class="py-3 text-sm">3,245</td>
                                    <td class="py-3 text-sm">31%</td>
                                    <td class="py-3 text-sm text-right">4m 12s</td>
                                </tr>
                                <tr>
                                    <td class="py-3 text-sm">Shopping Cart</td>
                                    <td class="py-3 text-sm">2,187</td>
                                    <td class="py-3 text-sm">67%</td>
                                    <td class="py-3 text-sm text-right">1m 53s</td>
                                </tr>
                                <tr>
                                    <td class="py-3 text-sm">Checkout</td>
                                    <td class="py-3 text-sm">1,654</td>
                                    <td class="py-3 text-sm">72%</td>
                                    <td class="py-3 text-sm text-right">2m 28s</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Real-Time Activity -->
                <div class="bg-white p-5 rounded-xl shadow-sm card-hover">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="font-semibold text-gray-800">Real-Time Activity</h3>
                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full flex items-center">
                            <span class="w-2 h-2 bg-red-500 rounded-full mr-1 real-time-pulse"></span>
                            LIVE
                        </span>
                    </div>
                    <div class="space-y-4 max-h-80 overflow-y-auto">
                        <div class="flex items-start">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <i class="fas fa-user text-blue-500"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium">New visitor from United States</p>
                                <p class="text-xs text-gray-500">Viewed homepage • Just now</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-green-100 p-2 rounded-full mr-3">
                                <i class="fas fa-shopping-cart text-green-500"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium">Product added to cart</p>
                                <p class="text-xs text-gray-500">Wireless Headphones • 30s ago</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-purple-100 p-2 rounded-full mr-3">
                                <i class="fas fa-money-bill-wave text-purple-500"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium">Order completed</p>
                                <p class="text-xs text-gray-500">Order #ORD-5890 • 2m ago</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-yellow-100 p-2 rounded-full mr-3">
                                <i class="fas fa-search text-yellow-500"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium">Search performed</p>
                                <p class="text-xs text-gray-500">Query: "wireless earbuds" • 3m ago</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <i class="fas fa-user text-blue-500"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium">New visitor from Canada</p>
                                <p class="text-xs text-gray-500">Viewed product catalog • 4m ago</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection


@push("script")

<script>


        // Initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            // Traffic Overview Chart
            const trafficCtx = document.getElementById('trafficChart').getContext('2d');
            const trafficChart = new Chart(trafficCtx, {
                type: 'line',
                data: {
                    labels: ['1 Aug', '5 Aug', '10 Aug', '15 Aug', '20 Aug', '25 Aug', '30 Aug'],
                    datasets: [{
                        label: 'Visitors',
                        data: [650, 710, 680, 810, 790, 850, 920],
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.3
                    }, {
                        label: 'Pageviews',
                        data: [1250, 1320, 1400, 1480, 1520, 1600, 1750],
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });

            // Traffic Sources Chart
            const trafficSourceCtx = document.getElementById('trafficSourceChart').getContext('2d');
            const trafficSourceChart = new Chart(trafficSourceCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Direct', 'Organic Search', 'Social Media', 'Referral', 'Paid Ads'],
                    datasets: [{
                        data: [35, 25, 20, 15, 5],
                        backgroundColor: [
                            '#3B82F6',
                            '#10B981',
                            '#F59E0B',
                            '#8B5CF6',
                            '#EC4899'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });

            // Device Breakdown Chart
            const deviceCtx = document.getElementById('deviceChart').getContext('2d');
            const deviceChart = new Chart(deviceCtx, {
                type: 'pie',
                data: {
                    labels: ['Mobile', 'Desktop', 'Tablet'],
                    datasets: [{
                        data: [62, 30, 8],
                        backgroundColor: [
                            '#3B82F6',
                            '#10B981',
                            '#F59E0B'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });

            // Simulate real-time updates
            setInterval(() => {
                // Update live visitors count
                const liveVisitors = document.querySelector('.stats-change').parentElement.querySelector('.text-2xl');
                const currentVisitors = parseInt(liveVisitors.textContent);
                const randomChange = Math.floor(Math.random() * 5) - 2; // -2 to +2
                const newVisitors = Math.max(200, currentVisitors + randomChange);
                liveVisitors.textContent = newVisitors;

                // Add new real-time activity
                const activities = [
                    {icon: 'user', color: 'blue', action: 'New visitor from United Kingdom', detail: 'Viewed product page • Just now'},
                    {icon: 'shopping-cart', color: 'green', action: 'Product added to cart', detail: 'Wireless Earbuds • 30s ago'},
                    {icon: 'search', color: 'yellow', action: 'Search performed', detail: 'Query: "headphones" • 1m ago'},
                    {icon: 'user', color: 'blue', action: 'Returning visitor from Australia', detail: 'Viewed checkout • 2m ago'}
                ];
                
                const randomActivity = activities[Math.floor(Math.random() * activities.length)];
                const activityContainer = document.querySelector('.space-y-4');
                
                if (activityContainer.children.length > 4) {
                    activityContainer.removeChild(activityContainer.lastChild);
                }
                
                const newActivity = document.createElement('div');
                newActivity.className = 'flex items-start';
                newActivity.innerHTML = `
                    <div class="bg-${randomActivity.color}-100 p-2 rounded-full mr-3">
                        <i class="fas fa-${randomActivity.icon} text-${randomActivity.color}-500"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium">${randomActivity.action}</p>
                        <p class="text-xs text-gray-500">${randomActivity.detail}</p>
                    </div>
                `;
                
                activityContainer.insertBefore(newActivity, activityContainer.firstChild);
            }, 5000);
        });

        // Tab switching for charts
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('active');
                });
                button.classList.add('active');
            });
        });
    </script>
@endpush