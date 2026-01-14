{{-- user/components/product-cards.blade.php --}}
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


    <div class="relative h-[170px] md:h-[220px] lg:h-[280px] bg-theme-page/50 p-3 flex items-center justify-center group-hover:bg-theme-page/80 transition-colors">

    @php
    $tags = $product->tags;

    while (is_string($tags)) {
        $decoded = json_decode($tags, true);
        if ($decoded === null) break;
        $tags = $decoded;
    }
@endphp

        @if(is_array($tags) && count($tags))
        <span class="hidden md:block absolute top-4 left-4 text-[9px] font-body font-medium tracking-widest text-theme-muted uppercase opacity-70 z-20">
        {{ implode(', ', $tags) }}
    
    </span>
        @endif

        <div class="absolute top-2 right-2 md:top-4 md:right-4 flex items-center gap-1 bg-theme-page/80 border border-theme-content/10 rounded-full px-1.5 py-0.5 md:px-2 md:py-1 backdrop-blur-sm z-20">
            <i class="fas fa-star text-[8px] md:text-[9px] text-theme-primary"></i>
            <span class="text-[9px] md:text-[10px] text-theme-content font-body font-bold">{{ $rating }}</span>
        </div>

        {{-- MAIN IMAGE --}}
        <img src="{{ asset('storage/' . $product->mainImage->image_path) }}"
             alt="{{ $product->name }}"
             class="relative z-10 w-full h-full object-contain drop-shadow-2xl transition-all duration-500 group-hover:scale-110 
             {{ $product->sub_image ? 'group-hover:opacity-0' : '' }}" />

        {{-- SUB IMAGE --}}
        @if($product->subIimage)
            <img src="{{ asset('storage/' . $product->subImage->image_path) }}"
                 alt="{{ $product->name }}"
                 class="absolute top-0 left-0 z-10 w-full h-full object-contain drop-shadow-2xl transition-all duration-500 opacity-0 group-hover:opacity-100 group-hover:scale-110 p-4" />
        @endif
    </div>


    <div class="px-2 py-2.5 md:p-3 lg:p-4 flex flex-col flex-grow justify-between relative z-20 bg-theme-surface">
        <div>
            <div class="flex justify-between items-start gap-1.5 md:gap-2 mb-1 md:mb-2">
                
     
                <h3 class="font-sans text-theme-content font-bold 
                           text-[12px] md:text-[15px] lg:text-lg 
                           truncate md:whitespace-normal md:line-clamp-2 
                           leading-tight w-full tracking-wide">
                    {{ $product->name }}
                </h3>

                @if($discountPercentage > 0)
                <span class="flex-shrink-0 bg-theme-primary text-theme-inverted font-body
                             text-[8px] md:text-[9px] lg:text-[10px] font-extrabold
                             px-1 py-0.5 md:px-1.5 md:py-0.5
                             shadow-lg uppercase tracking-wide whitespace-nowrap rounded-sm">
                      {{ $discountPercentage }}%
                </span>
                @endif
            </div>

            <p class="text-theme-muted font-body text-[10px] md:text-xs leading-relaxed mb-1.5 md:mb-3 truncate font-light">
                {{ $product->description }}
            </p>
        </div>

        {{-- ========================================== --}}
        {{-- 3. FOOTER                                  --}}
        {{-- ========================================== --}}
        <div class="flex items-end justify-between pt-2 md:pt-3 border-t border-theme-content/5 mt-auto">
            
            <div class="flex flex-col leading-none">
                {{-- Cut Price --}}
                @if($product->cut_price > $product->price)
                <span class="text-theme-muted line-through font-body text-[9px] md:text-[10px] mb-0.5">
                    Rs.{{ number_format($product->cut_price) }}
                </span>
                @endif
                
                {{-- Price --}}
                <span class="text-sm md:text-base lg:text-xl font-bold text-theme-content font-body tracking-tight">
                    Rs.{{ number_format($product->price) }}
                </span>
            </div>

            {{-- Arrow --}}
            <div class="w-6 h-6 md:w-8 md:h-8 lg:w-10 lg:h-10 rounded-full bg-theme-content/5 border border-theme-content/10 flex items-center justify-center
                        group-hover:bg-theme-primary group-hover:text-theme-inverted group-hover:border-theme-primary transition-all duration-300 shadow-lg">
                <i class="fas fa-arrow-right text-[10px] md:text-xs lg:text-sm transition-transform duration-300 group-hover:translate-x-0.5"></i>
            </div>
        </div>
    </div>
</a>