@extends("admin.layouts.master-layouts.plain")



@section('title', 'Order Detail | Luxorix | Admin Panel')

@push("style")
<style>
    /* Custom animations and transitions */
    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .slide-in {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            transform: translateX(-10px);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Custom scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #c5c5c5;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Hover effects */
    .hover-lift {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }

    #timer-count {
        font-weight: bold;
        color: green;
    }

    #timer-count.expired {
        color: red;
    }
</style>
@endpush

@section("content")

<div class="max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="max-w-7xl mx-auto">

        <header class="mb-8 fade-in">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center py-4 px-6 gap-4">

                <div class="w-full sm:w-auto">
                    <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Order Details
                    </h1>
                    <p class="text-gray-500 mt-2">Order code: <span class="font-mono bg-blue-50 text-blue-600 px-2 py-1 rounded-md">{{ $order->order_code }}</span></p>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('admin.orders') }}" class="px-4 py-2 bg-white text-gray-700 rounded-lg border border-gray-200 hover:bg-gray-50 flex items-center justify-center shadow-sm w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Orders
                    </a>
                    <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 flex items-center justify-center shadow-sm w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Invoice
                    </button>
                    @include("admin.components.dark-mode.dark-toggle")
                </div>

            </div>
        </header>

        <div class="bg-white rounded-xl shadow-sm p-5 mb-6 fade-in">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 w-full">

                <!-- Order Status -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between w-full sm:w-auto gap-1">
                    <span class="text-lg font-medium text-gray-700">Order Status:</span>

                    <span class="px-3 py-1 rounded-full text-sm font-semibold w-fit
        @if($order->status=='pending') bg-yellow-100 text-yellow-800 
        @elseif($order->status=='delivered') bg-green-100 text-green-800 
        @elseif($order->status=='cancelled') bg-red-100 text-red-800 
        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <button class="px-4 py-2 bg-blue-50 text-blue-600 rounded-lg text-sm hover:bg-blue-100 transition-colors w-full sm:w-auto">
                        Update Status
                    </button>
                    <button class="px-4 py-2 bg-red-50 text-red-600 rounded-lg text-sm hover:bg-red-100 transition-colors w-full sm:w-auto">
                        Cancel Order
                    </button>
                </div>

            </div>


            {{-- Status Update --}}
            {{-- Status Update --}}
            {{-- Status Update --}}
            <div class="bg-white rounded-xl shadow-sm p-5 mb-6 fade-in">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <span class="text-lg font-medium text-gray-700 mr-3">Order Status:</span>

                        @php
                        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'return'];
                        @endphp

                        <select id="order-status"
                            class="border rounded-lg px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            @if($order->status === 'cancelled' && optional($order->cancellation)->cancelled_by === 'user') disabled @endif>
                            @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <button id="update-status-btn"
                        class="px-4 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors"
                        @if($order->status === 'cancelled' && optional($order->cancellation)->cancelled_by === 'user') disabled @endif>
                        Update Status
                    </button>
                </div>

                {{-- Show reason/note if cancelled by user --}}
                @if($order->status === 'cancelled' && optional($order->cancellation)->cancelled_by === 'user')
                <p class="mt-3 text-sm text-red-600 font-medium">
                    ⚠️ This order was cancelled by the customer. Reason: <strong>{{ $order->cancellation->reason }}</strong><br>
                    Status updates are disabled.
                </p>
                @endif
            </div>



            {{-- Progress Steps --}}
            <div class="mt-6">
                <div class="flex items-center">
                    @php
                    $statuses = ['pending', 'processing', 'shipped', 'delivered'];
                    $currentIndex = array_search($order->status, $statuses);
                    $currentIndex = $currentIndex !== false ? $currentIndex : 0;
                    @endphp

                    @foreach($statuses as $index => $status)
                    <div class="flex items-center {{ $index > 0 ? 'w-full' : '' }}">
                        @if($index > 0)
                        <div class="w-full h-1 bg-gray-200 rounded">
                            <div class="h-1 bg-blue-500 rounded {{ $index <= $currentIndex ? 'w-full' : 'w-0' }} transition-all duration-500"></div>
                        </div>
                        @endif

                        <div class="relative">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center 
                                        {{ $index <= $currentIndex ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 text-xs font-medium text-gray-500 whitespace-nowrap">
                                {{ ucfirst($status) }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            {{-- Left Column --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Order & Customer Info --}}
                <div class="grid md:grid-cols-2 gap-6">
                    {{-- Order Info --}}
                    <div class="bg-white rounded-xl shadow-sm p-6 hover-lift slide-in">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Order Information
                        </h2>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Order Code:</span>
                                <span class="font-medium">{{ $order->order_code }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Placed At:</span>
                                <span class="font-medium">{{ $order->placed_at }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Amount:</span>
                                <span class="font-medium text-blue-600">${{ number_format($order->total_amount,2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tracking Code:</span>
                                <span class="font-medium">{{ $order->tracking_code ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">IP Address:</span>
                                <span class="font-mono text-sm">{{ $order->ip_address }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Customer Info --}}
                    <div class="bg-white rounded-xl shadow-sm p-6 hover-lift slide-in" style="animation-delay: 0.1s">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Customer Information
                        </h2>
                        @php $shipping = $order->addresses; @endphp
                        <div class="space-y-3">
                            <div>
                                <p class="text-gray-600">Name</p>
                                <p class="font-medium">{{ $shipping->first_name ?? 'Guest' }} {{ $shipping->last_name ?? '' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Contact</p>
                                <p class="font-medium">{{ $shipping->email ?? $order->user->email ?? 'N/A' }}</p>
                                <p class="font-medium">{{ $shipping->phone ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <p class="text-gray-600">City</p>
                                <p class="font-medium">{{ $shipping->city ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Shipping Address</p>
                                <p class="font-medium">{{ $shipping->address_1 ?? '' }} {{ $shipping->address_2 ?? '' }}</p>
                                <p class="font-medium">{{ $shipping->city ?? '' }}, {{ $shipping->state ?? '' }} {{ $shipping->zip ?? '' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Postal Code</p>
                                <p class="font-medium">{{ $shipping->zip ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 hover-lift">
    <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
        </svg>
        Order Items ({{ count($order->items) }})
    </h2>

    <!-- Table (visible on md and up) -->
    <div class="hidden md:block overflow-x-auto custom-scrollbar">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-600 text-sm">
                <tr>
                    <th class="p-3 font-medium">Product</th>
                    <th class="p-3 font-medium">Price</th>
                    <th class="p-3 font-medium">Qty</th>
                    <th class="p-3 font-medium">Total</th>
                    <th class="p-3 font-medium">Discount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($order->items as $item)
                @php
                    $quantity = $item->quantity;
                    $paidPrice = $item->price;
                    $originalPrice = $item->product->cut_price ?? 0;
                    $subtotal = $paidPrice * $quantity;
                    $itemDiscount = $originalPrice - $paidPrice;
                @endphp

                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-3">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0 mr-3">
                                @if($item->product && $item->product->image)
                                    <img class="h-12 w-12 object-cover"
                                         src="{{ asset('storage/' . $item->product->image) }}"
                                         alt="{{ $item->product->name }}">
                                @else
                                    <div class="w-12 h-12 flex items-center justify-center bg-gray-200 text-gray-400 rounded-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div>
                                <p class="font-medium text-gray-800">{{ $item->product->name ?? 'Product' }}</p>
                                <p class="text-sm text-gray-500">SKU: {{ $item->product->sku ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </td>

                    <td class="p-3">
                        <p class="font-medium">Rs. {{ number_format($paidPrice,2) }}</p>
                        <p class="text-sm text-gray-500 line-through">
                            Rs. {{ number_format($originalPrice,2) }}
                        </p>
                    </td>

                    <td class="p-3">
                        <span class="bg-gray-100 text-gray-800 py-1 px-2 rounded-md font-medium">
                            {{ $quantity }}
                        </span>
                    </td>

                    <td class="p-3 font-medium">
                        Rs. {{ number_format($subtotal,2) }}
                    </td>

                    <td class="p-3 text-red-500 font-medium">
                        -Rs. {{ number_format($itemDiscount,2) }}
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div class="md:hidden space-y-4">
        @foreach($order->items as $item)
        @php
            $quantity = $item->quantity;
            $paidPrice = $item->price;
            $originalPrice = $item->product->cut_price ?? 0;
            $subtotal = $paidPrice * $quantity;
            $itemDiscount = $originalPrice - $paidPrice;
        @endphp

        <div class="border rounded-xl p-4 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-14 h-14 bg-gray-200 rounded-lg overflow-hidden">
                    @if($item->product && $item->product->image)
                        <img src="{{ asset('storage/' . $item->product->image) }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <div>
                    <p class="font-medium text-gray-800">{{ $item->product->name }}</p>
                    <p class="text-sm text-gray-500">SKU: {{ $item->product->sku }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 text-sm">
                <p class="text-gray-600">Price:</p>
                <p class="font-medium">Rs. {{ number_format($paidPrice,2) }}</p>

                <p class="text-gray-600">Qty:</p>
                <p class="font-medium">{{ $quantity }}</p>

                <p class="text-gray-600">Total:</p>
                <p class="font-semibold text-gray-800">Rs. {{ number_format($subtotal,2) }}</p>

                <p class="text-gray-600">Discount:</p>
                <p class="font-semibold text-red-500">-Rs. {{ number_format($itemDiscount,2) }}</p>
            </div>
        </div>

        @endforeach
    </div>
</div>

            </div>

            {{-- Right Column --}}
            <div class="space-y-6">
                {{-- Payment & Shipping Info --}}
                <div class="bg-white rounded-xl shadow-sm p-6 hover-lift">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Payment & Shipping
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-gray-600 text-sm">Payment Method</p>
                            <p class="font-medium">{{ $order->paymentMethod->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Shipping Method</p>
                            <p class="font-medium">{{ $order->shippingMethod->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Promo Code</p>
                            @if($order->promoCode)
                            <span class="inline-flex items-center bg-blue-50 text-blue-700 px-2 py-1 rounded-md text-sm font-medium">
                                {{ $order->promoCode->code }} ({{ $order->promoCode->discount_percent }}%)
                            </span>
                            @else
                            <span class="text-gray-500">N/A</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Referral Code</p>
                            <p class="font-medium">{{ $order->referral_code ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                @if($order->notes)
                <div class="bg-white rounded-xl shadow-sm p-6 hover-lift">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Order Notes
                    </h2>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    {{ $order->notes }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                {{-- Summary Panel --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift sticky top-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-2">Order Summary</h2>

                    <div class="space-y-4">

                        {{-- Subtotal --}}
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Subtotal</span>
                            <span class="font-semibold text-gray-800">Rs. {{ number_format($order->items->sum('subtotal'), 2) }}</span>
                        </div>

                        {{-- Item Discounts --}}
                        <div class="flex justify-between items-center bg-red-50 px-3 py-2 rounded-md">
                            <span class="text-red-600 font-medium">Item Discounts</span>
                            <span class="text-red-600 font-semibold">Rs. {{ number_format($order->discount_amount, 2) }}</span>
                        </div>

                        {{-- Shipping --}}
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Shipping</span>
                            <span class="font-semibold text-gray-800">Rs. {{ number_format($order->shippingMethod->cost ?? 0, 2) }}</span>
                        </div>

                        {{-- Promo Discount --}}
                        @if($order->promoCode)
                        <div class="flex justify-between items-center bg-blue-50 px-3 py-2 rounded-md">
                            <span class="text-blue-700 font-medium">
                                Promo: {{ $order->promoCode->code }} ({{ $order->promoCode->discount_percent }}%)
                            </span>
                            <span class="text-blue-700 font-semibold">-Rs. {{ number_format($order->promoCode->discount_percent , 2) }}</span>
                        </div>
                        @endif

                        {{-- Sale Discount --}}
                        @if($sale)
                        @php
                        $saleDiscount = $sale->discount ?? 0;
                        @endphp
                        <div class="flex justify-between items-center bg-yellow-50 px-3 py-2 rounded-md">
                            <span class="text-yellow-700 font-medium">Sale Discount</span>
                            <span class="text-yellow-700 font-semibold">-Rs .{{ number_format($saleDiscount, 2) }}</span>
                        </div>
                        @endif

                        {{-- Grand Total --}}
                        <div class="border-t mt-4 pt-4 flex justify-between items-center text-lg font-bold text-gray-800">
                            <span>Grand Total</span>
                            <span>Rs. {{ number_format($order->total_amount, 2) }}</span>
                        </div>

                    </div>

                    <div class="mb-4">
                        <label for="estimated_delivery_time" class="block text-sm font-medium text-gray-700">
                            Estimated Delivery Time
                        </label>


                        {{-- Download Invoice Button --}}
                        <div class="mt-6 pt-4 border-t">
                            <a href="{{ route('admin.orders.invoice', $order->id) }}"
                                class="w-full bg-blue-500 text-white py-3 rounded-lg font-semibold hover:bg-blue-600 transition-colors flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download Invoice
                            </a>

                        </div>
                    </div>
                    <form action="{{ route('admin.orders.updateEstimatedDelivery', $order->id) }}" method="POST">
                        @csrf
                        <div class="flex items-center space-x-2 mb-4">
                            <input type="number" name="days" min="0" max="30" placeholder="Days"
                                class="border rounded p-2 w-24" value="{{ old('days', 0) }}">
                            <input type="number" name="hours" min="0" max="23" placeholder="Hours"
                                class="border rounded p-2 w-24" value="{{ old('hours', 0) }}">
                            <button type="submit"
                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">
                                Update
                            </button>
                        </div>
                    </form>

                    <div class="mb-4">
                        <p class="text-gray-500 text-sm mb-2">Estimated delivery countdown:</p>

                        <div id="remaining-timer" class="text-lg font-semibold text-green-600">
                            @if($order->estimated_delivery_time)
                            Remaining: <span id="timer-count"></span>
                            @else
                            No estimated delivery set.
                            @endif
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
    document.getElementById('update-status-btn').addEventListener('click', function() {


        const select = document.getElementById('order-status');
        const status = select.value;

        fetch("{{ route('admin.orders.updateStatus', $order->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    status: status
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message); // optional: show success toast instead
                } else {
                    alert('Something went wrong');
                }
            })
            .catch(err => console.error(err));
    });
    document.addEventListener('DOMContentLoaded', function() {
        const timerCount = document.getElementById('timer-count');
        if (!timerCount) return;

        const endTimeStr = "{{ optional($order->estimated_delivery_time)->format('Y-m-d H:i:s') ?? '' }}";

        if (!endTimeStr) {
            timerCount.innerText = "No estimated delivery set.";
            return;
        }

        let endTime = new Date(endTimeStr);

        function updateTimer() {
            const now = new Date();
            let diff = endTime - now;

            if (diff <= 0) {
                timerCount.innerText = "Delivered / Time expired";
                timerCount.classList.add('text-red-600');
                return;
            }

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            timerCount.innerText = `${days}d ${hours}h ${minutes}m ${seconds}s`;
        }

        updateTimer();
        setInterval(updateTimer, 1000);
    });
</script>
@endpush