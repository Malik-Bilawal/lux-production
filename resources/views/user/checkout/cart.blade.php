@extends('user.layouts.master-layouts.plain')

@section('title', 'Your Selection | Luxorix')

@section('content')
<style>
    [x-cloak] {
        display: none !important;
    }

    /* Elegant Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #0a0a0a;
    }

    ::-webkit-scrollbar-thumb {
        background: #333;
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #d4af37;
    }

    /* Shimmer Effect for Loading */
    @keyframes shimmer {
        0% {
            background-position: -1000px 0;
        }

        100% {
            background-position: 1000px 0;
        }
    }

    .skeleton {
        animation: shimmer 2s infinite linear;
        background: linear-gradient(to right, #1a1a1a 4%, #252525 25%, #1a1a1a 36%);
        background-size: 1000px 100%;
    }
</style>

<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

<main
    x-data="cartLogic()"
    x-cloak
    class="min-h-screen bg-theme-deep text-white selection:bg-[#d4af37] selection:text-black font-sans relative overflow-x-hidden">

    {{-- Background Ambient Glows --}}

    {{-- Toast Notification (Glassmorphism) --}}
    <div x-show="toast.show"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-8 scale-90"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-8 scale-90"
        class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[100] flex items-center gap-4 px-6 py-4 rounded-full border border-white/10 bg-black/60 backdrop-blur-xl shadow-[0_0_30px_rgba(0,0,0,0.5)]">
        <div class="w-2 h-2 rounded-full shadow-[0_0_10px_currentColor]"
            :class="toast.type === 'error' ? 'bg-red-500 text-red-500' : 'bg-[#d4af37] text-[#d4af37]'"></div>
        <p class="text-xs font-medium tracking-widest uppercase text-white/90" x-text="toast.message"></p>
    </div>

    {{-- Remove Confirmation Modal --}}
    <div x-show="removeModalOpen" class="fixed inset-0 z-[200] flex items-center justify-center px-4" x-cloak>
        <div x-show="removeModalOpen"
            x-transition.opacity.duration.500ms
            class="absolute inset-0 bg-black/90 backdrop-blur-sm"
            @click="removeModalOpen = false">
        </div>

        <div x-show="removeModalOpen"
            x-transition:enter="ease-out duration-500"
            x-transition:enter-start="opacity-0 scale-90 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            class="relative z-10 w-full max-w-md bg-[#0a0a0a] border border-white/10 p-10 text-center shadow-[0_0_50px_rgba(0,0,0,0.8)]">
            <h3 class="font-serif text-2xl text-white mb-2">Remove Item</h3>
            <div class="h-px w-16 bg-[#d4af37] mx-auto mb-6"></div>
            <p class="text-white/50 text-sm mb-8 font-light leading-relaxed">
                Are you sure you wish to remove this masterpiece from your selection?
            </p>

            <div class="grid grid-cols-2 gap-4">
                <button @click="removeModalOpen = false" class="py-4 text-xs tracking-[0.2em] uppercase border border-white/10 hover:bg-white hover:text-black transition-colors duration-300">
                    Keep It
                </button>
                <button @click="proceedRemove()" class="py-4 text-xs tracking-[0.2em] uppercase bg-red-900/20 text-red-400 border border-red-900/30 hover:bg-red-900/40 transition-colors duration-300">
                    Remove
                </button>
            </div>
        </div>
    </div>

    {{-- Main Layout --}}
    <div class="relative z-10 max-w-[1400px] mx-auto px-6 py-24">

        {{-- Header Section --}}
        <header class="mb-20 text-center relative group">
            <h1 class="font-serif text-4xl md:text-7xl text-white font-light tracking-wider relative z-10 mix-blend-overlay opacity-90">
                YOUR SELECTION
            </h1>
            {{-- Ghost Text Animation --}}
            <h1 class="font-serif text-4xl md:text-7xl text-[#d4af37]/10 font-light tracking-wider absolute top-0 left-0 w-full blur-sm scale-105 transition-transform duration-[2000ms] group-hover:scale-110 group-hover:blur-md pointer-events-none">
                YOUR SELECTION
            </h1>

            <div class="mt-6 flex items-center justify-center gap-3 text-[10px] tracking-[0.3em] text-white/40 uppercase font-medium">
                <span>Secure</span>
                <span class="w-1 h-1 bg-[#d4af37] rounded-full"></span>
                <span>Encrypted</span>
                <span class="w-1 h-1 bg-[#d4af37] rounded-full"></span>
                <span>Worldwide Shipping</span>
            </div>
        </header>

        {{-- Loading Skeleton --}}
        <div x-show="loading" class="max-w-4xl mx-auto space-y-6">
            <div class="h-32 skeleton w-full"></div>
            <div class="h-32 skeleton w-full"></div>
            <div class="h-32 skeleton w-full"></div>
        </div>

        {{-- Empty State --}}
        <div x-show="!loading && items.length === 0" class="text-center py-24" style="display: none;">
            <p class="font-serif text-2xl text-white/40 italic mb-6">"Simplicity is the ultimate sophistication."</p>
            <p class="text-xs uppercase tracking-widest text-white/30">Your bag is currently empty</p>
            <a href="/" class="inline-block mt-8 px-8 py-3 border border-[#d4af37]/30 text-[#d4af37] text-xs uppercase tracking-[0.2em] hover:bg-[#d4af37] hover:text-black transition-all duration-500">
                Explore Collection
            </a>
        </div>

        <div x-show="!loading && items.length > 0" class="flex flex-col lg:flex-row gap-16 lg:gap-24">

        <section class="flex-1">
    <template x-for="item in items" :key="item.id">
        <div class="relative py-4 md:py-6 border-b border-white/10 last:border-b-0 group">

            <div class="flex flex-col md:flex-row md:items-center gap-4 md:gap-6">

                {{-- Image --}}
                <div class="w-24 md:w-32 aspect-[3/4] overflow-hidden  rounded-md flex-shrink-0">

                    <img :src="item.img"
                         class="w-full h-full object-cover transition-transform duration-500
                                md:group-hover:scale-[1.02]"
                         alt="Product Image">
                </div>

                {{-- Content --}}
                <div class="flex-1 flex flex-col md:flex-row md:items-center justify-between gap-3 md:gap-6">

                    {{-- Name + Variant --}}
                    <div class="flex-1 space-y-1">
                        <h3 class="font-serif text-base md:text-lg text-white"
                            x-text="item.name"></h3>
                        <p class="text-[11px] text-white/40 tracking-wide uppercase"
                           x-text="item.variant"></p>
                    </div>

                    {{-- Quantity + Price Row --}}
                    <div class="flex flex-col md:flex-row md:items-center md:gap-6 gap-2 w-full md:w-auto">

                        {{-- Desktop: center quantity, Mobile: left --}}
                        <div class="flex items-center gap-3 md:mx-auto">

                            {{-- Quantity Controls --}}
                            <div class="flex items-center gap-3 px-3 py-2 border border-white/10 rounded-full
                                        transition-colors duration-300 hover:border-white/20">

                                <button @click="updateQuantity(item.id, -1)"
                                        :disabled="item.updating"
                                        class="w-6 h-6 flex items-center justify-center
                                               text-white/40 hover:text-white disabled:opacity-20">
                                    <i class="fa-solid fa-minus text-[10px]"></i>
                                </button>

                                <div class="w-5 text-center relative h-6">
                                    <span x-show="!item.updating" class="text-sm font-medium"
                                          x-text="item.qty"></span>

                                    <div x-show="item.updating"
                                         class="absolute inset-0 flex items-center justify-center">
                                        <svg class="animate-spin h-3 w-3 text-white/60"
                                             xmlns="http://www.w3.org/2000/svg"
                                             fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                    </div>
                                </div>

                                <button @click="updateQuantity(item.id, 1)"
                                        :disabled="item.updating"
                                        class="w-6 h-6 flex items-center justify-center
                                               text-white/40 hover:text-white disabled:opacity-20">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Price --}}
                        <p class="text-sm md:text-base font-medium text-white text-left md:text-right md:min-w-[120px]"
                           x-text="'Rs. ' + (item.price * item.qty).toLocaleString()"></p>

                        {{-- Remove Button --}}
                        <button @click="askRemove(item.id)"
                                class="flex md:hidden items-center justify-center text-white/30 hover:text-white/60 p-1">
                            <span class="sr-only">Remove</span>
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>

                    </div>
                </div>

                <button @click="askRemove(item.id)"
                        class="hidden md:flex items-center justify-center absolute top-1/2 -translate-y-1/2 right-0 p-2
                               text-white/20 hover:text-white/60 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <span class="sr-only">Remove</span>
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>

            </div>
        </div>
    </template>
</section>


            <aside class="lg:w-[400px] shrink-0">
                <div class="sticky top-12">
                    <div class="p-8 md:p-10 rounded-lg
                    bg-[#0b0b0b]
                    border border-white/10
                    shadow-[0_20px_40px_rgba(0,0,0,0.4)]">

                        <h2 class="font-serif text-xl text-white mb-8 tracking-wide">
                            Order Summary
                        </h2>

                        <div class="space-y-6 mb-8">

                            {{-- Subtotal --}}
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-white/50">Subtotal</span>
                                <span class="text-white font-medium"
                                    x-text="'Rs. ' + subtotal.toLocaleString()"></span>
                            </div>

                            {{-- Shipping --}}
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-white/50">Shipping</span>

                                <template x-if="shippingCost === 0">
                                    <span class="text-[#d4af37] text-[11px] font-medium tracking-wide">
                                        Complimentary
                                    </span>
                                </template>

                                <template x-if="shippingCost > 0">
                                    <span class="text-white font-medium"
                                        x-text="'Rs. ' + shippingCost.toLocaleString()"></span>
                                </template>
                            </div>

                            {{-- Free shipping progress --}}
                            <div x-show="shippingCost > 0 && subtotal > 0" class="pt-2">
                                <div class="flex justify-between text-[11px] text-white/40 mb-2">
                                    <span>Free shipping</span>
                                    <span x-text="Math.round((subtotal / threshold) * 100) + '%'"></span>
                                </div>

                                <div class="w-full h-[2px] bg-white/10">
                                    <div class="h-full bg-[#d4af37]"
                                        :style="'width: ' + (subtotal / threshold) * 100 + '%'"></div>
                                </div>

                                <p class="mt-2 text-[11px] text-white/40">
                                    Add
                                    <span class="text-[#d4af37]"
                                        x-text="'Rs. ' + (threshold - subtotal).toLocaleString()"></span>
                                    for free shipping
                                </p>
                            </div>

                            <div class="h-px w-full bg-white/10"></div>

                            {{-- Total --}}
                            <div class="flex justify-between items-center pt-2">
                                <span class="text-white/80 font-medium">
                                    Total
                                </span>
                                <span class="text-xl font-serif text-white"
                                    x-text="'Rs. ' + (subtotal + shippingCost).toLocaleString()"></span>
                            </div>
                        </div>

                        {{-- Checkout Button --}}
                        <a href="{{ route('user.checkout', ['mode' => 'cart']) }}"
                            class="block w-full py-3 text-center
                      bg-white text-black
                      text-xs font-semibold uppercase tracking-[0.25em]
                      rounded-md
                      transition-opacity duration-200
                      hover:opacity-90">

                            Proceed to Checkout
                        </a>

                        {{-- Payment icons --}}
                        <div class="mt-8 flex justify-center gap-4 text-white/20">
                            <i class="fa-brands fa-cc-visa text-xl"></i>
                            <i class="fa-brands fa-cc-mastercard text-xl"></i>
                            <i class="fa-brands fa-cc-amex text-xl"></i>
                        </div>

                    </div>
                </div>
            </aside>

        </div>
    </div>
</main>
<script>
    function cartLogic() {
        return {
            items: [],
            loading: true,
            removeModalOpen: false,
            itemToRemove: null,

            // NEW: Shipping Variables
            shippingCost: 0,
            shippingName: 'Shipping',
            threshold: 0,
            serverSubtotal: 0, // To keep math consistent with backend

            toast: {
                show: false,
                message: '',
                type: 'error'
            },
            toastTimeout: null,

            async init() {
                console.log('Cart Logic Initialized');
                await this.fetchCart();
            },

            async fetchCart() {
                try {
                    let response = await fetch("{{ route('cart.data') }}");
                    let data = await response.json();
                    this.syncCartData(data);
                } catch (error) {
                    console.error('Error fetching cart:', error);
                    this.showToast('Failed to load selection', 'error');
                } finally {
                    setTimeout(() => {
                        this.loading = false;
                    }, 500);
                }
            },

            syncCartData(data) {
                if (!data || !data.items) return;

                // 1. Map Items
                this.items = data.items.map(item => ({
        id: item.id,
        name: item.product ? item.product.name : 'Unknown Item',
        variant: item.variant_name || 'Standard Edition',
        price: parseFloat(item.price) || 0,
        qty: parseInt(item.quantity) || 1,
        
        img: item.img, 
        
        updating: false
    }));
                // 2. NEW: Sync Shipping & Totals from Backend
                this.shippingCost = parseFloat(data.shipping_cost) || 0;
                this.shippingName = data.shipping_name || 'Shipping';
                this.threshold = parseFloat(data.threshold) || 0;
                this.serverSubtotal = parseFloat(data.subtotal) || 0; // Optional validation
            },

            async updateQuantity(id, change) {
                let item = this.items.find(i => i.id === id);
                if (!item || item.updating) return;

                if (item.qty + change < 1) return;

                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                let oldQty = item.qty;

                // Optimistic UI Update (makes it feel instant)
                item.qty += change;
                item.updating = true;

                try {
                    let response = await fetch("{{ route('cart.update') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            cart_item_id: id,
                            change: change
                        })
                    });

                    if (response.status === 422) {
                        let errorData = await response.json();
                        item.qty = oldQty; // Revert on error
                        this.showToast(errorData.message, 'error');
                        return;
                    }

                    if (!response.ok) throw new Error('Server error');

                    // Sync response (this updates Shipping Cost & Progress bar automatically)
                    let data = await response.json();
                    this.syncCartData(data);

                } catch (error) {
                    item.qty = oldQty; // Revert
                    this.showToast('Unable to update quantity', 'error');
                } finally {
                    item.updating = false;
                }
            },

            // ... [askRemove and proceedRemove remain exactly the same] ...
            askRemove(id) {
                this.itemToRemove = id;
                this.removeModalOpen = true;
            },

            async proceedRemove() {
                const id = this.itemToRemove;
                this.removeModalOpen = false;
                if (!id) return;

                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                let backupItems = [...this.items];

                // Optimistic Remove
                this.items = this.items.filter(i => i.id !== id);

                try {
                    let response = await fetch("{{ route('cart.remove') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            cart_item_id: id
                        })
                    });

                    if (!response.ok) throw new Error('Failed');

                    let data = await response.json();
                    this.syncCartData(data);
                    this.showToast('Item removed from selection', 'success');
                } catch (error) {
                    this.items = backupItems;
                    this.showToast('Could not remove item', 'error');
                }
            },

            showToast(message, type = 'error') {
                this.toast.message = message;
                this.toast.type = type;
                this.toast.show = true;
                if (this.toastTimeout) clearTimeout(this.toastTimeout);
                this.toastTimeout = setTimeout(() => {
                    this.toast.show = false;
                }, 4000);
            },

            // Calculated property for instant UI feedback
            get subtotal() {
                return this.items.reduce((sum, item) => {
                    return sum + ((item.price || 0) * (item.qty || 0));
                }, 0);
            }
        };
    }
</script>
@endsection