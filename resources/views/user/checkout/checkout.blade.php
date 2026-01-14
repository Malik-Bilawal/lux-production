<!DOCTYPE html>
<html lang="en" x-data="checkoutApp()" x-init="init()">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Zero Lifestyle - Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Cinzel:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Montserrat', sans-serif;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            box-shadow: none;
            border-color: #D4AF37 !important;
        }

        .checkout-container {
            max-width: 1100px;
            margin: 0 auto;
        }

        @media (min-width: 1024px) {
            .checkout-container {
                padding: 0 1rem;
            }
        }

        [x-cloak] {
            display: none !important;
        }

        .form-input {
            height: 44px;
            padding: 0 14px;
        }

        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .error-border {
            border-color: #ef4444 !important;
        }

        .success-border {
            border-color: #10b981 !important;
        }

        .skeleton {
            background: linear-gradient(90deg, #1a1a1a 25%, #050505 50%, #1a1a1a 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s infinite;
        }

        @keyframes skeleton-loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* Apply theme colors */
        .bg-theme-page {
            background-color: #0f0f0f;
        }

        .bg-theme-surface {
            background-color: #1a1a1a;
        }

        .bg-theme-deep {
            background-color: #050505;
        }

        .bg-theme-primary {
            background-color: #D4AF37;
        }

        .bg-theme-primary-light {
            background-color: #F6E6B6;
        }

        .bg-theme-primary-dark {
            background-color: #AA8C2C;
        }

        .text-theme-content {
            color: #FFFFFF;
        }

        .text-theme-muted {
            color: #9CA3AF;
        }

        .text-theme-inverted {
            color: #000000;
        }

        .border-theme-deep {
            border-color: #050505;
        }

        .border-theme-primary {
            border-color: #D4AF37;
        }

        .border-theme-surface {
            border-color: #1a1a1a;
        }

        .hover\:bg-theme-primary-dark:hover {
            background-color: #AA8C2C;
        }

        .focus\:border-theme-primary:focus {
            border-color: #D4AF37;
        }

        .focus\:ring-theme-primary:focus {
            --tw-ring-color: #D4AF37;
        }

        .text-theme-primary {
            color: #D4AF37;
        }

        /* Font classes */
        .font-heading {
            font-family: 'Cinzel', serif;
            letter-spacing: 0.025em;
        }

        .font-accent {
            font-family: 'Playfair Display', serif;
        }

        /* Custom shadows */
        .shadow-glow {
            box-shadow: 0 0 25px rgba(212, 175, 55, 0.25);
        }

        .shadow-card {
            box-shadow: 0 15px 40px -10px rgba(0, 0, 0, 0.9);
        }
    </style>
</head>

<body class="bg-theme-page text-theme-content font-body">
    <!-- Skeleton Loading -->
    <div id="skeleton-loading" x-show="loading" x-transition x-cloak class="fixed inset-0 bg-theme-page z-50 flex flex-col">
        <div class="lg:hidden border-b border-theme-deep px-4 py-3 flex items-center justify-between">
            <div class="skeleton w-6 h-6 rounded"></div>
            <div class="skeleton w-32 h-6 rounded"></div>
            <div class="skeleton w-6 h-6 rounded"></div>

        </div>
        <div class="flex-1 overflow-auto p-4">
            <div class="max-w-6xl mx-auto">
                <div class="lg:flex lg:gap-6">
                    <div class="lg:w-[60%] space-y-4">
                        <div class="bg-theme-surface rounded-lg border border-theme-deep p-5">
                            <div class="skeleton w-40 h-7 mb-4 rounded"></div>
                            <div class="space-y-3">
                                <div class="skeleton w-full h-12 rounded"></div>
                                <div class="skeleton w-64 h-4 rounded"></div>
                            </div>
                        </div>
                        <div class="bg-theme-surface rounded-lg border border-theme-deep p-5">
                            <div class="skeleton w-40 h-6 mb-4 rounded"></div>
                            <div class="space-y-3">
                                <div class="skeleton w-full h-12 rounded"></div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="skeleton h-12 rounded"></div>
                                    <div class="skeleton h-12 rounded"></div>
                                </div>
                                <div class="skeleton h-24 rounded"></div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden lg:block lg:w-[40%]">
                        <div class="bg-theme-surface rounded-lg border border-theme-deep p-5">
                            <div class="skeleton w-40 h-7 mb-4 rounded"></div>
                            <div class="space-y-3 mb-4">
                                <div class="skeleton h-20 rounded"></div>
                                <div class="skeleton h-20 rounded"></div>
                            </div>
                            <div class="space-y-2">
                                <div class="skeleton w-full h-4 rounded"></div>
                                <div class="skeleton w-full h-4 rounded"></div>
                                <div class="skeleton w-full h-8 rounded mt-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="min-h-screen flex flex-col" x-show="!loading" x-cloak>
        <!-- Mobile Header -->
        <header class="lg:hidden sticky z-50 flex items-center justify-between px-6 py-4 bg-theme-surface">

            <a href="{{ route('user.welcome') }}" class="flex items-center gap-3 group">
                <div class="w-8 h-8 bg-theme-deep border border-theme-primary flex items-center justify-center rotate-45 transition-transform active:scale-90">
                    <span class="font-heading text-theme-primary -rotate-45 text-sm">A</span>
                </div>
                <span class="font-heading text-lg tracking-[0.2em] text-theme-content uppercase">
                    Apex
                </span>
            </a>

            <div class="relative flex items-center justify-center p-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="drop-shadow-glow">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                    <path d="M3 6h18" />
                    <path d="M16 10a4 4 0 0 1-8 0" />
                </svg>
                <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-theme-primary text-theme-inverted font-body text-[10px] font-bold flex items-center justify-center rounded-full border border-theme-deep">
                    1
                </span>
            </div>
        </header>

        <!-- Mobile Top Accordion -->
        <div class="lg:hidden p-5 shadowbg-theme-page" x-cloak>
            <button class="w-full px-4 py-3 flex items-center justify-between"
                @click="showTopSummary = !showTopSummary"
                type="button">
                <span class="font-medium text-theme-content">Show order summary</span>
                <div class="flex items-center">
                    <span class="font-semibold mr-2 text-theme-primary" x-text="formatCurrency(summary.total)"></span>
                    <i class="fas fa-chevron-down transition-transform duration-200 text-theme-muted"
                        :class="{'transform rotate-180': showTopSummary}"></i>
                </div>
            </button>

            <div x-show="showTopSummary" x-transition x-cloak>
                <div class="p-4">
                    <div class="mb-4 max-h-48 overflow-y-auto">
                        <template x-for="item in cart" :key="item.id">
                            <div class="flex items-center justify-between mb-3 pb-3 border-b border-theme-deep last:border-0">
                                <div class="flex items-center">
                                    <template x-if="item.img"> <img :src="item.img" :alt="item.name" class="w-full h-full object-cover">
                                    </template>

                                    <div class="min-w-0">
                                        <div class="font-semibold text-theme-content text-sm truncate" x-text="item.name"></div>
                                        <div class="text-xs text-theme-muted mt-0.5">
                                            Qty: <span x-text="item.quantity"></span> ×
                                            <span x-text="formatCurrency(item.price)"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="font-medium text-theme-content whitespace-nowrap"
                                    x-text="formatCurrency(item.price * item.quantity)"></div>
                            </div>
                        </template>
                    </div>

                    <div class="mb-4">
                        <div class="text-sm font-medium text-theme-muted mb-2">Discount code</div>
                        <div class="flex gap-2">
                            <input type="text"
                                x-model="promoCode"
                                :class="{'error-border': promoError, 'success-border': promoSuccess}"
                                @input="promoError = null; promoSuccess = null"
                                @keyup.enter="applyPromoCode"
                                class="flex-1 border border-theme-deep bg-theme-surface text-theme-content rounded px-3 py-2 text-sm focus:border-theme-primary h-9"
                                placeholder="Enter code">
                            <button @click="applyPromoCode"
                                :disabled="isLoading || !form.country_id || isApplyingPromo"
                                :class="{'loading': isApplyingPromo, 'opacity-50 cursor-not-allowed': !form.country_id || isApplyingPromo}"
                                class="bg-theme-primary text-theme-inverted px-4 py-2 rounded text-xs font-medium hover:bg-theme-primary-dark whitespace-nowrap h-9 transition-colors">
                                <span x-show="!isApplyingPromo">Apply</span>
                                <span x-show="isApplyingPromo" class="flex items-center">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                </span>
                            </button>
                        </div>
                        <template x-if="promoError">
                            <div class="text-red-600 text-xs mt-1" x-text="promoError"></div>
                        </template>
                        <template x-if="promoSuccess">
                            <div class="text-green-600 text-xs mt-1" x-text="promoSuccess"></div>
                        </template>
                    </div>

                    <div class="space-y-2 pt-3 border-t border-theme-muted">
                        <div class="flex justify-between text-sm">
                            <span class="text-theme-muted">Subtotal</span>
                            <span class="font-medium text-theme-content" x-text="formatCurrency(summary.subtotal)"></span>
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="text-theme-muted">Tax</span>
                            <span class="font-medium text-theme-content" x-text="0">0</span>
                        </div>

                        <template x-if="summary.promo_discount > 0">
                            <div class="flex justify-between text-sm text-green-600">
                                <span>Promo Discount</span>
                                <span class="font-medium">-<span x-text="formatCurrency(summary.promo_discount)"></span></span>
                            </div>
                        </template>

                        <div class="flex justify-between text-sm">
                            <span class="text-theme-muted">Shipping</span>
                            <template x-if="summary.shipping_cost === 0">
                                <span class="text-green-600 font-medium">FREE</span>
                            </template>
                            <template x-if="summary.shipping_cost > 0">
                                <span class="font-medium text-theme-content" x-text="formatCurrency(summary.shipping_cost)"></span>
                            </template>
                        </div>

                        <div class="pt-3 border-t border-theme-content flex justify-between items-center">
                            <div>
                                <span class="font-semibold text-theme-content">Total</span>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-theme-primary text-lg" x-text="formatCurrency(summary.total)"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <header class="hidden lg:flex w-full h-24 items-center bg-theme-page top-0">

            <div class="w-full max-w-screen-2xl mx-auto px-12 flex justify-between items-center h-full">

                <div class="flex-1 flex justify-start">
                    <img src="{{ asset('images/logo.png') }}"
                        alt="Logo"
                        class="w-16 h-16 lg:w-24 lg:h-24 max-w-none object-contain transition-transform duration-300 group-hover:scale-110">
                </div>

                <div class="flex-1 flex justify-center">
                    <span class="font-heading text-lg text-theme-muted tracking-[0.2em] uppercase opacity-40">
                        Checkout
                    </span>
                </div>

                <div class="flex-1 flex justify-end items-center">
                    <div class="group flex items-center gap-4 cursor-pointer">



                        <div class="h-4 w-[1px] bg-theme-primary/30 rotate-12"></div>

                        <a href="{{ route('user.cart') }}"
                            class="relative flex items-center justify-center group">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-theme-muted group-hover:text-theme-primary transition-colors duration-300"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>

                            <span
                                class="absolute -bottom-2 -right-2 font-body font-bold text-[10px] text-theme-primary bg-theme-surface border border-theme-primary/20 rounded-full h-5 w-5 flex items-center justify-center shadow-glow">
                                1
                            </span>

                        </a>


                    </div>
                </div>

            </div>
        </header>


        <main class="flex-1 py-4 lg:py-6" x-cloak>
            <div class="checkout-container px-4 lg:px-0">
                <form id="checkout-form" @submit.prevent="placeOrder" class="lg:flex lg:gap-6">
                    <input type="hidden" name="billing_same" :value="billingSame ? 1 : 0">

                    <!-- Left Column - Form (60%) -->
                    <div class="lg:w-[60%]">
                        <!-- Contact Section -->
                        <div class="bg-theme-page shadow-card rounded-lg p-5 mb-3">
                            <h1 class="text-xl font-bold mb-4 text-theme-content font-heading flex items-center gap-2">
                                <i class="fas fa-envelope text-theme-primary"></i>
                                Contact
                            </h1>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-theme-muted mb-1.5">Email *</label>
                                <input type="email"
                                    name="email"
                                    x-model="form.email"
                                    required
                                    class="w-full border border-theme-deep bg-theme-surface text-theme-content rounded px-3 py-2.5 text-sm focus:border-theme-primary form-input"
                                    :class="{'error-border': errors.email}">
                                <template x-if="errors.email">
                                    <div class="text-red-600 text-xs mt-1" x-text="errors.email[0]"></div>
                                </template>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="news-offers" class="h-3.5 w-3.5 text-theme-primary border-theme-deep rounded focus:ring-theme-primary">
                                <label for="news-offers" class="ml-2 text-xs text-theme-muted">Email me with news and offers</label>
                            </div>
                        </div>

                        <!-- Delivery Section -->
                        <div class="bg-theme-page shadow-card rounded-lg p-5 mb-3">
                            <h2 class="text-lg font-bold mb-4 text-theme-content font-heading">
                                <i class="fas fa-truck text-theme-primary"></i>
                                Delivery
                            </h2>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-theme-muted mb-1.5">Country/Region *</label>
                                <select name="country_id"
                                    x-model="form.country_id"
                                    @change="onCountryChange"
                                    required
                                    class="w-full border border-theme-deep bg-theme-surface text-theme-content rounded px-3 py-2.5 text-sm focus:border-theme-primary form-input"
                                    :class="{'error-border': errors.country_id}">
                                    <option value="" class="bg-theme-surface text-theme-content">Select Country</option>
                                    <template x-for="country in shippingCountries" :key="country.id">
                                        <option :value="country.id" x-text="country.name" class="bg-theme-surface text-theme-content"></option>
                                    </template>
                                </select>
                                <template x-if="errors.country_id">
                                    <div class="text-red-600 text-xs mt-1" x-text="errors.country_id[0]"></div>
                                </template>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-theme-muted mb-1.5">First name *</label>
                                    <input type="text"
                                        name="first_name"
                                        x-model="form.first_name"
                                        required
                                        class="w-full border border-theme-deep bg-theme-surface text-theme-content rounded px-3 py-2.5 text-sm focus:border-theme-primary form-input"
                                        :class="{'error-border': errors.first_name}">
                                    <template x-if="errors.first_name">
                                        <div class="text-red-600 text-xs mt-1" x-text="errors.first_name[0]"></div>
                                    </template>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-theme-muted mb-1.5">Last name *</label>
                                    <input type="text"
                                        name="last_name"
                                        x-model="form.last_name"
                                        required
                                        class="w-full border border-theme-deep bg-theme-surface text-theme-content rounded px-3 py-2.5 text-sm focus:border-theme-primary form-input"
                                        :class="{'error-border': errors.last_name}">
                                    <template x-if="errors.last_name">
                                        <div class="text-red-600 text-xs mt-1" x-text="errors.last_name[0]"></div>
                                    </template>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-theme-muted mb-1.5">Address *</label>
                                <textarea name="address"
                                    x-model="form.address"
                                    required
                                    class="w-full border border-theme-deep bg-theme-surface text-theme-content rounded px-3 py-2.5 text-sm focus:border-theme-primary h-24 resize-none"
                                    placeholder="Add complete address & nearest landmark"
                                    :class="{'error-border': errors.address}"></textarea>
                                <template x-if="errors.address">
                                    <div class="text-red-600 text-xs mt-1" x-text="errors.address[0]"></div>
                                </template>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-theme-muted mb-1.5">Address 2 (Optional)</label>
                                <input type="text"
                                    name="address2"
                                    x-model="form.address2"
                                    class="w-full border border-theme-deep bg-theme-surface text-theme-content rounded px-3 py-2.5 text-sm focus:border-theme-primary form-input">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-theme-muted mb-1.5">City *</label>
                                    <input type="text"
                                        name="city"
                                        x-model="form.city"
                                        required
                                        class="w-full border border-theme-deep bg-theme-surface text-theme-content rounded px-3 py-2.5 text-sm focus:border-theme-primary form-input"
                                        :class="{'error-border': errors.city}">
                                    <template x-if="errors.city">
                                        <div class="text-red-600 text-xs mt-1" x-text="errors.city[0]"></div>
                                    </template>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-theme-muted mb-1.5">Postal code</label>
                                    <input type="text"
                                        name="zip"
                                        x-model="form.zip"
                                        class="w-full border border-theme-deep bg-theme-surface text-theme-content rounded px-3 py-2.5 text-sm focus:border-theme-primary form-input">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-theme-muted mb-1.5">Phone *</label>
                                <input type="tel"
                                    name="phone"
                                    x-model="form.phone"
                                    required
                                    class="w-full border border-theme-deep bg-theme-surface text-theme-content rounded px-3 py-2.5 text-sm focus:border-theme-primary form-input"
                                    :class="{'error-border': errors.phone}">
                                <template x-if="errors.phone">
                                    <div class="text-red-600 text-xs mt-1" x-text="errors.phone[0]"></div>
                                </template>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox"
                                    id="save-info"
                                    name="save_info"
                                    x-model="form.save_info"
                                    class="h-3.5 w-3.5 text-theme-primary border-theme-deep rounded focus:ring-theme-primary">
                                <label for="save-info" class="ml-2 text-xs text-theme-muted">Save this information for next time</label>
                            </div>
                        </div>

                        <!-- Shipping Method -->
                        <div class="bg-theme-page shadow-card rounded-lg p-5 mb-3">
                            <h2 class="text-lg font-bold mb-4 text-theme-content font-heading">
                                <i class="fas fa-truck text-theme-primary"></i>
                                Shipping method
                            </h2>

                            <!-- Fixed Country Shipping Rate Message -->
                            <div x-show="selectedCountryShippingRate > 0"
                                x-cloak
                                class="mb-4 p-3 bg-theme-primary-light/20 border border-theme-primary rounded">
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle text-theme-primary mr-2"></i>
                                    <span class="text-sm text-theme-primary">
                                        Fixed shipping rate for this country:
                                        <span x-text="formatCurrency(selectedCountryShippingRate)"></span>
                                        <template x-if="selectedCountryFreeThreshold > 0">
                                            <span class="block text-xs mt-1">
                                                Free shipping on orders over <span x-text="formatCurrency(selectedCountryFreeThreshold)"></span>
                                            </span>
                                        </template>
                                    </span>
                                </div>
                            </div>

                            <!-- Shipping Methods (Disabled when country has fixed rate) -->
                            <div x-show="selectedCountryShippingRate === 0" x-cloak>
                                <div class="space-y-3">
                                    <template x-for="method in shippingMethods" :key="method.id">
                                        <label class="flex items-center justify-between p-4 border border-theme-deep bg-theme-surface rounded cursor-pointer hover:border-theme-primary transition-colors"
                                            :class="{'border-theme-primary ring-1 ring-theme-primary': form.shipping_method_id == method.id}">
                                            <div class="flex items-center">
                                                <input type="radio"
                                                    name="shipping_method_id"
                                                    x-model="form.shipping_method_id"
                                                    :value="method.id"
                                                    @change="onShippingMethodChange"
                                                    :disabled="selectedCountryShippingRate > 0"
                                                    class="h-4 w-4 text-theme-primary border-theme-deep focus:ring-theme-primary">
                                                <div class="ml-3">
                                                    <div class="font-semibold text-theme-content text-sm" x-text="method.name"></div>
                                                    <div class="text-xs text-theme-muted mt-0.5" x-text="method.delivery_time"></div>
                                                </div>
                                            </div>
                                            <template x-if="method.cost === 0">
                                                <div class="font-bold text-green-600">FREE</div>
                                            </template>
                                            <template x-if="method.cost > 0">
                                                <div class="font-bold text-theme-primary" x-text="formatCurrency(method.cost)"></div>
                                            </template>
                                        </label>
                                    </template>
                                </div>
                                <div x-show="!form.country_id" class="text-xs text-yellow-600 mt-2">
                                    Please select a country to see shipping methods
                                </div>
                            </div>
                        </div>

                        <!-- Billing Address -->
                        <div class="bg-theme-page shadow-card rounded-lg p-5 mb-3">
                            <h2 class="text-lg font-bold mb-4 text-theme-content font-heading">
                                <i class="fas fa-house text-theme-primary"></i>
                                Billing address
                            </h2>
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center">
                                    <input type="radio"
                                        id="same-address"
                                        x-model.boolean="billingSame"
                                        :value="true"
                                        name="billing_toggle"
                                        class="h-3.5 w-3.5 text-theme-primary border-theme-deep focus:ring-theme-primary">
                                    <label for="same-address" class="ml-2 text-sm text-theme-content">Same as shipping address</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio"
                                        id="different-address"
                                        x-model.boolean="billingSame"
                                        :value="false"
                                        name="billing_toggle"
                                        class="h-3.5 w-3.5 text-theme-primary border-theme-deep focus:ring-theme-primary">
                                    <label for="different-address" class="ml-2 text-sm text-theme-content">Use a different billing address</label>
                                </div>
                            </div>

                            <div x-show="!billingSame" x-transition x-cloak class="space-y-4 pt-4 ">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-theme-muted mb-1.5">First name *</label>
                                        <input type="text"
                                            name="billing_first_name"
                                            x-model="form.billing_first_name"
                                            :required="billingSame ? false : true"                                            class="w-full border border-theme-deep bg-theme-surface text-theme-muted rounded px-3 py-2.5 text-sm focus:border-theme-primary form-input"
                                            :class="{'error-border': errors.billing_first_name}">
                                        <template x-if="errors.billing_first_name">
                                            <div class="text-red-600 text-xs mt-1" x-text="errors.billing_first_name[0]"></div>
                                        </template>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-theme-muted mb-1.5">Last name *</label>
                                        <input type="text"
                                            name="billing_last_name"
                                            x-model="form.billing_last_name"
                                            :required="billingSame ? false : true"                                            class="w-full border border-theme-deep bg-theme-surface text-theme-content rounded px-3 py-2.5 text-sm focus:border-theme-primary form-input"
                                            :class="{'error-border': errors.billing_last_name}">
                                        <template x-if="errors.billing_last_name">
                                            <div class="text-red-600 text-xs mt-1" x-text="errors.billing_last_name[0]"></div>
                                        </template>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-theme-muted mb-1.5">Address *</label>
                                    <input type="text"
                                        name="billing_address"
                                        x-model="form.billing_address"
                                        :required="billingSame ? false : true"                                        class="w-full border border-theme-deep bg-theme-surface text-theme-content rounded px-3 py-2.5 text-sm focus:border-theme-primary form-input"
                                        :class="{'error-border': errors.billing_address}">
                                    <template x-if="errors.billing_address">
                                        <div class="text-red-600 text-xs mt-1" x-text="errors.billing_address[0]"></div>
                                    </template>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-theme-muted mb-1.5">Address 2 (Optional)</label>
                                    <input type="text"
                                        name="billing_address2"
                                        x-model="form.billing_address2"
                                        class="w-full border border-theme-deep bg-theme-surface text-theme-content rounded px-3 py-2.5 text-sm focus:border-theme-primary form-input">
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-theme-muted mb-1.5">City *</label>
                                        <input type="text"
                                            name="billing_city"
                                            x-model="form.billing_city"
                                            :required="billingSame ? false : true"                                            class="w-full border border-theme-deep bg-theme-surface text-theme-content rounded px-3 py-2.5 text-sm focus:border-theme-primary form-input"
                                            :class="{'error-border': errors.billing_city}">
                                        <template x-if="errors.billing_city">
                                            <div class="text-red-600 text-xs mt-1" x-text="errors.billing_city[0]"></div>
                                        </template>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-theme-muted mb-1.5">Postal code (Optional)</label>
                                        <input type="text"
                                            name="billing_zip"
                                            x-model="form.billing_zip"
                                            class="w-full border border-theme-deep bg-theme-surface text-theme-muted rounded px-3 py-2.5 text-sm focus:border-theme-primary form-input">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-theme-muted mb-1.5">Phone *</label>
                                    <input type="tel"
                                        name="billing_phone"
                                        x-model="form.billing_phone"
                                        :required="billingSame ? false : true"
                                        class="w-full border border-theme-deep bg-theme-surface text-theme-content rounded px-3 py-2.5 text-sm focus:border-theme-primary form-input"
                                        :class="{'error-border': errors.billing_phone}">
                                    <template x-if="errors.billing_phone">
                                        <div class="text-red-600 text-xs mt-1" x-text="errors.billing_phone[0]"></div>
                                    </template>
                                </div>
                            </div>
                        </div>


                        <!-- Payment Section -->
                        <div class="bg-theme-page shadow-card rounded-lg p-5 mb-3">
                            <h2 class="text-lg font-bold mb-4 text-theme-content font-heading">
                                <i class="fas fa-wallet text-theme-primary"></i>
                                Payment
                            </h2>
                            <p class="text-xs text-theme-muted mb-4">All transactions are secure and encrypted.</p>
                            <div class="space-y-3">
                                <template x-for="method in paymentMethods" :key="method.id">
                                    <label class="flex items-center justify-between p-3 border border-theme-deep bg-theme-surface rounded cursor-pointer hover:border-theme-primary transition-colors"
                                        :class="{'border-theme-primary ring-1 ring-theme-primary': form.payment_method_id == method.id}">
                                        <div class="flex items-center">
                                            <input type="radio"
                                                name="payment_method_id"
                                                x-model="form.payment_method_id"
                                                :value="method.id"
                                                required
                                                class="h-4 w-4 accent-theme-primary">

                                            <span class="ml-3 text-sm font-medium text-theme-content" x-text="method.name"></span>
                                        </div>
                                        <div class="ml-4">
                                            <template x-if="method.name.toLowerCase().includes('stripe')">
                                                <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/stripe.svg"
                                                    class="h-6"
                                                    alt="Stripe">
                                            </template>

                                            <template x-if="method.name.toLowerCase().includes('easypaisa')">
                                                <img src="/assets/icons/easypaisa.svg" class="h-6" alt="EasyPaisa">
                                            </template>

                                            <template x-if="method.name.toLowerCase().includes('bank')">
                                                <i class="fa-solid fa-building-columns text-xl"></i>
                                            </template>

                                            <template x-if="method.name.toLowerCase().includes('cash')">
                                                <i class="fa-solid fa-money-bill-wave text-xl"></i>
                                            </template>
                                        </div>


                                    </label>
                                </template>
                            </div>
                            <template x-if="errors.payment_method_id">
                                <div class="text-red-600 text-xs mt-2" x-text="errors.payment_method_id[0]"></div>
                            </template>
                        </div>

                        <!-- Terms Agreement -->
                        <div class="bg-theme-page shadow-card rounded-lg p-5 mb-5">
                            <div class="flex items-start">
                                <input type="checkbox"
                                    id="agree-terms"
                                    name="agree_terms"
                                    x-model="form.agree_terms"
                                    required
                                    class="h-4 w-4 mt-1 text-theme-primary border-theme-deep rounded focus:ring-theme-primary"
                                    :class="{'error-border': errors.agree_terms}">
                                <label for="agree-terms" class="ml-2 text-xs text-theme-muted">
                                    I agree to the <a href="#" class="underline hover:text-theme-content text-theme-primary">Terms of Service</a>,
                                    <a href="#" class="underline hover:text-theme-content text-theme-primary">Refund policy</a>, and
                                    <a href="#" class="underline hover:text-theme-content text-theme-primary">Privacy policy</a>.
                                </label>
                            </div>
                            <template x-if="errors.agree_terms">
                                <div class="text-red-600 text-xs mt-2" x-text="errors.agree_terms[0]"></div>
                            </template>
                        </div>


                        <!-- Policies -->
                        <div class="bg-theme-page shadow-card p-5 rounded-lg">
                            <div class="text-xs text-theme-muted space-y-2">
                                <p>By completing your purchase, you agree to our policies.</p>
                                <div class="flex flex-wrap gap-3 pt-1">
                                    <a href="#" class="hover:text-theme-content text-theme-primary">Refund policy</a>
                                    <a href="#" class="hover:text-theme-content text-theme-primary">Shipping</a>
                                    <a href="#" class="hover:text-theme-content text-theme-primary">Privacy policy</a>
                                    <a href="#" class="hover:text-theme-content text-theme-primary">Terms of service</a>
                                    <a href="#" class="hover:text-theme-content text-theme-primary">Contact</a>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Mobile Accordion -->
                        <div class="lg:hidden bg-theme-page shadow-card rounded-lg p-5 mb-5" x-cloak>
                            <button class="w-full flex items-center justify-between"
                                @click="showBottomSummary = !showBottomSummary"
                                type="button">
                                <span class="font-medium text-theme-content">Show order summary</span>
                                <div class="flex items-center">
                                    <span class="font-semibold mr-2 text-theme-primary" x-text="formatCurrency(summary.total)"></span>
                                    <i class="fas fa-chevron-down transition-transform duration-200 text-theme-muted"
                                        :class="{'transform rotate-180': showBottomSummary}"></i>
                                </div>
                            </button>

                            <div x-show="showBottomSummary" x-transition x-cloak>
                                <div class="p-4 mt-4">
                                    <div class="mb-4 max-h-48 overflow-y-auto">
                                        <template x-for="item in cart" :key="item.id">
                                            <div class="flex items-center justify-between mb-3 pb-3 border-b border-theme-deep last:border-0">
                                                <div class="flex items-center">
                                                    <div class="w-12 h-12 bg-theme-deep rounded flex items-center justify-center mr-3 border border-theme-deep overflow-hidden flex-shrink-0">
                                                        <template x-if="item.image">
                                                            <img :src="item.image" :alt="item.name" class="w-full h-full object-cover">
                                                        </template>
                                                        <template x-if="!item.image">
                                                            <i class="fas fa-box text-theme-muted text-sm"></i>
                                                        </template>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <div class="font-semibold text-theme-content text-sm truncate" x-text="item.name"></div>
                                                        <div class="text-xs text-theme-muted mt-0.5">
                                                            Qty: <span x-text="item.quantity"></span> ×
                                                            <span x-text="formatCurrency(item.price)"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="font-medium text-theme-content whitespace-nowrap"
                                                    x-text="formatCurrency(item.price * item.quantity)"></div>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="mb-4">
                                        <div class="text-sm font-medium text-theme-content mb-2">Discount code</div>
                                        <div class="flex gap-2">
                                            <input type="text"
                                                x-model="promoCode"
                                                :class="{'error-border': promoError, 'success-border': promoSuccess}"
                                                @input="promoError = null; promoSuccess = null"
                                                @keyup.enter="applyPromoCode"
                                                class="flex-1 border border-theme-deep bg-theme-surface text-theme-content rounded px-3 py-2 text-sm focus:border-theme-primary h-9"
                                                placeholder="Enter code">
                                            <button @click="applyPromoCode"
                                                :disabled="isLoading || !form.country_id || isApplyingPromo"
                                                :class="{'loading': isApplyingPromo, 'opacity-50 cursor-not-allowed': !form.country_id || isApplyingPromo}"
                                                class="bg-theme-primary text-theme-inverted px-4 py-2 rounded text-xs font-medium hover:bg-theme-primary-dark whitespace-nowrap h-9 transition-colors">
                                                <span x-show="!isApplyingPromo">Apply</span>
                                                <span x-show="isApplyingPromo" class="flex items-center">
                                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                                </span>
                                            </button>
                                        </div>
                                        <template x-if="promoError">
                                            <div class="text-red-600 text-xs mt-1" x-text="promoError"></div>
                                        </template>
                                        <template x-if="promoSuccess">
                                            <div class="text-green-600 text-xs mt-1" x-text="promoSuccess"></div>
                                        </template>
                                    </div>

                                    <div class="space-y-2 pt-3 border-t border-theme-muted">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-theme-muted">Subtotal</span>
                                            <span class="font-medium text-theme-content" x-text="formatCurrency(summary.subtotal)"></span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-theme-muted">Tax</span>
                                            <span class="font-medium text-theme-content">0</span>
                                        </div>

                                        <template x-if="summary.promo_discount > 0">
                                            <div class="flex justify-between text-sm text-green-600">
                                                <span>Promo Discount</span>
                                                <span class="font-medium">-<span x-text="formatCurrency(summary.promo_discount)"></span></span>
                                            </div>
                                        </template>

                                        <div class="flex justify-between text-sm">
                                            <span class="text-theme-muted">Shipping</span>
                                            <template x-if="summary.shipping_cost === 0">
                                                <span class="text-green-600 font-medium">FREE</span>
                                            </template>
                                            <template x-if="summary.shipping_cost > 0">
                                                <span class="font-medium text-theme-content" x-text="formatCurrency(summary.shipping_cost)"></span>
                                            </template>
                                        </div>

                                        <div class="pt-3 border-t border-theme-muted flex justify-between items-center">
                                            <div>
                                                <span class="font-semibold text-theme-content">Total</span>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-semibold text-theme-primary text-lg" x-text="formatCurrency(summary.total)"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Complete Order Button -->
                        <div class="mb-5 p-5">
                            <button type="submit"
                                :disabled="isSubmitting"
                                :class="{'loading': isSubmitting}"
                                class="w-full bg-theme-primary text-theme-inverted py-3 rounded font-semibold text-base hover:bg-theme-primary-dark transition-colors disabled:bg-theme-muted disabled:cursor-not-allowed">
                                <span x-show="!isSubmitting">Complete order</span>
                                <span x-show="isSubmitting" class="flex items-center justify-center">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>Processing...
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Right Column - Order Summary (40%) - Desktop Only -->
                    <div class="lg:w-[40%] hidden lg:block">
                        <div class="bg-theme-page shadow-card p-5 sticky top-6 max-h-[calc(100vh-2rem)] overflow-auto">
                            <div class="mb-4">
                                <h2 class="text-lg font-bold text-theme-content mb-1 font-heading">Order summary</h2>
                                <p class="text-xs text-theme-muted">Review your items and shipping details.</p>
                            </div>

                            <div class="mb-4 max-h-60 overflow-y-auto">
                                <template x-for="item in cart" :key="item.id">
                                    <div class="flex items-center justify-between mb-3 pb-3 border-b border-theme-deep last:border-0">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-theme-deep rounded flex items-center justify-center mr-3 border border-theme-deep overflow-hidden flex-shrink-0">
                                                <template x-if="item.img">
                                                    <img :src="item.img" :alt="item.name" class="w-full h-full object-cover">
                                                </template>

                                            </div>
                                            <div class="min-w-0">
                                                <div class="font-semibold text-theme-content text-sm truncate" x-text="item.name"></div>
                                                <div class="text-xs text-theme-muted mt-0.5">
                                                    Qty: <span x-text="item.quantity"></span> ×
                                                    <span x-text="formatCurrency(item.price)"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="font-medium text-theme-content whitespace-nowrap"
                                            x-text="formatCurrency(item.price * item.quantity)"></div>
                                    </div>
                                </template>
                            </div>

                            <!-- Discount Code -->
                            <div class="mb-4">
                                <div class="text-sm font-medium text-theme-content mb-2">Discount code</div>
                                <div class="flex gap-2">
                                    <input type="text"
                                        x-model="promoCode"
                                        :class="{'error-border': promoError, 'success-border': promoSuccess}"
                                        @input="promoError = null; promoSuccess = null"
                                        @keyup.enter="applyPromoCode"
                                        class="flex-1 border border-theme-deep bg-theme-surface text-theme-content rounded px-3 py-2 text-sm focus:border-theme-primary h-11"
                                        placeholder="Enter code">
                                    <button @click="applyPromoCode"
                                        :disabled="isLoading || !form.country_id || isApplyingPromo"
                                        :class="{'loading': isApplyingPromo, 'opacity-50 cursor-not-allowed': !form.country_id || isApplyingPromo}"
                                        class="bg-theme-primary text-theme-inverted px-4 py-2 rounded text-xs font-medium hover:bg-theme-primary-dark whitespace-nowrap h-11 transition-colors">
                                        <span x-show="!isApplyingPromo">Apply</span>
                                        <span x-show="isApplyingPromo" class="flex items-center">
                                            <i class="fas fa-spinner fa-spin mr-2"></i>
                                        </span>
                                    </button>
                                </div>
                                <template x-if="promoError">
                                    <div class="text-red-600 text-xs mt-1" x-text="promoError"></div>
                                </template>
                                <template x-if="promoSuccess">
                                    <div class="text-green-600 text-xs mt-1" x-text="promoSuccess"></div>
                                </template>
                            </div>

                            <!-- Order Totals -->
                            <div class="space-y-2 pt-4 border-t border-theme-surface">
                                <div class="flex justify-between text-sm">
                                    <span class="text-theme-muted">Subtotal</span>
                                    <span class="font-medium text-theme-content" x-text="formatCurrency(summary.subtotal)"></span>
                                </div>

                                <div class="flex justify-between text-sm">
                                    <span class="text-theme-muted">Tax</span>
                                    <span class="font-medium text-theme-content">0</span>
                                </div>

                                <template x-if="summary.promo_discount > 0">
                                    <div class="flex justify-between text-sm text-green-600">
                                        <span>Promo Discount</span>
                                        <span class="font-medium">-<span x-text="formatCurrency(summary.promo_discount)"></span></span>
                                    </div>
                                </template>

                                <div class="flex justify-between text-sm">
                                    <span class="text-theme-muted">Shipping</span>
                                    <template x-if="summary.shipping_cost === 0">
                                        <span class="text-green-600 font-medium">FREE</span>
                                    </template>
                                    <template x-if="summary.shipping_cost > 0">
                                        <span class="font-medium text-theme-content" x-text="formatCurrency(summary.shipping_cost)"></span>
                                    </template>
                                </div>

                                <div class="pt-3 border-t border-theme-surface flex justify-between items-start">
                                    <div>
                                        <span class="font-bold text-theme-content">Total</span>
                                    </div>
                                    <div class="font-bold text-theme-primary text-lg" x-text="formatCurrency(summary.total)"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <div id="initial-data" data-initial='@json($initialData)' style="display: none;"></div>
    <script>
        function checkoutApp() {
            return {
                loading: true,
                cart: [],
                summary: {},
                shippingMethods: [],
                shippingCountries: [],
                paymentMethods: [],
                sale: null,
                promoCode: '',
                promoError: null,
                promoSuccess: null,
                promoCodeId: null,
                isLoading: false,
                isApplyingPromo: false,
                isSubmitting: false,
                errors: {},
                showTopSummary: false,
                showBottomSummary: false,
                billingSame: true,
                selectedCountryShippingRate: 0,
                selectedCountryFreeThreshold: 0,
                cartHash: '',

                form: {
                    email: '',
                    first_name: '',
                    last_name: '',
                    address: '',
                    address2: '',
                    city: '',
                    zip: '',
                    phone: '',
                    country_id: '',
                    shipping_method_id: '',
                    payment_method_id: '',
                    agree_terms: true,
                    save_info: false,
                    billing_first_name: '',
                    billing_last_name: '',
                    billing_address: '',
                    billing_address2: '',
                    billing_city: '',
                    billing_zip: '',
                    billing_phone: '',
                },

                async init() {
                    try {
                        const initialData = JSON.parse(document.getElementById('initial-data').getAttribute('data-initial'));

                        this.cart = initialData.cart || [];
                        this.summary = initialData.summary || {};
                        this.cartHash = this.summary.cart_hash || '';
                        this.shippingMethods = initialData.shippingMethods || [];
                        this.shippingCountries = initialData.shippingCountries || [];
                        this.paymentMethods = initialData.paymentMethods || [];
                        this.sale = initialData.sale || null;

                        const storedPromo = this.getStoredPromoWithExpiry();
                        if (storedPromo) {
                            this.promoCode = storedPromo.code;

                            // Check if promo is still valid
                            if (this.isPromoExpired(storedPromo.timestamp)) {
                                localStorage.removeItem('lastAppliedPromo');
                                this.promoCode = '';
                            }
                        }

                        if (initialData.user) {
                            this.form.email = initialData.user.email || '';
                            this.form.first_name = initialData.user.first_name || '';
                            this.form.last_name = initialData.user.last_name || '';
                            this.form.phone = initialData.user.phone || '';
                            this.form.billing_first_name = initialData.user.first_name || '';
                            this.form.billing_last_name = initialData.user.last_name || '';
                            this.form.billing_phone = initialData.user.phone || '';
                        }

                        if (initialData.default_shipping_country_id) {
                            this.form.country_id = initialData.default_shipping_country_id;
                            const selectedCountry = this.shippingCountries.find(c => c.id == this.form.country_id);
                            if (selectedCountry) {
                                this.selectedCountryShippingRate = selectedCountry.shipping_rate || 0;
                                this.selectedCountryFreeThreshold = selectedCountry.free_shipping_threshold || 0;
                            }
                        }

                        if (initialData.default_shipping_method_id && this.selectedCountryShippingRate === 0) {
                            this.form.shipping_method_id = initialData.default_shipping_method_id;
                        }

                        if (this.paymentMethods.length > 0) {
                            this.form.payment_method_id = this.paymentMethods[0].id;
                        }

                        const csrfToken = initialData.csrf_token;
                        if (csrfToken) {
                            document.querySelector('meta[name="csrf-token"]').setAttribute('content', csrfToken);
                        }

                        this.billingSame = true;

                        await this.updateSummary();

                        if (this.promoCode && this.form.country_id) {
                            await this.applyPromoCode(true);
                        }

                    } catch (error) {
                        console.error('Failed to initialize checkout:', error);
                        this.showError('Failed to load checkout page. Please refresh.');
                    } finally {
                        setTimeout(() => {
                            this.loading = false;
                        }, 500);
                    }
                },

                formatCurrency(amount) {
                    if (isNaN(amount)) amount = 0;
                    return `Rs. ${parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}`;
                },

                formatTimeRemaining(endTime) {
                    if (!endTime) return '';
                    const end = new Date(endTime);
                    const now = new Date();
                    const diffMs = end - now;

                    if (diffMs <= 0) return 'Expired';
                    const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
                    const diffDays = Math.floor(diffHours / 24);

                    if (diffDays > 0) {
                        return `${diffDays}d ${diffHours % 24}h left`;
                    } else if (diffHours > 0) {
                        return `${diffHours}h left`;
                    } else {
                        return '< 1h left';
                    }
                },

                async onCountryChange() {
                    if (!this.form.country_id) {
                        this.selectedCountryShippingRate = 0;
                        this.selectedCountryFreeThreshold = 0;
                        this.form.shipping_method_id = '';
                        return;
                    }

                    const selectedCountry = this.shippingCountries.find(c => c.id == this.form.country_id);
                    if (selectedCountry) {
                        this.selectedCountryShippingRate = parseFloat(selectedCountry.shipping_rate) || 0;
                        this.selectedCountryFreeThreshold = parseFloat(selectedCountry.free_shipping_threshold) || 0;

                        if (this.selectedCountryShippingRate > 0) {
                            this.form.shipping_method_id = '';
                        } else if (this.shippingMethods.length > 0 && !this.form.shipping_method_id) {
                            this.form.shipping_method_id = this.shippingMethods[0].id;
                        }
                    } else {
                        this.selectedCountryShippingRate = 0;
                        this.selectedCountryFreeThreshold = 0;
                    }

                    await this.updateSummary();

                    if (this.promoCode) {
                        await this.applyPromoCode(true);
                    }
                },

                async onShippingMethodChange() {
                    await this.updateSummary();

                    if (this.promoCode) {
                        await this.applyPromoCode(true);
                    }
                },

                async updateSummary() {
                    try {
                        this.isLoading = true;
                        const params = new URLSearchParams();

                        if (this.form.shipping_method_id && this.selectedCountryShippingRate === 0) {
                            params.append('shipping_method_id', this.form.shipping_method_id);
                        }

                        if (this.form.country_id) {
                            params.append('country_id', this.form.country_id);
                        }

                        if (this.promoCode) {
                            params.append('code', this.promoCode);
                        }

                        const response = await fetch('{{ route("user.checkout.cartSummary") }}?' + params);
                        const data = await response.json();

                        if (data.success) {
                            this.cartHash = data.cart_hash;
                            this.summary.cart_hash = data.cart_hash;
                            this.summary.shipping_cost = data.shipping_cost;
                            this.summary.subtotal = data.subtotal;
                            this.summary.total = data.total;
                            this.summary.sale_discount = data.sale_discount;
                            this.summary.promo_discount = data.promo_discount || this.summary.promo_discount || 0;

                            // Update country-specific shipping info
                            if (data.has_fixed_country_rate) {
                                this.selectedCountryShippingRate = data.country_shipping_rate;
                                this.selectedCountryFreeThreshold = data.country_free_shipping_threshold || 0;
                                this.summary.shipping_cost = data.shipping_cost;
                            }
                        } else {
                            console.error('Failed to update summary:', data.message);
                        }
                    } catch (error) {
                        console.error('Failed to update summary:', error);
                        this.showError('Failed to update shipping. Please try again.');
                    } finally {
                        this.isLoading = false;
                    }
                },

                // Promo code expiry helper methods
                getStoredPromoWithExpiry() {
                    const stored = localStorage.getItem('lastAppliedPromo');
                    if (!stored) return null;

                    try {
                        const data = JSON.parse(stored);
                        // Check if stored data has timestamp
                        if (!data.timestamp) {
                            // Old format without timestamp, remove it
                            localStorage.removeItem('lastAppliedPromo');
                            return null;
                        }
                        return data;
                    } catch (e) {
                        localStorage.removeItem('lastAppliedPromo');
                        return null;
                    }
                },

                isPromoExpired(timestamp) {
                    const now = new Date().getTime();
                    const expiryTime = 5 * 60 * 1000; // 5 minutes in milliseconds
                    return (now - timestamp) > expiryTime;
                },

                async applyPromoCode(isReapply = false) {
                    // Clear previous messages
                    if (!isReapply) {
                        this.promoError = null;
                        this.promoSuccess = null;
                    }

                    // Validate country selection
                    if (!this.form.country_id) {
                        this.promoError = 'Please select a country first';
                        return;
                    }

                    // Validate shipping method if country doesn't have fixed rate
                    if (this.selectedCountryShippingRate === 0 && !this.form.shipping_method_id) {
                        this.promoError = 'Please select a shipping method first';
                        return;
                    }

                    const code = this.promoCode.trim();

                    // If empty code, just update summary
                    if (!code && !isReapply) {
                        this.promoError = null;
                        this.promoSuccess = null;
                        localStorage.removeItem('lastAppliedPromo');
                        await this.updateSummary();
                        return;
                    }

                    this.isApplyingPromo = true;

                    try {
                        const response = await fetch('{{ route("user.checkout.applyPromo") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                code: code,
                                shipping_id: this.selectedCountryShippingRate > 0 ? null : this.form.shipping_method_id,
                                country_id: this.form.country_id,
                                cart_hash: this.cartHash
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            if (code) {
                                // Store with timestamp for 5 minutes expiry
                                localStorage.setItem('lastAppliedPromo', JSON.stringify({
                                    code: code,
                                    timestamp: new Date().getTime()
                                }));
                            }

                            if (!isReapply) {
                                this.promoSuccess = data.message;
                            }

                            this.promoCodeId = data.data.promo_code_id;
                            this.cartHash = data.data.cart_hash;

                            // Update summary with new data
                            this.summary = {
                                ...this.summary,
                                ...data.data
                            };
                            this.summary.cart_hash = this.cartHash;

                        } else {
                            // Handle cart hash mismatch - auto retry
                            if (data.new_hash) {
                                this.cartHash = data.new_hash;
                                this.summary.cart_hash = data.new_hash;
                                // Auto retry without showing error
                                setTimeout(() => this.applyPromoCode(isReapply), 100);
                                return;
                            } else {
                                this.promoError = data.message;
                                this.promoCodeId = null;
                                localStorage.removeItem('lastAppliedPromo');
                                await this.updateSummary();
                            }
                        }
                    } catch (error) {
                        console.error('Promo code error:', error);
                        this.promoError = 'Failed to apply promo code. Please try again.';
                    } finally {
                        this.isApplyingPromo = false;
                    }
                },

                async placeOrder() {
                    // Validate required fields
                    if (!this.form.agree_terms) {
                        this.showError('Please agree to the terms and conditions');
                        return;
                    }

                    const requiredFields = [
                        'email', 'first_name', 'last_name', 'address', 'city',
                        'phone', 'country_id', 'payment_method_id'
                    ];

                    for (const field of requiredFields) {
                        if (!this.form[field]) {
                            this.showError(`Please fill in all required fields`);
                            return;
                        }
                    }

                    if (this.selectedCountryShippingRate === 0 && !this.form.shipping_method_id) {
                        this.showError('Please select a shipping method');
                        return;
                    }

                    if (!this.billingSame) {
                        const billingFields = ['billing_first_name', 'billing_last_name', 'billing_address', 'billing_city', 'billing_phone'];
                        for (const field of billingFields) {
                            if (!this.form[field]) {
                                this.showError(`Please fill in all billing address fields`);
                                return;
                            }
                        }
                    }

                    this.isSubmitting = true;
                    this.errors = {};

                    try {
                        const formData = new FormData(document.getElementById('checkout-form'));

                        formData.append('total_amount', this.summary.total);
                        formData.append('promo_code_id', this.promoCodeId || '');
                        formData.append('billing_same', this.billingSame ? '1' : '0');

                        if (this.selectedCountryShippingRate === 0 && this.form.shipping_method_id) {
                            formData.append('shipping_method_id', this.form.shipping_method_id);
                        }

                        const response = await fetch('{{ route("user.checkout.placeOrder") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Clear local storage promo
                            localStorage.removeItem('lastAppliedPromo');
                            window.location.href = data.redirect_url;
                        } else {
                            if (data.errors) {
                                this.errors = data.errors;
                                this.showError('Please fix the errors in the form.');
                            } else {
                                this.showError(data.message || 'Failed to place order.');
                            }
                        }
                    } catch (error) {
                        console.error('Order placement error:', error);
                        this.showError('An error occurred. Please try again.');
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                showError(message) {
                    alert(message);
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const storedPromo = localStorage.getItem('lastAppliedPromo');
            if (storedPromo) {
                try {
                    const data = JSON.parse(storedPromo);
                    const now = new Date().getTime();
                    const expiryTime = 5 * 60 * 1000;
                    if (data.timestamp && (now - data.timestamp) > expiryTime) {
                        localStorage.removeItem('lastAppliedPromo');
                    }
                } catch (e) {
                    localStorage.removeItem('lastAppliedPromo');
                }
            }
        });
    </script>
</body>

</html>