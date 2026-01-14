@php
$navbarData = \App\Http\Controllers\User\Partial\NavbarController::getData();
@endphp

<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Luxorix | Premium Tech for Youth</title>

<script>
    function adjustBannerHeight() {
        const banner = document.getElementById("top-offer-banner");
        if (banner) {
            document.documentElement.style.setProperty(
                "--banner-height",
                banner.offsetHeight + "px"
            );
        }
    }
    window.addEventListener("load", adjustBannerHeight);
    window.addEventListener("resize", adjustBannerHeight);

    window.appConfig = {
        csrfToken: '{{ csrf_token() }}',
        navbarData: @json($navbarData)
    };
</script>

<!-- 🎯 Premium Top Offer Banner -->
<!-- <div id="top-offer-banner"
     class="bg-gradient-to-r from-brand-gold-dark via-brand-gold to-brand-gold-light 
            text-brand-black z-50 shadow-luxury-card border-b border-brand-gold/20 transition-all duration-300">
  <div class="max-w-7xl mx-auto px-3 py-2 
              flex flex-row flex-wrap items-center justify-center gap-3">
    
    <p class="flex items-center gap-2 text-center text-sm font-sans">
      <span class="animate-pulse">⚡</span>
      <span class="font-bold tracking-wide">EARN WITH US!</span>
      <span class="hidden sm:inline font-medium">Join our Exclusive Referral Program</span>
    </p>
    
    <a href="{{ route('referral.create') }}"
       class="luxury-btn luxury-btn-primary px-4 py-2 text-xs font-bold tracking-wider transform hover:scale-105 transition-all duration-300"
       target="_blank" rel="noopener noreferrer">
       Join Now
    </a>
  </div>
</div> -->
<nav id="main-navbar" class="navbar py-4 px-6 shadow-card z-50 bg-theme-page/95 backdrop-blur-xl border-b border-theme-content/10 transition-all duration-500 sticky top-0">
    <div class="container mx-auto flex justify-between items-center">
        
    <a href="{{ route('user.welcome') }}" class="luxury-logo group flex items-center">
    <div class="relative z-50 w-10 h-10 flex items-center justify-center overflow-visible transition-all duration-300">
    
    <img src="{{ asset('images/logo.png') }}"
         alt="Logo"
         class="w-16 h-16 lg:w-24 lg:h-24 max-w-none object-contain transition-transform duration-300 group-hover:scale-110">

