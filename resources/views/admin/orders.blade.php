@extends('admin.layouts.master-layouts.plain')

<title>Admin | Orders</title>

@push("script")
<script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#10B981',
                        dark: '#1F2937',
                        light: '#F9FAFB',
                        pending: '#F59E0B',
                        shipped: '#3B82F6',
                        delivered: '#10B981',
                        cancelled: '#EF4444'
                    }
                }
            }
        }
    </script>
@endpush


@push("style")
<style>
        .order-row:hover {
            background-color: #f9fafb;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
        }
        .status-badge i {
            margin-right: 4px;
        }
        .action-btn {
            transition: all 0.2s ease;
        }
        .action-btn:hover {
            transform: translateY(-2px);
        }
        .order-detail-section {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
        }
        .product-image {
            border-radius: 0.5rem;
            object-fit: cover;
        }
        .flatpickr-input {
            background-color: white;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
            width: 100%;
        }
    </style>
@endpush


@section("content")
<div class="bg-gray-100 font-sans flex h-screen">

@include("admin.layouts.master-layouts.sidebar")

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="flex justify-between items-center py-4 px-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Orders Management</h2>
                    <p class="text-sm text-gray-500">View and manage customer orders</p>
                </div>
                <div class="flex items-center">
                    <div class="relative mr-4">
                        <input type="text" placeholder="Search orders..." class="border rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center action-btn">
                        <i class="fas fa-file-export mr-2"></i>
                        Export
                    </button>
                </div>
            </div>
        </header>

        <!-- Filter Section -->
        <section class="px-6 py-4 bg-white shadow-sm mt-1">
            <div class="flex flex-wrap items-center gap-4">
                <div class="w-full md:w-auto">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Order Status</label>
                    <select class="border rounded-md px-3 py-2 text-sm w-40">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                
                <div class="w-full md:w-auto">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                    <select class="border rounded-md px-3 py-2 text-sm w-40">
                        <option value="">All Payments</option>
                        <option value="paid">Paid</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                
                <div class="w-full md:w-auto">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                    <div class="flex gap-2">
                        <input type="text" id="dateFrom" class="flatpickr-input" placeholder="From Date">
                        <input type="text" id="dateTo" class="flatpickr-input" placeholder="To Date">
                    </div>
                </div>
                
                <div class="flex items-end">
                    <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm flex items-center">
                        <i class="fas fa-filter mr-2"></i>
                        Apply Filters
                    </button>
                </div>
            </div>
        </section>

        <!-- Order List -->
        <section class="p-6 flex-1 overflow-y-auto">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <!-- Order 1 -->
                            <tr class="order-row transition-all">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">ORD-001</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium">John Smith</div>
                                    <div class="text-gray-500 text-sm">+1 (555) 123-4567</div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">john.smith@example.com</td>
                                <td class="px-6 py-4 font-medium">$299.99</td>
                                <td class="px-6 py-4">
                                    <span class="status-badge bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle"></i> Paid
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="status-badge bg-pending text-white">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">Aug 12, 2023</td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <button class="text-blue-500 hover:text-blue-700 action-btn" onclick="openOrderDetail('ORD-001')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="text-gray-500 hover:text-gray-700 action-btn">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Order 2 -->
                            <tr class="order-row transition-all">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">ORD-002</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium">Sarah Johnson</div>
                                    <div class="text-gray-500 text-sm">+1 (555) 987-6543</div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">sarah.j@example.com</td>
                                <td class="px-6 py-4 font-medium">$159.99</td>
                                <td class="px-6 py-4">
                                    <span class="status-badge bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-exclamation-circle"></i> Pending
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="status-badge bg-shipped text-white">
                                        <i class="fas fa-truck"></i> Shipped
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">Aug 10, 2023</td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <button class="text-blue-500 hover:text-blue-700 action-btn" onclick="openOrderDetail('ORD-002')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="text-gray-500 hover:text-gray-700 action-btn">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Order 3 -->
                            <tr class="order-row transition-all">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">ORD-003</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium">Michael Brown</div>
                                    <div class="text-gray-500 text-sm">+1 (555) 456-7890</div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">michael.b@example.com</td>
                                <td class="px-6 py-4 font-medium">$89.99</td>
                                <td class="px-6 py-4">
                                    <span class="status-badge bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle"></i> Failed
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="status-badge bg-cancelled text-white">
                                        <i class="fas fa-ban"></i> Cancelled
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">Aug 8, 2023</td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <button class="text-blue-500 hover:text-blue-700 action-btn" onclick="openOrderDetail('ORD-003')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="text-gray-500 hover:text-gray-700 action-btn">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Order 4 -->
                            <tr class="order-row transition-all">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">ORD-004</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium">Emma Wilson</div>
                                    <div class="text-gray-500 text-sm">+1 (555) 321-6547</div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">emma.w@example.com</td>
                                <td class="px-6 py-4 font-medium">$219.99</td>
                                <td class="px-6 py-4">
                                    <span class="status-badge bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle"></i> Paid
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="status-badge bg-delivered text-white">
                                        <i class="fas fa-check-circle"></i> Delivered
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">Aug 5, 2023</td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <button class="text-blue-500 hover:text-blue-700 action-btn" onclick="openOrderDetail('ORD-004')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="text-gray-500 hover:text-gray-700 action-btn">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Order 5 -->
                            <tr class="order-row transition-all">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">ORD-005</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium">David Taylor</div>
                                    <div class="text-gray-500 text-sm">+1 (555) 789-0123</div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">david.t@example.com</td>
                                <td class="px-6 py-4 font-medium">$129.99</td>
                                <td class="px-6 py-4">
                                    <span class="status-badge bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle"></i> Paid
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="status-badge bg-shipped text-white">
                                        <i class="fas fa-truck"></i> Shipped
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">Aug 3, 2023</td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <button class="text-blue-500 hover:text-blue-700 action-btn" onclick="openOrderDetail('ORD-005')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="text-gray-500 hover:text-gray-700 action-btn">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="p-4 flex items-center justify-between border-t border-gray-200">
                    <div class="text-sm text-gray-700">
                        Showing 1 to 5 of 32 orders
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="px-3 py-1 rounded-md bg-blue-500 text-white">1</button>
                        <button class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">2</button>
                        <button class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">3</button>
                        <button class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">...</button>
                        <button class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">7</button>
                        <button class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Order Detail Modal -->
    <div id="orderDetailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Order Details - <span id="orderIdHeader">ORD-001</span></h3>
                <button class="text-gray-500 hover:text-gray-700" onclick="closeOrderDetail()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Customer Info -->
                    <div class="order-detail-section">
                        <h4 class="text-lg font-medium text-gray-800 mb-4">Customer Information</h4>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Full Name</p>
                                <p class="font-medium" id="customerName">John Smith</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email Address</p>
                                <p class="font-medium" id="customerEmail">john.smith@example.com</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Phone Number</p>
                                <p class="font-medium" id="customerPhone">+1 (555) 123-4567</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Shipping Address</p>
                                <p class="font-medium" id="customerAddress">123 Main Street, Apt 4B, New York, NY 10001, United States</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Info -->
                    <div class="order-detail-section">
                        <h4 class="text-lg font-medium text-gray-800 mb-4">Order Information</h4>
                        <div class="space-y-3">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Order ID</p>
                                    <p class="font-medium" id="orderId">ORD-001</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Order Date</p>
                                    <p class="font-medium" id="orderDate">Aug 12, 2023 at 10:30 AM</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Payment Method</p>
                                    <p class="font-medium" id="paymentMethod">Credit Card</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Payment Status</p>
                                    <p class="font-medium">
                                        <span class="status-badge bg-green-100 text-green-800" id="paymentStatus">
                                            <i class="fas fa-check-circle"></i> Paid
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Order Notes</p>
                                <p class="font-medium italic" id="orderNotes">Please deliver after 5 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Order Items -->
                <div class="order-detail-section mb-6">
                    <h4 class="text-lg font-medium text-gray-800 mb-4">Order Items</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Product</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Price</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Quantity</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="orderItems">
                                <!-- Items will be populated by JS -->
                            </tbody>
                            <tfoot class="border-t border-gray-200">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right font-medium">Subtotal</td>
                                    <td class="px-4 py-3 font-medium" id="subtotal">$299.99</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right font-medium">Shipping</td>
                                    <td class="px-4 py-3 font-medium" id="shipping">$9.99</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right font-medium">Tax</td>
                                    <td class="px-4 py-3 font-medium" id="tax">$21.99</td>
                                </tr>
                                <tr class="border-t border-gray-200">
                                    <td colspan="3" class="px-4 py-3 text-right font-medium text-lg">Total</td>
                                    <td class="px-4 py-3 font-medium text-lg text-blue-600" id="orderTotal">$331.97</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                
                <!-- Order Status Update -->
                <div class="order-detail-section">
                    <h4 class="text-lg font-medium text-gray-800 mb-4">Update Order Status</h4>
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex-1">
                            <select id="orderStatus" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="pending">Pending</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                                Update Status
                            </button>
                        </div>
                        <div>
                            <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">
                                <i class="fas fa-print mr-2"></i> Print Invoice
                            </button>
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
        // Initialize date pickers
        flatpickr("#dateFrom", {
            dateFormat: "M j, Y",
            allowInput: true
        });
        
        flatpickr("#dateTo", {
            dateFormat: "M j, Y",
            allowInput: true
        });
        
        // Order data for the modal
        const orders = {
            "ORD-001": {
                customerName: "John Smith",
                email: "john.smith@example.com",
                phone: "+1 (555) 123-4567",
                address: "123 Main Street, Apt 4B, New York, NY 10001, United States",
                orderDate: "Aug 12, 2023 at 10:30 AM",
                paymentMethod: "Credit Card",
                paymentStatus: "paid",
                orderNotes: "Please deliver after 5 PM",
                items: [
                    {
                        name: "Premium Smart Watch",
                        price: 249.99,
                        quantity: 1,
                        image: "https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1999&q=80"
                    },
                    {
                        name: "Wireless Charging Pad",
                        price: 49.99,
                        quantity: 1,
                        image: "https://images.unsplash.com/photo-1606220588911-4561d4a0af5a?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2070&q=80"
                    }
                ],
                subtotal: 299.98,
                shipping: 9.99,
                tax: 21.99,
                total: 331.96
            },
            "ORD-002": {
                customerName: "Sarah Johnson",
                email: "sarah.j@example.com",
                phone: "+1 (555) 987-6543",
                address: "456 Park Avenue, Suite 1200, Los Angeles, CA 90001, United States",
                orderDate: "Aug 10, 2023 at 2:15 PM",
                paymentMethod: "PayPal",
                paymentStatus: "pending",
                orderNotes: "Leave at front desk if not home",
                items: [
                    {
                        name: "Wireless Headphones Pro",
                        price: 159.99,
                        quantity: 1,
                        image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2070&q=80"
                    }
                ],
                subtotal: 159.99,
                shipping: 0.00,
                tax: 11.20,
                total: 171.19
            },
            "ORD-003": {
                customerName: "Michael Brown",
                email: "michael.b@example.com",
                phone: "+1 (555) 456-7890",
                address: "789 Oak Street, Chicago, IL 60601, United States",
                orderDate: "Aug 8, 2023 at 9:45 AM",
                paymentMethod: "Credit Card",
                paymentStatus: "failed",
                orderNotes: "",
                items: [
                    {
                        name: "Bluetooth Earpods",
                        price: 89.99,
                        quantity: 1,
                        image: "https://images.unsplash.com/photo-1590658268037-6bf12165a8df?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2070&q=80"
                    }
                ],
                subtotal: 89.99,
                shipping: 4.99,
                tax: 6.30,
                total: 101.28
            },
            "ORD-004": {
                customerName: "Emma Wilson",
                email: "emma.w@example.com",
                phone: "+1 (555) 321-6547",
                address: "101 Pine Road, Miami, FL 33101, United States",
                orderDate: "Aug 5, 2023 at 4:20 PM",
                paymentMethod: "JazzCash",
                paymentStatus: "paid",
                orderNotes: "Gift wrapping required",
                items: [
                    {
                        name: "Noise Cancelling Headphones",
                        price: 219.99,
                        quantity: 1,
                        image: "https://images.unsplash.com/photo-1572569511254-d8f925fe2cbb?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2189&q=80"
                    },
                    {
                        name: "Leather Headphone Case",
                        price: 29.99,
                        quantity: 1,
                        image: "https://images.unsplash.com/photo-1606220588911-4561d4a0af5a?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2070&q=80"
                    }
                ],
                subtotal: 249.98,
                shipping: 12.99,
                tax: 17.50,
                total: 280.47
            },
            "ORD-005": {
                customerName: "David Taylor",
                email: "david.t@example.com",
                phone: "+1 (555) 789-0123",
                address: "222 Maple Avenue, Seattle, WA 98101, United States",
                orderDate: "Aug 3, 2023 at 11:10 AM",
                paymentMethod: "EasyPaisa",
                paymentStatus: "paid",
                orderNotes: "",
                items: [
                    {
                        name: "Fitness Tracker Pro",
                        price: 129.99,
                        quantity: 1,
                        image: "https://images.unsplash.com/photo-1546868871-7041f2a55e12?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2070&q=80"
                    }
                ],
                subtotal: 129.99,
                shipping: 5.99,
                tax: 9.10,
                total: 145.08
            }
        };

        function openOrderDetail(orderId) {
            const order = orders[orderId];
            if (!order) return;
            
            // Update header
            document.getElementById('orderIdHeader').textContent = orderId;
            
            // Update customer info
            document.getElementById('customerName').textContent = order.customerName;
            document.getElementById('customerEmail').textContent = order.email;
            document.getElementById('customerPhone').textContent = order.phone;
            document.getElementById('customerAddress').textContent = order.address;
            
            // Update order info
            document.getElementById('orderId').textContent = orderId;
            document.getElementById('orderDate').textContent = order.orderDate;
            document.getElementById('paymentMethod').textContent = order.paymentMethod;
            document.getElementById('orderNotes').textContent = order.orderNotes || "No notes";
            
            // Update payment status
            const paymentStatus = document.getElementById('paymentStatus');
            paymentStatus.className = "status-badge ";
            if (order.paymentStatus === "paid") {
                paymentStatus.classList.add("bg-green-100", "text-green-800");
                paymentStatus.innerHTML = '<i class="fas fa-check-circle"></i> Paid';
            } else if (order.paymentStatus === "pending") {
                paymentStatus.classList.add("bg-yellow-100", "text-yellow-800");
                paymentStatus.innerHTML = '<i class="fas fa-exclamation-circle"></i> Pending';
            } else {
                paymentStatus.classList.add("bg-red-100", "text-red-800");
                paymentStatus.innerHTML = '<i class="fas fa-times-circle"></i> Failed';
            }
            
            // Update order items
            const orderItems = document.getElementById('orderItems');
            orderItems.innerHTML = "";
            
            order.items.forEach(item => {
                const row = document.createElement('tr');
                row.className = "border-b border-gray-100";
                row.innerHTML = `
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            <img src="${item.image}" class="w-12 h-12 product-image mr-3" alt="${item.name}">
                            <div>
                                <div class="font-medium">${item.name}</div>
                                <div class="text-gray-500 text-sm">SKU: ${item.name.substring(0, 3).toUpperCase()}-${Math.floor(Math.random() * 1000)}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">$${item.price.toFixed(2)}</td>
                    <td class="px-4 py-3">${item.quantity}</td>
                    <td class="px-4 py-3 font-medium">$${(item.price * item.quantity).toFixed(2)}</td>
                `;
                orderItems.appendChild(row);
            });
            
            // Update totals
            document.getElementById('subtotal').textContent = `$${order.subtotal.toFixed(2)}`;
            document.getElementById('shipping').textContent = `$${order.shipping.toFixed(2)}`;
            document.getElementById('tax').textContent = `$${order.tax.toFixed(2)}`;
            document.getElementById('orderTotal').textContent = `$${order.total.toFixed(2)}`;
            
            // Show modal
            document.getElementById('orderDetailModal').classList.remove('hidden');
        }

        function closeOrderDetail() {
            document.getElementById('orderDetailModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('orderDetailModal');
            if (event.target === modal) {
                closeOrderDetail();
            }
        }
    </script>
@endpush