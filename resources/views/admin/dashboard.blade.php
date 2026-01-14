<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@extends('admin.layouts.master-layouts.plain')

<title>Admin | Dashboard</title>

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
    .chart-bar {
        transition: all 0.3s ease;
    }

    .chart-bar:hover {
        opacity: 0.8;
        transform: translateY(-2px);
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .nav-item.active {
        background-color: #3B82F6;
        color: white;
    }

    .nav-item.active i {
        color: white;
    }


    .responsive-td::before {
    content: attr(data-label);
    font-weight: 600;
    text-transform: uppercase;
    display: inline-block;
    width: 100px;
    color: #6B7280; /* Tailwind gray-500 */
    margin-right: 0.5rem;
}
@media (min-width: 768px) {
    .responsive-td::before {
        content: none;
    }
}
</style>
@endpush



@section("content")



@if ($errors->any())
    <div class="alert alert-danger" style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 10px;">
        <strong>Whoops! Something went wrong:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<!-- Main Content -->
<!-- Header -->
<header class="bg-white shadow-sm">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 py-4 px-6">

        <div>
            <h2 class="text-xl font-semibold text-gray-800">Dashboard Overview</h2>
            <p class="text-sm text-gray-500">Welcome back, Admin</p>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto">
            @include("admin.components.dark-mode.dark-toggle")

            <form action="{{ route('admin.activity-logs.index') }}" class="w-full sm:w-auto">
                @csrf
                <button
                    type="submit"
                    class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md 
                           hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 
                           focus:ring-offset-2 transition duration-200
                           w-full sm:w-auto">
                    View Logs
                </button>
            </form>
        </div>

    </div>
</header>
<!-- Stats Cards -->
<section class="p-6">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">

        <!-- Total Users Card -->
        <div class="stats-card bg-white rounded-xl shadow-md p-4 border-l-4 border-blue-500 transition-all duration-300">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Users</p>
                    <h3 class="text-xl font-bold mt-1">{{ number_format($totalUsers) }}</h3>
                    <p class="{{ $usersGrowth >= 0 ? 'text-green-500' : 'text-red-500' }} text-xs mt-2">
                        <i class="fas {{ $usersGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        {{ abs($usersGrowth) }}% from last month
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-users text-blue-500"></i>
                </div>
            </div>
        </div>

        <!-- Total Orders Card -->
        <div class="stats-card bg-white rounded-xl shadow-md p-4 border-l-4 border-green-500 transition-all duration-300">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Orders</p>
                    <h3 class="text-xl font-bold mt-1">{{ number_format($totalOrders) }}</h3>
                    <p class="{{ $ordersGrowth >= 0 ? 'text-green-500' : 'text-red-500' }} text-xs mt-2">
                        <i class="fas {{ $ordersGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        {{ abs($ordersGrowth) }}% from last month
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-green-500"></i>
                </div>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="stats-card bg-white rounded-xl shadow-md p-4 border-l-4 border-amber-500 transition-all duration-300">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Revenue</p>
                    <h3 class="text-xl font-bold mt-1"> Rs. {{ number_format($totalRevenue, 2) }}</h3>
                    <p class="{{ $revenueGrowth >= 0 ? 'text-green-500' : 'text-red-500' }} text-xs mt-2">
                        <i class="fas {{ $revenueGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        {{ abs($revenueGrowth) }}% from last month
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-amber-500"></i>
                </div>
            </div>
        </div>

        <!-- Products Listed Card -->
        <div class="stats-card bg-white rounded-xl shadow-md p-4 border-l-4 border-purple-500 transition-all duration-300">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Products Listed</p>
                    <h3 class="text-xl font-bold mt-1">{{ number_format($totalProducts) }}</h3>
                    <p class="{{ $productsGrowth >= 0 ? 'text-green-500' : 'text-red-500' }} text-xs mt-2">
                        <i class="fas {{ $productsGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        {{ abs($productsGrowth) }}% from last month
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-box text-purple-500"></i>
                </div>
            </div>
        </div>

        <!-- Refunds Card -->
        <div class="stats-card bg-white rounded-xl shadow-md p-4 border-l-4 border-red-500 transition-all duration-300">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Refunds</p>
                    <h3 class="text-xl font-bold mt-1">Rs. {{ number_format($refunds, 2) }}</h3>
                    <p class="{{ $refundGrowth >= 0 ? 'text-green-500' : 'text-red-500' }} text-xs mt-2">
                        <i class="fas {{ $refundGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        {{ abs($refundGrowth) }}% from last month
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-undo text-red-500"></i>
                </div>
            </div>
        </div>

        <!-- Referral Users Card -->
        <div class="stats-card bg-white rounded-xl shadow-md p-4 border-l-4 border-indigo-500 transition-all duration-300">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Referral Users</p>
                    <h3 class="text-xl font-bold mt-1">{{ number_format($referralUsers) }}</h3>
                    <p class="{{ $referralGrowth >= 0 ? 'text-green-500' : 'text-red-500' }} text-xs mt-2">
                        <i class="fas {{ $referralGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        {{ abs($referralGrowth) }}% from last month
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                    <i class="fas fa-user-friends text-indigo-500"></i>
                </div>
            </div>
        </div>

    </div>
</section>


<!-- Main Dashboard Grid -->
<div class="px-6 grid grid-cols-1 lg:grid-cols-3 gap-6 pb-6">
    <!-- Left Column -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
    <h3 class="font-semibold text-gray-700">Revenue & Orders Overview</h3>
    <div class="flex flex-wrap gap-2">
        <button onclick="updateChart('orders')" id="btn-orders" class="px-3 py-1 bg-blue-500 text-white rounded-md text-sm">Orders</button>
        <button onclick="updateChart('revenue')" id="btn-revenue" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md text-sm">Revenue</button>
        <button onclick="updateChart('users')" id="btn-users" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md text-sm">Users</button>
        <button onclick="updateChart('referrals')" id="btn-referrals" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md text-sm">Referral Users</button>
        <select id="rangeSelect" class="border rounded-md px-3 py-1 text-sm" onchange="loadChartData()">
            <option value="7">Last 7 Days</option>
            <option value="30">Last 30 Days</option>
            <option value="90" selected>Last 90 Days</option>
        </select>
    </div>
</div>
            <div class="h-64">
                <canvas id="dashboardChart"></canvas>
            </div>
        </div>

        <!-- Orders Overview -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-semibold text-gray-700">Orders Overview</h3>
                <button class="text-blue-500 text-sm">View Report</button>
            </div>
            <div class="grid grid-cols-4 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg text-center">
                    <p class="text-gray-500 text-sm">Today's Orders</p>
                    <p class="text-xl font-bold mt-1">{{ $totalOrders }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $totalValue }}</p>
                </div>
                <div class="bg-amber-50 p-4 rounded-lg text-center">
                    <p class="text-gray-500 text-sm">Pending Orders</p>
                    <p class="text-xl font-bold mt-1">{{ $pendingOrders }}</p>
                </div>
                <div class="bg-amber-50 p-4 rounded-lg text-center">
                    <p class="text-gray-500 text-sm">Cancelled Orders</p>
                    <p class="text-xl font-bold mt-1">{{ $cancelledOrders }}</p>
                </div>

                <div class="bg-green-50 p-4 rounded-lg text-center">
                    <p class="text-gray-500 text-sm">Completed Orders</p>
                    <p class="text-xl font-bold mt-1">{{ $completedOrders }}</p>
                </div>
            </div>
        </div>
       <!-- Recent Orders Table -->
