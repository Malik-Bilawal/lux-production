<a href="{{ route('products.show', $product->slug) }}"
   class="col-span-1 row-span-1 block h-full w-full">

    {{--
        CHANGES FOR BIGGER IMAGE:
        - p-3: Reduced padding from p-4 to give image more room.
    --}}
    <div class="group relative flex h-full min-h-[180px] md:min-h-[280px] flex-col overflow-hidden rounded-xl md:rounded-[2rem] border border-white/10 bg-[#0a0a0a] p-3 md:p-5 transition-all duration-500 hover:border-white/20 hover:bg-[#0f0f0f]">

        {{-- 1. HEADER ROW --}}
        {{-- Added items-center to align small title better with icon --}}
        <div class="relative z-20 flex w-full justify-between items-center gap-2">

            {{-- Left Side: Title & Tag --}}
            <div class="flex flex-col gap-1 pr-2">
                {{--
                     TITLE FIX - SMALLER & MULTI-LINE:
                     - text-[10px] md:text-sm: Significantly smaller font size on all devices.
                     - leading-snug: Tighter line height for multi-line text.
                     - Removed Str::limit: Allows the text to wrap to more than one row.
                --}}
                <h3 class="font-body text-[10px] md:text-sm font-semibold uppercase leading-snug tracking-wide text-white antialiased">
                    {{ $product->name }}
                </h3>

                {{-- TAG --}}
                @if($product->tag)
                <div class="flex items-center gap-1.5 opacity-80">
                    <div class="h-[1px] w-1.5 bg-amber-500/80"></div>
                    {{-- Even smaller tag font --}}
                    <span class="font-mono text-[8px] uppercase tracking-wider text-white/50">
{{ implode(', ', $product->tags) }}
                    </span>
                </div>
                @endif
            </div>

            {{-- ICON --}}
            {{-- Slightly reduced icon size on mobile to save space --}}
            <div class="flex h-5 w-5 md:h-8 md:w-8 shrink-0 items-center justify-center rounded-full bg-white text-black transition-transform duration-500 group-hover:-rotate-45 group-hover:scale-110">
                <svg class="h-2 w-2 md:h-3.5 md:w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </div>
        </div>

        {{-- 2. PRODUCT IMAGE --}}
        {{-- pt-1: Reduced top padding slightly --}}
        <div class="relative mt-auto flex w-full items-end justify-center pt-1">

            {{-- Spotlight --}}
            <div class="absolute bottom-1/2 left-1/2 -translate-x-1/2 translate-y-1/2 h-16 w-16 md:h-32 md:w-32 rounded-full bg-white/[0.03] blur-xl md:blur-2xl transition-all duration-700 group-hover:bg-white/[0.08]"></div>

            {{--
               IMAGE FIX - BIGGER:
               - max-h-[130px]: Increased significantly from 85px for mobile.
               - md:max-h-[210px]: Increased significantly from 140px for desktop.
               (Object-contain ensures it doesn't get cut off, it just gets as big as possible within this height)
            --}}
            <img src="{{ asset('storage/' . $product->mainImage->image_path) }}"
                 alt="{{ $product->name }}"
                 class="relative z-10 max-h-[130px] md:max-h-[210px] w-auto object-contain
                        drop-shadow-[0_10px_15px_rgba(0,0,0,0.5)]
                        transform transition-transform duration-700 cubic-bezier(0.2, 0.8, 0.2, 1)
                        group-hover:scale-110 group-hover:-translate-y-2">
        </div>

    </div>
</a>