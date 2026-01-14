@extends("admin.layouts.master-layouts.plain")

@section("title", "User Management | Luxorix Admin")

@push("styles")
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.0.0-beta.83/dist/themes/light.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.35.0/dist/apexcharts.css">
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --warning-gradient: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
        --danger-gradient: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
    }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .stat-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        position: relative;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }
    
    .stat-card.users::before { background: var(--primary-gradient); }
    .stat-card.active::before { background: var(--success-gradient); }
    .stat-card.blocked::before { background: var(--danger-gradient); }
    .stat-card.growth::before { background: var(--warning-gradient); }
    
    .user-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        object-fit: cover;
        border: 3px solid transparent;
        background: linear-gradient(white, white) padding-box,
                    var(--primary-gradient) border-box;
    }
    
    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }
    
    .status-dot.active { background-color: #10B981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2); }
    .status-dot.inactive { background-color: #6B7280; box-shadow: 0 0 0 3px rgba(107, 114, 128, 0.2); }
    .status-dot.blocked { background-color: #EF4444; box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2); }
    .status-dot.pending { background-color: #F59E0B; box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2); }
    
    .role-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .role-admin { background: linear-gradient(135deg, #8B5CF6, #EC4899); color: white; }
    .role-manager { background: linear-gradient(135deg, #3B82F6, #1D4ED8); color: white; }
    .role-user { background: linear-gradient(135deg, #10B981, #059669); color: white; }
    
    .action-btn {
        padding: 0.5rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .action-btn:hover {
        transform: scale(1.1);
    }
    
    .pagination-item {
        min-width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        margin: 0 2px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .pagination-item.active {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    
    .pagination-item:not(.active):hover {
        background-color: #F3F4F6;
    }
    
    .table-row {
        transition: all 0.2s ease;
        border-bottom: 1px solid #E5E7EB;
    }
    
    .table-row:hover {
        background-color: #F9FAFB;
        transform: scale(1.002);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    /* Advanced filter panel */
    .filter-panel {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
    }
    
    .filter-panel.expanded {
        max-height: 500px;
    }
    
    /* Chart container */
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    /* Loading skeleton */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    /* Toast notifications */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }
    
    .toast {
        min-width: 300px;
        background: white;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        animation: slideIn 0.3s ease;
        border-left: 4px solid;
    }
    
    .toast.success { border-left-color: #10B981; }
    .toast.error { border-left-color: #EF4444; }
    .toast.warning { border-left-color: #F59E0B; }
    .toast.info { border-left-color: #3B82F6; }
    
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    /* Mobile responsive */
    @media (max-width: 768px) {
        .user-table .desktop-only { display: none; }
        .user-table .mobile-only { display: table-cell; }
        
        .table-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            padding: 1rem;
        }
    }
</style>
@endpush

@section("content")
<!-- Header with Breadcrumb -->
<div class="border-b border-gray-200 pb-4 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-home mr-2"></i>Dashboard
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-primary font-medium">User Management</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
            <p class="text-gray-600 mt-1">Manage and monitor all user accounts and activities</p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <!-- Export Button -->
            <button onclick="exportUsers()" 
                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                <i class="fas fa-file-export"></i>
                <span class="hidden sm:inline">Export</span>
            </button>
            
            <!-- Bulk Actions Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                    <i class="fas fa-layer-group"></i>
                    <span class="hidden sm:inline">Bulk Actions</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
                
                <div x-show="open" @click.away="open = false" 
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50 border border-gray-200">
                    <div class="py-1">
                        <button onclick="bulkAction('activate')" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>Activate Selected
                        </button>
                        <button onclick="bulkAction('block')" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                            <i class="fas fa-ban text-red-500 mr-2"></i>Block Selected
                        </button>
                        <button onclick="bulkAction('delete')" class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600">
                            <i class="fas fa-trash mr-2"></i>Delete Selected
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Add User Button -->
            <button onclick="openUserModal('create')" 
                    class="px-4 py-2 bg-gradient-to-r from-primary to-blue-600 text-white rounded-lg hover:opacity-90 flex items-center gap-2 shadow-lg shadow-blue-500/25">
                <i class="fas fa-plus"></i>
                <span>Add User</span>
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="stat-card users glass-card rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Users</p>
                <h3 class="text-3xl font-bold mt-2">{{ $stats['total_users'] ?? 0 }}</h3>
                <div class="flex items-center mt-2">
                    <span class="text-green-500 text-sm font-medium">
                        <i class="fas fa-arrow-up mr-1"></i>{{ $stats['growth_rate'] ?? 0 }}%
                    </span>
                    <span class="text-gray-500 text-sm ml-2">from last month</span>
                </div>
            </div>
            <div class="p-3 rounded-full bg-blue-100">
                <i class="fas fa-users text-primary text-2xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Active Users -->
    <div class="stat-card active glass-card rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Active Users</p>
                <h3 class="text-3xl font-bold mt-2">{{ $stats['active_users'] ?? 0 }}</h3>
                <div class="flex items-center mt-2">
                    <span class="text-green-500 text-sm">
                        <i class="fas fa-circle mr-1"></i>{{ round(($stats['active_users'] / $stats['total_users']) * 100, 1) ?? 0 }}%
                    </span>
                    <span class="text-gray-500 text-sm ml-2">of total</span>
                </div>
            </div>
            <div class="p-3 rounded-full bg-green-100">
                <i class="fas fa-user-check text-green-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Blocked Users -->
    <div class="stat-card blocked glass-card rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Blocked Users</p>
                <h3 class="text-3xl font-bold mt-2">{{ $stats['blocked_users'] ?? 0 }}</h3>
                <div class="flex items-center mt-2">
                    <span class="text-red-500 text-sm">
                        <i class="fas fa-shield-alt mr-1"></i>{{ round(($stats['blocked_users'] / $stats['total_users']) * 100, 1) ?? 0 }}%
                    </span>
                    <span class="text-gray-500 text-sm ml-2">needs attention</span>
                </div>
            </div>
            <div class="p-3 rounded-full bg-red-100">
                <i class="fas fa-user-slash text-red-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <!-- New This Month -->
    <div class="stat-card growth glass-card rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">New This Month</p>
                <h3 class="text-3xl font-bold mt-2">{{ $stats['new_this_month'] ?? 0 }}</h3>
                <div class="flex items-center mt-2">
                    <span class="text-yellow-500 text-sm">
                        <i class="fas fa-chart-line mr-1"></i>{{ $stats['new_today'] ?? 0 }} today
                    </span>
                    <span class="text-gray-500 text-sm ml-2">{{ $stats['new_this_week'] ?? 0 }} this week</span>
                </div>
            </div>
            <div class="p-3 rounded-full bg-yellow-100">
                <i class="fas fa-user-plus text-yellow-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- User Growth Chart -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-gray-900">User Growth Trend</h3>
            <div class="flex gap-2">
                <button onclick="loadChartData('weekly')" class="px-3 py-1 text-xs rounded-lg bg-gray-100 hover:bg-gray-200">Week</button>
                <button onclick="loadChartData('monthly')" class="px-3 py-1 text-xs rounded-lg bg-primary text-white">Month</button>
                <button onclick="loadChartData('yearly')" class="px-3 py-1 text-xs rounded-lg bg-gray-100 hover:bg-gray-200">Year</button>
            </div>
        </div>
        <div id="growthChart" class="chart-container"></div>
    </div>
    
    <!-- User Distribution Chart -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-gray-900 mb-6">User Distribution by Role</h3>
        <div id="distributionChart" class="chart-container"></div>
    </div>
</div>

<!-- Advanced Filters -->
<div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
    <div class="p-4 border-b border-gray-200 flex justify-between items-center cursor-pointer" 
         onclick="toggleFilters()">
        <div class="flex items-center gap-3">
            <i class="fas fa-filter text-primary"></i>
            <span class="font-medium">Advanced Filters</span>
            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">
                {{ count(request()->except(['page', 'per_page'])) }} active
            </span>
        </div>
        <i id="filterIcon" class="fas fa-chevron-down transition-transform"></i>
    </div>
    
    <div id="filterPanel" class="filter-panel">
        <form id="filterForm" method="GET" class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search by name, email, or phone..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
            
            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                    <option value="">All Statuses</option>
                    @foreach($filters['statuses'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Role Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select name="role" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                    <option value="">All Roles</option>
                    @foreach($filters['roles'] as $id => $name)
                        <option value="{{ $name }}" {{ request('role') == $name ? 'selected' : '' }}>
                            {{ ucfirst($name) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Date Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
            </div>
            
            <!-- Sort Options -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                <div class="flex gap-2">
                    <select name="sortBy" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                        @foreach($filters['sortOptions'] as $value => $label)
                            <option value="{{ $value }}" {{ request('sortBy') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <select name="sortOrder" class="w-24 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                        <option value="asc" {{ request('sortOrder') == 'asc' ? 'selected' : '' }}>Asc</option>
                        <option value="desc" {{ request('sortOrder') == 'desc' ? 'selected' : '' }}>Desc</option>
                    </select>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="md:col-span-3 flex gap-3 pt-4 border-t border-gray-200">
                <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                    Apply Filters
                </button>
                <button type="button" onclick="resetFilters()" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    Clear All
                </button>
                <a href="{{ route('admin.user-management') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="flex items-center">
                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-primary focus:ring-primary">
                <label for="selectAll" class="ml-2 text-sm text-gray-700">Select All</label>
            </div>
            <span id="selectedCount" class="text-sm text-gray-500">0 selected</span>
        </div>
        
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-600">
                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
            </span>
            <select id="perPage" class="border border-gray-300 rounded-lg px-3 py-1 text-sm">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 per page</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per page</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
            </select>
        </div>
    </div>
    
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="w-12 px-6 py-3 text-left"></th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email & Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Active</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200" id="usersTableBody">
                @forelse($users as $user)
                    <tr class="table-row" data-user-id="{{ $user['id'] }}">
                        <td class="px-6 py-4">
                            <input type="checkbox" value="{{ $user['id'] }}" class="user-checkbox rounded border-gray-300 text-primary focus:ring-primary">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <img src="{{ $user['avatar'] }}" alt="{{ $user['name'] }}" class="user-avatar mr-3">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $user['name'] }}</div>
                                    <div class="text-sm text-gray-500">ID: {{ $user['id'] }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <a href="mailto:{{ $user['email'] }}" class="text-primary hover:underline">{{ $user['email'] }}</a>
                                @if($user['phone'])
                                    <div class="text-gray-500 mt-1">{{ $user['phone'] }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="role-badge role-{{ $user['role'] }}">
                                {{ $user['role'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <span class="status-dot {{ $user['status'] }}"></span>
                                <span class="text-sm font-medium capitalize">{{ $user['status'] }}</span>
                                @if($user['email_verified'])
                                    <i class="fas fa-check-circle text-green-500 ml-2" title="Email Verified"></i>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $user['last_active'] }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <!-- Quick View -->
                                <button onclick="quickView('{{ $user['id'] }}')" 
                                        class="action-btn text-blue-500 hover:bg-blue-50" 
                                        title="Quick View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                <!-- Edit -->
                                <button onclick="openUserModal('edit', {{ json_encode($user) }})" 
                                        class="action-btn text-primary hover:bg-blue-50" 
                                        title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <!-- Block/Unblock -->
                                <form action="{{ route('admin.user.toggleBlock', $user['id']) }}" 
                                      method="POST" 
                                      class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="action-btn {{ $user['status'] == 'blocked' ? 'text-green-500 hover:bg-green-50' : 'text-yellow-500 hover:bg-yellow-50' }}" 
                                            title="{{ $user['status'] == 'blocked' ? 'Unblock' : 'Block' }}">
                                        <i class="fas {{ $user['status'] == 'blocked' ? 'fa-unlock' : 'fa-ban' }}"></i>
                                    </button>
                                </form>
                                
                                <!-- Delete -->
                                <button onclick="deleteUser('{{ $user['id'] }}', '{{ $user['name'] }}')" 
                                        class="action-btn text-red-500 hover:bg-red-50" 
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                                
                                <!-- More Actions -->
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" 
                                            class="action-btn text-gray-500 hover:bg-gray-100">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div x-show="open" @click.away="open = false" 
                                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50 border border-gray-200">
                                        <div class="py-1">
                                            <a href="{{ $user['profile_url'] }}" 
                                               class="block px-4 py-2 hover:bg-gray-100">
                                                <i class="fas fa-user-circle mr-2"></i>View Profile
                                            </a>
                                            <button onclick="sendResetLink('{{ $user['id'] }}')" 
                                                    class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                                <i class="fas fa-key mr-2"></i>Send Reset Link
                                            </button>
                                            <button onclick="impersonateUser('{{ $user['id'] }}')" 
                                                    class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                                <i class="fas fa-user-secret mr-2"></i>Impersonate
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-400">
                                <i class="fas fa-users text-4xl mb-4"></i>
                                <p class="text-lg font-medium">No users found</p>
                                <p class="text-sm mt-1">Try adjusting your filters or add a new user</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="text-sm text-gray-700 mb-4 sm:mb-0">
                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
            </div>
            <div class="flex items-center space-x-2">
                {{ $users->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    @endif
</div>

<!-- Toast Container -->
<div class="toast-container"></div>

<!-- User Modal (Enhanced) -->
<div id="userModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal()"></div>
        
        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <form id="userForm" method="POST" class="bg-white">
                @csrf
                <input type="hidden" id="formMethod" name="_method" value="POST">
                <input type="hidden" id="userId" name="id">
                
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add User</h3>
                    <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Body -->
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" id="userName" name="name" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" id="userEmail" name="email" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>
                        
                        <!-- Password -->
                        <div id="passwordField">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                            <div class="relative">
                                <input type="password" id="userPassword" name="password"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                                <button type="button" onclick="togglePasswordVisibility()" 
                                        class="absolute right-3 top-2 text-gray-400 hover:text-gray-600">
                                    <i id="passwordIcon" class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div id="passwordStrength" class="mt-2 hidden">
                                <div class="flex gap-1">
                                    <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                                    <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                                    <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                                    <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                                </div>
                                <p id="passwordMessage" class="text-xs mt-1"></p>
                            </div>
                        </div>
                        
                        <!-- Confirm Password -->
                        <div id="confirmPasswordField" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                            <input type="password" id="userPasswordConfirm" name="password_confirmation"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>
                        
                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="tel" id="userPhone" name="phone"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>
                        
                        <!-- Role -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                            <select id="userRole" name="role" 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                                <option value="user">User</option>
                                <option value="admin">Administrator</option>
                                <option value="manager">Manager</option>
                                <option value="moderator">Moderator</option>
                            </select>
                        </div>
                        
                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="userStatus" name="status" 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="pending">Pending</option>
                                <option value="blocked">Blocked</option>
                            </select>
                        </div>
                        
                        <!-- Email Verification -->
                        <div id="emailVerificationField" class="hidden">
                            <label class="flex items-center">
                                <input type="checkbox" id="emailVerified" name="email_verified" 
                                       class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="ml-2 text-sm text-gray-700">Mark email as verified</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Additional Options -->
                    <div class="mt-6 border-t border-gray-200 pt-4">
                        <h4 class="font-medium text-gray-700 mb-3">Additional Options</h4>
                        <div class="flex flex-wrap gap-4">
                            <label class="flex items-center">
                                <input type="checkbox" id="sendWelcomeEmail" name="send_welcome_email" checked
                                       class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="ml-2 text-sm text-gray-700">Send welcome email</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" id="requirePasswordChange" name="require_password_change"
                                       class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="ml-2 text-sm text-gray-700">Require password change on first login</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" id="submitBtn"
                            class="px-6 py-2 bg-primary text-white rounded-lg hover:opacity-90">
                        Save User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick View Modal -->
<div id="quickViewModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <!-- Similar structure to user modal but for viewing -->
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.35.0/dist/apexcharts.min.js"></script>
<script>
    // Initialize charts
    let growthChart, distributionChart;
    
    document.addEventListener('DOMContentLoaded', function() {
        initializeCharts();
        initializeEventListeners();
        showToastMessages();
    });
    
    function initializeCharts() {
        // Growth Chart
        growthChart = new ApexCharts(document.querySelector("#growthChart"), {
            series: [{
                name: 'New Users',
                data: @json(array_values($stats['activity_trend'] ?? []))
            }],
            chart: {
                height: '100%',
                type: 'area',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        pan: false,
                        reset: false
                    }
                }
            },
            colors: ['#3B82F6'],
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: @json(array_keys($stats['activity_trend'] ?? [])),
                labels: {
                    style: {
                        colors: '#6B7280',
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#6B7280',
                        fontSize: '12px'
                    }
                }
            },
            tooltip: {
                theme: 'light',
                x: {
                    format: 'dd MMM'
                }
            }
        });
        
        growthChart.render();
        
        // Distribution Chart
        distributionChart = new ApexCharts(document.querySelector("#distributionChart"), {
            series: Object.values(@json($stats['user_distribution'] ?? [])),
            chart: {
                type: 'donut',
                height: '100%'
            },
            labels: Object.keys(@json($stats['user_distribution'] ?? [])),
            colors: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
            legend: {
                position: 'bottom',
                fontSize: '14px'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '60%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Users',
                                color: '#6B7280'
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: false
            }
        });
        
        distributionChart.render();
    }
    
    function initializeEventListeners() {
        // Select All checkbox
        document.getElementById('selectAll').addEventListener('change', function(e) {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(cb => cb.checked = e.target.checked);
            updateSelectedCount();
        });
        
        // Individual checkboxes
        document.querySelectorAll('.user-checkbox').forEach(cb => {
            cb.addEventListener('change', updateSelectedCount);
        });
        
        // Per page selector
        document.getElementById('perPage').addEventListener('change', function(e) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', e.target.value);
            window.location.href = url.toString();
        });
        
        // Password strength checker
        const passwordInput = document.getElementById('userPassword');
        if (passwordInput) {
            passwordInput.addEventListener('input', checkPasswordStrength);
        }
    }
    
    function updateSelectedCount() {
        const selected = document.querySelectorAll('.user-checkbox:checked');
        const countElement = document.getElementById('selectedCount');
        countElement.textContent = `${selected.length} selected`;
    }
    
    function checkPasswordStrength() {
        const password = this.value;
        const strengthBar = document.getElementById('passwordStrength');
        const strengthMessage = document.getElementById('passwordMessage');
        
        if (password.length === 0) {
            strengthBar.classList.add('hidden');
            return;
        }
        
        strengthBar.classList.remove('hidden');
        
        let strength = 0;
        let message = '';
        let color = '';
        
        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        const bars = strengthBar.querySelectorAll('div');
        bars.forEach((bar, index) => {
            bar.className = `h-1 flex-1 rounded ${index < strength ? 'bg-green-500' : 'bg-gray-200'}`;
        });
        
        switch(strength) {
            case 0:
            case 1:
                message = 'Very Weak';
                color = 'text-red-500';
                break;
            case 2:
                message = 'Weak';
                color = 'text-yellow-500';
                break;
            case 3:
                message = 'Good';
                color = 'text-blue-500';
                break;
            case 4:
                message = 'Strong';
                color = 'text-green-500';
                break;
        }
        
        strengthMessage.textContent = message;
        strengthMessage.className = `text-xs mt-1 ${color}`;
    }
    
    function toggleFilters() {
        const panel = document.getElementById('filterPanel');
        const icon = document.getElementById('filterIcon');
        panel.classList.toggle('expanded');
        icon.classList.toggle('rotate-180');
    }
    
    function resetFilters() {
        document.getElementById('filterForm').reset();
    }
    
    function loadChartData(period) {
        fetch(`/admin/users/activity-stats?period=${period}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateGrowthChart(data.stats);
                }
            });
    }
    
    function updateGrowthChart(stats) {
        const dates = stats.map(s => s.date);
        const counts = stats.map(s => s.count);
        
        growthChart.updateSeries([{
            name: 'New Users',
            data: counts
        }]);
        
        growthChart.updateOptions({
            xaxis: {
                categories: dates
            }
        });
    }
    
    function openUserModal(mode, user = null) {
        const modal = document.getElementById('userModal');
        const title = document.getElementById('modalTitle');
        const form = document.getElementById('userForm');
        const submitBtn = document.getElementById('submitBtn');
        const passwordField = document.getElementById('passwordField');
        const confirmField = document.getElementById('confirmPasswordField');
        const emailVerificationField = document.getElementById('emailVerificationField');
        
        // Reset form
        form.reset();
        
        if (mode === 'create') {
            title.textContent = 'Add New User';
            submitBtn.textContent = 'Create User';
            form.action = "{{ route('admin.user.store') }}";
            document.getElementById('formMethod').value = 'POST';
            passwordField.classList.remove('hidden');
            confirmField.classList.remove('hidden');
            emailVerificationField.classList.add('hidden');
            
            // Show password confirmation for new users
            confirmField.classList.remove('hidden');
        } else if (mode === 'edit' && user) {
            title.textContent = 'Edit User';
            submitBtn.textContent = 'Update User';
            form.action = `/admin/user-management/${user.id}`;
            document.getElementById('formMethod').value = 'PUT';
            
            // Populate fields
            document.getElementById('userId').value = user.id;
            document.getElementById('userName').value = user.name;
            document.getElementById('userEmail').value = user.email;
            document.getElementById('userPhone').value = user.phone || '';
            document.getElementById('userRole').value = user.role;
            document.getElementById('userStatus').value = user.status;
            
            // Hide password fields for editing (optional)
            passwordField.classList.add('hidden');
            confirmField.classList.add('hidden');
            emailVerificationField.classList.remove('hidden');
        }
        
        modal.classList.remove('hidden');
    }
    
    function closeModal() {
        document.getElementById('userModal').classList.add('hidden');
        document.getElementById('quickViewModal').classList.add('hidden');
    }
    
    function quickView(userId) {
        fetch(`/admin/user-management/${userId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showQuickView(data.user);
                }
            });
    }
    
    function showQuickView(user) {
        const modal = document.getElementById('quickViewModal');
        // Populate modal with user details
        modal.classList.remove('hidden');
    }
    
    function deleteUser(userId, userName) {
        if (confirm(`Are you sure you want to delete ${userName}? This action cannot be undone.`)) {
            fetch(`/admin/user-management/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('User deleted successfully', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message, 'error');
                }
            });
        }
    }
    
    function bulkAction(action) {
        const selectedIds = Array.from(document.querySelectorAll('.user-checkbox:checked'))
            .map(cb => cb.value);
            
        if (selectedIds.length === 0) {
            showToast('Please select at least one user', 'warning');
            return;
        }
        
        let confirmMessage = '';
        switch(action) {
            case 'delete':
                confirmMessage = `Delete ${selectedIds.length} selected users?`;
                break;
            case 'block':
                confirmMessage = `Block ${selectedIds.length} selected users?`;
                break;
            case 'activate':
                confirmMessage = `Activate ${selectedIds.length} selected users?`;
                break;
        }
        
        if (!confirm(confirmMessage)) return;
        
        fetch('/admin/users/bulk-action', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: action,
                users: selectedIds
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message, 'error');
            }
        });
    }
    
    function exportUsers() {
        const selectedIds = Array.from(document.querySelectorAll('.user-checkbox:checked'))
            .map(cb => cb.value);
            
        let url = '/admin/users/export';
        if (selectedIds.length > 0) {
            url += '?users=' + selectedIds.join(',');
        }
        
        window.location.href = url;
    }
    
    function showToast(message, type = 'info') {
        const container = document.querySelector('.toast-container');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <div class="mr-3">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 
                               type === 'error' ? 'fa-times-circle' : 
                               type === 'warning' ? 'fa-exclamation-triangle' : 
                               'fa-info-circle'}"></i>
            </div>
            <div class="flex-1">${message}</div>
            <button onclick="this.parentElement.remove()" class="ml-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }
    
    function showToastMessages() {
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
        
        @if($errors->any())
            @foreach($errors->all() as $error)
                showToast('{{ $error }}', 'error');
            @endforeach
        @endif
    }
    
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('userPassword');
        const icon = document.getElementById('passwordIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            passwordInput.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }
    
    function sendResetLink(userId) {
        fetch(`/admin/users/${userId}/send-reset-link`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Password reset link sent successfully', 'success');
            } else {
                showToast(data.message, 'error');
            }
        });
    }
    
    function impersonateUser(userId) {
        if (confirm('Impersonate this user? You will be logged in as them.')) {
            fetch(`/admin/users/${userId}/impersonate`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    showToast(data.message, 'error');
                }
            });
        }
    }
</script>
@endpush