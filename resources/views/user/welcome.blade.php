@extends("user.layouts.master-layouts.plain")

@section('title', 'Welcome | Luxorix')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="newsletter-route" content="{{ route('newsletter.subscribe') }}">



@section("content")
@if($header)
<section class="relative isolate h-screen w-full flex flex-col items-center justify-center overflow-hidden bg-theme-page">

    {{-- 1. Grain / Noise Overlay --}}
    <div class="absolute inset-0 z-0 opacity-[0.05] pointer-events-none mix-blend-overlay"
        style="background-image: url('https://grainy-gradients.vercel.app/noise.svg'); background-size: 100px;">
    </div>

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-theme-primary/10 blur-[120px] rounded-full pointer-events-none animate-pulse duration-[4000ms]"></div>

    <div class="absolute bottom-0 left-0 w-full h-40 bg-gradient-to-t from-theme-page via-theme-page/80 to-transparent z-10"></div>

    <div class="relative z-20 text-center px-4 flex flex-col items-center max-w-4xl mx-auto">

        <div class="mb-8 flex flex-col items-center gap-4 animate-fade-in-up">
            {{-- Vertical Line --}}
            <div class="h-16 w-[1px] bg-gradient-to-b from-transparent via-theme-primary/50 to-transparent"></div>

            <span class="font-body text-[10px] tracking-[0.4em] uppercase text-theme-muted/60">
                {!! $header->eyebrow_text !!}
            </span>
        </div>

        <h1 class="font-heading text-6xl md:text-8xl lg:text-[10rem] leading-[0.8] tracking-tighter mb-8 
                   text-transparent bg-clip-text bg-gradient-to-b from-theme-content via-theme-primary-light to-theme-primary-dark
                   drop-shadow-2xl mix-blend-normal uppercase">
            {!! $header->main_heading !!}
        </h1>

        {{-- Description Area --}}
        <div class="flex flex-col items-center gap-6 max-w-lg mx-auto">
            <p class="font-body text-xs md:text-sm text-theme-muted font-light leading-relaxed tracking-wide">

                {{-- Highlight Text (The Gold Part) --}}
                @if($header->highlight_text)
                <span class="text-theme-primary font-medium block mb-1">
                    {!! $header->highlight_text !!}
                </span>
                @endif

                {{-- Regular Description (The White/Gray Part) --}}
                {!! $header->description !!}
            </p>

            {{-- CTA Button --}}
            @if($header->cta_text)
            <a href="{{ $header->cta_link }}" class="group flex flex-col items-center gap-4 mt-12 opacity-60 hover:opacity-100 transition-opacity cursor-pointer">

                <span class="font-body text-[10px] uppercase tracking-[0.2em] text-theme-content group-hover:text-theme-primary transition-colors">
                    {!! $header->cta_text !!}
                </span>

                {{-- Animated Line --}}
                <div class="w-[1px] h-12 bg-theme-content/20 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1/2 bg-theme-primary animate-slide-down"></div>
                </div>
            </a>
            @endif
        </div>

    </div>

    {{-- Bottom Border --}}
    <div class="absolute bottom-0 inset-x-0 border-b border-theme-content/10 w-full z-0 pointer-events-none"></div>

    {{-- Custom Animations --}}
    <style>
        @keyframes slide-down {
            0% {
                transform: translateY(-100%);
            }

            100% {
                transform: translateY(200%);
            }
        }

        .animate-slide-down {
            animation: slide-down 1.5s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }
    </style>
</section>
@endif





<!-- //SALE\\ -->
@include("user.components.sale-timer")


<!-- TOP SELLING -->
@include('user.components.top-selling-section')



