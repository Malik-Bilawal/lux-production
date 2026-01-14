@extends('user.layouts.master-layouts.plain')
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $product->name }} - {{ config('app.name') }}</title>

@section('content')

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    theme: {
                        page: '#0f0f0f',
                        surface: '#1a1a1a',
                        deep: '#050505',
                        primary: '#D4AF37',
                        'primary-light': '#F6E6B6',
                        'primary-dark': '#AA8C2C',
                        content: '#FFFFFF',
                        muted: '#9CA3AF',
                        inverted: '#000000',
                        overlay: 'rgba(5, 5, 5, 0.98)',
                    }
                },
                fontFamily: {
                    body: ['Montserrat', 'sans-serif'],
                    heading: ['Cinzel', 'serif'],
                    accent: ['Playfair Display', 'serif'],
                },
                spacing: {
                    '128': '32rem',
                    '144': '36rem',
                },
                letterSpacing: {
                    tight: '-0.025em',
                    normal: '0em',
                    wide: '0.025em',
                    wider: '0.05em',
                    widest: '0.1em',
                },
                boxShadow: {
                    'glow': '0 0 25px rgba(212, 175, 55, 0.15)',
                    'glow-strong': '0 0 40px rgba(212, 175, 55, 0.25)',
                    'card': '0 15px 40px -10px rgba(0, 0, 0, 0.9)',
                    'luxury': '0 25px 50px -12px rgba(212, 175, 55, 0.15)',
                    'elegant': '0 30px 60px -15px rgba(0, 0, 0, 0.7)',
                },
                animation: {
                    'spin-slow': 'spin 12s linear infinite',
                    'spin-reverse-slow': 'spin 15s linear reverse infinite',
                    'fade-in': 'fadeIn 0.6s ease-in-out',
                    'slide-up': 'slideUp 0.4s ease-out',
                    'float': 'float 3s ease-in-out infinite',
                    'pulse-soft': 'pulseSoft 2s ease-in-out infinite',
                    'gradient': 'gradient 3s ease infinite',
                },
                keyframes: {
                    fadeIn: {
                        '0%': {
                            opacity: '0',
                            transform: 'translateY(10px)'
                        },
                        '100%': {
                            opacity: '1',
                            transform: 'translateY(0)'
                        }
                    },
                    slideUp: {
                        '0%': {
                            transform: 'translateY(20px)',
                            opacity: '0'
                        },
                        '100%': {
                            transform: 'translateY(0)',
                            opacity: '1'
                        }
                    },
                    float: {
                        '0%, 100%': {
                            transform: 'translateY(0)'
                        },
                        '50%': {
                            transform: 'translateY(-10px)'
                        }
                    },
                    pulseSoft: {
                        '0%, 100%': {
                            opacity: '1'
                        },
                        '50%': {
                            opacity: '0.7'
                        }
                    },
                    gradient: {
                        '0%, 100%': {
                            backgroundPosition: '0% 50%'
                        },
                        '50%': {
                            backgroundPosition: '100% 50%'
                        }
                    }
                }
            }
        }
    }
</script>

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">

