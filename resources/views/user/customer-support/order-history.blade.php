@extends("user.layouts.master-layouts.plain")



<!-- Your custom JS (after jQuery) -->



@section('title', ' Order History | Luxorix')



@push("style")
<style type="text/css">
      

    </style>
@endpush


@section("content")
<div class="grid-bg mt-32">
    <div class="min-h-screen bg-primary text-light font-poppins pb-16">
        <div class="container mx-auto px-4 py-8">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white">
            <span class="bg-gradient-to-r from-amber-400 via-yellow-300 to-amber-500 bg-clip-text text-transparent">
                Order
            <span class="text-slate-300 font-light">History</span>
        </h2>

             <div class="flex items-center gap-2 bg-secondary/50 rounded-xl p-2">
    <div class="stats-card text-center">
        <p class="text-2xl font-bold">{{ $totalOrders }}</p>
        <p class="text-xs text-gray-400">Total Orders</p>
    </div>
    <div class="stats-card text-center">
        <p class="text-2xl font-bold text-green-400">{{ $delivered }}</p>
        <p class="text-xs text-gray-400">Delivered</p>
    </div>
    <div class="stats-card text-center">
        <p class="text-2xl font-bold text-amber-400">{{ $processing }}</p>
        <p class="text-xs text-gray-400">Processing</p>
    </div>
    <div class="stats-card text-center">
        <p class="text-2xl font-bold text-red-400">{{ $cancelled }}</p>
        <p class="text-xs text-gray-400">Cancelled</p>
    </div>
</div>

            </div>
            <form method="GET" action="{{ route('user.order-history') }}">
    <div class="modern-card p-6 mb-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">

            <!--  Search -->
            <div class="relative w-full md:w-1/3">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-accent"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by Order ID or Product"
                       class="search-input w-full pl-12 pr-4">
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap gap-3 w-full md:w-2/3 justify-end">
                <!-- Status -->
                <select name="status" class="filter-select w-full md:w-auto">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status')=='processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status')=='shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status')=='delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="refunded" {{ request('status')=='refunded' ? 'selected' : '' }}>Refunded</option>
                </select>

                <!-- Time -->
                <select name="days" class="filter-select w-full md:w-auto">
                    <option value="">All Time</option>
                    <option value="7" {{ request('days')=='7' ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="30" {{ request('days')=='30' ? 'selected' : '' }}>Last 30 Days</option>
                    <option value="90" {{ request('days')=='90' ? 'selected' : '' }}>Last 90 Days</option>
                </select>

                <!-- Submit -->
                <button type="submit" class="px-4 py-2 rounded-lg bg-accent3 text-white flex items-center gap-2">
                    <i class="fas fa-filter"></i>
                    <span>Apply</span>
                </button>
            </div>
        </div>
</form>

            </div>

      <!-- Orders List -->
<div class="space-y-6" id="orders-container">
    @forelse($orders as $order)
        <div class="modern-card p-6">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
                <div>
                    <h2 class="text-xl font-semibold flex items-center gap-2">
                        <span>Order #{{ $order->order_code }}</span>
                        @if($order->user && $order->user->is_vip)
                            <span class="text-xs bg-accent/20 text-accent px-2 py-1 rounded-full">VIP Customer</span>
                        @endif
                    </h2>
                    <p class="text-gray-400 text-sm">
                        Placed on {{ $order->placed_at->format('d M Y \a\t H:i') }}
                    </p>
                </div>
                @php
    $statusClasses = match($order->status) {
        'deliverd' => 'bg-green-100 text-green-800',
        'inactive' => 'bg-gray-100 text-gray-800',
        'canceled' => 'bg-red-100 text-red-800',
        'pending' => 'bg-yellow-100 text-yellow-800',
        'processing' => 'bg-blue-100 text-blue-800',
        default => 'bg-gray-200 text-gray-600' 
    };
@endphp

<span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusClasses }}">
    {{ ucfirst($order->status) }}