<section class="py-16 bg-theme-page">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between sm:justify-center items-center gap-4 sm:gap-8 mb-12 py-4">

            <div class="flex flex-col items-center w-1/4 min-w-[70px]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 sm:w-10 sm:h-10 mb-1 text-theme-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2l3 7h7l-5.5 4.5 2 7-6-4.5-6 4.5 2-7L2 9h7l3-7z" />
                </svg>
                <span class="text-[10px] sm:text-xs md:text-sm font-body font-medium text-theme-muted text-center tracking-wider">
                    1 YEAR<br>WARRANTY
                </span>
            </div>

            <div class="flex flex-col items-center w-1/4 min-w-[70px]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 sm:w-10 sm:h-10 mb-1 text-theme-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v6h6M20 20v-6h-6M4 14v6h6M20 10V4h-6" />
                </svg>
                <span class="text-[10px] sm:text-xs md:text-sm font-body font-medium text-theme-muted text-center tracking-wider">
                    7 DAYS<br>REPLACEMENT
                </span>
            </div>

            <div class="flex flex-col items-center w-1/4 min-w-[70px]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 sm:w-10 sm:h-10 mb-1 text-theme-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7h13v13H3zM16 7h5v6h-5zM16 13h5v7h-5z" />
                </svg>
                <span class="text-[10px] sm:text-xs md:text-sm font-body font-medium text-theme-muted text-center tracking-wider">
                    FREE<br>DELIVERY
                </span>
            </div>

            <div class="flex flex-col items-center w-1/4 min-w-[70px]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 sm:w-10 sm:h-10 mb-1 text-theme-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a6 6 0 00-5-5.91M9 20H4v-2a6 6 0 015-5.91M12 12a5 5 0 100-10 5 5 0 000 10z" />
                </svg>
                <span class="text-[10px] sm:text-xs md:text-sm font-body font-medium text-theme-muted text-center tracking-wider">
                    1,000,000+<br>USERS
                </span>
            </div>
        </div>

        @if($video && $video->video_link)
        <div class="relative w-full rounded-2xl overflow-hidden aspect-video 
                    border-[3px] border-theme-primary/50 shadow-glow 
                    transition-shadow duration-500">

            <video
                src="{{ asset('storage/' . $video->video_link) }}"
                autoplay
                loop
                muted
                playsinline
                class="absolute top-0 left-0 w-full h-full object-cover">
                Your browser does not support the video tag.
            </video>

            <span class="absolute bottom-3 right-3 text-theme-primary text-xs font-bold uppercase bg-theme-page/60 backdrop-blur-sm px-3 py-1 rounded-full tracking-widest">
                Live Demo
            </span>
        </div>
        @endif

    </div>
</section>

<style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Smooth Scroll Snap behavior */
    .snap-x-mandatory {
        scroll-snap-type: x mandatory;
        scroll-behavior: smooth;
    }

    .snap-center {
        scroll-snap-align: center;
    }
</style>