<style>
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #1a1a1a;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #D4AF37 0%, #AA8C2C 100%);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #F6E6B6 0%, #D4AF37 100%);
    }

    /* Luxury Effects */
    .luxury-border {
        position: relative;
    }

    .luxury-border::before {
        content: '';
        position: absolute;
        top: -1px;
        left: -1px;
        right: -1px;
        bottom: -1px;
        background: linear-gradient(45deg, #D4AF37, transparent, #D4AF37, transparent, #D4AF37);
        border-radius: inherit;
        z-index: -1;
        opacity: 0.2;
        animation: gradient-border 4s linear infinite;
        background-size: 200% 200%;
    }

    @keyframes gradient-border {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    .gold-gradient {
        background: linear-gradient(135deg, #D4AF37 0%, #F6E6B6 50%, #D4AF37 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        background-size: 200% auto;
        animation: text-gradient 3s ease infinite;
    }

    @keyframes text-gradient {

        0%,
        100% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }
    }

    .image-mask {
        mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 1) 80%, rgba(0, 0, 0, 0));
        -webkit-mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 1) 80%, rgba(0, 0, 0, 0));
    }

    .swiper-button-next,
    .swiper-button-prev {
        color: rgba(212, 175, 55, 0.9) !important;
        background: rgba(15, 15, 15, 0.9);
        border: 1px solid rgba(212, 175, 55, 0.3);
        border-radius: 50%;
        width: 48px;
        height: 48px;
        backdrop-filter: blur(12px);
        transition: all 0.3s ease;
    }

    .swiper-button-next:hover,
    .swiper-button-prev:hover {
        background: rgba(212, 175, 55, 0.1);
        transform: scale(1.1);
        box-shadow: 0 0 20px rgba(212, 175, 55, 0.3);
    }

    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 20px;
        font-weight: bold;
    }

    .swiper-pagination-bullet {
        background: rgba(212, 175, 55, 0.5);
        width: 8px;
        height: 8px;
        opacity: 0.5;
    }

    .swiper-pagination-bullet-active {
        background: #D4AF37;
        opacity: 1;
        transform: scale(1.2);
    }

    .hover-glow:hover {
        box-shadow: 0 0 30px rgba(212, 175, 55, 0.2);
    }

    /* Smooth transitions */
    .smooth-transition {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Loading animation */
    .shimmer {
        background: linear-gradient(90deg,
                rgba(212, 175, 55, 0.1) 0%,
                rgba(212, 175, 55, 0.2) 50%,
                rgba(212, 175, 55, 0.1) 100%);
        background-size: 200% 100%;
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }

        100% {
            background-position: 200% 0;
        }
    }

    /* Elegant dividers */
    .divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.3), transparent);
    }

    /* Product image hover effect */
    .product-image-hover {
        transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .product-image-hover:hover {
        transform: scale(1.05);
    }

    /* Premium badge */
    .premium-badge {
        background: linear-gradient(135deg, #D4AF37 0%, #F6E6B6 100%);
        color: #000;
        position: relative;
        overflow: hidden;
    }

    .premium-badge::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transform: rotate(45deg);
        animation: shine 3s infinite;
    }

    @keyframes shine {
        0% {
            transform: translateX(-100%) rotate(45deg);
        }

        100% {
            transform: translateX(100%) rotate(45deg);
        }
    }
</style>
</head>

<div class="bg-theme-page text-theme-content font-body antialiased min-h-screen overflow-x-hidden"
    x-data="productPage()"
    x-init="init()">


    <!-- Notification Modal -->
    <div x-show="showNotifyModal"
        x-transition.opacity.duration.300ms
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        style="display: none;">
        <div class="relative w-full max-w-md transform transition-all"
            @click.away="closeNotifyModal"
            x-show="showNotifyModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">

            <div class="bg-theme-surface border border-theme-primary/20 rounded-xl shadow-2xl overflow-hidden">
                <!-- Header -->
                <div class="p-6 border-b border-white/10">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-theme-primary/20 to-transparent flex items-center justify-center">
                                <svg class="w-5 h-5 text-theme-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-heading text-lg text-theme-content">Notify When Available</h3>
                                <p class="text-sm text-theme-muted mt-1">Get notified when {{ $product->name }} is back in stock</p>
                            </div>
                        </div>
                        <button @click="closeNotifyModal" class="text-theme-muted hover:text-theme-primary smooth-transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-6">
                    <form @submit.prevent="submitNotification" class="space-y-4">
                        <!-- Email Input -->
                        <div>
                            <label for="notify-email" class="block text-xs font-medium text-theme-muted uppercase tracking-wider mb-2">
                                Email Address
                            </label>
                            <input type="email"
                                id="notify-email"
                                x-model="notification.email"
                                :disabled="notification.loading"
                                placeholder="your@email.com"
                                class="w-full px-4 py-3 bg-theme-deep border border-white/10 rounded-lg focus:outline-none focus:border-theme-primary focus:ring-2 focus:ring-theme-primary/20 smooth-transition text-theme-content placeholder-theme-muted/50"
                                :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': notification.errors.email }">
                            <template x-if="notification.errors.email">
                                <p class="mt-1 text-xs text-red-400" x-text="notification.errors.email[0]"></p>
                            </template>
                        </div>

                        <!-- Privacy Notice -->
                        <div class="bg-theme-deep/50 border border-white/5 p-4 rounded-lg">
                            <div class="flex items-start space-x-3">
                                <svg class="w-4 h-4 text-theme-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-xs text-theme-muted leading-relaxed">
                                    We'll only use your email to notify you about this product's availability. No spam, unsubscribe anytime.
                                </p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            :disabled="notification.loading"
                            class="w-full bg-gradient-to-r from-theme-primary to-theme-primary-dark text-theme-inverted hover:shadow-glow smooth-transition font-medium py-3 rounded-lg flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg x-show="notification.loading" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="notification.loading ? 'Processing...' : 'Notify Me'"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Toast -->
    <div x-show="showSuccessToast"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-2 opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="translate-y-2 opacity-0"
        class="fixed top-4 right-4 z-[9998] bg-gradient-to-r from-green-900/90 to-green-800/90 text-green-100 px-6 py-3 rounded-lg border border-green-700/50 shadow-2xl backdrop-blur-sm min-w-[300px]"
        style="display: none;">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium" x-text="successMessage"></span>
        </div>
    </div>

    <!-- Error Toast -->
    <div x-show="showErrorToast"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-2 opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="translate-y-2 opacity-0"
        class="fixed top-4 right-4 z-[9998] bg-gradient-to-r from-red-900/90 to-red-800/90 text-red-100 px-6 py-3 rounded-lg border border-red-700/50 shadow-2xl backdrop-blur-sm min-w-[300px]"
        style="display: none;">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.404 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
            <span class="font-medium" x-text="errorMessage"></span>
        </div>
    </div>




    <!-- Main Content -->
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6  border-b border-white/5 pb-6 animate-fade-in">

            <nav class="flex items-center space-x-3 text-[11px] uppercase tracking-[0.15em] font-medium text-theme-muted">
                <a href="{{ url('/') }}" class="hover:text-theme-content transition-colors duration-300">Home</a>

                <span class="text-white/10">/</span>

                @if($product->category)
                <a href="{{ route('category.show', $product->category->slug) }}" class="hover:text-theme-content transition-colors duration-300">
                    {{ $product->category->name }}
                </a>
                <span class="text-white/10">/</span>
                @endif

                <span class="text-theme-content">{{ $product->name }}</span>
            </nav>

            <div class="flex flex-col md:items-end space-y-1">

                @if($product->productDetail && $product->productDetail->model_name)
                <h2 class="text-xs font-semibold text-theme-content uppercase tracking-wider">
                    {{ $product->productDetail->model_name }}
                </h2>
                @endif


                @if($product->productDetail && $product->productDetail->reference_number)
                <div class="group relative flex items-center gap-2 cursor-pointer"
                    x-data="{ copied: false }"
                    @click="navigator.clipboard.writeText('{{ $product->productDetail->reference_number }}'); copied = true; setTimeout(() => copied = false, 2000)">

                    <span class="text-[10px] text-theme-muted tracking-widest">REF.</span>

                    <span class="font-mono text-xs text-theme-muted/70 group-hover:text-theme-primary transition-colors duration-300">
                        {{ $product->productDetail->reference_number }}
                    </span>

                    <svg class="w-3 h-3 text-theme-primary opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>

                    <span x-show="copied"
                        x-transition.opacity
                        class="absolute right-0 -top-6 text-[10px] bg-theme-primary text-black px-2 py-0.5 rounded font-bold tracking-tight">
                        COPIED
                    </span>
                </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8" id="product-main">
            <div class="lg:col-span-3 w-full h-full">
                <div class="lg:sticky lg:top-0 lg:self-start">
                    <div class="w-full h-full p-6 pt-0 lg:p-12 lg:pt-0 animate-fade-in">

                        <div class="space-y-6">
                            <div class="relative overflow-hidden rounded-2xl  ">
                                <div class="swiper main-swiper aspect-[4/3] w-full bg-theme-page">
                                    <div class="swiper-wrapper">
                                        @php
                                        // Logic to collect all images clean and simple
                                        $allImages = collect();
                                        if ($product->mainImage) { $allImages->push($product->mainImage->image_path); }
                                        if ($product->subImage) { $allImages->push($product->subImage->image_path); }
                                        if ($product->galleryImages) {
                                        $allImages = $allImages->merge($product->galleryImages->pluck('image_path'));
                                        }
                                        @endphp

                                        @forelse($allImages as $image)
                                        <div class="swiper-slide">
                                            <div class="relative h-full w-full overflow-hidden flex items-center justify-center bg-theme-page">
                                                <img src="{{ Storage::url($image) }}"
                                                    alt="{{ $product->name }}"
                                                    class="w-full h-full object-contain hover:scale-105 transition-transform duration-700 ease-out"
                                                    loading="lazy"
                                                    onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1547996160-81f8f43f4bc6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80'">
                                            </div>

                                        </div>
                                        @empty
                                        <div class="swiper-slide">
                                            <div class="h-full w-full bg-gray-100 flex items-center justify-center">
                                                <span class="text-gray-400">No image available</span>
                                            </div>
                                        </div>
                                        @endforelse
                                    </div>

                                    @if($allImages->count() > 1)
                                    <div class="swiper-button-next !text-gray-800 !w-10 !h-10 !shadow-lg backdrop-blur-sm hover:!bg-white"></div>
                                    <div class="swiper-button-prev !text-gray-800 !w-10 !h-10 !shadow-lg backdrop-blur-sm hover:!bg-white"></div>
                                    @endif

                                </div>
                            </div>
                            <style>
                                .scrollbar-hide::-webkit-scrollbar {
                                    display: none;
                                }

                                .scrollbar-hide {
                                    -ms-overflow-style: none;
                                    scrollbar-width: none;
                                }
                            </style>
                            @if($allImages->count() > 0)
                            <div class="flex justify-center w-full px-4 scrollbar-hide">
                                <div class="flex space-x-3 overflow-x-auto pb-2 scrollbar-hide">
                                    @foreach($allImages as $image)
                                    <div class="relative w-16 h-24 lg:w-20 lg:h-20 flex-shrink-0 cursor-pointer rounded-lg overflow-hidden border-2 border-transparent  transition-all duration-300 thumbnails-slide" onclick="mainSwiper.slideTo({{ $loop->index }})">
                                        <img src="{{ Storage::url($image) }}"
                                            alt="Thumbnail"
                                            class="w-full h-full object-cover">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Details Section -->
            <div class="lg:col-span-2 space-y-8 animate-fade-in relative" style="animation-delay: 0.1s;">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between py-6 border-b border-white/10 gap-4">

                    <div class="flex items-center space-x-4">
                        @if($product->category)
                        <a href="#" class="group relative">
                            <span class="text-[10px] uppercase tracking-[0.25em] font-medium text-theme-content group-hover:text-theme-primary transition-colors duration-300">
                                {{ $product->category->name }}
                            </span>
                            <span class="absolute -bottom-1 left-0 w-0 h-[1px] bg-theme-primary transition-all duration-300 group-hover:w-full"></span>
                        </a>
                        @endif
                    </div>

                    <div class="flex items-center space-x-6 sm:space-x-8">

                        <div class="relative group cursor-pointer" x-data="{ ratingOpen: false }">
                            <div class="flex items-center space-x-2 text-theme-content" @mouseenter="ratingOpen = true" @mouseleave="ratingOpen = false">
                                <div class="flex items-baseline space-x-1">
                                    <span class="font-mono text-sm font-medium">{{ number_format($averageRating, 1) }}</span>
                                    <span class="text-xs text-theme-muted/50">/</span>
                                    <span class="text-xs text-theme-muted/50">5.0</span>
                                </div>
                                <div class="h-3 w-[1px] bg-white/10 mx-2"></div>
                                <span class="text-[10px] uppercase tracking-wider text-theme-muted underline decoration-white/20 group-hover:decoration-theme-primary transition-all">
                                    {{ $totalReviews }} Reviews
                                </span>
                            </div>

                            <div class="absolute right-0 top-full mt-4 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:-translate-y-1">
                                <div class="bg-[#0a0a0a] border border-white/10 p-5 w-64 shadow-[0_20px_40px_-15px_rgba(0,0,0,1)]">
                                    <div class="flex justify-between items-center mb-4 border-b border-white/5 pb-2">
                                        <span class="text-[10px] uppercase tracking-widest text-theme-muted">Rating Breakdown</span>
                                    </div>
                                    <div class="space-y-2">
                                        @for($i = 5; $i >= 1; $i--)
                                        <div class="flex items-center text-[10px] space-x-3">
                                            <span class="w-2 font-mono text-theme-muted">{{ $i }}</span>
                                            <div class="flex-1 h-[2px] bg-white/5">
                                                <div class="h-full bg-theme-content" style="width: {{ rand(20, 100) }}%"></div>
                                            </div>
                                        </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div x-data="{ count: 14 }" x-init="setInterval(() => count = count + Math.floor(Math.random() * 3) - 1, 8000)">
                            <div class="flex items-center space-x-2 px-3 py-1.5 border border-white/10 rounded-full bg-white/[0.02]">
                                <span class="relative flex h-1.5 w-1.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                                </span>
                                <span class="text-[10px] uppercase tracking-widest font-medium text-theme-muted">
                                    <span x-text="count" class="font-mono text-theme-content"></span> viewing
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Product Title with Premium Typography -->
                <div>
                    <h1 class="font-heading text-4xl lg:text-5xl text-theme-content leading-tight tracking-tight bg-gradient-to-r from-white via-gray-100 to-gray-300 bg-clip-text text-transparent">
                        {{ $product->name }}
                    </h1>
                    @if($product->title)
                    <p class="font-accent font-light italic text-xl text-theme-muted/80 mt-3 tracking-wide pl-1 border-l-4 border-theme-primary/30 pl-3">
                        {{ $product->title }}
                    </p>
                    @endif
                </div>

                <!-- Premium Price & Stock Section -->
                <div class="p-6 bg-gradient-to-br from-white/[0.02] to-transparent backdrop-blur-sm border border-white/10 rounded-2xl relative overflow-hidden group hover:border-white/20 transition-all duration-500">
                    <!-- Animated Background Effect -->
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-theme-primary/5 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>

                    <!-- Main Price Display -->
                    <div class="relative z-10">
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <!-- Main Price (left) -->
                            <div class="relative">
                                <span class="text-3xl md:text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-amber-200 via-yellow-400 to-amber-600 tracking-tight drop-shadow-lg">
                                    {{ $product->formatted_price }}
                                </span>
                            </div>

                            @if($product->cut_price > $product->price)
                            <!-- Cut Price + Savings (right on desktop) -->
                            <div class="flex flex-col items-end md:items-end">

                                <div class="flex items-center space-x-1 mt-1 justify-end">
                                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                    <span class="text-sm font-medium text-green-400">Save {{ $product->formatted_savings }}</span>
                                </div>

                                <span class="text-lg text-theme-muted/50 line-through decoration-red-500/50">
                                    {{ $product->formatted_cut_price }}
                                </span>

                            </div>
                            @endif
                        </div>

                        <!-- Stock Status with Visual Indicator -->
                        @if($product->is_in_stock)
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-theme-muted">Stock Status</span>
                                @if($product->stock_quantity < 10)
                                    <div class="flex items-center gap-2">
                                    <span class="text-orange-400 font-medium flex items-center gap-1">
                                        <svg class="w-4 h-4 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        Low Stock
                                    </span>
                                    <span class="text-xs bg-orange-500/20 text-orange-400 px-2 py-1 rounded-full">{{ $product->stock_quantity }} left</span>
                            </div>
                            @else
                            <span class="text-green-400 font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                In Stock
                            </span>
                            @endif
                        </div>

                        @if($product->stock_quantity < 20)
                            <div class="relative pt-1">
                            <div class="flex mb-2 items-center justify-between">
                                <div>
                                    <span class="text-xs font-semibold inline-block text-theme-muted">
                                        Stock Level
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-semibold inline-block {{ $product->stock_quantity < 10 ? 'text-orange-400' : 'text-green-400' }}">
                                        {{ $product->stock_quantity }} units
                                    </span>
                                </div>
                            </div>
                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded-full bg-white/10">
                                <div style="width: {{ ($product->stock_quantity / 20) * 100 }}%"
                                    class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center transition-all duration-500 {{ $product->stock_quantity < 10 ? 'bg-gradient-to-r from-orange-500 to-red-500' : 'bg-gradient-to-r from-green-500 to-emerald-500' }}">
                                </div>
                            </div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Sale Timer with Premium Design -->
                @if($saleOffer && $saleOffer->end_time)
                @php
                $endTime = \Carbon\Carbon::parse($saleOffer->end_time);
                @endphp

                @if(now()->lt($endTime))
                <div class="mt-6 pt-5 border-t border-white/10"
                    x-data="{
             days: '00',
             hours: '00',
             minutes: '00',
             seconds: '00',
             init() {
                 const endTime = new Date('{{ $endTime }}').getTime();
                 const updateTimer = () => {
                     const now = new Date().getTime();
                     const distance = endTime - now;

                     if (distance <= 0) {
                         // Hide the timer when sale ends
                         this.$el.style.display = 'none';
                         clearInterval(timerInterval);
                         return;
                     }

                     this.days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                     this.hours = String(Math.floor((distance / (1000 * 60 * 60)) % 24)).padStart(2, '0');
                     this.minutes = String(Math.floor((distance / (1000 * 60)) % 60)).padStart(2, '0');
                     this.seconds = String(Math.floor((distance / 1000) % 60)).padStart(2, '0');
                 };

                 updateTimer();
                 const timerInterval = setInterval(updateTimer, 1000);
             }
         }"
                    x-init="init()">

                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs uppercase tracking-widest text-theme-muted font-semibold">Limited Time Offer</span>
                        <span class="text-xs text-theme-primary animate-pulse">Ends Soon</span>
                    </div>

                    <div class="grid grid-cols-4 gap-2 text-center">
                        <div class="bg-black/30 rounded-xl p-3 border border-white/10 backdrop-blur-sm transform hover:scale-105 transition-transform duration-300">
                            <span class="block font-bold text-white text-2xl leading-none mb-1" x-text="days"></span>
                            <span class="text-xs text-theme-muted uppercase tracking-wider">Days</span>
                        </div>
                        <div class="bg-black/30 rounded-xl p-3 border border-white/10 backdrop-blur-sm transform hover:scale-105 transition-transform duration-300">
                            <span class="block font-bold text-white text-2xl leading-none mb-1" x-text="hours"></span>
                            <span class="text-xs text-theme-muted uppercase tracking-wider">Hours</span>
                        </div>
                        <div class="bg-black/30 rounded-xl p-3 border border-white/10 backdrop-blur-sm transform hover:scale-105 transition-transform duration-300">
                            <span class="block font-bold text-white text-2xl leading-none mb-1" x-text="minutes"></span>
                            <span class="text-xs text-theme-muted uppercase tracking-wider">Minutes</span>
                        </div>
                        <div class="bg-black/30 rounded-xl p-3 border border-white/10 backdrop-blur-sm transform hover:scale-105 transition-transform duration-300">
                            <span class="block font-bold text-theme-primary text-2xl leading-none mb-1" x-text="seconds"></span>
                            <span class="text-xs text-theme-primary uppercase tracking-wider">Seconds</span>
                        </div>
                    </div>
                </div>
                @endif
                @endif



                <!-- Quick Description with Read More -->
                <div class="mt-6">
                    <p class="text-theme-content/80 leading-relaxed text-base font-light line-clamp-2">
                        {{ Str::limit($product->description, 120) }}
                    </p>
                    <button @click="document.getElementById('full-details').scrollIntoView({behavior: 'smooth'})"
                        class="mt-2 text-theme-primary text-sm hover:underline flex items-center gap-1 group cursor-pointer">
                        <span>Read full details</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>


            </div>
        </div>

        <!-- Frictionless Add to Cart Section -->
        <div class="space-y-4" x-data="{
            quantity: 1,
            cartLoading: false,
            buyNowLoading: false,
            
            incrementQuantity() {
                if(this.quantity < {{ $product->stock_quantity }}) {
                    this.quantity++;
                    this.playHapticFeedback();
                }
            },
            
            decrementQuantity() {
                if(this.quantity > 1) {
                    this.quantity--;
                    this.playHapticFeedback();
                }
            },
            
            playHapticFeedback() {
                if('vibrate' in navigator) {
                    navigator.vibrate(10);
                }
            },
            
            showSuccess(message) {
                // Create toast notification
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-y-0 opacity-0 transition-all duration-300';
                toast.textContent = message;
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.classList.remove('opacity-0');
                    toast.classList.add('opacity-100');
                }, 10);
                
                setTimeout(() => {
                    toast.classList.remove('opacity-100');
                    toast.classList.add('opacity-0');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }
        }">
        @if(!empty($product) && $product->stock_quantity > 0)