</div>
</a>



        <div class="luxury-desktop-nav-force text-sm font-body font-medium  lg:flex items-center gap-8">
            <a href="{{ route('user.welcome') }}" class="relative group py-2">
                <span class="text-theme-content group-hover:text-theme-primary transition-colors duration-300 uppercase tracking-widest text-xs">Home</span>
                <div class="absolute bottom-0 left-0 w-0 h-[1px] bg-theme-primary transition-all duration-300 group-hover:w-full"></div>
            </a>
            <a href="{{ route('user.watches') }}" class="relative group py-2">
                <span class="text-theme-content group-hover:text-theme-primary transition-colors duration-300 uppercase tracking-widest text-xs">Watches</span>
                <div class="absolute bottom-0 left-0 w-0 h-[1px] bg-theme-primary transition-all duration-300 group-hover:w-full"></div>
            </a>
            <a href="{{ route('user.object') }}" class="relative group py-2">
                <span class="text-theme-content group-hover:text-theme-primary transition-colors duration-300 uppercase tracking-widest text-xs">Objects</span>
                <div class="absolute bottom-0 left-0 w-0 h-[1px] bg-theme-primary transition-all duration-300 group-hover:w-full"></div>
            </a>
            <a href="{{ route('user.new-drops') }}" class="relative group py-2">
                <span class="text-theme-content group-hover:text-theme-primary transition-colors duration-300 uppercase tracking-widest text-xs">New Drops</span>
                <div class="absolute bottom-0 left-0 w-0 h-[1px] bg-theme-primary transition-all duration-300 group-hover:w-full"></div>
            </a>
            <a href="{{ route('user.about') }}" class="relative group py-2">
                <span class="text-theme-content group-hover:text-theme-primary transition-colors duration-300 uppercase tracking-widest text-xs">About</span>
                <div class="absolute bottom-0 left-0 w-0 h-[1px] bg-theme-primary transition-all duration-300 group-hover:w-full"></div>
            </a>
            <a href="{{ route('user.contact') }}" class="relative group py-2">
                <span class="text-theme-content group-hover:text-theme-primary transition-colors duration-300 uppercase tracking-widest text-xs">Contact</span>
                <div class="absolute bottom-0 left-0 w-0 h-[1px] bg-theme-primary transition-all duration-300 group-hover:w-full"></div>
            </a>
        </div>

        <div class="flex items-center space-x-5">
            <div class="cursor-pointer group" id="searchToggle">
                <i class="fas fa-search text-theme-muted group-hover:text-theme-primary transition-colors duration-300 text-sm"></i>
            </div>

            <div id="cartToggle" class="cursor-pointer group relative">
                <i class="fas fa-shopping-bag text-theme-muted group-hover:text-theme-primary transition-colors duration-300 text-sm"></i>
                <span id="cartBadge" class="absolute -top-2 -right-2 w-4 h-4 bg-theme-primary text-theme-inverted text-[9px] font-bold rounded-full flex items-center justify-center opacity-0 transition-opacity duration-300">0</span>
            </div>

            <div class="relative">
                <button id="userMenuButton" class="cursor-pointer group outline-none">
                    <i class="fas fa-user text-theme-muted group-hover:text-theme-primary transition-colors duration-300 text-sm"></i>
                </button>

                <div id="userDropdown" class="absolute right-0 mt-4 w-48 bg-theme-surface border border-theme-content/10 rounded-sm shadow-card hidden z-50 py-2">
                    @auth
                   
                    <form method="POST" action="{{ route('user.logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-3 text-xs text-red-400 hover:bg-theme-page hover:text-red-300 transition-colors text-left">
                            <i class="fas fa-sign-out-alt mr-3 w-4"></i>
                            <span class="font-body uppercase tracking-wider">Logout</span>
                        </button>
                    </form>
                    @endauth

                    @guest
                    <a href="{{ route('login.form') }}" class="flex items-center px-4 py-3 text-xs text-theme-muted hover:bg-theme-page hover:text-theme-primary transition-colors">
                        <i class="fas fa-sign-in-alt mr-3 w-4"></i>
                        <span class="font-body uppercase tracking-wider">Login</span>
                    </a>
                    <a href="{{ route('register.form') }}" class="flex items-center px-4 py-3 text-xs text-theme-muted hover:bg-theme-page hover:text-theme-primary transition-colors">
                        <i class="fas fa-user-plus mr-3 w-4"></i>
                        <span class="font-body uppercase tracking-wider">Sign Up</span>
                    </a>
                    @endguest
                </div>
            </div>

            <button id="mobile-menu-button" class="lg:hidden cursor-pointer group outline-none">
                <i class="fas fa-bars text-theme-muted group-hover:text-theme-primary transition-colors duration-300 text-lg"></i>
            </button>
        </div>
    </div>

    <div id="mobile-menu" class="lg:hidden mt-0 hidden transition-all duration-500 transform origin-top border-t border-theme-content/10 bg-theme-page">
        <div class="flex flex-col p-6 space-y-1">
            <a href="{{ route('user.welcome') }}" class="flex items-center justify-between py-4 border-b border-theme-content/5 group">
                <div class="flex items-center gap-4">
                    <i class="fas fa-home text-theme-primary w-5 text-center group-hover:scale-110 transition-transform"></i>
                    <span class="text-theme-muted group-hover:text-theme-content font-body text-sm uppercase tracking-widest transition-colors">Home</span>
                </div>
                <i class="fas fa-chevron-right text-xs text-theme-muted/30 group-hover:text-theme-primary transition-colors"></i>
            </a>
            <a href="{{ route('user.watches') }}" class="flex items-center justify-between py-4 border-b border-theme-content/5 group">
                <div class="flex items-center gap-4">
                    <i class="fas fa-clock text-theme-primary w-5 text-center group-hover:scale-110 transition-transform"></i>
                    <span class="text-theme-muted group-hover:text-theme-content font-body text-sm uppercase tracking-widest transition-colors">Watches</span>
                </div>
                <i class="fas fa-chevron-right text-xs text-theme-muted/30 group-hover:text-theme-primary transition-colors"></i>
            </a>
            <a href="{{ route('user.object') }}" class="flex items-center justify-between py-4 border-b border-theme-content/5 group">
                <div class="flex items-center gap-4">
                    <i class="fas fa-gem text-theme-primary w-5 text-center group-hover:scale-110 transition-transform"></i>
                    <span class="text-theme-muted group-hover:text-theme-content font-body text-sm uppercase tracking-widest transition-colors">Neck & Wrist</span>
                </div>
                <i class="fas fa-chevron-right text-xs text-theme-muted/30 group-hover:text-theme-primary transition-colors"></i>
            </a>
            <a href="" class="flex items-center justify-between py-4 border-b border-theme-content/5 group">
                <div class="flex items-center gap-4">
                    <i class="fas fa-bolt text-theme-primary w-5 text-center group-hover:scale-110 transition-transform"></i>
                    <span class="text-theme-muted group-hover:text-theme-content font-body text-sm uppercase tracking-widest transition-colors">New Drops</span>
                </div>
                <i class="fas fa-chevron-right text-xs text-theme-muted/30 group-hover:text-theme-primary transition-colors"></i>
            </a>
            <a href="{{ route('user.about') }}" class="flex items-center justify-between py-4 border-b border-theme-content/5 group">
                <div class="flex items-center gap-4">
                    <i class="fas fa-info-circle text-theme-primary w-5 text-center group-hover:scale-110 transition-transform"></i>
                    <span class="text-theme-muted group-hover:text-theme-content font-body text-sm uppercase tracking-widest transition-colors">About</span>
                </div>
                <i class="fas fa-chevron-right text-xs text-theme-muted/30 group-hover:text-theme-primary transition-colors"></i>
            </a>
            <a href="{{ route('user.contact') }}" class="flex items-center justify-between py-4 group">
                <div class="flex items-center gap-4">
                    <i class="fas fa-phone text-theme-primary w-5 text-center group-hover:scale-110 transition-transform"></i>
                    <span class="text-theme-muted group-hover:text-theme-content font-body text-sm uppercase tracking-widest transition-colors">Contact</span>
                </div>
                <i class="fas fa-chevron-right text-xs text-theme-muted/30 group-hover:text-theme-primary transition-colors"></i>
            </a>
        </div>
    </div>