<section class="py-20 bg-theme-page relative overflow-hidden">

    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[500px]  rounded-full pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 relative z-10">

        <div class="text-center mb-16">
            <span class="font-heading text-theme-primary tracking-[0.4em] text-xs uppercase mb-4 block">
                The Curated Edit
                <h2 class="font-serif mt-5 text-4xl  sm:text-5xl leading-none 
        text-transparent bg-clip-text bg-gradient-to-b from-white via-white to-white/40
        uppercase tracking-widest font-extralight mix-blend-screen
        transition-transform duration-1000 ease-out group-hover/header:translate-x-4">
                    SHOP BY CATEGORY
                </h2>

                <h2 class="absolute top-0 left-4 md:left-8 font-serif text-3xl md:text-8xl lg:text-6xl leading-none 
        text-white/10 uppercase tracking-widest pointer-events-none blur-md
        transition-transform duration-1000 ease-out group-hover/header:-translate-x-4">
                    SHOP BY CATEGORY
                </h2>

                <!-- Decorative Divider (like your rotated square + lines) -->
                <div class="mt-8 flex justify-center items-center gap-4">
                    <div class="h-[1px] w-12 bg-theme-muted/30"></div>
                    <div class="w-2 h-2 rotate-45 border border-theme-primary"></div>
                    <div class="h-[1px] w-12 bg-theme-muted/30"></div>
                </div>
        </div>

        <div id="categoryCarousel"
            class="flex overflow-x-auto hide-scrollbar snap-x-mandatory pb-12
                    sm:flex-wrap sm:justify-center sm:gap-12 sm:overflow-visible">

            <div class="shrink-0 w-[calc(50vw-110px)] sm:hidden"></div>

            @foreach($categories as $index => $category)
            <div class="snap-center shrink-0 w-[220px] sm:w-auto flex flex-col items-center group cursor-pointer mx-2 sm:mx-0"
                data-index="{{ $index }}">

                <div class="relative w-[140px] h-[140px] mb-8">

                    <div class="absolute inset-[-10px] border border-theme-primary/30 rounded-full animate-spin-slow opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>

                    <div class="absolute inset-[-5px] border border-dashed border-theme-content/20 rounded-full animate-spin-reverse-slow"></div>

                    <div class="relative w-full h-full rounded-full overflow-hidden border-2 border-theme-surface shadow-card z-10 group-hover:scale-105 transition-transform duration-500 ease-out">

                        @if(!empty($category->image))
                        <img src="{{ asset('storage/'.$category->image) }}"
                            alt="{{ $category->title }}"
                            class="w-full h-full object-cover filter grayscale group-hover:grayscale-0 transition-all duration-700">
                        @else
                        <div class="w-full h-full bg-theme-surface flex items-center justify-center">
                            <span class="font-heading text-4xl text-theme-primary/50 italic">{{ substr($category->title, 0, 1) }}</span>
                        </div>
                        @endif

                        <div class="absolute inset-0 bg-theme-primary/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>
                </div>

                <div class="text-center">
                    <h3 class="font-heading text-theme-content text-lg tracking-widest uppercase mb-2 group-hover:text-theme-primary transition-colors duration-300">
                        {{ $category->title }}
                    </h3>
                    <div class="h-[1px] w-0 bg-theme-primary mx-auto group-hover:w-12 transition-all duration-500"></div>
                </div>
            </div>
            @endforeach

            <div class="shrink-0 w-[calc(50vw-110px)] sm:hidden"></div>

        </div>

        <div class="flex justify-center gap-3 sm:hidden mt-2">
            @foreach($categories as $index => $category)
            <div class="slide-dot w-1.5 h-1.5 rounded-full bg-theme-content/20 transition-all duration-300" id="dot-{{$index}}"></div>
            @endforeach
        </div>

    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const carousel = document.getElementById('categoryCarousel');
        const dots = document.querySelectorAll('.slide-dot');
        const itemWidth = 236; // 220px card + 16px margin
        const scrollIntervalTime = 3000;
        let autoScrollTimer;
        let isUserInteracting = false;

        // 1. Highlight Active Dot
        const updateActiveDot = () => {
            if (window.innerWidth >= 640) return;

            const scrollCenter = carousel.scrollLeft + (window.innerWidth / 2);
            let activeIndex = Math.floor(scrollCenter / itemWidth);

            if (activeIndex < 0) activeIndex = 0;
            if (activeIndex >= dots.length) activeIndex = dots.length - 1;

            dots.forEach((dot, idx) => {
                if (idx === activeIndex) {
                    // Active State: Gold & Larger
                    dot.classList.remove('bg-theme-content/20');
                    dot.classList.add('bg-theme-primary', 'scale-125');
                } else {
                    // Inactive State: Faded White
                    dot.classList.add('bg-theme-content/20');
                    dot.classList.remove('bg-theme-primary', 'scale-125');
                }
            });
        };

        // 2. Auto Scroll
        const startAutoScroll = () => {
            if (window.innerWidth >= 640) return;
            autoScrollTimer = setInterval(() => {
                if (isUserInteracting) return;
                const maxScroll = carousel.scrollWidth - carousel.clientWidth;
                if (carousel.scrollLeft >= maxScroll - 50) {
                    carousel.scrollTo({
                        left: 0,
                        behavior: 'smooth'
                    });
                } else {
                    carousel.scrollBy({
                        left: itemWidth,
                        behavior: 'smooth'
                    });
                }
            }, scrollIntervalTime);
        };

        const stopAutoScroll = () => {
            clearInterval(autoScrollTimer);
            isUserInteracting = true;
            setTimeout(() => {
                isUserInteracting = false;
                startAutoScroll();
            }, 5000);
        };

        carousel.addEventListener('scroll', updateActiveDot);
        carousel.addEventListener('touchstart', stopAutoScroll, {
            passive: true
        });

        updateActiveDot();
        startAutoScroll();
    });
