@extends("user.layouts.master-layouts.plain")

@section('title', 'Track Order | Luxorix')






@section("content")
<div class="font-poppins text-light min-h-screen">
    <!-- Main Container -->
    <div class="min-h-screen flex flex-col justify-start items-center py-12 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <header class="text-center mb-12 w-full max-w-4xl">
            <h1 class="text-3xl sm:text-5xl font-bold tracking-tight mb-4 section-title">
                <span class="gradient-text">TRACK YOUR ORDER</span>
            </h1>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                Enter your tracking code and email or phone to view your order details and status.
            </p>
        </header>

        <!-- Main Content -->
        <main class="w-full max-w-4xl">
            <!-- Tracking Form Card -->
            <div class="card w-full mb-10 p-8 md:p-10 fade-in">
                <form id="trackingForm" class="space-y-6">
                    <!-- Tracking Code -->
                    <div>
                        <label for="trackingCode" class="block text-base font-medium text-gray-300 mb-3">
                            <i class="fas fa-barcode mr-2"></i>Tracking Code
                        </label>
                        <div class="relative group">
                            <input type="text" id="trackingCode" placeholder="Enter your tracking code"
                                class="w-full px-5 py-4 rounded-xl input-field text-white placeholder-gray-500 text-lg"
                                required>
                            <div class="absolute right-5 top-4 text-gray-500 group-focus-within:text-accent-cyan transition-colors duration-300">
                                <i class="fas fa-search text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Email or Phone -->
                    <div>
                        <label for="emailOrPhone" class="block text-base font-medium text-gray-300 mb-3">
                            <i class="fas fa-user-circle mr-2"></i>Email or Phone
                        </label>
                        <div class="relative group">
                            <input type="text" id="emailOrPhone" placeholder="Enter your email or phone number"
                                class="w-full px-5 py-4 rounded-xl input-field text-white placeholder-gray-500 text-lg"
                                required>
                            <div class="absolute right-5 top-4 text-gray-500 group-focus-within:text-accent-cyan transition-colors duration-300">
                                <i class="fas fa-envelope text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full py-4 btn-primary text-white font-semibold rounded-xl 
                               flex items-center justify-center gap-3 text-lg mt-8">
                        <i class="fas fa-search-location"></i>
                        Track Order
                    </button>

                    <!-- Help Link -->
                    <div class="text-center pt-4">
                        <a href="#" class="text-accent-cyan hover:text-accent-purple transition-colors duration-300 text-base flex items-center justify-center gap-2">
                            <i class="fas fa-question-circle"></i>
                            Need help finding your tracking code?
                        </a>
                    </div>
                </form>
            </div>

            <!-- Loader -->
            <div id="loader" class="hidden text-center p-8 card fade-in">
                <div class="flex flex-col items-center justify-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-accent-cyan mb-4"></div>
                    <h3 class="text-xl font-semibold text-gray-300 mb-2">Loading Order Details</h3>
                    <p class="text-gray-400">Please wait while we retrieve your order information...</p>
                </div>
            </div>

            <!-- No Order Message -->
            <div id="noOrderMessage" class="hidden text-center p-8 card fade-in">
                <div class="flex flex-col items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-error text-5xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-error mb-2">Order Not Found</h3>
                    <p class="text-gray-400 max-w-md">No order found with the provided details. Please check your tracking code and contact information, then try again.</p>
                </div>
            </div>

            <!-- Results Section -->
            <div id="resultsSection" class="hidden space-y-8 fade-in">
                <!-- Order Summary Card -->
                <div class="card p-8 relative overflow-hidden">
                    <!-- Status Badge -->
                    <div id="order-status-badge" class="absolute top-6 right-6 status-badge z-10">
                        -
                    </div>

                    <!-- Decorative elements -->
                    <div class="absolute top-0 right-0 w-40 h-40 bg-accent-cyan/5 rounded-full -translate-y-20 translate-x-20"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-accent-purple/5 rounded-full translate-y-16 -translate-x-16"></div>

                    <div class="flex items-center gap-4 mb-8 relative z-10">
                        <div class="icon-circle">
                            <i class="fas fa-receipt text-white text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold section-title text-white">Order Summary</h2>
                    </div>

                    <div class="grid lg:grid-cols-2 gap-8 text-gray-200 relative z-10">
                        <div class="space-y-6">
                            <div class="flex justify-between items-center pb-4 border-b border-gray-700/50">
                                <span class="font-semibold text-gray-300 text-lg">Order Code:</span>
                                <span id="order-code" class="font-mono text-accent-cyan text-lg font-bold">-</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-700/50">
                                <span class="font-semibold text-gray-300 text-lg">Order Date:</span>
                                <span id="order-date" class="text-white text-lg">-</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-700/50">
                                <span class="font-semibold text-gray-300 text-lg">Payment Method:</span>
                                <span id="order-payment" class="font-medium text-accent-cyan text-lg">-</span>
                            </div>
                        </div>

                        <div class="space-y-6 bg-primary-medium/50 rounded-xl p-6 border border-gray-700/50">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-300">Subtotal:</span>
                                <span id="order-subtotal" class="text-white">-</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-300">Shipping:</span>
                                <span id="order-shipping" class="text-white">-</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-300">Tax:</span>
                                <span id="order-tax" class="text-white">-</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-300">Promo Discount:</span>
                                <span id="promo-code-discount" class="text-success">-</span>
                            </div>
                            <div class="flex justify-between items-center pt-4 border-t border-gray-700/50">
                                <span class="font-semibold text-white text-xl">Grand Total:</span>
                                <span id="order-grand-total" class="text-2xl font-bold gradient-text">-</span>
                                <span id="order-total" class="hidden"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items Card -->
                <div class="card p-8 relative overflow-hidden">
                    <!-- Decorative elements -->
                    <div class="absolute top-0 left-0 w-32 h-32 bg-accent-pink/5 rounded-full -translate-y-16 -translate-x-16"></div>

                    <div class="flex items-center gap-4 mb-8 relative z-10">
                        <div class="icon-circle">
                            <i class="fas fa-box text-white text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold section-title text-white">
                            Order Items (<span id="items-count">0</span>)
                        </h2>
                    </div>

                    <div id="order-items" class="divide-y divide-gray-700/50 relative z-10">
                        <!-- Items will be dynamically inserted here -->
                        <div class="text-center py-10 text-gray-400">
                            <i class="fas fa-shopping-basket text-5xl mb-4 opacity-50"></i>
                            <p class="text-lg">No items to display</p>
                        </div>
                    </div>
                </div>

                <!-- Delivery Info Card -->
                <div class="card p-8 relative overflow-hidden">
                    <!-- Decorative elements -->
                    <div class="absolute bottom-0 right-0 w-28 h-28 bg-accent-cyan/5 rounded-full translate-y-14 translate-x-14"></div>

                    <div class="flex items-center gap-4 mb-8 relative z-10">
                        <div class="icon-circle">
                            <i class="fas fa-truck text-white text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold section-title text-white">Delivery Information</h2>
                    </div>

                    <div class="grid md:grid-cols-2 gap-8 text-gray-200 relative z-10">
                        <div class="space-y-6">
                            <div class="flex justify-between items-center pb-4 border-b border-gray-700/50">
                                <span class="font-semibold text-gray-300 text-lg">Estimate:</span>
                                <span id="delivery-estimate" class="text-white text-lg">-</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-300 text-lg">Shipping Method:</span>
                                <span id="shipping-method" class="text-white text-lg">-</span>
                            </div>
                        </div>

                        <div class="bg-primary-medium/50 rounded-xl p-6 border border-gray-700/50">
                            <span class="font-semibold text-gray-300 text-lg block mb-3">Shipping Address:</span>
                            <span id="shipping-address" class="text-white text-lg">-</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8">
                    <button id="downloadInvoiceBtn" class="hidden px-8 py-4 rounded-xl btn-primary text-white font-semibold transition-all flex items-center gap-3 justify-center text-lg">
                        <i class="fas fa-download"></i> Download Invoice
                    </button>

                    <button id="cancelButton" class="hidden px-8 py-4 rounded-xl bg-gradient-to-r from-error to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold transition-all flex items-center gap-3 justify-center text-lg">
                        <i class="fas fa-times-circle"></i> Cancel Order
                    </button>

                    <button id="reorderButton" class="hidden px-8 py-4 rounded-xl btn-primary text-white font-semibold transition-all flex items-center gap-3 justify-center text-lg">
                        <i class="fas fa-redo-alt"></i> Reorder
                    </button>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Cancel Order Modal -->
