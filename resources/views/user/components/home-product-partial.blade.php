{{-- user/components/home-product-partial.blade.php --}}
@php
    /**
     * Helper to fill feature groups.
     */
    function getFilledFeatureItems($products, $startIndex, $neededCount) {
        $slice = $products->slice($startIndex, $neededCount);
        $current = $slice->count();
        if ($current < $neededCount && $products->count() > 0) {
            $gap = $neededCount - $current;
            $fillers = $products->take($gap);
            return $slice->merge($fillers);
        }
        return $slice;
    }

    $heroCategory = $heroCategory ?? null;
    $stackedCategories = $stackedCategories ?? collect([]);
@endphp

<div class="animate-fade-in-up space-y-24">

    {{-- ========================================== --}}
    {{-- HERO CATEGORY SECTION                      --}}
    {{-- ========================================== --}}
    @if($heroCategory && $heroCategory->homeProducts->count() >= 2)
        @php
            $allProducts = $heroCategory->homeProducts;
            $total = $allProducts->count();
            $gridLimit = $total >= 4 ? 4 : 2;
            $gridItems = $allProducts->take($gridLimit);

            // Feature groups logic
            $primaryStart = $gridLimit;
            $primaryFeature = getFilledFeatureItems($allProducts, $primaryStart, 4);

            $isDoubleBanner = ($total >= 9);
            $secondaryFeature = collect([]);
            if ($isDoubleBanner) {
                $secondaryStart = 8;
                $secondaryFeature = getFilledFeatureItems($allProducts, $secondaryStart, 4);
            }

            $banner1Img = $heroCategory->image ? asset('storage/' . $heroCategory->image) : asset('path/to/placeholder.jpg');
            $banner1Txt = $heroCategory->tagline ?? $heroCategory->name;

            $banner2Img = $heroCategory->second_image ?? $heroCategory->second_image_path
                ? asset('storage/' . ($heroCategory->second_image ?? $heroCategory->second_image_path))
                : null;
            $banner2Txt = $heroCategory->second_tagline ?? 'Editor\'s Pick';
        @endphp

        <div class="hero-section group" data-category="{{ $heroCategory->slug }}">
            
{{-- A. Top Grid --}}
<div class="flex overflow-x-auto gap-4 mb-6 pb-4 snap-x snap-mandatory hide-scrollbar
            md:min-w-0 md:flex-nowrap md:overflow-x-auto md:snap-x md:snap-mandatory
            lg:grid lg:grid-cols-4 lg:gap-4 lg:overflow-visible lg:snap-none">

    @foreach($gridItems as $product)
        <div class="flex-shrink-0 min-w-[55vw] sm:min-w-[50vw] md:min-w-[33.333vw] lg:min-w-0 lg:w-full">
            @include('user.components.product-cards', ['product' => $product])
        </div>
    @endforeach
</div>

            {{-- B. Feature Area --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 auto-rows-[minmax(200px,auto)] grid-flow-row-dense">
                
                {{-- 1. PRIMARY BANNER --}}
                <div class="col-span-2 row-span-2 md:col-start-1 relative rounded-xl overflow-hidden min-h-[420px] border">
                    <img src="{{ $banner1Img }}" alt="{{ $banner1Txt }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                        <h3 class="text-white text-3xl font-bold uppercase tracking-widest">{{ $banner1Txt }}</h3>
                    </div>
                </div>

                {{-- 2. PRIMARY PRODUCTS --}}
                @foreach($primaryFeature as $product)
                    @include('user.components.vibe-cards', ['product' => $product])
                @endforeach

                {{-- 3. SECONDARY SECTION --}}
                @if($isDoubleBanner)
                    <div class="col-span-2 row-span-2 md:col-start-3 relative rounded-xl overflow-hidden border border-theme-surface/50">
                        <img src="{{ $banner2Img }}" 
                             alt="{{ $banner2Txt }}" 
                             class="absolute inset-0 w-full h-full object-cover">
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none"></div>
                    </div>

                    @foreach($secondaryFeature as $product)
                        <div class="h-full">
                            @include('user.components.vibe-cards', ['product' => $product])
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- Explore Link --}}
            <div class="mt-10 text-center">
                <a href="{{ url('category/'.$heroCategory->slug) }}" 
                   class="inline-block border-b-2 border-theme-primary pb-1 text-sm font-bold uppercase tracking-widest text-theme-content hover:text-theme-primary transition-colors duration-300 font-body">
                    Explore All {{ $heroCategory->name }} &rarr;
                </a>
            </div>
        </div>
    @endif


    {{-- ========================================== --}}
    {{-- STACKED CATEGORIES SECTION                 --}}
    {{-- ========================================== --}}
    @foreach($stackedCategories as $category)
        @if($category->homeProducts->count() < 2) @continue @endif

        @php
            $stackProducts = $category->homeProducts;
            $stackTotal = $stackProducts->count();
            $stackCap = min($stackTotal, 8);
            $gridLimit = $stackCap >= 4 ? 4 : 2;
            $gridItems = $stackProducts->take($gridLimit);
            $featureItems = getFilledFeatureItems($stackProducts, $gridLimit, 4);
            $isRightBanner = ($loop->index % 2 == 0);
            $bannerClass = $isRightBanner ? 'md:col-start-3 md:row-start-1' : 'md:col-start-1 md:row-start-1';
        @endphp

        <div class="stack-section border-t border-theme-surface pt-16">
            
            <h3 class="text-2xl font-bold uppercase tracking-widest mb-6 text-theme-primary font-heading">
                {{ $category->name }}
            </h3>

            {{-- TOP GRID (Horizontal Scroll) --}}
            <div class="flex overflow-x-auto md:grid md:grid-cols-4 gap-4 mb-4 pb-4 md:pb-0 snap-x snap-mandatory hide-scrollbar">
                @foreach($gridItems as $product)
                    {{-- 
                       UPDATED WIDTH: min-w-[55vw]
                    --}}
                    <div class="min-w-[55vw] md:min-w-0 md:w-auto flex-shrink-0 snap-center">
                        @include('user.components.product-cards', ['product' => $product])
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 auto-rows-[minmax(200px,auto)]">
                <div class="col-span-2 row-span-2 {{ $bannerClass }} relative rounded-xl overflow-hidden min-h-[420px]">
                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex items-end p-6">
                        <span class="text-theme-content font-heading text-xl font-bold uppercase tracking-wide">
                            {{ $category->name }}
                        </span>
                    </div>
                </div>

                @foreach($featureItems as $product)
                    @include('user.components.vibe-cards', ['product' => $product])
                @endforeach
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('category.show', $category->slug) }}"
                   class="text-xs font-bold uppercase tracking-widest text-theme-primary hover:text-theme-primary transition-colors duration-300 font-body">
                    View {{ $category->name }} &rarr;
                </a>
            </div>
        </div>
    @endforeach
</div>