</script>
<section class="py-12 md:py-16 bg-theme-page">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- HEADER & TABS --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 border-b border-gray-700 pb-6 gap-6">

            <!-- Heading & Description -->
            <div class="text-left relative max-w-2xl">
                <!-- Label / Accent (Optional) -->
                <span class="font-heading text-theme-primary tracking-[0.2em] text-xs uppercase mb-2 block">
                    Curated Selection
                </span>

                <!-- Main Heading -->
                <h2 class="font-serif text-3xl md:text-3xl lg:text-6xl leading-none 
        text-transparent bg-clip-text bg-gradient-to-b from-white via-white to-white/40
        uppercase tracking-widest font-extralight mix-blend-screen
        transition-transform duration-1000 ease-out group-hover/header:translate-x-4">
                    EXCLUSIVE COLLECTION
                </h2>

                <h2 class="absolute top-0 left-4 md:left-8 font-serif text-3xl md:text-8xl lg:text-6xl leading-none 
        text-white/10 uppercase tracking-widest pointer-events-none blur-md
        transition-transform duration-1000 ease-out group-hover/header:-translate-x-4">
                    Exclusive Collection
                </h2>

                <!-- Description -->
                <p class="mt-4 text-theme-muted text-base md:text-lg font-medium leading-relaxed max-w-xl">
                    "Elegance is not about being noticed, but about being remembered."
                </p>

                <!-- Decorative Divider -->
                <div class="mt-6 flex justify-start items-center gap-3">
                    <div class="h-[1px] w-12 bg-theme-muted/30"></div>
                    <div class="w-2 h-2 rotate-45 border border-theme-primary"></div>
                    <div class="h-[1px] w-12 bg-theme-muted/30"></div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="flex gap-6 overflow-x-auto hide-scrollbar mt-4 md:mt-0">
                @foreach($categories as $cat)
                @php
                $isActive = (isset($activeSlug) && $activeSlug === $cat->slug)
                || (!isset($activeSlug) && $loop->first);
                @endphp

                <button
                    type="button"
                    data-slug="{{ $cat->slug }}"
                    onclick="filterCategory(this.dataset.slug, this)"
                    @class([ 'tab-btn text-sm relative z-50 font-bold uppercase pb-2 transition-all duration-300' , 'text-theme-primary border-b-2 border-theme-primary scale-105'=> $isActive,
                    'text-theme-muted border-b-2 border-transparent hover:text-theme-primary hover:scale-105' => !$isActive
                    ])
                    >
                    {{ $cat->name }}
                </button>
                @endforeach
            </div>

        </div>


        {{-- PRODUCT FEED --}}
        <div id="product-feed-container" class="min-h-[600px] transition-opacity duration-300 relative">
            {{-- Render default hero category and stacked categories on page load --}}
            @include('user.components.home-product-partial', [
            'heroCategory' => $heroCategory,
            'stackedCategories' => $stackedCategories
            ])
        </div>
    </div>
</section>

{{-- JAVASCRIPT --}}
<script>
    // 1. Debugging: Prove the script block itself is loading
    console.log("✅ Script block loaded successfully.");

    // 2. FIX: Attach function to 'window' to make it globally accessible to HTML onclick
    window.filterCategory = async function(slug, btn) {

        console.log("🖱️ Click detected. Slug:", slug);

        if (!slug) {
            console.error("❌ Error: Slug is missing!");
            return;
        }

        // --- UI Logic ---
        const allBtns = document.querySelectorAll('.tab-btn');
        allBtns.forEach(b => {
            b.classList.remove('text-theme-primary', 'border-theme-primary');
            b.classList.add('text-theme-muted', 'border-transparent');
        });

        btn.classList.remove('text-theme-muted', 'border-transparent');
        btn.classList.add('text-theme-primary', 'border-theme-primary');

        // --- Fetch Logic ---
        const container = document.getElementById('product-feed-container');
        if (container) container.style.opacity = '0.4';

        try {
            // Note: Ensure this route exists in your web.php
            const baseUrl = "{{ route('home.products.fetch') }}";
            const url = `${baseUrl}?category=${encodeURIComponent(slug)}`;

            console.log("🌐 Fetching:", url);

            const res = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!res.ok) throw new Error('HTTP ' + res.status);

            const html = await res.text();

            if (container) container.innerHTML = html;

        } catch (err) {
            console.error("❌ Fetch Error:", err);
            // Optional: alert('Unable to load products.');
        } finally {
            if (container) container.style.opacity = '1';
        }
    };
</script>



@php
$trustData = [
'Shipping Policy' => [
'icon' => 'fas fa-truck-fast', // Updated icon for speed
'desc' => 'Expedited logistics. Complimentary on orders over ₹5,000.'
],
'Customer Support' => [
'icon' => 'fas fa-headset',
'desc' => '24/7 Priority access. Dedicated concierge team.'
],
'Secure Payments' => [
'icon' => 'fas fa-fingerprint', // More modern icon
'desc' => '256-bit AES encryption. Zero-liability protection.'
],
'Warranty Info' => [
'icon' => 'fas fa-shield-halved',
'desc' => 'Comprehensive coverage against manufacturing anomalies.'
],
];
$i = 1;
@endphp

