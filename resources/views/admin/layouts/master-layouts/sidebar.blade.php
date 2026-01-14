<!-- Sidebar -->
<aside class="bg-gray-900 text-white w-64 h-screen p-0 flex flex-col shadow-lg">

    <!-- Scrollable Top Logo + Nav -->
    <div class="flex-1 overflow-y-auto">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-800 bg-gray-800">
            <h1 class="text-2xl font-bold flex items-center gap-2 text-blue-400">
                <i class="fas fa-cog"></i>  Kamran 
            </h1>
        </div>

        <!-- Navigation -->
        <nav class="py-4 px-2">
            <ul class="space-y-1">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                        <i class="fas fa-tachometer-alt w-5"></i> Dashboard
                    </a>
                </li>

                <!-- Admin + Subadmin1 -->
                @if(in_array(Auth::user()->role, ['admin', 'subadmin1']))
                    <li>
                        <a href="{{ route("admin.products.index") }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                            <i class="fas fa-box w-5"></i> Products Management
                        </a>
                    </li>
                    <li>
                        <a href="{{ route("admin.sliders") }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                            <i class="fas fa-file-alt w-5"></i> Slider Management
                        </a>
                    </li>
                    <li>
                        <a href="{{ route("admin.categories.index") }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                            <i class="fas fa-envelope w-5"></i> Category Management
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                            <i class="fas fa-users-cog w-5"></i> Team & CEO Info
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                            <i class="fas fa-tags w-5"></i> Edit Static Pages
                        </a>
                    </li>
                @endif

                <!-- Admin + Subadmin2 -->
                @if(in_array(Auth::user()->role, ['admin', 'subadmin2']))
                    <li>
                        <a href="{{ route("admin.orders") }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                            <i class="fas fa-shopping-cart w-5"></i> Orders
                        </a>
                    </li>
                @endif

                <!-- Admin Only -->
                @if(Auth::user()->role === 'admin')
                    <li>
                        <a href="#" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                            <i class="fas fa-users w-5"></i> Verified Users List
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                            <i class="fas fa-images w-5"></i> Contact Messages
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                            <i class="fas fa-star w-5"></i> Reviews
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                            <i class="fas fa-percent w-5"></i> Discounts
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                            <i class="fas fa-chart-line w-5"></i> Analytics
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                            <i class="fas fa-cog w-5"></i> Settings
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>

    <!-- Sticky Bottom: Profile + Logout -->
    <div class="border-t border-gray-800 px-4 py-5 bg-gray-800">
        <!-- Profile Info -->
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-user text-white"></i>
            </div>
            <div>
                <p class="font-semibold">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
            </div>
        </div>

        <!-- Logout Button -->
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white py-2 rounded-md transition">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
</aside>