<!-- Compact Premium Quantity Selector -->
<div class="flex items-center justify-between bg-theme-surface border border-theme-deep rounded-xl px-4 py-2 w-full max-w-2xl mx-auto mt-4 shadow-md">
    <!-- Left: Label + Selected Quantity -->
    <div class="flex flex-col">
        <span class="text-xs font-semibold text-theme-muted uppercase tracking-wide">Quantity</span>
        <span class="text-[10px] text-theme-muted mt-0.5">
            Selected: <span x-text="quantity"></span>
        </span>
    </div>

    <!-- Right: Controls -->
    <div class="flex items-center space-x-2 bg-theme-deep rounded-lg shadow-inner">
        <!-- Decrement Button -->
        <button 
            @click="quantity = Math.max(1, quantity - 1)"
            :disabled="quantity <= 1"
            class="w-9 h-9 flex items-center justify-center text-theme-content rounded-l-lg hover:bg-theme-deep-hover active:bg-theme-deep-active disabled:opacity-40 disabled:cursor-not-allowed transition-all duration-150 ease-out">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
        </button>

        <!-- Display Only Quantity -->
        <div class="w-10 h-9 flex items-center justify-center text-sm font-semibold text-theme-content select-none">
            <span x-text="quantity"></span>
        </div>

        <!-- Increment Button -->
        <button 
            @click="quantity = Math.min({{ $product->stock_quantity }}, quantity + 1)"
            :disabled="quantity >= {{ $product->stock_quantity }}"
            class="w-9 h-9 flex items-center justify-center text-theme-content rounded-r-lg hover:bg-theme-deep-hover active:bg-theme-deep-active disabled:opacity-40 disabled:cursor-not-allowed transition-all duration-150 ease-out">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
        </button>
    </div>