</nav>
<!-- 🎯 Enhanced Search Overlay -->
<div class="luxury-overlay" id="searchOverlay"></div>

<!-- 🎯 Luxury Search Drawer -->
<div class="luxury-drawer search-drawer" id="searchDrawer">
    <div class="luxury-search-results flex-1 overflow-y-auto pr-0">

        <div class="luxury-drawer-content flex flex-col h-full">

            <!-- Header -->
            <div class="luxury-drawer-header">
                <h2 class="luxury-drawer-title">
                    <span class="text-theme-muted font-body font-light tracking-widest">DISCOVER</span>
                    <span class="luxury-gradient-text text-gradient-gold font-heading font-bold tracking-widest">TECH</span>
                </h2>
                <button class="luxury-close-btn" id="closeSearch">
                    <i class="fas fa-times text-brand-gray hover:text-brand-gold"></i>
                </button>
            </div>

          <!-- Search Input -->
<!-- Search Input -->
<div class="relative w-full mb-4">
    <input type="text"
        id="searchInput"
        placeholder="Search watches, accessories, tech..."
        class="w-full bg-theme-page/80 border-2 border-theme-primary/25 text-theme-content placeholder-theme-muted/70 px-6 py-4 rounded-none focus:border-theme-primary focus:shadow-[0_4px_20px_-5px_rgba(255,215,0,0.15)] focus:-translate-y-0.5 transition-all duration-300">
    
    <button id="searchIcon" class="absolute right-4 top-1/2 -translate-y-1/2 text-theme-primary text-xl hover:scale-125 hover:shadow-[0_0_8px_rgba(255,215,0,0.8)] transition-transform duration-300">
        <i class="fas fa-search"></i>
    </button>