<section class="relative bg-theme-page border-t border-b border-theme-content/10 overflow-hidden">

    {{-- Background Noise/Grain (Optional texture for luxury feel) --}}
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPgo8cmVjdCB3aWR0aD0iNCIgaGVpZ2h0PSI0IiBmaWxsPSIjZmZmIi8+CjxyZWN0IHdpZHRoPSIxIiBoZWlnaHQ9IjEiIGZpbGw9IiMwMDAiLz4KPC9zdmc+');"></div>
    {{-- HEADER SECTION --}}
    <div class="container mx-auto px-6 py-24 text-center relative border-b border-theme-content/10">

   
        <!-- Small Label / Accent -->
        <span class="inline-block text-[10px] md:text-xs uppercase tracking-[0.4em] text-theme-primary mb-6 font-bold">
            The Gold Standard
        </span>

        <h2 class="font-serif text-3xl md:text-3xl lg:text-6xl leading-none 
        text-transparent bg-clip-text bg-gradient-to-b from-white via-white to-white/40
        uppercase tracking-widest font-extralight mix-blend-screen
        transition-transform duration-1000 ease-out group-hover/header:translate-x-4">
        WHY ELITER USERS
                </h2>

                <h2 class="absolute top-0 left-4 md:left-8 font-serif text-3xl md:text-8xl lg:text-6xl leading-none 
        text-white/10 uppercase tracking-widest pointer-events-none blur-md
        transition-transform duration-1000 ease-out group-hover/header:-translate-x-4">
        WHY ELITER USERS
                </h2>

        <!-- Description -->
        <p class="relative max-w-2xl mt-3 mx-auto text-base md:text-lg text-theme-muted font-medium leading-relaxed">
            Experience an ecosystem designed for distinction, precision, and unmatched excellence.
        </p>

        <!-- Decorative Divider -->
        <div class="mt-3 flex justify-center items-center gap-4">
            <div class="h-[1px] w-16 bg-theme-muted/30"></div>
            <div class="w-3 h-3 rotate-45 border border-theme-primary"></div>
            <div class="h-[1px] w-16 bg-theme-muted/30"></div>
        </div>

    </div>




    {{-- THE GRID (Horizontal Scroll on Mobile, Grid on Desktop) --}}
    <div class="flex flex-nowrap overflow-x-auto snap-x snap-mandatory md:grid md:grid-cols-4 md:overflow-visible divide-x divide-theme-content/10 scrollbar-hide">

        @foreach ($trustData as $title => $data)
        @php
        $pageSlug = strtolower(str_replace(' ', '-', $title));
        $index = str_pad($i++, 2, '0', STR_PAD_LEFT);
        @endphp

        {{-- CARD ITEM --}}
        {{-- Mobile: w-[85vw] + aspect-square (Square Box) --}}
        {{-- Desktop: Auto width + aspect-auto --}}
        <div class="group relative flex-none w-[85vw] aspect-square snap-center border-r border-theme-content/10 md:w-auto md:aspect-auto md:border-r-0 md:h-[400px] flex flex-col justify-between p-8 md:p-10 transition-colors duration-500 hover:bg-theme-content/5">

            {{-- Top: Icon & Number --}}
            <div class="flex justify-between items-start">
                <div class="text-2xl text-theme-muted group-hover:text-theme-primary transition-colors duration-300">
                    <i class="{{ $data['icon'] }}"></i>
                </div>
                <span class="font-mono text-xs text-theme-content/20 font-bold group-hover:text-theme-primary transition-colors">
                    / {{ $index }}
                </span>
            </div>

            {{-- Middle: Content --}}
            <div class="mt-auto mb-8">
                <h3 class="text-xl md:text-lg font-heading font-bold uppercase tracking-widest text-theme-content mb-3 group-hover:translate-x-1 transition-transform duration-300">
                    {{ $title }}
                </h3>
                <p class="text-sm text-theme-muted font-light leading-relaxed group-hover:text-theme-content transition-colors">
                    {{ $data['desc'] }}
                </p>
            </div>

            {{-- Bottom: Action --}}
            <div class="pt-6 border-t border-theme-content/10 flex items-center justify-between">
                <a href="{{ route('pages.show', $pageSlug) }}" class="text-[10px] font-bold uppercase tracking-[0.25em] text-theme-primary hover:text-theme-content transition-colors flex items-center gap-2 group-hover:gap-4 duration-300">
                    Read Policy <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            {{-- Hover Glow Effect (Desktop Only) --}}
            <div class="hidden md:block absolute inset-0 bg-gradient-to-b from-transparent to-theme-primary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
        </div>
        @endforeach

    </div>

    {{-- MOBILE PROGRESS BAR (Optional visual cue that it scrolls) --}}
    <div class="md:hidden h-1 w-full bg-theme-content/5">
        <div class="h-full bg-theme-primary w-1/4"></div>
    </div>

