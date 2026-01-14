<style>
    /* 1. Hide Scrollbars Globally */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* 2. Track Configuration */
    #infiniteTrack {
        display: flex;
        gap: 1.25rem;
        /* 20px */
        width: 100%;
        overflow-x: auto;
        /* Mobile: Native Scroll */
        scroll-behavior: smooth;
        scroll-snap-type: x mandatory;
    }

    .slider-card {
        flex: 0 0 55vw;
        scroll-snap-align: start;
    }

    @media (min-width: 768px) {
        #infiniteTrack {
            overflow-x: visible;
        }

        .slider-card {
            flex: 0 0 calc((100% - 2.5rem) / 3);
        }
    }

    @media (min-width: 1280px) {
        .slider-card {

            flex: 0 0 calc((100% - 3.75rem) / 4);
        }
    }
</style>

<section class="py-16 bg-theme-page overflow-hidden relative group-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-end justify-between mb-10 relative">
            <div class="text-left">
                <h2 class="font-serif text-3xl md:text-3xl lg:text-6xl leading-none 
        text-transparent bg-clip-text bg-gradient-to-b from-white via-white to-white/40
        uppercase tracking-widest font-extralight mix-blend-screen
        transition-transform duration-1000 ease-out group-hover/header:translate-x-4">
                    TOP SELLING PRODUCTS
                </h2>

                <h2 class="absolute top-0 left-4 md:left-8 font-serif text-3xl md:text-8xl lg:text-6xl leading-none 
        text-white/10 uppercase tracking-widest pointer-events-none blur-md
        transition-transform duration-1000 ease-out group-hover/header:-translate-x-4">
                    TOP SELLING PRODUCTS
                </h2>

                <p class="mt-3 text-theme-muted font-body text-sm sm:text-base tracking-wide max-w-xl font-light">
    Carefully curated selections from our premium collection.
