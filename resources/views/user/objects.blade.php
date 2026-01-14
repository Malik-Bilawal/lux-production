@extends("user.layouts.master-layouts.plain")

@section('title', 'Objects & Artifacts | Luxorix')

@section("content")

{{-- 1. HERO SECTION --}}
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
                COZ™ // REF. 001 // KARACHI
            </span>
        </div>
        <h1 class="font-serif text-7xl md:text-[9rem] lg:text-[12rem] leading-[0.8] tracking-tighter mb-8 
               bg-gradient-to-b from-white via-amber-100 to-amber-800 bg-clip-text text-transparent 
               drop-shadow-2xl mix-blend-hard-light uppercase">
            OBJECT
        </h1>
        <div class="flex flex-col items-center gap-6 max-w-lg mx-auto">
            <p class="font-sans text-xs md:text-sm text-white/50 font-light leading-relaxed tracking-wide">
                <span class="text-amber-100 font-medium italic">"Do not use. Display."</span><br>
                Function is accidental. Form is absolute.<br>
                Handle with ego.
            </p>
            <a href="#collection" class="group flex flex-col items-center gap-2 mt-8 opacity-60 hover:opacity-100 transition-opacity cursor-pointer">
                <span class="font-mono text-[9px] uppercase tracking-widest text-white group-hover:text-amber-400 transition-colors">
                    INSPECT FORM
                </span>
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

{{-- 2. DYNAMIC COLLECTION GRID --}}
<section id="collection" class="bg-[#050505] pb-32 min-h-screen relative">

    {{-- Global Grain Overlay --}}
    <div class="absolute inset-0 z-0 opacity-[0.03] pointer-events-none"
        style="background-image: url('https://grainy-gradients.vercel.app/noise.svg');">
    </div>

    <div class="container mx-auto px-2 md:px-8 relative z-10 pt-24">

        @foreach($categories as $category)

        @php
        // --- LOGIC: SETUP IMAGES & LAYOUT MODE ---

        // 1. Placeholders (Safety Net)
        $placeholders = [
        'https://images.unsplash.com/photo-1618331835717-801e976710b2?q=80&w=2500&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1550505459-0f6224d4554f?q=80&w=2500&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1469334031218-e382a71b716b?q=80&w=2670&auto=format&fit=crop'
        ];

        // 2. Resolve Primary Banner
        // PRIORITIZE DB IMAGE. Fallback to placeholder if null.
        $banner1 = $category->image ? asset('storage/' . $category->image) : $placeholders[$loop->index % 3];
        $tagline1 = $category->tagline ?? 'Archive Selection';

        // 3. Resolve Secondary Banner (Flagship Check)
        // Logic: Must have secondary image in DB AND have enough products to justify 2 grids
        $hasSecondSection = !empty($category->second_image) && $category->products->count() > 4;

        $banner2 = $category->second_image ? asset('storage/' . $category->second_image) : null;
        $tagline2 = $category->second_tagline ?? 'Extended Archive';

        // 4. Zig-Zag Logic for Single Sections
        // If it's a flagship (Double) section, banner is ALWAYS left first.
        // If it's a normal section, we alternate Left/Right based on loop index.
        $isBannerLeft = $hasSecondSection ? true : $loop->odd;
        @endphp

        <div class="mb-40 last:mb-0 group/section">

        <div class="relative py-16 md:py-24 border-t border-white/10 group/header overflow-hidden">

{{-- 1. Background Interaction (Subtle Glow on Hover) --}}
{{-- Maintained subtle glow --}}
<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[80%] h-[150%] bg-gradient-to-r from-transparent via-white/[0.03] to-transparent skew-x-12 opacity-0 group-hover/header:opacity-100 transition-opacity duration-700 pointer-events-none"></div>

{{-- 2. Technical Top Row (Adjusted for spacing/cleanliness) --}}
<div class="flex justify-between items-center mb-6 px-4 md:px-8"> {{-- Increased padding for air --}}
    <div class="flex items-center gap-4">
        {{-- Status Indicator: Slightly more elegant border color --}}
        <div class="flex items-center gap-2 border border-amber-500/30 rounded-full px-3 py-1">
            <div class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></div>
            <span class="font-mono text-[9px] text-white/60 uppercase tracking-[0.2em] font-medium">Live Archive</span>
        </div>
        {{-- Serial Number --}}
        <span class="font-mono text-[9px] text-white/40 uppercase tracking-[0.2em] hidden md:block">
            SYS.ID // {{ str_pad($category->id, 3, '0', STR_PAD_LEFT) }}
        </span>
    </div>

    {{-- Item Count --}}
    <span class="font-mono text-[10px] text-white/50 uppercase tracking-widest font-medium">
        [{{ str_pad($category->products->count(), 2, '0', STR_PAD_LEFT) }} ARTIFACTS]
    </span>