</section>
<section class="relative py-32 bg-theme-page overflow-hidden selection:bg-theme-primary selection:text-theme-inverted">

    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-theme-surface via-theme-page to-theme-page opacity-80 pointer-events-none"></div>

    <div class="absolute inset-0 flex justify-center pointer-events-none opacity-10">
        <div class="w-px h-full bg-gradient-to-b from-transparent via-theme-content to-transparent"></div>
        <div class="w-px h-full bg-gradient-to-b from-transparent via-theme-content to-transparent mx-[300px] hidden lg:block"></div>
    </div>

    <div class="max-w-3xl mx-auto px-6 relative z-10">

        <div class="flex justify-center mb-12">
            <div class="relative w-12 h-12 flex items-center justify-center border border-theme-primary/30 rotate-45">
                <div class="w-8 h-8 border border-theme-content/20"></div>
                <div class="absolute w-1 h-1 bg-theme-primary rounded-full shadow-glow"></div>
            </div>
        </div>

        <div class="text-center mb-16 space-y-6">

            <p class="font-body text-[10px] text-theme-primary uppercase tracking-[0.4em] animate-pulse">
                Members Only
            </p>

            <h2 class="font-heading text-4xl sm:text-6xl text-theme-content uppercase tracking-widest leading-tight">
                The <span class="text-transparent bg-clip-text bg-gradient-to-r from-theme-primary via-theme-primary-light to-theme-primary">Archive</span>
            </h2>

            <div class="w-24 h-px bg-gradient-to-r from-transparent via-theme-primary/50 to-transparent mx-auto"></div>

            <p class="font-accent text-theme-muted text-lg italic max-w-lg mx-auto">
                "Access the unseen. Curated edits and private invitations for the discerning few."
            </p>
        </div>

        <form class="relative max-w-md mx-auto group">

            <div class="relative flex flex-col sm:flex-row items-end gap-6 sm:gap-0">

                <div class="relative w-full">
                    <input type="email" id="email" placeholder=" "
                        class="peer block w-full bg-transparent border-b border-theme-content/20 py-4 text-theme-content font-body text-sm tracking-wide focus:border-theme-primary focus:outline-none transition-colors duration-500 placeholder-transparent" />

                    <label for="email"
                        class="absolute left-0 top-4 text-theme-muted font-body text-xs uppercase tracking-widest transition-all duration-300 pointer-events-none
                                  peer-placeholder-shown:text-xs peer-placeholder-shown:top-4 peer-placeholder-shown:text-theme-muted/50
                                  peer-focus:-top-4 peer-focus:text-[10px] peer-focus:text-theme-primary peer-valid:-top-4 peer-valid:text-[10px] peer-valid:text-theme-primary">
                        Enter Digital Signature
                    </label>

                    <div class="absolute bottom-0 left-0 h-[1px] w-0 bg-theme-primary shadow-glow transition-all duration-700 peer-focus:w-full peer-valid:w-full"></div>
                </div>

                <button type="button"
                    class="w-full sm:w-auto sm:ml-8 py-4 border-b border-transparent hover:border-theme-primary group/btn transition-all duration-300">
                    <span class="font-body font-bold text-xs text-theme-content uppercase tracking-[0.25em] group-hover/btn:text-theme-primary transition-colors">
                        Request Access
                    </span>
                </button>
            </div>

            <div class="mt-8 flex justify-between items-center opacity-40 hover:opacity-100 transition-opacity duration-500">
                <span class="font-body text-[10px] text-theme-muted uppercase tracking-wider">
                    Encryption: 256-Bit
                </span>
                <div class="flex items-center gap-2">
                    <span class="w-1 h-1 rounded-full bg-theme-primary"></span>
                    <span class="font-body text-[10px] text-theme-muted uppercase tracking-wider">
                        Waitlist Active
                    </span>
                </div>
            </div>

        </form>

        <div class="absolute top-0 left-0 w-4 h-4 border-t border-l border-theme-content/20"></div>
        <div class="absolute top-0 right-0 w-4 h-4 border-t border-r border-theme-content/20"></div>
        <div class="absolute bottom-0 left-0 w-4 h-4 border-b border-l border-theme-content/20"></div>
        <div class="absolute bottom-0 right-0 w-4 h-4 border-b border-r border-theme-content/20"></div>

    </div>
</section>
@endsection

@section('scripts')


@endsection