</span>
            </div>

            <!-- Products (just show first item, or loop all if needed) -->
            <div class="flex flex-col lg:flex-row gap-6 mb-6">
                @foreach($order->items as $item)
                    <div class="flex items-center gap-4 flex-1">
                    <img src="{{ asset('storage/' . $item->product->image) }}"
                    class="product-img" 
     alt="{{ $item->product->name }}"
                             class="product-img" alt="{{ $item->product->name }}">
                        <div>
                            <h3 class="font-medium">{{ $item->product->name }}</h3>
                            <p class="text-gray-400 text-sm">
                                Qty: {{ $item->quantity }}
                            </p>
                            <p class="text-accent font-semibold">
                                PKR {{ number_format($item->price * $item->quantity) }}
                            </p>
                        </div>
                    </div>
                @endforeach

                <div class="lg:w-1/3">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-400 text-sm">Total Amount</p>
                            <p class="font-semibold">PKR {{ number_format($order->total_amount) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Payment Method</p>
                            <p class="text-{{ strtolower($order->paymentMethod->name ?? 'gray') }} font-medium">
                                {{ $order->paymentMethod->name ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-wrap gap-3 mt-6 pt-6 border-t border-gray-700">
            @if(
    in_array($order->status, ['pending', 'processing']) 
    && $order->paymentMethod 
    && strtolower($order->paymentMethod->name) === 'cod'
)
    @php 
        \Log::info('Cancel button visible', [
            'order_id' => $order->id,
            'status' => $order->status,
            'payment_method' => $order->paymentMethod->name ?? 'N/A'
        ]); 
    @endphp
    <button class="action-btn cancel-btn" 
            onclick="openCancelModal({{ $order->id }})">
        <i class="fas fa-times-circle"></i> Cancel Order
    </button>
@endif




@if(!in_array($order->status, ['cancelled', 'refunded']))
    <a href="{{ route('orders.invoice', $order->order_code) }}" class="action-btn invoice-btn">
        <i class="fas fa-file-invoice"></i> Download Invoice
    </a>
@endif

@if(in_array($order->status, ['delivered', 'completed']))
    <form action="{{ route('orders.reorder', $order->order_code) }}" method="POST" class="inline reorder-form">
        @csrf
        <button type="submit" class="action-btn reorder-btn">
            <i class="fas fa-rotate-left"></i> Reorder
        </button>
    </form>
@endif


                <button class="action-btn" style="background: rgba(239, 68, 68, 0.2); color: #ef4444;">
                    <i class="fas fa-headset"></i> Support
                </button>
            </div>
        </div>
    @empty
        <p class="text-center text-gray-400">You have no orders yet.</p>
    @endforelse
</div>

<!-- Pagination -->
<div class="flex justify-center items-center gap-2 mt-12">
    {{ $orders->links() }}
</div>

<!-- Cancel Order Modal -->
<div id="cancelModal" class="fixed inset-0 bg-black/70 flex items-center justify-center hidden z-50">
    <div class="bg-secondary rounded-2xl shadow-xl w-full max-w-lg p-6 relative">
        <!-- Close Button -->
        <button onclick="closeCancelModal()" 
                class="absolute top-3 right-3 text-gray-400 hover:text-white">
            <i class="fas fa-times text-lg"></i>
        </button>

        <!-- Modal Header -->
        <h2 class="text-xl font-semibold text-white mb-4 flex items-center gap-2">
            <i class="fas fa-ban text-accent"></i>
            Cancel Order
        </h2>

        <!-- Form -->
        <form method="POST" action="{{ route('orders.cancel') }}" id="cancelOrderForm">
    @csrf
    <input type="hidden" name="order_id" id="cancelOrderId">
            <!-- Reason Dropdown -->
            <div class="mb-4">
                <label class="block text-sm text-gray-300 mb-2">Reason for Cancellation</label>
                <select name="reason" required
                        class="w-full px-4 py-2 rounded-lg bg-dark text-white border border-gray-600 focus:ring-2 focus:ring-accent">
                    <option value="">-- Select a reason --</option>
                    <option value="changed_mind">Changed my mind</option>
                    <option value="found_cheaper">Found a cheaper alternative</option>
                    <option value="delivery_delay">Delivery taking too long</option>
                    <option value="wrong_order">Ordered by mistake</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <!-- Comment Box -->
            <div class="mb-4">
                <label class="block text-sm text-gray-300 mb-2">Additional Comments (optional)</label>
                <textarea name="comment" rows="3"
                          class="w-full px-4 py-2 rounded-lg bg-dark text-white border border-gray-600 focus:ring-2 focus:ring-accent"
                          placeholder="Write your reason..."></textarea>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeCancelModal()" 
                        class="px-4 py-2 rounded-lg bg-gray-700 text-gray-300 hover:bg-gray-600">
                    Close
                </button>
                <button type="submit" 
                        class="px-4 py-2 rounded-lg bg-accent text-dark font-semibold hover:bg-neon">
                    Confirm Cancel
                </button>
            </div>
        </form>
    </div>
</div>

</div>

@endsection


@push("script")
@vite('resources/js/user/customer-support/order-history.js')
@endpush


