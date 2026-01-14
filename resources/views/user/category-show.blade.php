@extends("user.layouts.master-layouts.plain")

{{-- Dynamic Title based on Category Name --}}
@section('title', $category->name . ' | Archive')

@section("content")

{{-- 1. ARCHIVE HEADER SECTION --}}
<section class="relative pt-32 pb-12 bg-[#050505] border-b border-white/5">
    
    {{-- Background Noise --}}
    <div class="absolute inset-0 z-0 opacity-[0.03] pointer-events-none" 
         style="background-image: url('https://grainy-gradients.vercel.app/noise.svg');">
    </div>

    <div class="container mx-auto px-4 relative z-10">
        
        {{-- Top Navigation (Breadcrumbs) --}}
        <div class="mb-12 animate-fade-in flex justify-between items-center">
            
            {{-- Back Button (Dynamic Route) --}}
            <a href="{{ route('user.object') }}" class="group inline-flex items-center gap-2 text-white/30 hover:text-white transition-colors">
                <div class="w-6 h-6 rounded-full border border-white/10 flex items-center justify-center group-hover:border-amber-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 group-hover:text-amber-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </div>
                <span class="font-mono text-[9px] uppercase tracking-widest group-hover:text-amber-500 transition-colors">
                    // Return to Base
                </span>
            </a>

            {{-- Live Date --}}
            <div class="hidden md:block">
                <span class="font-mono text-[9px] text-white/20 uppercase tracking-widest">
                    {{ now()->format('d.m.Y // H:i:s') }}
                </span>
            </div>
        </div>

        {{-- The Main Title Area --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 border-l-2 border-amber-500/50 pl-6 md:pl-8">
            
            {{-- Left: Big Title (Dynamic) --}}
            <div>
                <span class="font-mono text-[9px] text-amber-500 uppercase tracking-[0.3em] mb-3 block">
                    Restricted Access // Vol. 0{{ $category->id }}
                </span>
                <h1 class="font-serif text-6xl md:text-8xl text-white uppercase tracking-tighter leading-[0.85] mb-4">
                    {{ $category->name }}
                </h1>
                <p class="font-sans text-xs text-white/40 max-w-md leading-relaxed tracking-wide">
                    {{ $category->description ?? 'Authenticated artifacts. Sourced globally. Curated for the modern archive.' }}
                </p>
            </div>

            {{-- Right: The Data Grid --}}
            <div class="flex gap-8 md:gap-12 pt-4 md:pt-0 border-t md:border-t-0 border-white/10 mt-4 md:mt-0">
                
                {{-- Status --}}
                <div class="text-left md:text-right">
                    <span class="block font-mono text-[9px] text-white/30 uppercase tracking-widest mb-1">Status</span>
                    <div class="flex items-center gap-2 md:justify-end">
                        <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
                        <span class="block font-sans text-xs font-bold text-white uppercase tracking-widest">Online</span>
                    </div>
                </div>

                {{-- Inventory (Dynamic Count) --}}
                <div class="text-left md:text-right">
                    <span class="block font-mono text-[9px] text-white/30 uppercase tracking-widest mb-1">Inventory</span>
                    <span class="block font-sans text-xs font-bold text-white uppercase tracking-widest">
                        {{ str_pad($category->products->count(), 2, '0', STR_PAD_LEFT) }} Units
                    </span>
                </div>

                {{-- Region --}}
                <div class="text-left md:text-right">
                    <span class="block font-mono text-[9px] text-white/30 uppercase tracking-widest mb-1">Region</span>
                    <span class="block font-sans text-xs font-bold text-white uppercase tracking-widest">KHI-PK</span>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- 2. THE VAULT GRID SECTION --}}
<section class="bg-[#050505] min-h-screen py-16 relative">
    <div class="container mx-auto px-2 md:px-8">
        
        {{-- FILTER BAR (Optional Luxury Touch) --}}
        <div class="flex justify-between items-center mb-12 border-b border-white/5 pb-4 px-2">
            <span class="font-mono text-[9px] text-white/30 uppercase tracking-widest">
                Database Results: {{ $category->products->count() }}
            </span>
            <div class="flex gap-4">
                <span class="font-mono text-[9px] text-amber-500 cursor-pointer">SORT: NEWEST</span>
            </div>
        </div>

        {{-- THE GRID LOOP --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-3 gap-y-6 md:gap-x-4 md:gap-y-16">            
            @forelse($category->products as $product)
                
                {{-- Animation Wrapper --}}
                <div class="animate-fade-in-up" style="animation-delay: {{ $loop->index * 50 }}ms">
                    
                    {{-- THIS IS WHERE WE INCLUDE THE CARD --}}
                    @include('user.components.secret-cards', ['product' => $product])

                </div>

            @empty
                
                {{-- EMPTY STATE (If no products found) --}}
                <div class="col-span-full flex flex-col items-center justify-center py-32 border border-white/5 rounded-2xl bg-white/[0.01]">
                    <div class="w-16 h-16 border border-white/10 rounded-full flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-6 h-6 text-white/20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                    <h3 class="font-serif text-2xl text-white/40 mb-2">Archive Empty</h3>
                    <p class="font-mono text-[10px] text-amber-500 uppercase tracking-widest">Restocking Soon</p>
                </div>

            @endforelse

        </div>

        {{-- End Marker --}}
        @if($category->products->count() > 0)
        <div class="mt-24 flex flex-col items-center gap-4 opacity-50">
            <div class="h-12 w-[1px] bg-gradient-to-b from-white/20 to-transparent"></div>
            <span class="font-mono text-[9px] text-white/20 uppercase tracking-[0.3em]">End of Archive</span>
        </div>
        @endif

    </div>
</section>

@endsection

@push("script")
{{-- Any specific scripts for this page can go here --}}
@endpush