@extends("user.layouts.master-layouts.plain")

@section('title', 'Timepieces | Luxorix')

@section("content")



<section class="relative isolate h-screen w-full flex flex-col items-center justify-center overflow-hidden bg-[#050505]">

    <div class="absolute inset-0 z-0 opacity-[0.06] pointer-events-none mix-blend-overlay"
        style="background-image: url('https://grainy-gradients.vercel.app/noise.svg');">
    </div>

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-amber-500/10 blur-[120px] rounded-full pointer-events-none animate-pulse duration-[4000ms]"></div>

    <div class="absolute bottom-0 left-0 w-full h-40 bg-gradient-to-t from-[#050505] via-[#050505]/80 to-transparent z-10"></div>


    <div class="relative z-20 text-center px-4 flex flex-col items-center">

        <div class="mb-8 flex flex-col items-center gap-4 animate-fade-in-up">
            <div class="h-16 w-[1px] bg-gradient-to-b from-transparent via-amber-400/50 to-transparent"></div>
            <span class="font-mono text-[9px] tracking-[0.4em] uppercase text-white/40">
            Luxorix // Vol. 01
            </span>
        </div>

        <h1 class="font-serif text-6xl md:text-8xl lg:text-[11rem] leading-[0.8] tracking-tighter mb-8 
               bg-gradient-to-b  from-white via-amber-100 to-amber-600 bg-clip-text text-transparent 
               drop-shadow-2xl mix-blend-hard-light">
               TIMEPIECES
        </h1>

        <div class="flex flex-col items-center gap-6 max-w-lg mx-auto">
            <p class="font-sans text-xs md:text-sm text-white/50 font-light leading-relaxed tracking-wide">
                <span class="text-amber-100">Curated Essentials.</span><br>

                We don't sell everything; we only sell what matters.
            </p>

            <a href="#collection" class="group flex flex-col items-center gap-2 mt-8 opacity-60 hover:opacity-100 transition-opacity cursor-pointer">
                <span class="font-mono text-[9px] uppercase tracking-widest text-white group-hover:text-amber-400 transition-colors">Enter Archive</span>

                <div class="w-[1px] h-12 bg-white/20 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1/2 bg-amber-400 animate-slide-down"></div>
                </div>
            </a>
        </div>

    </div>

    <div class="absolute bottom-0 inset-x-0 border-b border-white/10 w-full z-0 pointer-events-none"></div>

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

