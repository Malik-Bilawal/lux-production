@extends('admin.layouts.master-layouts.plain')

@section('title', 'Order Management | Luxorix | Admin Panel')

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

        tailwind.config = {
        darkMode: 'class', // 👈 important
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
        .order-row {
            transition: all 0.2s ease;
        }
        .order-row:hover {
            background-color: #f9fafb;
            transform: translateY(-1px);
        }
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
        }
        .action-btn {
            transition: all 0.2s ease;
        }
        .action-btn:hover {
            transform: translateY(-2px);
        }
        .modal-overlay {
            transition: opacity 0.3s ease;
        }
        .modal-content {
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
        .modal-open .modal-overlay {
            opacity: 1;
            pointer-events: auto;
        }
        .modal-open .modal-content {
            transform: translateY(0);
            opacity: 1;
        }
        .dropdown-menu {
            transition: all 0.2s ease;
            transform-origin: top right;
        }
        .dropdown-open .dropdown-menu {
            transform: scale(1);
            opacity: 1;
            pointer-events: auto;
        }
        .tab-button {
            transition: all 0.2s ease;
        }
        .tab-button.active {
            background-color: #3B82F6;
            color: white;
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
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #e5e7eb;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -2rem;
            top: 0.5rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #3B82F6;
        }




</Style>
@endpush


@section("content")


<header class="bg-white shadow-sm">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center py-4 px-6 gap-4">
        
        <div class="w-full sm:w-auto">
            <h2 class="text-xl font-semibold text-gray-800">Orders Management</h2>
            <p class="text-sm text-gray-500">Manage customer orders and their status</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
            
            <form method="GET" action="{{ route('admin.orders') }}" class="relative w-full sm:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search orders..." 
                       class="border rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-64">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </form>

            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center justify-center action-btn w-full sm:w-auto">
                <i class="fas fa-plus mr-2"></i>
                New Order
            </button>
            
            @include("admin.components.dark-mode.dark-toggle")
        </div>
    </div>
</header>

<section class="px-6 py-4 mt-1">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Total Orders</p>
                    <p class="text-2xl font-bold">{{ $totalOrders }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-shopping-cart text-blue-500"></i>
                </div>
            </div>
            <p class="text-xs {{ $totalOrdersChange >= 0 ? 'text-green-500' : 'text-red-500' }} mt-2">
                <i class="fas fa-arrow-{{ $totalOrdersChange >= 0 ? 'up' : 'down' }} mr-1"></i>{{ abs($totalOrdersChange) }}% from last month
            </p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-yellow-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Pending Orders</p>
                    <p class="text-2xl font-bold">{{ $pendingOrders }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-clock text-yellow-500"></i>
                </div>
            </div>
            <p class="text-xs {{ $pendingOrdersChange >= 0 ? 'text-green-500' : 'text-red-500' }} mt-2">
                <i class="fas fa-arrow-{{ $pendingOrdersChange >= 0 ? 'up' : 'down' }} mr-1"></i>{{ abs($pendingOrdersChange) }}% from last month
            </p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Completed Orders</p>
                    <p class="text-2xl font-bold">{{ $completedOrders }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
            </div>
            <p class="text-xs {{ $completedOrdersChange >= 0 ? 'text-green-500' : 'text-red-500' }} mt-2">
                <i class="fas fa-arrow-{{ $completedOrdersChange >= 0 ? 'up' : 'down' }} mr-1"></i>{{ abs($completedOrdersChange) }}% from last month
            </p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-red-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Cancelled Orders</p>
                    <p class="text-2xl font-bold">{{ $cancelledOrders }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-times-circle text-red-500"></i>
                </div>
            </div>
            <p class="text-xs {{ $cancelledOrdersChange >= 0 ? 'text-green-500' : 'text-red-500' }} mt-2">
                <i class="fas fa-arrow-{{ $cancelledOrdersChange >= 0 ? 'up' : 'down' }} mr-1"></i>{{ abs($cancelledOrdersChange) }}% from last month
            </p>
        </div>
    </div>
</section>


<section class="px-6 py-4 bg-white shadow-sm mt-1">
    <form method="GET" action="{{ route('admin.orders') }}">
        <div class="flex flex-col sm:flex-row sm:flex-wrap items-end gap-4">
            
            <div class="w-full sm:w-auto">
                <label class="block text-sm font-medium text-gray-700 mb-1">Order Date</label>
                <div class="flex flex-col sm:flex-row items-center gap-2 border rounded-md px-3 py-2 text-sm w-full sm:w-64">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full outline-none">
                    <span class="text-gray-500 hidden sm:block">to</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full outline-none">
                </div>
            </div>

            <div class="w-full sm:w-auto">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="border rounded-md px-3 py-2 text-sm w-full sm:w-40">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="w-full sm:w-auto">
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <select name="sort" class="border rounded-md px-3 py-2 text-sm w-full sm:w-40">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="price-low" {{ request('sort') == 'price-low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="customer" {{ request('sort') == 'customer' ? 'selected' : '' }}>Customer A-Z</option>
                </select>
            </div>

            <div class="w-full sm:w-auto">
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm flex items-center justify-center action-btn w-full sm:w-auto">
                    <i class="fas fa-filter mr-2"></i>
                    Apply Filters
                </button>
            </div>
        </div>
    </form>
</section>

<div class="p-6">
    <style>
      @media (max-width: 767px) {
        /* This creates the label-left, data-right layout */
        td[data-label] {
          display: flex;
          justify-content: space-between;
          align-items: center;
          padding: 0.75rem 1.5rem; /* py-3 px-6 */
        }
        
        /* The Label (Left Side) */
        td[data-label]::before {
          content: attr(data-label);
          font-weight: 600;
          color: #6b7280; /* gray-500 */
          text-transform: uppercase;
          font-size: 0.75rem; /* text-xs */
          text-align: left;
          margin-right: 1rem;
        }
        
        /* The Data (Right Side) */
        td[data-label] > * {
          text-align: right;
        }
        
        /* Special case for the first cell (checkbox) */
        td[data-label="Select"] {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        /* Hide the label for the checkbox, but keep the layout */
        td[data-label="Select"]::before {
            content: "Select";
            /* display: none; */ /* Hides label */
        }
      }
    </style>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 hidden md:table-header-group">
                    <tr>
                        <th class="px-6 py-3"><input type="checkbox" class="rounded text-blue-500"></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="block md:table-row-group divide-y divide-gray-200">
                    @foreach($orders as $order)

                    <tr class="order-row block md:table-row mb-4 md:mb-0 shadow-md md:shadow-none border border-gray-200 md:border-none rounded-lg md:rounded-none">
                        
                        <td class="block md:table-cell md:px-6 md:py-4 border-b md:border-b-0" data-label="Select">
                            <input type="checkbox" class="rounded text-blue-500">
                        </td>

                        <td class="block md:table-cell md:px-6 md:py-4 border-b" data-label="Order ID">
                            <div class="font-medium text-blue-600">{{ $order->order_code }}</div>
                        </td>

                        <td class="block md:table-cell md:px-6 md:py-4 border-b" data-label="Customer">
                            @if($order->addresses)
                                <div class="font-medium text-gray-900">
                                    {{ $order->addresses->first_name }} {{ $order->addresses->last_name }}
                                </div>
                            @else
                                <div class="text-gray-500">Guest</div>
                            @endif
                        </td>

                        <td class="block md:table-cell md:px-6 md:py-4 border-b" data-label="Date">
                            <div>
                                <div class="text-gray-900">{{ $order->created_at->format('M d, Y') }}</div>
                                <div class="text-gray-500 text-sm">{{ $order->created_at->format('h:i A') }}</div>
                            </div>
                        </td>

                        <td class="block md:table-cell md:px-6 md:py-4 border-b" data-label="Amount">
                            <span class="font-medium">
                                Rs. {{ number_format($order->total_amount, 2) }}
                            </span>
                        </td>

                        <td class="block md:table-cell md:px-6 md:py-4 border-b" data-label="Payment">
                            <span class="font-medium">
                                {{ $order->paymentMethod ? $order->paymentMethod->name : 'N/A' }}
                            </span>
                        </td>

                        <td class="block md:table-cell md:px-6 md:py-4 border-b" data-label="Status">
                            <span class="status-badge 
                                {{ $order->status == 'Processing' ? 'bg-blue-100 text-blue-800' : 
                                   ($order->status == 'Pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($order->status == 'Delivered' ? 'bg-green-100 text-green-800' : 
                                   'bg-gray-100 text-gray-800')) }}
                                   px-2 py-1 text-xs font-semibold rounded-full">
                                {{ $order->status }}
                            </span>
                        </td>

                        <td class="block md:table-cell md:px-6 md:py-4" data-label="Actions">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-500 hover:text-blue-700">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>




@endsection


@push("script")
<script>

  
    </script>
@endpush