</div>


            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">


                <!-- Add to Cart Button -->
                <button @click="addToCart({{ $product->id }}, quantity)"

                    :disabled="cartLoading"
                    class="group relative overflow-hidden bg-theme-surface border border-white/10 p-4 flex items-center justify-center gap-3 transition-all duration-500 hover:scale-[1.02] hover:border-theme-primary/50">

                    <!-- Animated gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-r from-theme-primary/0 via-theme-primary/5 to-theme-primary/0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>

                    <!-- Icon -->
                    <svg x-show="!cartLoading" class="w-6 h-6 text-theme-primary relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>

                    <svg x-show="cartLoading" class="animate-spin h-6 w-6 text-theme-primary relative z-10" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>

                    <span class="font-semibold text-lg relative z-10" x-text="cartLoading ? 'Adding...' : ' Cart'"></span>
                    <span class="text-sm text-theme-muted relative z-10">• {{ $product->formatted_price }}</span>
                </button>

                <button @click="buyNow({{ $product->id }}, quantity)"
                    :disabled="buyNowLoading"
                    class="group relative overflow-hidden bg-theme-primary text-black font-bold p-4 flex items-center justify-center gap-3 transition-all duration-500 ">

                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>

                    <svg x-show="!buyNowLoading" class="w-6 h-6 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>

                    <svg x-show="buyNowLoading" class="animate-spin h-6 w-6 relative z-10" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>

                    <span class="text-lg relative z-10" x-text="buyNowLoading ? 'Processing...' : 'Get Now'">Get Now</span>
                    <span class="text-sm bg-black/20 px-2 py-1 relative z-10">Secure</span>
                </button>
            </div>



            @else
            <!-- Sold Out - Premium Waitlist -->
            <div class="bg-gradient-to-br from-red-500/10 to-transparent border border-red-500/20 rounded-2xl p-8 text-center space-y-6 backdrop-blur-sm">
                <div class="relative">
                    <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="absolute -top-2 -right-2">
                        <span class="text-xs font-bold bg-red-500 text-white px-3 py-1 rounded-full animate-pulse">SOLD OUT</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <p class="text-2xl font-bold text-white">Join the Waitlist</p>
                    <p class="text-red-300/80">Be the first to know when this item is back in stock</p>
                </div>
                <div class="space-y-2 flex flex-col items-center">
                    <button @click="openNotifyModal"
                        class="bg-theme-primary text-black font-semibold px-8 py-3  hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        Notify Me
                    </button>
                    <p class="text-xs text-gray-400 text-center">25 people already joined the waitlist</p>
                </div>


            </div>
            @endif
        </div>

        @if($product->productDetail)
        <div x-data="{ expanded: false, activeTab: 'details' }" class="border-t border-white/5 pt-6">

            <!-- Accordion Button -->
            <button @click="expanded = !expanded"
                class="flex items-center justify-between w-full p-4 bg-white/5 hover:bg-white/10 rounded-xl transition-all duration-300 group">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-gradient-to-br from-theme-primary/20 to-transparent rounded-lg">
                        <svg class="w-5 h-5 text-theme-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="font-heading text-lg text-white group-hover:text-theme-primary transition-colors">
                            Product Specifications
                        </h3>
                        <p class="text-sm text-theme-muted">Click to view specifications</p>
                    </div>
                </div>
                <svg class="w-6 h-6 text-theme-muted transform transition-transform duration-500"
                    :class="expanded ? 'rotate-180 text-theme-primary' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Accordion Content -->
            <div x-show="expanded" x-collapse class="mt-4 p-6 bg-gradient-to-br from-white/[0.03] to-transparent border border-white/10 rounded-xl space-y-6 backdrop-blur-sm">

                <!-- Tab Buttons -->
                <div class="flex gap-8 border-b border-white/20 mb-6">
                    <button
                        @click="activeTab = 'details'"
                        :class="activeTab === 'details' ? 'text-white border-b-2 border-theme-primary' : 'text-theme-muted hover:text-white border-b-2 border-transparent hover:border-theme-primary'"
                        class="pb-2 font-medium transition-all duration-300">
                        Details
                    </button>

                    <button
                        @click="activeTab = 'specs'"
                        :class="activeTab === 'specs' ? 'text-white border-b-2 border-theme-primary' : 'text-theme-muted hover:text-white border-b-2 border-transparent hover:border-theme-primary'"
                        class="pb-2 font-medium transition-all duration-300">
                        Specifications
                    </button>
                </div>


                <!-- Tab Content -->
                <div>
                    <!-- Details Tab -->
                    <div x-show="activeTab === 'details'" x-transition>
                        @if($product->productDetail->detailed_description)
                        <div class="prose prose-invert max-w-none">
                            {!! nl2br(e($product->productDetail->detailed_description)) !!}
                        </div>
                        @else
                        <p class="text-sm text-theme-muted">No detailed description available.</p>
                        @endif
                    </div>

                    @php
                    $specs = json_decode($product->productDetail->specs, true) ?: [];
                    @endphp

                    <div x-show="activeTab === 'specs'" x-transition>
                        @php
                        $decodedSpecs = $specs ?? [];
                        @endphp


                        @if(!empty($decodedSpecs))
                        <div class="grid grid-cols-2 md:grid-cols-2 gap-3">
                            @foreach($decodedSpecs as $spec)
                            <div class="flex justify-between items-center px-3 py-2 rounded-lg bg-white/3 border border-white/10">
                                <span class="text-[11px] text-theme-muted font-semibold uppercase">
                                    {{ $spec['key'] }}
                                </span>
                                <span class="text-xs font-medium text-white text-right">
                                    {{ $spec['value'] }}
                                </span>
                            </div>
                            @endforeach
                        </div>

                        @else
                        <p class="text-sm text-theme-muted">No specifications available.</p>
                        @endif
                    </div>





                </div>

            </div>
        </div>
        @endif

    </div>