<div class="bg-white rounded-xl shadow-md">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h3 class="font-semibold text-gray-700">Recent Orders</h3>
        <a href="{{ route('admin.orders') }}" class="text-blue-500 text-sm">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full block md:table">
            <!-- Table Head (Desktop only) -->
            <thead class="bg-gray-50 hidden md:table-header-group">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>

            <tbody class="block md:table-row-group">
                @forelse($recentOrders as $order)
                <tr class="block md:table-row border border-gray-200 rounded-lg shadow-md mb-4 md:mb-0 hover:bg-gray-50 transition-colors">
                    <!-- Order ID -->
                    <td class="block md:table-cell px-6 py-3 relative border-b md:border-none border-gray-200 responsive-td" data-label="Order ID">
                        #ORD-{{ $order->id }}
                    </td>

                    <!-- Customer -->
                    <td class="block md:table-cell px-6 py-3 relative border-b md:border-none border-gray-200 responsive-td" data-label="Customer">
                        {{ $order->addresses->first()->first_name ?? 'Guest' }} {{ $order->addresses->first()->last_name ?? '' }}
                    </td>

                    <!-- Date -->
                    <td class="block md:table-cell px-6 py-3 relative border-b md:border-none border-gray-200 responsive-td" data-label="Date">
                        {{ $order->created_at->format('M d, Y') }}
                    </td>

                    <!-- Amount -->
                    <td class="block md:table-cell px-6 py-3 relative border-b md:border-none border-gray-200 responsive-td" data-label="Amount">
                        Rs. {{ number_format($order->total_amount, 2) }}
                    </td>

                    <!-- Status -->
                    <td class="block md:table-cell px-6 py-3 relative border-b md:border-none border-gray-200 responsive-td" data-label="Status">
                        @php
                        $colors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'processing' => 'bg-blue-100 text-blue-800',
                            'shipped' => 'bg-indigo-100 text-indigo-800',
                            'delivered' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                            'return' => 'bg-gray-100 text-gray-800',
                        ];
                        @endphp
                        <span class="px-2 py-1 text-xs rounded-full {{ $colors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>

                    <!-- Action -->
                    <td class="block md:table-cell px-6 py-3 relative border-b md:border-none border-gray-200 responsive-td" data-label="Action">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-500 hover:text-blue-700">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr class="block md:table-row">
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Responsive TD Label CSS -->


    </div>

    <!-- Right Column -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="font-semibold text-gray-700 mb-4">Users Overview</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-gray-500 text-sm">New Users This Month</p>
                    <p class="text-xl font-bold">{{ $newUsersThisMonth }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Active Users</p>
                    <p class="text-xl font-bold">{{ $activeUsers }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm mb-2">Top Customers</p>
                    <div class="space-y-2">
                        @foreach($topCustomers as $customer)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 rounded-full mr-2"></div>
                                <div>
                                    <p class="text-sm font-medium">{{ $customer->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $customer->orders_count }} orders</p>
                                </div>
                            </div>
                            <p class="text-sm font-bold">
                                Rs. {{ number_format($customer->orders_sum_total_amount, 2) }}
                            </p>

                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


        <!-- Products Overview -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="font-semibold text-gray-700 mb-4">Products Overview</h3>
            <div class="space-y-4">
                <!-- Low Stock Alert -->
                <div>
                    <p class="text-gray-500 text-sm mb-2">Low Stock Alert</p>
                    <div class="bg-red-50 p-3 rounded-lg space-y-2">
                        @forelse($lowStockProducts as $product)
                        <div class="flex justify-between items-center">
                            <p class="text-sm font-medium">{{ $product->name }}</p>
                            <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">
                                {{ $product->stock }} left
                            </span>
                        </div>
                        @empty
                        <p class="text-xs text-gray-500">All products in stock</p>
                        @endforelse
                    </div>
                </div>

                <!-- Top Selling Products -->
                <div>
                    <p class="text-gray-500 text-sm">Top Selling Products</p>
                    <div class="mt-2 space-y-2">
                        @forelse($topSellingProducts as $product)
                        <div class="flex justify-between">
                            <p class="text-sm">{{ $product->name }}</p>
                            <p class="text-sm font-bold">{{ $product->order_items_count }} sold</p>
                        </div>
                        @empty
                        <p class="text-xs text-gray-500">No sales yet</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recently Added -->
                <div>
                    <p class="text-gray-500 text-sm">Recently Added</p>
                    <div class="mt-2 space-y-2">
                        @forelse($recentProducts as $product)
                        <div class="flex justify-between">
                            <p class="text-sm">{{ $product->name }}</p>
                            <p class="text-xs text-gray-500">{{ $product->created_at->diffForHumans() }}</p>
                        </div>
                        @empty
                        <p class="text-xs text-gray-500">No new products</p>
                        @endforelse
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
    // Simple toggle for mobile menu (if needed in the future)
    function toggleMenu() {
        const sidebar = document.querySelector('aside');
        sidebar.classList.toggle('-translate-x-full');
    }

    // Add interactivity to chart bars
    document.querySelectorAll('.chart-bar').forEach(bar => {
        bar.addEventListener('mouseover', function() {
            this.style.height = (parseInt(this.style.height) + 5) + '%';
        });

        bar.addEventListener('mouseout', function() {
            this.style.height = (parseInt(this.style.height) - 5) + '%';
        });
    });

    // Add active state to navigation items
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.nav-item').forEach(i => {
                i.classList.remove('active');
                i.classList.add('text-gray-300');
            });
            this.classList.add('active');
            this.classList.remove('text-gray-300');
        });
    });


    let currentType = 'orders';
    let chart;

    function loadChartData() {
        const days = document.getElementById('rangeSelect').value;

        fetch(`{{ route('admin.chartData') }}?days=${days}`)
            .then(res => res.json())
            .then(data => {
                updateChart(currentType, data);
            });
    }

    function updateChart(type, data = null) {
        currentType = type;

        // Reset button styles
        ['orders', 'revenue', 'users', 'referrals'].forEach(t => {
            document.getElementById(`btn-${t}`).classList.remove('bg-blue-500', 'text-white');
            document.getElementById(`btn-${t}`).classList.add('bg-gray-200', 'text-gray-700');
        });

        // Active button
        document.getElementById(`btn-${type}`).classList.remove('bg-gray-200', 'text-gray-700');
        document.getElementById(`btn-${type}`).classList.add('bg-blue-500', 'text-white');

        if (!data) return loadChartData();

        const labels = Object.keys(data.orders); // x-axis dates
        let dataset;

        if (type === 'orders') {
            dataset = {
                type: 'bar',
                label: 'Orders',
                data: Object.values(data.orders),
                backgroundColor: '#3b82f6'
            };
        } else if (type === 'revenue') {
            dataset = {
                type: 'line',
                label: 'Revenue',
                data: Object.values(data.revenue),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16,185,129,0.2)',
                tension: 0.4,
                fill: true
            };
        } else if (type === 'users') {
            dataset = {
                type: 'line',
                label: 'New Users',
                data: Object.values(data.users),
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245,158,11,0.2)',
                tension: 0.4,
                fill: true
            };
        } else {
            dataset = {
                type: 'line',
                label: 'Referral Users',
                data: Object.values(data.referrals),
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139,92,246,0.2)',
                tension: 0.4,
                fill: true
            };
        }

        if (!chart) {
            const ctx = document.getElementById('dashboardChart').getContext('2d');
            chart = new Chart(ctx, {
                data: {
                    labels: labels,
                    datasets: [dataset]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    let value = ctx.raw;
                                    if (currentType === 'revenue') {
                                        return 'Revenue: $' + value.toLocaleString();
                                    }
                                    return ctx.dataset.label + ': ' + value;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        } else {
            chart.data.labels = labels;
            chart.data.datasets = [dataset];
            chart.update();
        }
    }



    // Initial load
    loadChartData();
</script>

@endpush