</div>


            <!-- Scrollable Content -->
            <!-- Popular Searches -->
            <div id="popularSearches" class="luxury-section mb-6">
                <h3 class="luxury-section-title mb-2"> Trending Now</h3>
                <div class="luxury-tags-container flex flex-wrap gap-2">
                    @foreach($navbarData['newArrivals'] as $product)
                    <span class="luxury-tag">{{ $product->name }}</span>
                    @endforeach
                </div>
            </div>

            <!-- Recommended Products -->
            <div id="recommendedProducts" class="luxury-section mb-6">
                <h3 class="luxury-section-title mb-2">You Might Like</h3>
                <div class="luxury-products-grid grid grid-cols-1 gap-4" id="recommendedContainer">
                    @foreach($navbarData['featuredProducts'] as $product)
                    @include("user.components.navbar-cards", ['product' => $product])
                    @endforeach
                </div>
            </div>

            <!-- Search Results -->
            <div id="searchResults" class="luxury-section hidden">
                <h3 class="luxury-section-title mb-2">Search Results</h3>
                <div class="luxury-products-grid grid grid-cols-1 gap-4" id="searchResultsContainer"></div>
            </div>
        </div>
    </div>
</div>

<div id="cartOverlay" class="luxury-overlay"></div>

<div id="cartDrawer" class="luxury-drawer cart-drawer">
<div class="luxury-drawer-content flex flex-col h-full  overflow-y-auto"> 
           <!-- Header -->
           <div class="luxury-drawer-header flex-shrink-0">
                        <h2 class="luxury-drawer-title">
                <span class="text-theme-muted font-body font-light tracking-widest">YOUR</span>
                <span class="luxury-gradient-text text-gradient-gold font-heading font-bold tracking-widest">COLLECTION</span>
            </h2>
            <button id="closeCart" class="luxury-close-btn">
                <i class="fas fa-times text-theme-muted hover:text-theme-primary transition-colors "></i>
            </button>
        </div>

        <!-- Cart Items -->
        <div id="cartItems" class="luxury-cart-items flex-grow">      

    </div>


        <!-- Checkout Section -->
        <div id="checkoutContainer" class="luxury-checkout-section flex flex-col justify-end p-3 shadow-md flex-shrink-0">   
            
        <a id="checkoutBtn" 
   href="{{ route('user.checkout', ['mode' => 'cart']) }}"
   class="luxury-btn item-total-price luxury-btn-primary w-full text-center py-4 text-base font-extrabold whitespace-nowrap">Checkout Now — ₹0
</a>


        </div>



    <!-- Empty State -->
<!-- Empty State -->
<div id="emptyCart" class="luxury-empty-state text-center">
    <div class="luxury-empty-icon mb-4">
        <i class="fas fa-shopping-bag text-6xl text-theme-primary"></i>
    </div>

    <h3 class="text-theme-content font-heading text-xl uppercase font-bold mb-2 tracking-wide">Your Collection Awaits</h3>
    <p class="text-white rface font-body text-sm mb-6">Add some premium tech to get started</p>

    <a href="{{ route('user.watches') }}" 
       class="luxury-btn luxury-btn-primary font-body uppercase tracking-widest whitespace-nowrap text-sm sm:text-base md:text-lg lg:text-xl"
       style="max-width: 100%;"
       onclick="closeCartDrawer()">
        Explore 
    </a>
</div>


    </div>
</div>