</div>
<div class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-16">

    <div class="flex flex-col items-center text-center group cursor-default">
        <div class="mb-4 text-theme-primary transition-transform duration-500 group-hover:-translate-y-1">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h3 class="text-xs uppercase tracking-[0.2em] font-medium text-theme-content mb-2">Authentic Quality</h3>
        <p class="text-xs text-theme-muted/60 font-light">100% genuine sourced</p>
    </div>

    <div class="flex flex-col items-center text-center group cursor-default">
        <div class="mb-4 text-theme-primary transition-transform duration-500 group-hover:-translate-y-1">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <h3 class="text-xs uppercase tracking-[0.2em] font-medium text-theme-content mb-2">Secure Payment</h3>
        <p class="text-xs text-theme-muted/60 font-light">256-bit encryption</p>
    </div>

    <div class="flex flex-col items-center text-center group cursor-default">
        <div class="mb-4 text-theme-primary transition-transform duration-500 group-hover:-translate-y-1">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 2a10 10 0 100 20 10 10 0 000-20zM12 6v6l4 2" />
            </svg>
        </div>
        <h3 class="text-xs uppercase tracking-[0.2em] font-medium text-theme-content mb-2">Fast Delivery</h3>
        <p class="text-xs text-theme-muted/60 font-light">Global priority shipping</p>
    </div>

    <div class="flex flex-col items-center text-center group cursor-default">
        <div class="mb-4 text-theme-primary transition-transform duration-500 group-hover:-translate-y-1">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h3 class="text-xs uppercase tracking-[0.2em] font-medium text-theme-content mb-2">Concierge Support</h3>
        <p class="text-xs text-theme-muted/60 font-light">24/7 dedicated assistance</p>
    </div>

