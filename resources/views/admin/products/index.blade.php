@extends('admin.layouts.master-layouts.plain')

@section('title', 'Product Management | Luxorix | Admin Panel')

@push('style')
<style>
    /* Responsive Design */
    @media (max-width: 767px) {
        .mobile-card {
            display: block;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
            padding: 1.25rem;
            background: white;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .mobile-card-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .mobile-card-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        
        .mobile-label {
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            font-size: 0.75rem;
            flex: 1;
        }
        
        .mobile-value {
            flex: 1.5;
            text-align: right;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 0.5rem;
        }
        
        .product-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f3f4f6;
            margin-bottom: 0.75rem;
        }
        
        .desktop-table {
            display: none;
        }

        .mobile-table {
            display: block;
        }

        .image-preview-container {
            position: relative;
            display: inline-block;
            margin: 0.5rem;
        }

        .image-preview-remove {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
            z-index: 10;
        }

        .image-sortable-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            padding: 0.75rem;
            background: #f9fafb;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            flex-wrap: wrap;
        }

        .image-sort-handle {
            cursor: grab;
            color: #6b7280;
        }

        /* Modal adjustments for mobile */
        #productModal .inline-block {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
        }

        #productModal .sm\:max-w-6xl {
            max-width: 95%;
            margin: 0.5rem;
        }

        #productModal .align-bottom {
            align-items: flex-end;
        }

        #productModal .sm\:p-6 {
            padding: 1rem;
        }

        #productModal .grid {
            grid-template-columns: 1fr !important;
            gap: 1rem !important;
        }

        .tab-button {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            white-space: nowrap;
        }

        .spec-item {
            grid-template-columns: 1fr !important;
            gap: 0.5rem !important;
        }

        /* Image upload areas */
        .image-upload-area {
            padding: 1rem !important;
            margin-bottom: 1rem;
        }

        .image-upload-area svg {
            height: 2rem !important;
            width: 2rem !important;
        }

        .image-upload-area p {
            font-size: 0.75rem !important;
        }
    }
    
    @media (min-width: 768px) {
        .mobile-table {
            display: none;
        }

        .desktop-table {
            display: block;
        }

        .mobile-card {
            display: none;
        }

        .image-sortable-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            padding: 1rem;
            background: #f9fafb;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
        }

        .image-sort-handle {
            cursor: grab;
            color: #6b7280;
        }

        .image-preview-container {
            position: relative;
            display: inline-block;
            margin: 0.5rem;
        }

        .image-preview-remove {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
            z-index: 10;
        }
    }

    /* Modal responsiveness */
    @media (max-width: 640px) {
        #productModal .sm\:align-middle {
            align-items: flex-start;
        }

        #productModal .sm\:my-8 {
            margin-top: 1rem;
            margin-bottom: 1rem;
        }

        #productModal .text-center {
            text-align: left;
        }

        #productModal .sm\:ml-4 {
            margin-left: 0;
        }
    }

    /* Analytics responsive grid */
    .analytics-grid {
        display: grid;
        gap: 1.5rem;
    }
    
    @media (max-width: 640px) {
        .analytics-grid {
            grid-template-columns: repeat(1, 1fr);
        }
        
        .filter-grid {
            grid-template-columns: repeat(1, 1fr) !important;
        }
    }
    
    @media (min-width: 641px) and (max-width: 1024px) {
        .analytics-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-grid {
            grid-template-columns: repeat(6, 1fr) !important;
        }
        
        #category {
            grid-column: span 3;
        }
        
        #status, #stock {
            grid-column: span 2;
        }
        
        #applyFilters {
            grid-column: span 1;
        }
    }
    
    @media (min-width: 1025px) {
        .analytics-grid {
            grid-template-columns: repeat(4, 1fr);
        }

        .filter-grid {
            grid-template-columns: repeat(12, 1fr) !important;
        }
    }

    /* Loading animation */
    .loading-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid #e5e7eb;
        border-top: 2px solid #3b82f6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Modal animations */
    .modal-enter {
        animation: modalEnter 0.3s ease-out;
    }
    
    @keyframes modalEnter {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Analytics cards */
    .analytics-card {
        transition: all 0.3s ease;
        min-height: 120px;
    }
    
    .analytics-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    /* Toast notifications */
    .toast {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 9999;
        animation: slideIn 0.3s ease-out;
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Filter section responsive */
    .filter-grid {
        display: grid;
        gap: 1rem;
        align-items: end;
    }

    /* Specs input styling */
    .specs-container {
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        padding: 1rem;
        background: #f9fafb;
    }
    
    .spec-item {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 1rem;
        margin-bottom: 0.75rem;
        align-items: center;
    }
    
    @media (max-width: 640px) {
        .spec-item {
            grid-template-columns: 1fr;
        }
    }
    
    /* Tab styling */
    .tab {
        display: none;
        animation: fadeIn 0.3s ease;
    }
    
    .tab.active {
        display: block;
    }
    
    .tab-button {
        padding: 0.75rem 1.5rem;
        border: none;
        background: none;
        color: #6b7280;
        font-weight: 500;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        transition: all 0.3s ease;
        white-space: nowrap;
    }
    
    .tab-button.active {
        color: #3b82f6;
        border-bottom-color: #3b82f6;
    }
    
    .tab-nav {
        display: flex;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .tab-nav::-webkit-scrollbar {
        display: none;
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

    /* Form responsiveness */
    .form-grid {
        display: grid;
        gap: 1rem;
    }
    
    @media (min-width: 768px) {
        .form-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .form-grid-full {
            grid-template-columns: 1fr;
        }
        
        .form-grid-3 {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* Image upload area */
    .image-upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: #f9fafb;
    }
    
    .image-upload-area:hover {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    
    .image-upload-area svg {
        height: 3rem;
        width: 3rem;
        color: #9ca3af;
        margin: 0 auto 1rem;
    }

    /* Button group responsiveness */
    .button-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    @media (min-width: 640px) {
        .button-group {
            flex-direction: row;
            justify-content: flex-end;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Product Management</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage your products inventory and stock levels</p>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <button onclick="showCreateModal()" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full sm:w-auto">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Product
                    </button>
                    @include('admin.components.dark-mode.dark-toggle')
                </div>
            </div>
        </div>
    </header>

    <!-- Analytics -->
    <div class="px-4 mt-6 sm:px-6 lg:px-8">
        <div class="analytics-grid">
            <!-- Analytics cards will be loaded here -->
        </div>
    </div>

    <!-- Filters -->
    <div class="px-4 mt-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="filter-grid">
                    <div class="sm:col-span-12 md:col-span-4">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" id="search" name="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Search products...">
                        </div>
                    </div>
                    
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select id="category" name="category" class="block w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">All Categories</option>
                            <!-- Categories will be loaded here -->
                        </select>
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" name="status" class="block w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stock</label>
                        <select id="stock" name="stock" class="block w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">All</option>
                            <option value="in-stock">In Stock</option>
                            <option value="low-stock">Low Stock</option>
                            <option value="out-of-stock">Out of Stock</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button id="applyFilters" class="w-full py-2 px-4 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="px-4 mt-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <!-- Desktop Table -->
            <div class="desktop-table overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Product
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Category
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Price
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stock
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sort Order
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="productsTable" class="bg-white divide-y divide-gray-200">
                        <!-- Products will be loaded here -->
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div id="mobileProducts" class="mobile-table p-4">
                <!-- Mobile cards will be loaded here -->
            </div>

            <!-- Loading State -->
            <div id="loadingState" class="p-12 text-center">
                <div class="inline-block loading-spinner"></div>
                <p class="mt-2 text-sm text-gray-500">Loading products...</p>
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="hidden p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new product.</p>
                <div class="mt-6">
                    <button onclick="showCreateModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Product
                    </button>
                </div>
            </div>

            <!-- Pagination -->
            <div id="paginationContainer" class="hidden px-4 py-4 bg-white border-t border-gray-200 sm:px-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div id="paginationInfo" class="text-sm text-gray-700"></div>
                    <div id="paginationLinks" class="flex gap-2"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="productModal" class="hidden fixed z-50 inset-0 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="hideModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full w-full mx-auto">
            <form id="productForm" enctype="multipart/form-data" class="bg-white">
                @csrf
                <input type="hidden" id="productId" name="id">
                <input type="hidden" id="formMethod" name="_method" value="POST">
                
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <div class="flex justify-between items-center mb-6">
                                <h3 id="modalTitle" class="text-lg leading-6 font-medium text-gray-900">Add New Product</h3>
                                <button type="button" onclick="hideModal()" class="text-gray-400 hover:text-gray-500 sm:hidden">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Tabs -->
                            <div class="border-b border-gray-200 mb-6">
                                <nav class="tab-nav" aria-label="Tabs">
                                    <button type="button" class="tab-button active" data-tab="basic">Basic Info</button>
                                    <button type="button" class="tab-button" data-tab="details">Product Details</button>
                                    <button type="button" class="tab-button" data-tab="images">Images</button>
                                    <button type="button" class="tab-button" data-tab="advanced">Advanced</button>
                                </nav>
                            </div>

                            <!-- Tab Contents -->
                            <div class="tab-content">
                                <!-- Basic Information Tab -->
                                <div id="tab-basic" class="tab active space-y-6">
                                    <div class="form-grid">
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                                            <input type="text" name="name" id="name" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                            <div id="nameError" class="text-red-500 text-xs mt-1 hidden"></div>
                                        </div>
                                        
                                        <div>
                                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Product Title</label>
                                            <input type="text" name="title" id="title" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        </div>
                                    </div>

                                    <div class="form-grid">
                                        <div>
                                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                                            <select name="category_id" id="category_id" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                                <option value="">Select Category</option>
                                            </select>
                                            <div id="categoryError" class="text-red-500 text-xs mt-1 hidden"></div>
                                        </div>
                                        
                                        <div>
                                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order *</label>
                                            <input type="number" name="sort_order" id="sort_order" min="1" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                            <div id="sortOrderError" class="text-red-500 text-xs mt-1 hidden"></div>
                                            <p class="mt-1 text-xs text-gray-500">Must be unique within category</p>
                                        </div>
                                    </div>

                                    <div class="form-grid form-grid-3">
                                        <div>
                                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (PKR) *</label>
                                            <input type="number" name="price" id="price" step="0.01" min="0" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                            <div id="priceError" class="text-red-500 text-xs mt-1 hidden"></div>
                                        </div>
                                        
                                        <div>
                                            <label for="cut_price" class="block text-sm font-medium text-gray-700 mb-2">Discounted Price (PKR)</label>
                                            <input type="number" name="cut_price" id="cut_price" step="0.01" min="0" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        </div>
                                        
                                        <div>
                                            <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                                            <input type="number" name="stock_quantity" id="stock_quantity" min="0" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="0">
                                        </div>
                                    </div>

                                    <div class="form-grid">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                            <div class="flex space-x-4">
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="status" value="active" class="text-blue-600 focus:ring-blue-500" checked>
                                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="status" value="inactive" class="text-blue-600 focus:ring-blue-500">
                                                    <span class="ml-2 text-sm text-gray-700">Inactive</span>
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Rating (0-5)</label>
                                            <input type="number" name="rating" id="rating" min="0" max="5" step="0.1" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        </div>
                                    </div>

                                    <div class="form-grid-full">
                                        <div>
                                            <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                                            <input type="text" name="tags" id="tags" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Separate tags with commas">
                                        </div>
                                    </div>

                                    <div class="form-grid-full">
                                        <div>
                                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                                            <textarea name="description" id="description" rows="4" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required></textarea>
                                            <div id="descriptionError" class="text-red-500 text-xs mt-1 hidden"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Details Tab -->
                                <div id="tab-details" class="tab space-y-6">
                                    <div class="form-grid">
                                        <div>
                                            <label for="model_name" class="block text-sm font-medium text-gray-700 mb-2">Model Name</label>
                                            <input type="text" name="model_name" id="model_name" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        </div>
                                        
                                        <div>
                                            <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-2">Reference Number</label>
                                            <input type="text" name="reference_number" id="reference_number" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        </div>
                                    </div>

                                    <div class="form-grid-full">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Specifications</label>
                                        <div class="specs-container">
                                            <div id="specsList">
                                                <!-- Specs will be added here -->
                                            </div>
                                            <button type="button" onclick="addSpecField()" class="mt-4 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Add Specification
                                            </button>
                                        </div>
                                    </div>

                                    <div class="form-grid-full">
                                        <div>
                                            <label for="detailed_description" class="block text-sm font-medium text-gray-700 mb-2">Detailed Description</label>
                                            <textarea name="detailed_description" id="detailed_description" rows="6" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Images Tab -->
                                <div id="tab-images" class="tab space-y-6">
                                    <!-- Main Image -->
                                    <div class="form-grid-full">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Main Image *</label>
                                        <div id="mainImageContainer" class="mb-4 flex flex-wrap">
                                            <!-- Existing main image will be shown here -->
                                        </div>
                                        <div class="image-upload-area" onclick="document.getElementById('main_image').click()">
                                            <div class="space-y-1">
                                                <svg class="mx-auto" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                                <div class="flex text-sm text-gray-600 justify-center">
                                                    <span class="relative font-medium text-blue-600 hover:text-blue-500">
                                                        Upload Main Image
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 5MB</p>
                                            </div>
                                        </div>
                                        <input id="main_image" name="main_image" type="file" class="hidden" accept="image/*">
                                    </div>

                                    <!-- Sub Image -->
                                    <div class="form-grid-full">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Sub Image</label>
                                        <div id="subImageContainer" class="mb-4 flex flex-wrap">
                                            <!-- Existing sub image will be shown here -->
                                        </div>
                                        <div class="image-upload-area" onclick="document.getElementById('sub_image').click()">
                                            <div class="space-y-1">
                                                <svg class="mx-auto" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                                <div class="flex text-sm text-gray-600 justify-center">
                                                    <span class="relative font-medium text-blue-600 hover:text-blue-500">
                                                        Upload Sub Image
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 5MB</p>
                                            </div>
                                        </div>
                                        <input id="sub_image" name="sub_image" type="file" class="hidden" accept="image/*">
                                    </div>

                                    <!-- Gallery Images -->
                                    <div class="form-grid-full">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Images</label>
                                        <div id="galleryImagesContainer" class="mb-4">
                                            <!-- Existing gallery images will be shown here -->
                                        </div>
                                        <div class="image-upload-area" onclick="document.getElementById('gallery_images').click()">
                                            <div class="space-y-1">
                                                <svg class="mx-auto" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                                <div class="flex text-sm text-gray-600 justify-center">
                                                    <span class="relative font-medium text-blue-600 hover:text-blue-500">
                                                        Upload Gallery Images
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-500">Multiple images allowed</p>
                                            </div>
                                        </div>
                                        <input id="gallery_images" name="gallery_images[]" type="file" class="hidden" multiple accept="image/*">
                                        <input type="hidden" name="gallery_images_sort[]" id="galleryImagesSort">
                                    </div>

                                    <!-- Desktop Detail Images -->
                                    <div class="form-grid-full">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Desktop Detail Images</label>
                                        <div id="desktopDetailImagesContainer" class="mb-4">
                                            <!-- Existing desktop detail images will be shown here -->
                                        </div>
                                        <div class="image-upload-area" onclick="document.getElementById('desktop_detail_images').click()">
                                            <div class="space-y-1">
                                                <svg class="mx-auto" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                                <div class="flex text-sm text-gray-600 justify-center">
                                                    <span class="relative font-medium text-blue-600 hover:text-blue-500">
                                                        Upload Desktop Detail Images
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-500">Multiple images allowed</p>
                                            </div>
                                        </div>
                                        <input id="desktop_detail_images" name="desktop_detail_images[]" type="file" class="hidden" multiple accept="image/*">
                                        <input type="hidden" name="desktop_detail_images_sort[]" id="desktopDetailImagesSort">
                                    </div>

                                    <!-- Mobile Detail Images -->
                                    <div class="form-grid-full">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Detail Images</label>
                                        <div id="mobileDetailImagesContainer" class="mb-4">
                                            <!-- Existing mobile detail images will be shown here -->
                                        </div>
                                        <div class="image-upload-area" onclick="document.getElementById('mobile_detail_images').click()">
                                            <div class="space-y-1">
                                                <svg class="mx-auto" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                                <div class="flex text-sm text-gray-600 justify-center">
                                                    <span class="relative font-medium text-blue-600 hover:text-blue-500">
                                                        Upload Mobile Detail Images
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-500">Multiple images allowed</p>
                                            </div>
                                        </div>
                                        <input id="mobile_detail_images" name="mobile_detail_images[]" type="file" class="hidden" multiple accept="image/*">
                                        <input type="hidden" name="mobile_detail_images_sort[]" id="mobileDetailImagesSort">
                                    </div>
                                </div>

                                <!-- Advanced Tab -->
                                <div id="tab-advanced" class="tab space-y-6">
                                    <div class="form-grid-full">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Product Flags</label>
                                        <div class="flex flex-wrap gap-4">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="is_top_selling" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm text-gray-700">Top Selling</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="is_new_arrival" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm text-gray-700">New Arrival</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="is_feature_card" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm text-gray-700">Featured</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 button-group">
                    <button type="submit" id="submitBtn" class="w-full sm:w-auto inline-flex justify-center items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                        <span id="submitText">Create Product</span>
                        <span id="submitSpinner" class="hidden ml-2 loading-spinner"></span>
                    </button>
                    <button type="button" onclick="hideModal()" class="w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed z-50 inset-0 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="hideDeleteModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full w-full mx-auto">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.791-.833-2.561 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Product</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Are you sure you want to delete this product? This action cannot be undone.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 button-group">
                <button type="button" id="confirmDelete" class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                    Delete
                </button>
                <button type="button" onclick="hideDeleteModal()" class="w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="hidden fixed top-4 right-4 z-50 max-w-sm w-full sm:w-auto"></div>

<script>
    // Global state
    let currentState = {
        search: '',
        category: '',
        status: '',
        stock: '',
        sort_by: 'created_at',
        sort_dir: 'desc',
        page: 1
    };


    
    let deleteProductId = null;
    let deletedImageIds = [];
    let specsData = [];
    if (!Array.isArray(specsData)) {
    console.warn('specsData was an Object/Null. converting to Array...');
    if (specsData && typeof specsData === 'object') {
        specsData = Object.values(specsData); 
    } else {
        specsData = []; 
    }
}

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadCategories();
        loadAnalytics();
        loadProducts();
        setupEventListeners();
        initializeTabs();
    });

    // Setup event listeners
    function setupEventListeners() {
        // Search input with debounce
        let searchTimeout;
        document.getElementById('search').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentState.search = e.target.value;
                currentState.page = 1;
                loadProducts();
            }, 500);
        });

        // Filter selectors
        document.getElementById('category').addEventListener('change', function(e) {
            currentState.category = e.target.value;
            currentState.page = 1;
            loadProducts();
        });

        document.getElementById('status').addEventListener('change', function(e) {
            currentState.status = e.target.value;
            currentState.page = 1;
            loadProducts();
        });

        document.getElementById('stock').addEventListener('change', function(e) {
            currentState.stock = e.target.value;
            currentState.page = 1;
            loadProducts();
        });

        // Apply filters button
        document.getElementById('applyFilters').addEventListener('click', function() {
            currentState.page = 1;
            loadProducts();
        });

        // Image upload handlers
        document.getElementById('main_image').addEventListener('change', function(e) {
            previewSingleImage(e.target, 'mainImageContainer', 'main_image');
        });

        document.getElementById('sub_image').addEventListener('change', function(e) {
            previewSingleImage(e.target, 'subImageContainer', 'sub_image');
        });

        document.getElementById('gallery_images').addEventListener('change', function(e) {
            previewImages(e.target, 'galleryImagesContainer', 'gallery_images');
        });

        document.getElementById('desktop_detail_images').addEventListener('change', function(e) {
            previewImages(e.target, 'desktopDetailImagesContainer', 'desktop_detail_images');
        });

        document.getElementById('mobile_detail_images').addEventListener('change', function(e) {
            previewImages(e.target, 'mobileDetailImagesContainer', 'mobile_detail_images');
        });

        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveProduct();
        });

        // Set up delete confirmation
        document.getElementById('confirmDelete').addEventListener('click', deleteProduct);
    }

    // Initialize tabs
    function initializeTabs() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabs = document.querySelectorAll('.tab');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                // Update button states
                tabButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Show selected tab
                tabs.forEach(tab => {
                    tab.classList.remove('active');
                    if (tab.id === `tab-${tabId}`) {
                        tab.classList.add('active');
                    }
                });
            });
        });
    }

    // Load products
    async function loadProducts() {
        showLoading();
        
        try {
            const params = new URLSearchParams(currentState);
            const response = await fetch(`{{ route('admin.products.data') }}?${params}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                renderProducts(data.products);
                renderPagination(data);
                hideLoading();
            } else {
                throw new Error(data.message || 'Failed to load products');
            }
        } catch (error) {
            console.error('Error loading products:', error);
            showToast(error.message || 'Network error. Please try again.', 'error');
            hideLoading();
        }
    }

    // Load categories
    async function loadCategories() {
        try {
            const response = await fetch('/admin/products/categories');
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                const categorySelect = document.getElementById('category');
                const categorySelectModal = document.getElementById('category_id');
                
                // Clear existing options except first
                while (categorySelect.options.length > 1) {
                    categorySelect.remove(1);
                }
                while (categorySelectModal.options.length > 1) {
                    categorySelectModal.remove(1);
                }
                
                data.categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    
                    const option2 = option.cloneNode(true);
                    categorySelect.appendChild(option);
                    categorySelectModal.appendChild(option2);
                });
            } else {
                throw new Error(data.message || 'Failed to load categories');
            }
        } catch (error) {
            console.error('Error loading categories:', error);
            showToast('Failed to load categories', 'error');
        }
    }

    // Load analytics
    async function loadAnalytics() {
        try {
            const response = await fetch('{{ route('admin.products.analytics') }}');
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                renderAnalytics(data.analytics);
            } else {
                throw new Error(data.message || 'Failed to load analytics');
            }
        } catch (error) {
            console.error('Error loading analytics:', error);
        }
    }

    // Render products
    function renderProducts(products) {
        const desktopTable = document.getElementById('productsTable');
        const mobileContainer = document.getElementById('mobileProducts');
        
        desktopTable.innerHTML = '';
        mobileContainer.innerHTML = '';
        
        if (products.length === 0) {
            document.getElementById('emptyState').classList.remove('hidden');
            document.getElementById('paginationContainer').classList.add('hidden');
            return;
        }
        
        document.getElementById('emptyState').classList.add('hidden');
        document.getElementById('paginationContainer').classList.remove('hidden');
        
        // Render desktop table
        products.forEach(product => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';
            row.innerHTML = `
                <td class="px-4 py-4">
                    <div class="flex items-center">
                        <div class="h-10 w-10 flex-shrink-0">
                            <img class="h-10 w-10 rounded-md object-cover" src="${product.image}" alt="${product.name}" onerror="this.src='/images/abc.png'">
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">${product.name}</div>
                            ${product.title ? `<div class="text-sm text-gray-500">${product.title}</div>` : ''}
                            ${product.product_detail ? `
                                <div class="text-xs text-gray-400 mt-1">
                                    ${product.product_detail.model_name ? product.product_detail.model_name + ' • ' : ''}
                                    ${product.product_detail.reference_number || ''}
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </td>
                <td class="px-4 py-4">
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        ${product.category.name}
                    </span>
                </td>
                <td class="px-4 py-4 text-sm text-gray-900">
                    PKR ${product.price}
                </td>
                <td class="px-4 py-4">
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${
                        product.stock_quantity === 0 ? 'bg-red-100 text-red-800' :
                        product.stock_quantity <= 10 ? 'bg-yellow-100 text-yellow-800' :
                        'bg-green-100 text-green-800'
                    }">
                        ${product.stock_quantity}
                    </span>
                </td>
                <td class="px-4 py-4 text-sm text-gray-900">
                    <div class="flex items-center space-x-2">
                        <span class="font-medium">${product.sort_order}</span>
                        <div class="flex flex-col space-y-1">
                            <button onclick="updateSortOrder(${product.id}, ${product.sort_order - 1})" 
                                    class="text-gray-400 hover:text-blue-600 ${product.sort_order <= 1 ? 'opacity-50 cursor-not-allowed' : ''}"
                                    ${product.sort_order <= 1 ? 'disabled' : ''}>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                            <button onclick="updateSortOrder(${product.id}, ${product.sort_order + 1})" 
                                    class="text-gray-400 hover:text-blue-600">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-4">
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${product.status_badge}">
                        ${product.status_text}
                    </span>
                </td>
                <td class="px-4 py-4 text-sm font-medium">
                    <div class="flex space-x-3">
                        <button onclick="editProduct(${product.id})" class="text-blue-600 hover:text-blue-900" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button onclick="confirmDelete(${product.id})" class="text-red-600 hover:text-red-900" title="Delete">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            `;
            desktopTable.appendChild(row);
        });
        
        // Render mobile cards
        products.forEach(product => {
            const card = document.createElement('div');
            card.className = 'mobile-card';
            card.innerHTML = `
                <div class="product-header">
                    <img class="h-16 w-16 rounded-lg object-cover" src="${product.image}" alt="${product.name}" onerror="this.src='/abc-product.png'">
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900 text-base">${product.name}</div>
                        ${product.title ? `<div class="text-sm text-gray-600 mt-1">${product.title}</div>` : ''}
                        ${product.product_detail ? `
                            <div class="text-xs text-gray-400 mt-1">
                                ${product.product_detail.model_name ? product.product_detail.model_name + ' • ' : ''}
                                ${product.product_detail.reference_number || ''}
                            </div>
                        ` : ''}
                    </div>
                </div>
                
                <div class="mobile-card-row">
                    <span class="mobile-label">Category</span>
                    <span class="mobile-value">
                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            ${product.category.name}
                        </span>
                    </span>
                </div>
                
                <div class="mobile-card-row">
                    <span class="mobile-label">Price</span>
                    <span class="mobile-value font-medium text-gray-900">PKR ${product.price}</span>
                </div>
                
                <div class="mobile-card-row">
                    <span class="mobile-label">Stock</span>
                    <span class="mobile-value">
                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full ${
                            product.stock_quantity === 0 ? 'bg-red-100 text-red-800' :
                            product.stock_quantity <= 10 ? 'bg-yellow-100 text-yellow-800' :
                            'bg-green-100 text-green-800'
                        }">
                            ${product.stock_quantity}
                        </span>
                    </span>
                </div>
                
                <div class="mobile-card-row">
                    <span class="mobile-label">Sort Order</span>
                    <span class="mobile-value">
                        <div class="flex items-center space-x-3">
                            <span class="font-medium text-gray-900">${product.sort_order}</span>
                            <div class="flex space-x-2">
                                <button onclick="updateSortOrder(${product.id}, ${product.sort_order - 1})" 
                                        class="p-1 text-gray-400 hover:text-blue-600 ${product.sort_order <= 1 ? 'opacity-50 cursor-not-allowed' : ''}"
                                        ${product.sort_order <= 1 ? 'disabled' : ''}>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                </button>
                                <button onclick="updateSortOrder(${product.id}, ${product.sort_order + 1})" 
                                        class="p-1 text-gray-400 hover:text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </span>
                </div>
                
                <div class="mobile-card-row">
                    <span class="mobile-label">Status</span>
                    <span class="mobile-value">
                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full ${product.status_badge}">
                            ${product.status_text}
                        </span>
                    </span>
                </div>
                
                <div class="mobile-card-row pt-4 !border-b-0">
                    <div class="flex w-full space-x-3">
                        <button onclick="editProduct(${product.id})" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-blue-600 text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </button>
                        <button onclick="confirmDelete(${product.id})" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-red-600 text-sm font-medium rounded-md text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>
            `;
            mobileContainer.appendChild(card);
        });
    }

    // Render pagination
    function renderPagination(data) {
        const infoDiv = document.getElementById('paginationInfo');
        const linksDiv = document.getElementById('paginationLinks');
        
        infoDiv.innerHTML = `
            <p class="text-sm text-gray-700">
                Showing <span class="font-medium">${data.from}</span> to 
                <span class="font-medium">${data.to}</span> of 
                <span class="font-medium">${data.total}</span> products
            </p>
        `;
        
        linksDiv.innerHTML = '';
        
        // Previous button
        const prevButton = document.createElement('button');
        prevButton.innerHTML = 'Previous';
        prevButton.className = `px-3 py-2 border border-gray-300 text-sm font-medium rounded-md ${
            data.current_page === 1 
                ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
                : 'bg-white text-gray-700 hover:bg-gray-50'
        }`;
        prevButton.disabled = data.current_page === 1;
        prevButton.addEventListener('click', () => {
            if (data.current_page > 1) {
                currentState.page = data.current_page - 1;
                loadProducts();
            }
        });
        linksDiv.appendChild(prevButton);
        
        // Next button
        const nextButton = document.createElement('button');
        nextButton.innerHTML = 'Next';
        nextButton.className = `ml-2 px-3 py-2 border border-gray-300 text-sm font-medium rounded-md ${
            data.current_page === data.last_page 
                ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
                : 'bg-white text-gray-700 hover:bg-gray-50'
        }`;
        nextButton.disabled = data.current_page === data.last_page;
        nextButton.addEventListener('click', () => {
            if (data.current_page < data.last_page) {
                currentState.page = data.current_page + 1;
                loadProducts();
            }
        });
        linksDiv.appendChild(nextButton);
    }

    // Render analytics
    function renderAnalytics(analytics) {
        const analyticsSection = document.querySelector('.analytics-grid');
        if (!analyticsSection) return;
        
        analyticsSection.innerHTML = `
            <div class="analytics-card bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-md bg-blue-500 p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Products</dt>
                                <dd class="text-xl font-bold text-gray-900">${analytics.total_products}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="analytics-card bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-md bg-green-500 p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Active Products</dt>
                                <dd class="text-xl font-bold text-gray-900">${analytics.active_products}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="analytics-card bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-md bg-red-500 p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.791-.833-2.561 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Out of Stock</dt>
                                <dd class="text-xl font-bold text-gray-900">${analytics.out_of_stock}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="analytics-card bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-md bg-yellow-500 p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Low Stock</dt>
                                <dd class="text-xl font-bold text-gray-900">${analytics.low_stock}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // Show create modal
    function showCreateModal() {
        resetForm();
        document.getElementById('modalTitle').textContent = 'Add New Product';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('submitText').textContent = 'Create Product';
        document.getElementById('productModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Edit product
    async function editProduct(id) {
        try {
            showLoading();

            const response = await fetch(`/admin/products/${id}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);

                loadProducts();
            }

            const data = await response.json();
            
            if (data.success) {
                resetForm();
                
                const product = data.product;
                document.getElementById('productId').value = product.id;
                document.getElementById('modalTitle').textContent = 'Edit Product';
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('submitText').textContent = 'Update Product';
                
                // Fill basic info
                document.getElementById('name').value = product.name;
                document.getElementById('title').value = product.title || '';
                document.getElementById('category_id').value = product.category_id;
                document.getElementById('price').value = product.price;
                document.getElementById('cut_price').value = product.cut_price || '';
                document.getElementById('stock_quantity').value = product.stock_quantity;
                document.getElementById('sort_order').value = product.sort_order;
                document.getElementById('rating').value = product.rating || '';
                document.getElementById('tags').value = Array.isArray(product.tags)
    ? product.tags.join(', ')
    : product.tags || '';
                document.getElementById('description').value = product.description;

                // Set status radio
                document.querySelectorAll('input[name="status"]').forEach(radio => {
                    radio.checked = radio.value === product.status;
                });

                // Set checkboxes
                document.querySelector('input[name="is_top_selling"]').checked = product.is_top_selling == 1;
                document.querySelector('input[name="is_new_arrival"]').checked = product.is_new_arrival == 1;
                document.querySelector('input[name="is_feature_card"]').checked = product.is_feature_card == 1;

                // Fill product details
                if (data.product_detail) {
                    document.getElementById('model_name').value = data.product_detail.model_name || '';
                    document.getElementById('reference_number').value = data.product_detail.reference_number || '';
                    document.getElementById('detailed_description').value = data.product_detail.detailed_description || '';

                    if (data.specs && data.specs.length > 0) {
    try {
        let rawSpecs = (typeof data.specs === 'string') ? JSON.parse(data.specs) : data.specs;

        if (!Array.isArray(rawSpecs)) {
            rawSpecs = [];
        }


        const uniqueSpecs = rawSpecs.filter((value, index, self) =>
            index === self.findIndex((t) => (
                t.key === value.key && t.value === value.value
            ))
        );

        specsData = uniqueSpecs;
        
        renderSpecs();

    } catch (e) {
        console.error("Failed to parse specs:", e);
        specsData = [];
    }
}

                }

                // Load existing images
                if (data.images) {
                    renderExistingImages('mainImageContainer', 'main_image', data.images.main_image || []);
                    renderExistingImages('subImageContainer', 'sub_image', data.images.sub_image || []);
                    renderExistingImages('galleryImagesContainer', 'gallery_images', data.images.gallery_images || []);
                    renderExistingImages('desktopDetailImagesContainer', 'desktop_detail_images', data.images.desktop_detail_images || []);
                    renderExistingImages('mobileDetailImagesContainer', 'mobile_detail_images', data.images.mobile_detail_images || []);
                }

                // Show modal
                document.getElementById('productModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                // Switch to basic tab
                document.querySelector('.tab-button[data-tab="basic"]').click();
            } else {
                throw new Error(data.message || 'Failed to load product');
            }
        } catch (error) {
            console.error('Error editing product:', error);
            showToast(error.message || 'Failed to load product', 'error');
            loadProducts();
        } finally {
            hideLoading();
        }
    }

    async function saveProduct() {
        const submitBtn = document.getElementById('submitBtn');
        const submitSpinner = document.getElementById('submitSpinner');
        const submitText = document.getElementById('submitText');
        
        submitBtn.disabled = true;
        submitSpinner.classList.remove('hidden');
        submitText.textContent = 'Saving...';
        
        try {
            const form = document.getElementById('productForm');
            const formData = new FormData(form);
            if (Array.isArray(specsData)) {
    const validSpecs = specsData.filter(s => s.key.trim() || s.value.trim());
    
    console.log('Sending specs:', validSpecs); 

    formData.set('specs', JSON.stringify(validSpecs));
}



console.log(specsData);
            // Add deleted image IDs
            if (deletedImageIds.length > 0) {
                formData.append('deleted_image_ids', JSON.stringify(deletedImageIds));
            }

            // Add image sort orders
            updateImageSortOrders('gallery_images');
            updateImageSortOrders('desktop_detail_images');
            updateImageSortOrders('mobile_detail_images');

            const method = document.getElementById('formMethod').value;
            const productId = document.getElementById('productId').value;
            let url = '{{ route('admin.products.store') }}';
            
            if (method === 'PUT') {
                url = `{{ url('admin/products') }}/${productId}`;
            }
            
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast(data.message, 'success');
                hideModal();
                loadProducts();
                loadAnalytics();
            } else {
                // Clear previous errors
                document.querySelectorAll('[id$="Error"]').forEach(el => {
                    el.classList.add('hidden');
                    el.textContent = '';
                });

                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorElement = document.getElementById(`${field}Error`);
                        if (errorElement) {
                            errorElement.textContent = data.errors[field][0];
                            errorElement.classList.remove('hidden');
                        }
                    });
                    showToast('Please fix the validation errors', 'error');
                } else {
                    showToast(data.message || 'Operation failed', 'error');
                }
            }
        } catch (error) {
            console.error('Error saving product:', error);
            showToast('Network error. Please try again.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitSpinner.classList.add('hidden');
            submitText.textContent = document.getElementById('formMethod').value === 'POST' ? 'Create Product' : 'Update Product';
        }
    }

    // Update sort order
    async function updateSortOrder(productId, newSortOrder) {
        if (newSortOrder < 1) return;
        
        try {
            const response = await fetch(`{{ url('admin/products') }}/${productId}/sort-order`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    sort_order: newSortOrder
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast('Sort order updated', 'success');
                loadProducts();
            } else {
                showToast(data.message || 'Failed to update sort order', 'error');
            }
        } catch (error) {
            console.error('Error updating sort order:', error);
            showToast('Network error', 'error');
        }
    }

    // Confirm delete
    function confirmDelete(id) {
        deleteProductId = id;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Delete product
    async function deleteProduct() {
        try {
            const response = await fetch(`{{ url('admin/products') }}/${deleteProductId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast('Product deleted successfully', 'success');
                hideDeleteModal();
                loadProducts();
                loadAnalytics();
            } else {
                showToast(data.message || 'Failed to delete product', 'error');
                hideDeleteModal();
            }
        } catch (error) {
            console.error('Error deleting product:', error);
            showToast('Network error', 'error');
            hideDeleteModal();
        }
    }

    // Image preview functions
    function previewSingleImage(input, containerId, type) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        container.innerHTML = '';
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const item = document.createElement('div');
                item.className = 'image-preview-container';
                item.innerHTML = `
                    <img src="${e.target.result}" class="h-40 w-40 object-cover rounded-md shadow" alt="${type} preview">
                    <button type="button" onclick="removeSingleImage('${type}')" class="image-preview-remove">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                container.appendChild(item);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewImages(input, containerId, type) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        if (input.files) {
            const sortInput = document.getElementById(`${type}Sort`);
            const sortOrders = sortInput && sortInput.value ? JSON.parse(sortInput.value) : [];
            
            Array.from(input.files).forEach((file, index) => {
                // Check file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    showToast('File size exceeds 5MB limit', 'error');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const itemId = `new-${type}-${Date.now()}-${index}`;
                    const sortOrder = sortOrders[index] || (container.children.length + 1);
                    
                    const item = document.createElement('div');
                    item.className = 'image-sortable-item';
                    item.id = itemId;
                    item.setAttribute('data-sort-order', sortOrder);
                    item.innerHTML = `
                        <div class="image-sort-handle cursor-grab">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                            </svg>
                        </div>
                        <img src="${e.target.result}" class="h-20 w-20 object-cover rounded-md shadow" alt="Preview">
                        <input type="number" class="w-16 px-2 py-1 border border-gray-300 rounded text-sm" 
                               value="${sortOrder}" min="1" 
                               onchange="updateImageSortValue('${type}', '${itemId}', this.value)">
                        <button type="button" onclick="removeImageItem('${type}', '${itemId}')" 
                                class="ml-auto text-red-600 hover:text-red-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    `;
                    container.appendChild(item);
                    
                    // Initialize sortable
                    initSortable(container, type);
                };
                reader.readAsDataURL(file);
            });
        }
    }

    function initSortable(container, type) {
        if (typeof Sortable !== 'undefined') {
            new Sortable(container, {
                animation: 150,
                handle: '.image-sort-handle',
                onEnd: function() {
                    updateImageSortOrders(type);
                }
            });
        }
    }

    function updateImageSortOrders(type) {
        const container = document.getElementById(`${type}Container`);
        if (!container) return;
        
        const items = container.querySelectorAll('.image-sortable-item');
        const sortOrders = [];
        
        items.forEach((item, index) => {
            const newOrder = index + 1;
            item.setAttribute('data-sort-order', newOrder);
            const input = item.querySelector('input[type="number"]');
            if (input) {
                input.value = newOrder;
            }
            sortOrders.push(newOrder);
        });
        
        const sortInput = document.getElementById(`${type}Sort`);
        if (sortInput) {
            sortInput.value = JSON.stringify(sortOrders);
        }
    }

    function updateImageSortValue(type, itemId, value) {
        const item = document.getElementById(itemId);
        if (item) {
            item.setAttribute('data-sort-order', value);
            updateImageSortOrders(type);
        }
    }

    function removeImageItem(type, itemId) {
        const item = document.getElementById(itemId);
        if (item) {
            item.remove();
            updateImageSortOrders(type);
        }
    }

    function removeSingleImage(type) {
        const container = document.getElementById(`${type}Container`);
        const input = document.getElementById(type);
        
        if (container) {
            container.innerHTML = '';
        }
        
        if (input) {
            input.value = '';
        }
        
        // If editing, mark for deletion
        const existingImage = container ? container.querySelector('img[data-id]') : null;
        if (existingImage) {
            const imageId = existingImage.getAttribute('data-id');
            if (imageId && !deletedImageIds.includes(parseInt(imageId))) {
                deletedImageIds.push(parseInt(imageId));
            }
        }
    }

    function renderExistingImages(containerId, type, images) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        container.innerHTML = '';
        
        images.forEach((image, index) => {
            if (type === 'main_image' || type === 'sub_image') {
                const item = document.createElement('div');
                item.className = 'image-preview-container';
                item.innerHTML = `
                    <img src="${image.url}" class="h-40 w-40 object-cover rounded-md shadow" alt="${type}" data-id="${image.id}">
                    <button type="button" onclick="removeExistingImage(${image.id}, '${type}')" class="image-preview-remove">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                container.appendChild(item);
            } else {
                const item = document.createElement('div');
                item.className = 'image-sortable-item';
                item.id = `existing-${type}-${image.id}`;
                item.setAttribute('data-sort-order', image.sort_order);
                item.innerHTML = `
                    <div class="image-sort-handle cursor-grab">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                        </svg>
                    </div>
                    <img src="${image.url}" class="h-20 w-20 object-cover rounded-md shadow" alt="${type}" data-id="${image.id}">
                    <input type="number" class="w-16 px-2 py-1 border border-gray-300 rounded text-sm" 
                           value="${image.sort_order}" min="1" 
                           onchange="updateExistingImageSort('${type}', ${image.id}, this.value)">
                    <button type="button" onclick="removeExistingImage(${image.id}, '${type}')" 
                            class="ml-auto text-red-600 hover:text-red-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                `;
                container.appendChild(item);
                
                initSortable(container, type);
            }
        });
    }

    function removeExistingImage(imageId, type) {
        if (!deletedImageIds.includes(imageId)) {
            deletedImageIds.push(imageId);
        }
        
        if (type === 'main_image' || type === 'sub_image') {
            removeSingleImage(type);
        } else {
            const item = document.getElementById(`existing-${type}-${imageId}`);
            if (item) {
                item.remove();
                updateImageSortOrders(type);
            }
        }
    }

    function updateExistingImageSort(type, imageId, value) {
        const productId = document.getElementById('productId').value;
        if (productId) {
            fetch(`/admin/products/${productId}/image-sort-order`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    image_id: imageId,
                    type: type,
                    sort_order: parseInt(value)
                })
            }).catch(error => console.error('Error updating image sort order:', error));
        }
    }


    function addSpecField(key = '', value = '', fromRender = false) {
    const container = document.getElementById('specsList');
    if (!container) return;

    if (!Array.isArray(specsData)) {
        console.warn('specsData was broken (Object/Null). Converting to Array now.');
        

        if (specsData && typeof specsData === 'object') {
            specsData = Object.values(specsData);
        } else {
            specsData = [];
        }
    }
    // --------------------------------

    const specId = `spec-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;

    const specItem = document.createElement('div');
    specItem.className = 'spec-item';
    specItem.id = specId;
    specItem.innerHTML = `
        <input type="text" placeholder="Specification name" value="${key}" 
               oninput="updateSpec('${specId}', this.value, 'key')">
        <input type="text" placeholder="Value" value="${value}" 
               oninput="updateSpec('${specId}', this.value, 'value')">
        <button type="button" onclick="removeSpecField('${specId}')">Remove</button>
    `;
    container.appendChild(specItem);

    // FIXED LOGIC:
    if (!fromRender) {
        specsData.push({ key: key, value: value });
        console.log('Added spec. Current Array:', specsData);
    }
}

function renderSpecs() {
    const container = document.getElementById('specsList');
    if (!container) return;

    container.innerHTML = '';

    
    specsData.forEach(spec => {
        addSpecField(spec.key, spec.value, true); 
    });
}

function updateSpec(specId, value, type) {
    const specItem = document.getElementById(specId);
    if (!specItem) return;

    const index = Array.from(specItem.parentElement.children).indexOf(specItem);

    if (specsData[index]) {
        if (type === 'key') {
            specsData[index].key = value;
        } else {
            specsData[index].value = value;
        }
    }
}
function removeSpecField(specId) {
    const specItem = document.getElementById(specId);
    if (!specItem) return;

    const index = Array.from(specItem.parentElement.children).indexOf(specItem);
    if (index !== -1) {
        specsData.splice(index, 1); // remove from array
    }

    specItem.remove();
}




    function hideModal() {
        const modal = document.getElementById('productModal');
        if (modal) {
            modal.classList.add('hidden');
        }
        document.body.style.overflow = 'auto';
        resetForm();

        loadProducts();
    }

    function hideDeleteModal() {
        const modal = document.getElementById('deleteModal');
        if (modal) {
            modal.classList.add('hidden');
        }
        document.body.style.overflow = 'auto';
        deleteProductId = null;
    }

    function resetForm() {
        const form = document.getElementById('productForm');
        if (form) {
            form.reset();
        }
        
        document.getElementById('productId').value = '';
        
        // Clear all image containers
        ['mainImageContainer', 'subImageContainer', 'galleryImagesContainer', 'desktopDetailImagesContainer', 'mobileDetailImagesContainer'].forEach(id => {
            const container = document.getElementById(id);
            if (container) container.innerHTML = '';
        });
        
        // Clear file inputs
        ['main_image', 'sub_image', 'gallery_images', 'desktop_detail_images', 'mobile_detail_images'].forEach(id => {
            const input = document.getElementById(id);
            if (input) input.value = '';
        });
        
        // Clear specs
        const specsList = document.getElementById('specsList');
        if (specsList) specsList.innerHTML = '';
        specsData = {};
        
        // Clear deleted image IDs
        deletedImageIds = [];
        
        // Clear error messages
        document.querySelectorAll('[id$="Error"]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        
        // Reset status radio
        const statusRadio = document.querySelector('input[name="status"][value="active"]');
        if (statusRadio) statusRadio.checked = true;
        
        // Switch to first tab
        const firstTabButton = document.querySelector('.tab-button[data-tab="basic"]');
        if (firstTabButton) firstTabButton.click();
    }

    // Loading state
    function showLoading() {
        const loadingState = document.getElementById('loadingState');
        if (loadingState) {
            loadingState.classList.remove('hidden');
        }
        
        const productsTable = document.getElementById('productsTable');
        if (productsTable) productsTable.innerHTML = '';
        
        const mobileProducts = document.getElementById('mobileProducts');
        if (mobileProducts) mobileProducts.innerHTML = '';
        
        const paginationContainer = document.getElementById('paginationContainer');
        if (paginationContainer) paginationContainer.classList.add('hidden');
    }

    function hideLoading() {
        const loadingState = document.getElementById('loadingState');
        if (loadingState) {
            loadingState.classList.add('hidden');
        }
    }

    // Toast notification
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        if (!toast) return;
        
        const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
        const icon = type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ';
        
        toast.innerHTML = `
            <div class="${bgColor} text-white px-4 py-3 rounded-lg shadow-lg flex items-center animate-slideIn">
                <span class="font-bold mr-2">${icon}</span>
                <span class="flex-1">${message}</span>
            </div>
        `;
        
        toast.classList.remove('hidden');
        
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3000);
    }
</script>

<!-- Include SortableJS -->
<!-- Include SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
@endsection