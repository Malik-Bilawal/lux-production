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

    <div class="relative h-[250px] sm:h-[280px] bg-theme-page/50 p-4 flex items-center justify-center group-hover:bg-theme-page/80 transition-colors">

    @php
    $tags = $product->tags;

    while (is_string($tags)) {
        $decoded = json_decode($tags, true);
        if ($decoded === null) break;
        $tags = $decoded;
    }
@endphp

@if(is_array($tags) && count($tags))
{{-- 
             UPDATED TAG SIZE: 
             Reduced from text-[10px] to text-[9px] 
        --}}
        <span class="absolute top-4 left-4 text-[9px] font-body font-medium tracking-widest text-theme-muted uppercase opacity-70 z-20">
        {{ implode(', ', $tags) }}
        </span>
        @endif

        <div class="absolute top-4 right-4 flex items-center gap-1 bg-theme-page/80 border border-theme-content/10 rounded-full px-2 py-1 backdrop-blur-sm z-20">
            {{-- 
                 UPDATED RATING SIZE: 
                 Star: text-[9px] (was 10px)
                 Text: text-[10px] (was text-xs/12px)
            --}}
            <i class="fas fa-star text-[9px] text-theme-primary"></i>
            <span class="text-[10px] text-theme-content font-body font-bold">{{ $rating }}</span>
        </div>

        {{-- MAIN IMAGE --}}
{{-- MAIN IMAGE --}}
@if($product->mainImage)
    <img src="{{ asset('storage/' . $product->mainImage->image_path) }}"
         alt="{{ $product->name }}"
         class="relative z-10 w-full h-full object-contain drop-shadow-2xl transition-all duration-500 group-hover:scale-110" />
@endif

{{-- SUB IMAGE (HOVER) --}}
@if($product->subImage)
    <img src="{{ asset('storage/' . $product->subImage->image_path) }}"
         alt="{{ $product->name }}"
         class="absolute top-0 left-0 z-10 w-full h-full object-contain drop-shadow-2xl transition-all duration-500 opacity-0 group-hover:opacity-100 group-hover:scale-110 p-4" />
@endif
    </div>

    {{-- Details Container --}}
    {{-- 
         UPDATED PADDING (HEIGHT): 
         Changed 'py-4' to 'py-[18px]'. 
         This adds exactly 2-3px more vertical breathing room to the caption area.
    --}}
    <div class="px-3 py-[18px] sm:p-4 flex flex-col flex-grow justify-between relative z-20 bg-theme-surface">
        <div>
            <div class="flex justify-between items-start gap-2 mb-0.5 sm:mb-2">
                
                {{-- 
                     UPDATED FONT FAMILY: 
                     Changed 'font-heading' to 'font-sans'. 
                     This removes the decorative font and makes it very clear and easy to read.
                --}}
                <h3 class="font-sans text-theme-content font-semibold text-[16px] sm:text-lg leading-relaxed w-full sm:w-auto tracking-wide">
    {{ $product->name }}
</h3>


                @if($discountPercentage > 0)
                <span class="flex-shrink-0 bg-theme-primary text-theme-inverted font-body
                             text-[9px] sm:text-[10px] font-extrabold
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