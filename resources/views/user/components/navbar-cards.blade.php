<div class="space-y-4">
  <style>
    /* Smooth Luxury Animation */
    .product-card {
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .product-card:hover {
      transform: translateY(-4px);
      /* Updated shadow color to match new Gold (#D4AF37) */
      box-shadow: 0 20px 40px -12px rgba(212, 175, 55, 0.15);
    }
    @keyframes shine {
      100% { transform: translateX(100%); }
    }
    .animate-shine {
      animation: shine 0.75s;
    }
    .shadow-glow-gold {
      /* Updated shadow color to match new Gold */
      box-shadow: 0 0 10px rgba(212, 175, 55, 0.3);
    }
  </style>

  <a href="{{ route('products.show', $product->slug) }}" class="block group">
    <div class="product-card relative flex flex-row bg-theme-page rounded-xl overflow-hidden border border-theme-primary/10 hover:border-theme-primary/40 w-full h-28 sm:h-36">
      
      <span class="absolute top-2 right-2 bg-theme-surface/80 backdrop-blur-sm border border-theme-primary/20 text-theme-primary font-body text-[10px] sm:text-xs font-bold px-2 py-0.5 rounded-full z-10 shadow-sm">
        ⭐ {{ $product->rating }}
      </span>

      <div class="w-[35%] sm:w-[30%] bg-gradient-to-br from-theme-surface to-theme-page flex items-center justify-center overflow-hidden relative">
         <img src="{{ asset('storage/' . $product->mainImage->image_path) }}"
              class="h-full w-full object-contain p-3 transition-transform duration-500 group-hover:scale-110 mix-blend-normal" 
              alt="{{ $product->name }}" />
      </div>

      <div class="flex-1 flex flex-col justify-between p-3 sm:p-5">
        
        <div class="space-y-1">
        <h4 class="text-xs sm:text-lg font-heading font-bold text-theme-content leading-tight line-clamp-1 pr-14 group-hover:text-theme-primary transition-colors uppercase tracking-wide">
    {{ $product->name }}
</h4>

          
          <p class="text-[10px] sm:text-sm text-theme-muted font-body font-light line-clamp-1">
            {{ $product->description }}
          </p>
        </div>

        <div class="flex items-end justify-between mt-2">
          
          <div class="flex flex-col leading-none">
             <span class="text-[10px] sm:text-xs text-theme-muted/60 font-body line-through decoration-theme-muted/60 mb-1">
               PKR. {{ number_format($product->cut_price) }}
             </span>
             
             <div class="flex items-baseline gap-1">
                <span class="text-sm sm:text-xl font-bold text-theme-primary font-body tracking-wide">
                  <span class="text-[10px] sm:text-sm font-normal mr-0.5 text-theme-muted">PKR</span>{{ number_format($product->price) }}
                </span>
             </div>
          </div>

          <div class="flex flex-col items-end">
            <span class="bg-theme-primary text-theme-inverted font-body text-[10px] sm:text-xs font-bold px-2 py-1 rounded-md shadow-glow-gold">
              -{{ $product->offer }}%
            </span>
          </div>

        </div>
      </div>

      <div class="absolute inset-0 bg-gradient-to-r from-transparent via-theme-primary/10 to-transparent -translate-x-full group-hover:animate-shine pointer-events-none"></div>
    </div>
  </a>
</div>