{{-- 2. DYNAMIC PRODUCT GRID --}}
<section class="bg-[#050505] pb-24 min-h-screen">
  <div class="container mx-auto px-2 md:px-8"> 
    
    {{-- SETUP DYNAMIC BANNERS --}}
    @php
        $placeholder1 = 'https://images.unsplash.com/photo-1618331835717-801e976710b2?q=80&w=2500&auto=format&fit=crop';
        $placeholder2 = 'https://images.unsplash.com/photo-1469334031218-e382a71b716b?q=80&w=2670&auto=format&fit=crop';

        $banner1Img = (isset($watchCategory) && $watchCategory->image) ? asset('storage/' . $watchCategory->image) : $placeholder1;
        $banner1Txt = (isset($watchCategory) && $watchCategory->tagline) ? $watchCategory->tagline : 'The Collection';

        $dbSecondImage = $watchCategory->second_image ?? $watchCategory->second_image_path ?? null;
        $banner2Img = $dbSecondImage ? asset('storage/' . $dbSecondImage) : null;
        $banner2Txt = (isset($watchCategory) && $watchCategory->second_tagline) ? $watchCategory->second_tagline : 'Modern Classics';
        
        $showBanner2 = !empty($dbSecondImage) && $products->count() > 8;
        
        // SPLIT THE COLLECTION
        $topProducts = $products->take(4);
        $bottomProducts = $products->skip(4);
    @endphp

    {{-- 
        ==================================================
        PART 1: TOP 4 ITEMS (Horizontal Scroll on Mobile)
        ==================================================
    --}}
    <div class="
        flex overflow-x-auto gap-4 pb-4 mb-4 snap-x snap-mandatory hide-scrollbar
        lg:grid lg:grid-cols-4 lg:gap-4 lg:pb-0 lg:mb-4 lg:snap-none
    ">
        @foreach($topProducts as $product)
            {{-- Mobile Width: 55vw (Scroll), Desktop: Auto (Grid) --}}
            <div class="flex-shrink-0 snap-center min-w-[55vw] sm:min-w-[50vw] md:min-w-[33.333vw] lg:min-w-0 lg:w-auto h-full">
                @include('user.components.product-cards', ['product' => $product])
            </div>
        @endforeach
    </div>


    {{-- 
        ==================================================
        PART 2: REMAINING ITEMS (Vertical Grid + Banners)
        ==================================================
    --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 grid-flow-dense">

      @foreach($bottomProducts as $product)
        
        {{-- CALCULATE REAL INDEX (Since we skipped 4, we add 4 to iteration) --}}
        @php 
            $realIteration = $loop->iteration + 4; 
        @endphp

        {{-- === INJECT BANNER 1 (At item 5) === --}}
        @if($realIteration == 5)
        <div class="col-span-2 row-span-1 md:row-span-2 relative overflow-hidden group border border-white/10 min-h-[300px] md:min-h-auto rounded-2xl md:rounded-[2rem]">
            <img src="{{ $banner1Img }}"
                 alt="{{ $banner1Txt }}"
                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-[1.5s] group-hover:scale-105 opacity-80 filter grayscale-[20%] group-hover:grayscale-0">
            
            <div class="absolute bottom-6 left-6 z-10">
                <span class="font-mono text-[9px] text-white/60 uppercase tracking-widest mb-2 block">Featured</span>
                <h2 class="font-serif text-3xl md:text-4xl text-white leading-none drop-shadow-lg">
                    {!! nl2br(e($banner1Txt)) !!}
                </h2>
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
        </div>
        @endif

        {{-- === INJECT BANNER 2 (At item 9) === --}}
        @if($realIteration == 9 && $showBanner2)
        <div class="col-span-2 row-span-1 md:row-span-2 md:col-start-3 relative overflow-hidden group border border-white/10 min-h-[300px] md:min-h-auto rounded-2xl md:rounded-[2rem]">
            <img src="{{ $banner2Img }}"
                 alt="{{ $banner2Txt }}"
                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-[1.5s] group-hover:scale-105 opacity-80 filter grayscale-[20%] group-hover:grayscale-0">
            
            <div class="absolute bottom-6 left-6 z-10">
                <span class="font-mono text-[9px] text-white/60 uppercase tracking-widest mb-2 block">Flagship</span>
                <h2 class="font-serif text-3xl md:text-4xl text-white leading-none drop-shadow-lg">
                    {!! nl2br(e($banner2Txt)) !!}
                </h2>
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
        </div>
        @endif


        {{-- === RENDER PRODUCT CARD === --}}
        @php
            // Logic: Use "Vibe Cards" for products surrounding the banners
            $isAroundBanner1 = ($realIteration >= 5 && $realIteration <= 8);
            $isAroundBanner2 = ($realIteration >= 9 && $realIteration <= 12 && $showBanner2);
            $useVibeCard = $isAroundBanner1 || $isAroundBanner2;
        @endphp

        {{-- No specific widths needed here, the grid handles it --}}
        <div>
            @if($useVibeCard)
                 @include('user.components.vibe-cards', ['product'=> $product])
            @else
                 @include('user.components.product-cards', ['product' => $product])
            @endif
        </div>

      @endforeach

    </div>

    {{-- OPTIONAL: LOAD MORE BUTTON --}}
    @if($products->count() > 12)
        <div class="mt-24 flex justify-center opacity-50 hover:opacity-100 transition-opacity">
            <button class="group flex flex-col items-center gap-2">
                <span class="font-mono text-[10px] uppercase tracking-[0.3em] text-white">End of Curated List</span>
                <div class="h-8 w-[1px] bg-white/20"></div>
            </button>
        </div>
    @endif

  </div>
</section>

{{-- FOOTER (Unchanged) --}}
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