<div id="cancelModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 modal-overlay fade-in">
    <div class="card max-w-md w-full p-6">
        <div class="flex items-center gap-3 mb-6">
            <i class="fas fa-exclamation-triangle text-warning text-2xl"></i>
            <h3 class="text-2xl font-bold font-rajdhani">Cancel Order</h3>
        </div>

        <p class="text-gray-300 mb-6 text-lg">Are you sure you want to cancel this order? Please select a reason:</p>

        <div class="space-y-4 mb-6">
            <select id="cancelReason" class="w-full p-4 rounded-xl bg-primary-medium border border-gray-700 text-light text-lg" required>
                <option value="">Select a reason</option>
                <option value="changed-mind">Changed my mind</option>
                <option value="wrong-item">Ordered wrong item</option>
                <option value="duplicate">Duplicate order</option>
                <option value="shipping-delay">Shipping takes too long</option>
                <option value="other">Other</option>
            </select>

            <textarea id="cancelComment" rows="3" class="w-full p-4 rounded-xl bg-primary-medium border border-gray-700 text-light text-lg" placeholder="Additional comments (optional)"></textarea>
        </div>

        <div class="flex justify-end gap-4">
            <button id="closeModal" class="px-6 py-3 btn-secondary text-white rounded-xl transition-colors text-lg">Close</button>
            <button id="confirmCancel" class="px-6 py-3 bg-error hover:bg-error/90 text-white rounded-xl transition-colors text-lg">Confirm Cancel</button>
        </div>
    </div>
</div>
@endsection

@push("script")
<meta name="order-cancellation-url" content="{{ route('order.cancellation') }}">
@vite('resources/js/user/customer-support/order-tracking.js')
@endpush