</p>

            </div>

            <div class=" md:flex gap-2 relative z-10 flex-shrink-0">
                <button id="slidePrev" class="w-10 h-10 rounded-full border border-theme-content/10 flex items-center justify-center text-theme-content hover:bg-theme-primary hover:border-theme-primary hover:text-theme-inverted transition-all duration-300 bg-theme-page">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button id="slideNext" class="w-10 h-10 rounded-full border border-theme-content/10 flex items-center justify-center text-theme-content hover:bg-theme-primary hover:border-theme-primary hover:text-theme-inverted transition-all duration-300 bg-theme-page">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <div class="relative w-full">
            <div id="infiniteTrack" class="scrollbar-hide px-0 pb-4">

                @foreach ($topSellingProducts as $product)

                <div class="slider-card h-full">
                    @php
                    $rating = $product->rating ?? 4.5;
                    $discountPercentage = $product->offer ? $product->offer : 0;
                    if($discountPercentage == 0 && $product->cut_price > $product->price){
                    $discountPercentage = round((($product->cut_price - $product->price) / $product->cut_price) * 100);
                    }
                    @endphp

                    <a href="{{ route('products.show', $product->slug) }}"
                        class="group relative
                               bg-theme-surface border border-theme-content/5 rounded-sm
                               transition-all duration-300 hover:-translate-y-1 hover:shadow-glow
                               flex flex-col overflow-hidden h-full">
                        @php
                        $tags = $product->tags;

                        while (is_string($tags)) {
                        $decoded = json_decode($tags, true);
                        if ($decoded === null) break;
                        $tags = $decoded;
                        }
                        @endphp



                        {{-- IMAGE CONTAINER --}}
                        <div class="relative h-[240px] sm:h-[280px] bg-theme-page/50 p-4 flex items-center justify-center group-hover:bg-theme-page/80 transition-colors">
                            @if(is_array($tags) && count($tags))
                            <span class="absolute top-4 left-4 text-[10px] font-body font-medium tracking-widest text-theme-muted uppercase opacity-70 z-20">
                                {{ implode(', ', $tags) }}
                            </span>
                            @endif


                            <div class="absolute top-4 right-4 flex items-center gap-1 bg-theme-page/80 border border-theme-content/10 rounded-full px-2 py-1 backdrop-blur-sm z-20">
                                <i class="fas fa-star text-[10px] text-theme-primary"></i>
                                <span class="text-xs text-theme-content font-body font-bold">{{ $rating }}</span>
                            </div>

                            {{-- MAIN IMAGE --}}
                            @php
                            $mainImg = $product->mainImage->image_path ?? null;
                            $subImg = $product->subImage->image_path ?? null;


                            $col = 'path';
                            @endphp

                            @if($mainImg)
                            <img src="{{ Storage::url($mainImg) }}"
                                alt="{{ $product->name }}"
                                class="relative z-10 w-full h-full object-contain drop-shadow-2xl transition-all duration-500 group-hover:scale-110 
         {{ $subImg ? 'group-hover:opacity-0' : '' }}" />
                            @else
                            <img src="https://via.placeholder.com/300" alt="No Image" class="w-full h-full object-contain" />
                            @endif

                            {{-- SUB IMAGE (HOVER) --}}
                            @if($subImg)
                            <img src="{{ Storage::url($subImg) }}"
                                alt="{{ $product->name }}"
                                class="absolute top-0 left-0 z-10 w-full h-full object-contain drop-shadow-2xl transition-all duration-500 opacity-0 group-hover:opacity-100 group-hover:scale-110 p-4" />
                            @endif
                        </div>

                        {{-- DETAILS CONTAINER --}}
                        <div class="p-4 sm:p-4 flex flex-col flex-grow justify-between relative z-20 bg-theme-surface">
                            <div>
                                <div class="flex justify-between items-start gap-2 mb-0.5 sm:mb-2">
                                    <h3 class="font-heading text-theme-content font-bold text-sm sm:text-lg leading-tight truncate w-full sm:w-auto tracking-wide">
                                        {{ $product->name }}
                                    </h3>

                                    @if($discountPercentage > 0)
                                    <span class="flex-shrink-0 bg-theme-primary text-theme-inverted font-body
                                                     text-[8px] sm:text-[10px] font-extrabold
                                                     px-1.5 sm:px-2 py-0.5 sm:py-1
                                                     shadow-lg uppercase tracking-wide whitespace-nowrap rounded-sm">
                                        {{ $discountPercentage }}% OFF
                                    </span>
                                    @endif
                                </div>

                                <p class="text-theme-muted font-body text-[10px] sm:text-xs leading-relaxed mb-2 sm:mb-3 line-clamp-1 sm:line-clamp-2 font-light">
                                    {{ $product->description }}
                                </p>
                            </div>

                            <div class="flex items-end justify-between pt-1 sm:pt-3 border-t border-theme-content/5 mt-auto">
                                <div class="flex flex-col leading-none">
                                    @if($product->cut_price > $product->price)
                                    <span class="text-theme-muted line-through font-body text-[9px] sm:text-[10px] mb-0.5">
                                        Rs.{{ number_format($product->cut_price) }}
                                    </span>
                                    @endif
                                    <span class="text-base sm:text-lg lg:text-xl font-bold text-theme-content font-body tracking-tight">
                                        Rs.{{ number_format($product->price) }}
                                    </span>
                                </div>

                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-theme-content/5 border border-theme-content/10 flex items-center justify-center
                                                group-hover:bg-theme-primary group-hover:text-theme-inverted group-hover:border-theme-primary transition-all duration-300 shadow-lg">
                                    <i class="fas fa-arrow-right text-xs sm:text-sm transition-transform duration-300 group-hover:translate-x-0.5"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const track = document.getElementById('infiniteTrack');
        const btnNext = document.getElementById('slideNext');
        const btnPrev = document.getElementById('slidePrev');
        const section = document.querySelector('.group-section');

        // Configuration
        const GAP = 20;
        const ANIMATION_SPEED = 400;
        const AUTO_PLAY_DELAY = 3500;

        let isAnimating = false;
        let autoPlayTimer;

        // --- 1. CHECK DEVICE TYPE ---
        function isDesktopOrTablet() {
            return window.innerWidth >= 768; // Tailwind 'md'
        }

        // --- 2. INFINITE SCROLL LOGIC ---
        function moveSlider(direction) {
            if (!isDesktopOrTablet() || isAnimating) return;
            isAnimating = true;

            const card = track.firstElementChild;
            const cardWidth = card.getBoundingClientRect().width;
            const totalMove = cardWidth + GAP;

            // FADE OUT & SLIDE
            track.style.transition = `transform ${ANIMATION_SPEED}ms ease-in-out, opacity ${ANIMATION_SPEED}ms ease-in-out`;
            track.style.opacity = '0.4';

            if (direction === 'next') {
                track.style.transform = `translateX(-${totalMove}px)`;
            } else {
                track.style.transition = 'none';
                track.style.opacity = '0.4';
                track.insertBefore(track.lastElementChild, track.firstElementChild);
                track.style.transform = `translateX(-${totalMove}px)`;

                void track.offsetWidth;

                track.style.transition = `transform ${ANIMATION_SPEED}ms ease-in-out, opacity ${ANIMATION_SPEED}ms ease-in-out`;
                track.style.transform = `translateX(0px)`;
            }

            // RESET DOM
            setTimeout(() => {
                if (direction === 'next') {
                    track.appendChild(track.firstElementChild);
                    track.style.transition = 'none';
                    track.style.transform = 'translateX(0)';
                }

                setTimeout(() => {
                    track.style.transition = `opacity 200ms ease-in`;
                    track.style.opacity = '1';
                    isAnimating = false;
                }, 50);

            }, ANIMATION_SPEED);
        }

        // --- 3. CONTROLS ---
        function startAutoPlay() {
            stopAutoPlay();
            if (isDesktopOrTablet()) {
                autoPlayTimer = setInterval(() => moveSlider('next'), AUTO_PLAY_DELAY);
            }
        }

        function stopAutoPlay() {
            if (autoPlayTimer) clearInterval(autoPlayTimer);
        }

        if (btnNext && btnPrev) {
            btnNext.addEventListener('click', () => {
                stopAutoPlay();
                moveSlider('next');
                startAutoPlay();
            });

            btnPrev.addEventListener('click', () => {
                stopAutoPlay();
                moveSlider('prev');
                startAutoPlay();
            });
        }

        if (section) {
            section.addEventListener('mouseenter', stopAutoPlay);
            section.addEventListener('mouseleave', startAutoPlay);
        }

        window.addEventListener('resize', () => {
            stopAutoPlay();
            startAutoPlay();

            if (!isDesktopOrTablet()) {
                track.style.transform = '';
                track.style.opacity = '';
            }
        });

        startAutoPlay();
    });
</script>