</div>

{{-- 3. THE REFINED LUXURY TITLE --}}
<div class="relative px-4 md:px-8">
    {{-- MAIN TITLE: Smaller but wider tracking for a premium, spacious look --}}
    <h2 class="font-serif text-5xl md:text-8xl lg:text-9xl leading-none 
        text-transparent bg-clip-text bg-gradient-to-b from-white via-white to-white/40
        uppercase tracking-widest font-extralight mix-blend-screen
        transition-transform duration-1000 ease-out group-hover/header:translate-x-4">
        {{ $category->name }}
    </h2>

    {{-- Overlapping "Ghost" Text: Sharper effect with less blend --}}
    <h2 class="absolute top-0 left-4 md:left-8 font-serif text-5xl md:text-8xl lg:text-9xl leading-none 
        text-white/10 uppercase tracking-widest pointer-events-none blur-md
        transition-transform duration-1000 ease-out group-hover/header:-translate-x-4">
        {{ $category->name }}
    </h2>
</div>

{{-- 4. Bottom Row Description --}}
<div class="flex flex-col md:flex-row justify-between items-end mt-8 md:mt-16 gap-6 px-4 md:px-8"> {{-- Adjusted spacing --}}

    {{-- Left: The "Collection" Tag (Made more prominent) --}}
    <div class="hidden md:flex flex-col gap-1">
        <div class="w-16 h-[1px] bg-amber-500/80 mb-2"></div> {{-- Wider, slightly subdued line --}}
        <span class="font-mono text-[10px] text-amber-500 uppercase tracking-[0.3em] font-bold"> {{-- Bolded for emphasis --}}
            Vol. 0{{ $loop->iteration }} Edition
        </span>
    </div>

    {{-- Right: The Description --}}
    <p class="font-sans text-sm md:text-base text-white/60 font-light max-w-lg text-left md:text-right leading-relaxed">
        <span class="text-white font-normal">Curated Selection.</span>
        {{ $category->description ?? 'Defined by what is essential. An exploration of form, function, and the space between.' }}
    </p>
</div>

