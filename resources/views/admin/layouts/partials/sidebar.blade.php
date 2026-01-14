<aside 
    x-cloak
    class="bg-gray-900 text-white w-64 h-screen p-0 flex flex-col shadow-lg 
           fixed inset-y-0 left-0 z-40 transform
           transition-transform duration-300 ease-in-out
           -translate-x-full md:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : ''"
    @click.outside="sidebarOpen = false"
>

    <div class="flex-1 overflow-y-auto">

        <div class="px-6 py-5 border-b border-gray-800 bg-gray-800">
            <h1 class="text-2xl font-bold flex items-center gap-2 text-blue-400">
                <i class="fas fa-shield-alt"></i> Admin Panel 
            </h1>
        </div>

        <nav class="py-4 px-2">
            <ul class="space-y-1">

                @if(Auth::guard('admin')->user()?->hasPermission('main_dashboard'))
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                        <i class="fas fa-tachometer-alt w-5"></i> Dashboard
                    </a>
                </li>
                @endif

                @if(Auth::guard('admin')->user()?->hasPermission('admin_management'))
                <li>
                    <a href="{{ route('admin.admins-management') }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                        <i class="fas fa-user-shield w-5"></i> Admin Management
                    </a>
                </li>
                @endif

                @if(Auth::guard('admin')->user()?->hasPermission('users'))
                <li>
                    <a href="{{ route('admin.user-management') }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                        <i class="fas fa-users w-5"></i> User Management
                    </a>
                </li>
                @endif

                @if(Auth::guard('admin')->user()?->hasPermission('store_settings'))
                <li>
                    <a href="{{ route('admin.store-settings') }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                        <i class="fas fa-store-alt w-5"></i> Store Settings
                    </a>
                </li>
                @endif

                @if(Auth::guard('admin')->user()?->hasPermission('product_management'))
                <li>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                        <i class="fas fa-box w-5"></i> Products
                    </a>
                </li>
                @endif

                @if(Auth::guard('admin')->user()?->hasPermission('category_management'))
                <li>
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                        <i class="fas fa-layer-group w-5"></i> Categories
                    </a>
                </li>
                @endif

                @if(Auth::guard('admin')->user()?->hasPermission('slider_management'))
                <li>
                    <a href="{{ route('admin.sliders') }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                        <i class="fas fa-images w-5"></i> Sliders
                    </a>
                </li>
                @endif

                @if(Auth::guard('admin')->user()?->hasPermission('order_management'))
                <li>
                    <a href="{{ route('admin.orders') }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                        <i class="fas fa-shopping-cart w-5"></i> Orders
                    </a>
                </li>
                @endif

                @if(Auth::guard('admin')->user()?->hasPermission('sales_offers'))
                <li>
                    <a href="{{ route('admin.sales-offers.index') }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                        <i class="fas fa-percentage w-5"></i> Sales & Offers
                    </a>
                </li>
                @endif

                @if(Auth::guard('admin')->user()?->hasPermission('referral_management'))
                <li>
                    <a href="{{ route('admin.referral.index') }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                        <i class="fas fa-handshake w-5"></i> Referral Partners
                    </a>
                </li>
                @endif

                @if(Auth::guard('admin')->user()?->hasPermission('about_us_management'))
                <li>
                    <a href="{{ route('admin.about') }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                        <i class="fas fa-info-circle w-5"></i> About Us
                    </a>
                </li>
                @endif

                @if(Auth::guard('admin')->user()?->hasPermission('contact_messages'))
                <li>
                    <a href="{{ route('admin.contact.index') }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                        <i class="fas fa-envelope w-5"></i> Contact Messages
                    </a>
                </li>
                @endif

                @if(Auth::guard('admin')->user()?->hasPermission('newsletters'))
                <li>
                    <a href="{{ route('admin.newsletter.index') }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                        <i class="fas fa-paper-plane w-5"></i> Newsletter
                    </a>
                </li>
                @endif

                @if(Auth::guard('admin')->user()?->hasPermission('content_management'))
                <li>
                    <a href="{{ route('admin.cms.index') }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                        <i class="fas fa-file-alt w-5"></i> CMS
                    </a>
                </li>
                @endif

                @if(Auth::guard('admin')->user()?->hasPermission('analytics'))
                <li>
                    <a href="{{ route('admin.analytics.index') }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                        <i class="fas fa-chart-line w-5"></i> Analytics
                    </a>
                </li>
                @endif

                @if(Auth::guard('admin')->user()?->hasPermission('admin_chatting'))
                <li>
                    <a href="{{ route('admin.chatting.index') }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                        <i class="fas fa-comment-dots w-5"></i> Admin Chat
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-gray-700 transition">
                        <i class="fas fa-star w-5"></i> Reviews
                    </a>
                </li>
                @endif

            </ul>
        </nav>

        <div class="border-t border-gray-800 px-4 py-5 bg-gray-800 mt-auto">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-600 flex items-center justify-center mr-3">
                    @if(Auth::guard('admin')->user()?->profile_pic)
                        <img src="{{ asset('storage/admins/' . Auth::guard('admin')->user()->profile_pic) }}" 
                             alt="{{ Auth::guard('admin')->user()?->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-user text-white text-sm"></i>
                    @endif
                </div>
                <div>
                    <p class="font-semibold">{{ Auth::guard('admin')->user()?->name }}</p>
                    <p class="text-xs text-gray-400">{{ Auth::guard('admin')->user()?->email }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white py-2 rounded-md transition">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>

    </div>
</aside>