</div>

</div>
</div>
<!-- @if($desktopImages && count($desktopImages) > 0)
    <div class="hidden lg:block w-full">
        @foreach($desktopImages as $image)
        <div class="w-full h-[600px] flex items-center justify-center bg-gray-100">
            <img
                src="{{ Storage::url($image) }}"
                alt="{{ $product->name }} - Desktop Banner {{ $loop->iteration }}"
                class="max-w-full max-h-full object-contain"
                loading="lazy">
        </div>
        @endforeach
    </div>
    @endif
 -->


<!-- Reviews Section -->



<!-- Reviews List -->
<!-- Reviews List (Static) -->

<!-- Review 1 -->

<!-- Load More Reviews (if applicable) -->
</div>
</div>


<div class="fixed bottom-0 left-0 w-full z-50 transform transition-transform duration-500 ease-[cubic-bezier(0.23,1,0.32,1)]"
    x-show="showStickyBar"
    x-transition:enter="translate-y-full"
    x-transition:enter-end="translate-y-0"
    x-transition:leave="translate-y-0"
    x-transition:leave-end="translate-y-full"
    style="display: none;">
    <div class="bg-black/80 backdrop-blur-xl border-t border-white/10 p-4 shadow-[0_-20px_40px_-15px_rgba(0,0,0,1)]">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">

            <div class="hidden sm:flex flex-col">
                <span class="text-xs text-theme-muted uppercase tracking-wider truncate max-w-[200px]">
                    {{ $product->name }}
                </span>
                <span class="text-sm font-mono text-theme-content">
                    {{ $product->formatted_price }}
                </span>
            </div>

            <div class="flex items-center gap-3 flex-1 sm:flex-none justify-end">

                <button @click="addToCart({{ $product->id }}, quantity)"
                    :disabled="cartLoading"
                    class="h-12 w-12 sm:w-auto sm:px-6 flex items-center justify-center border border-white/10 hover:border-theme-primary/50 bg-white/5 hover:bg-white/10 transition-all rounded-none">
                    <svg x-show="!cartLoading" class="w-5 h-5 text-theme-content" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <svg x-show="cartLoading" class="animate-spin h-5 w-5 text-theme-primary" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>

                <button @click="buyNow({{ $product->id }}, quantity)"
                    :disabled="buyNowLoading"
                    class="h-12 flex-1 sm:flex-initial px-8 bg-theme-primary text-black font-bold uppercase tracking-widest text-xs hover:bg-theme-primary/90 transition-all flex items-center justify-center gap-2">
                    <span x-show="!buyNowLoading">Buy Now</span>
                    <span x-show="buyNowLoading">...</span>
                    <span class="sm:hidden font-mono opacity-60 border-l border-black/20 pl-2 ml-1">
                        {{ $product->formatted_price }}
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    const isAuthenticated = @json(auth()->check());
    const userHasPurchased = @json($userHasPurchased);
    const userAlreadyReviewed = @json($userAlreadyReviewed);
    const loginRedirectUrl = @json(route('login').
        '?redirect='.url()-> current());
    const stockQuantity = @json($product-> stock_quantity);
    const productId = parseInt(@json($product-> id), 10);


    const quantityInput = document.getElementById('quantity-input');

    quantityInput.addEventListener('input', function() {
        let quantity = parseInt(this.value) || 0;
        quantity = Math.max(1, Math.min(quantity, stockQuantity));
        this.value = quantity;
    });


    function initSwipers() {
        const swiperContainer = document.querySelector('.main-swiper');

        if (swiperContainer) {
            const mainSwiper = new Swiper('.main-swiper', {
                loop: true,
                speed: 800,
                spaceBetween: 0,
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                autoplay: {
                    delay: 5000,
                    pauseOnMouseEnter: true,
                    disableOnInteraction: false,
                },
                grabCursor: true,
            });

            window.mainSwiper = mainSwiper;
        }
    }


    // Product Page Alpine.js Component
    function productPage() {
        return {
            // State
            quantity: 1,
            showNotifyModal: false,
            showSuccessToast: false,
            showErrorToast: false,
            successMessage: '',
            errorMessage: '',
            cartLoading: false,
            notification: {
                email: '',
                loading: false,
                errors: {}
            },

            // Methods
            init() {
                initSwipers();
                setTimeout(() => {
                    const elements = document.querySelectorAll('.animate-fade-in');
                    elements.forEach((el, index) => {
                        el.style.animationDelay = `${index * 0.1}s`;
                    });
                }, 100);
            },


            incrementQuantity() {
                if (this.quantity < stockQuantity) {
                    this.quantity++;
                }
            },
            decrementQuantity() {
                if (this.quantity > 1) {
                    this.quantity--;
                }
            },

            openNotifyModal() {
                this.showNotifyModal = true;
                document.body.style.overflow = 'hidden';
                setTimeout(() => {
                    const emailInput = document.getElementById('notify-email');
                    if (emailInput) emailInput.focus();
                }, 100);
            },

            closeNotifyModal() {
                this.showNotifyModal = false;
                this.notification.email = '';
                this.notification.errors = {};
                document.body.style.overflow = '';
            },

            showToast(message, type = 'success') {
                if (type === 'success') {
                    this.successMessage = message;
                    this.showSuccessToast = true;
                    setTimeout(() => {
                        this.showSuccessToast = false;
                    }, 4000);
                } else {
                    this.errorMessage = message;
                    this.showErrorToast = true;
                    setTimeout(() => {
                        this.showErrorToast = false;
                    }, 4000);
                }
            },

            async submitNotification() {
                this.notification.loading = true;
                this.notification.errors = {};

                try {
                    const response = await fetch(`/products/{{ $product->id }}/notify`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            email: this.notification.email
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        if (response.status === 422) {
                            this.notification.errors = data.errors || {};
                            throw new Error('Please enter a valid email address');
                        } else if (response.status === 429) {
                            throw new Error('Too many attempts. Please try again in a few minutes.');
                        } else if (response.status === 409) {
                            throw new Error('You are already subscribed to notifications for this product.');
                        } else {
                            throw new Error(data.message || 'An error occurred. Please try again.');
                        }
                    }

                    this.showToast('You will be notified when this product is back in stock!');
                    this.closeNotifyModal();

                } catch (error) {
                    console.error('Notification error:', error);
                    this.showToast(error.message, 'error');
                } finally {
                    this.notification.loading = false;
                }
            },

            async addToCart(productId, quantity) {
                try {
                    this.cartLoading = true;

                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const response = await fetch('/add-to-cart', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            product_id: parseInt(productId, 10),
                            quantity: parseInt(quantity, 10)
                        })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        

                        this.successMessage = data.message;
                        this.showSuccessToast = true;

                        window.openCartDrawer();


                        setTimeout(() => this.showSuccessToast = false, 3000);
                    } else {
                        this.errorMessage = data.message ?? 'Something went wrong!';
                        this.showErrorToast = true;

                        setTimeout(() => this.showErrorToast = false, 3000);
                    }

                } catch (error) {
                    console.error('Network error:', error);
                    this.errorMessage = 'Network error!';
                    this.showErrorToast = true;
                    setTimeout(() => this.showErrorToast = false, 3000);
                } finally {
                    this.cartLoading = false;
                }
            },


            async buyNow(productId, quantity) {
                if (quantity < 1) {
                    alert('Please select a valid quantity.');
                    return;
                }

                this.buyNowLoading = true;

                try {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const response = await fetch('/buy-now', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: quantity
                        })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        window.location.href = data.redirect_url;
                    } else {
                        this.showToast(data.message || 'Something went wrong!', 'error');
                    }
                } catch (error) {
                    console.error(error);
                    this.showToast('Network error. Please try again.', 'error');
                } finally {
                    this.buyNowLoading = false;
                }
            },


            openReviewModal() {
                if (!isAuthenticated) {
                    window.location.href = loginRedirectUrl;
                    return;
                }

                if (!userHasPurchased) {
                    this.showToast('You need to purchase this product before writing a review.', 'error');
                    return;
                }

                if (userAlreadyReviewed) {
                    this.showToast('You have already reviewed this product.', 'info');
                    return;
                }

                this.showToast('Review feature will be available soon!', 'info');
            }

        }
    }

    function countdownTimer(endTime) {
        return {
            hours: '00',
            minutes: '00',
            seconds: '00',
            interval: null,

            init() {
                this.updateTimer();
                this.interval = setInterval(() => this.updateTimer(), 1000);
            },

            updateTimer() {
                const end = new Date(endTime).getTime();
                const now = new Date().getTime();
                const distance = end - now;

                if (distance < 0) {
                    this.hours = '00';
                    this.minutes = '00';
                    this.seconds = '00';
                    clearInterval(this.interval);
                    return;
                }

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                this.hours = hours.toString().padStart(2, '0');
                this.minutes = minutes.toString().padStart(2, '0');
                this.seconds = seconds.toString().padStart(2, '0');
            },

            destroy() {
                if (this.interval) {
                    clearInterval(this.interval);
                }
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const target = document.querySelector(targetId);
                if (target) {
                    const headerOffset = 80;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.1
            });

            document.querySelectorAll('img.lazy').forEach(img => imageObserver.observe(img));
        }
    });

    document.addEventListener('error', function(e) {
        if (e.target.tagName === 'IMG') {
            e.target.src = 'https://images.unsplash.com/photo-1547996160-81f8f43f4bc6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80';
            e.target.classList.add('error-fallback');
        }
    }, true);
</script>
@endsection