</div>

            {{-- === GRID BLOCK 1 === --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 grid-flow-dense mb-4">

                {{-- BANNER 1 (Left Side Logic) --}}
                @if($isBannerLeft)
                <div class="col-span-2 row-span-2 relative overflow-hidden rounded-sm border border-white/10 group h-[500px] md:h-auto">
                    <img src="{{ $banner1 }}" alt="{{ $category->name }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-[1.5s] group-hover:scale-105 opacity-80 filter grayscale-[20%] group-hover:grayscale-0">
                    <div class="absolute bottom-0 left-0 p-8 w-full bg-gradient-to-t from-black via-black/50 to-transparent">
                        <span class="font-mono text-[9px] text-white/60 uppercase tracking-widest mb-2 block">Vol. 1</span>
                        <p class="font-serif text-3xl text-white italic">"{{ $tagline1 }}"</p>
                    </div>
                </div>
                @endif

                {{-- PRODUCTS 1-4 --}}
                @foreach($category->products->take(4) as $product)
                @include('user.components.vibe-cards', ['product' => $product])
                @endforeach

                {{-- BANNER 1 (Right Side Logic - Only if NOT Banner Left) --}}
                @if(!$isBannerLeft)
                <div class="col-span-2 row-span-2 md:col-start-3 md:row-start-1 relative overflow-hidden rounded-sm border border-white/10 group h-[500px] md:h-auto">
                    <img src="{{ $banner1 }}" alt="{{ $category->name }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-[1.5s] group-hover:scale-105 opacity-80 filter grayscale-[20%] group-hover:grayscale-0">
                    <div class="absolute bottom-0 left-0 p-8 w-full bg-gradient-to-t from-black via-black/50 to-transparent text-right">
                        <span class="font-mono text-[9px] text-white/60 uppercase tracking-widest mb-2 block">Vol. 1</span>
                        <p class="font-serif text-3xl text-white italic">"{{ $tagline1 }}"</p>
                    </div>
                </div>
                @endif

            </div>


            {{-- === GRID BLOCK 2 (FLAGSHIP EXTENSION) === --}}
            {{-- Only renders if second_image exists AND > 4 products --}}
            @if($hasSecondSection)
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 grid-flow-dense mt-4">

                {{-- PRODUCTS 5-8 --}}
                @foreach($category->products->slice(4, 4) as $product)
                @include('user.components.product-cards', ['product' => $product])
                @endforeach

                {{-- BANNER 2 (Always Right Side for Flagship Layout) --}}
                <div class="col-span-2 row-span-2 md:col-start-3 md:row-start-1 relative overflow-hidden rounded-sm border border-white/10 group h-[500px] md:h-auto">
                    <img src="{{ $banner2 }}" alt="{{ $category->name }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-[1.5s] group-hover:scale-105 opacity-80 filter grayscale-[20%] group-hover:grayscale-0">
                    <div class="absolute bottom-0 left-0 p-8 w-full bg-gradient-to-t from-black via-black/50 to-transparent text-right">
                        <span class="font-mono text-[9px] text-white/60 uppercase tracking-widest mb-2 block">Vol. 2</span>
                        <p class="font-serif text-3xl text-white italic">"{{ $tagline2 }}"</p>
                    </div>
                </div>

            </div>
            @endif


            {{-- === ENTER ARCHIVE BUTTON === --}}
            @php
            $shownCount = $hasSecondSection ? 8 : 4;
            $remaining = $category->products->count() - $shownCount;
            @endphp

            @if($remaining > 0)
            <div class="mt-12 flex justify-center">
            <a href="{{ route('category.show', $category->slug) }}" 
   class="relative inline-flex items-center justify-center px-10 py-4 rounded-full border border-white/20 overflow-hidden text-white font-mono uppercase tracking-widest text-[10px] group perspective">

    <!-- Background glow / shine -->
    <span class="absolute inset-0 bg-gradient-to-r from-amber-400 via-amber-200 to-white opacity-0 group-hover:opacity-30 transition-opacity duration-500 rounded-full blur-xl"></span>

    <!-- Hover fill animation -->
    <span class="absolute inset-0 bg-white origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-500 ease-out rounded-full"></span>

    <!-- Inner shadow & depth -->
    <span class="absolute inset-0 rounded-full shadow-inner shadow-black/20"></span>

    <!-- Content -->
    <span class="relative flex items-center gap-4 z-10">
        <span class="transition-colors duration-300 group-hover:text-black">
            Enter Archive (+{{ $remaining }})
        </span>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" 
             class="w-4 h-4 text-white transition-all duration-300 group-hover:text-black group-hover:translate-x-2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
        </svg>
    </span>

    <!-- Floating glow ring -->
    <span class="absolute -inset-1 rounded-full border border-amber-400 opacity-0 group-hover:opacity-60 animate-pulse-slow"></span>
</a>

            </div>
            @endif

        </div>
        @endforeach

    </div>
</section>

{{-- FOOTER SECTION (Unchanged) --}}
<section class="bg-[#050505] border-t border-white/10 overflow-hidden relative">
    {{-- ... Paste your Footer code here ... --}}
    <div class="relative w-full border-b border-white/10 py-4 bg-white/[0.02]">
        <div class="overflow-hidden flex">
            <div class="animate-marquee whitespace-nowrap flex gap-12 text-white/30 font-mono text-[9px] md:text-[10px] uppercase tracking-[0.3em]">
                <span>Global Shipping</span><span>//</span>
                <span>Authenticity Guaranteed</span><span>//</span>
                <span>Karachi HQ</span><span>//</span>
                <span>Vol. 01 Collection</span><span>//</span>
                <span>Limited Stock</span><span>//</span>
            </div>
        </div>
    </div>

    <div class="p-24 text-center">
        <h2 class="font-serif text-2xl text-white mb-4">NEVER MISS A DROP</h2>
        <p class="font-mono text-[10px] text-white/50 uppercase">Karachi, Pakistan</p>
    </div>
</section>

@endsection