@extends("user.layouts.master-layouts.plain")

@section('title', 'About | Luxorix')





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
                Luxorix Atelier // Manifesto // Vol. 01
            </span>
        </div>

        <h1 class="font-serif text-5xl md:text-7xl lg:text-8xl xl:text-[9rem] leading-[0.9] tracking-tighter mb-8                bg-gradient-to-b from-white via-amber-100 to-amber-600 bg-clip-text text-transparent 
               drop-shadow-2xl mix-blend-hard-light">
               The Art Behind Our Existence
               </h1>

        <div class="flex flex-col items-center gap-6 max-w-xl mx-auto">

            <p class="font-sans text-xs md:text-sm text-white/60 font-light leading-relaxed tracking-wide italic">
                “Luxury is not in possession — it is in perception.  
                It is the dialogue between the object and the soul.”
            </p>

            <p class="font-sans text-xs md:text-sm text-white/50 font-light leading-relaxed tracking-wide">
                At <span class="text-amber-200">Luxorix</span>, we craft essentials for the minds who  
                seek meaning in form, precision in detail,  
                and beauty in the quiet moments between.
            </p>

            <a href="#story" class="group flex flex-col items-center gap-2 mt-8 opacity-60 hover:opacity-100 transition-opacity cursor-pointer">
                <span class="font-mono text-[9px] uppercase tracking-widest text-white group-hover:text-amber-400 transition-colors">
                    Discover Our Story
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
            0% { transform: translateY(-100%); }
            100% { transform: translateY(200%); }
        }

        .animate-slide-down {
            animation: slide-down 1.5s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }
    </style>
</section>
<section id="story" class="relative w-full bg-theme-page py-32 lg:py-48 overflow-hidden font-body selection:bg-theme-primary selection:text-theme-inverted">

    {{-- 1. Texture: Film Grain (Essential for the non-techy, analog feel) --}}
    <div class="absolute inset-0 opacity-[0.05] pointer-events-none mix-blend-overlay z-0" 
         style="background-image: url('https://grainy-gradients.vercel.app/noise.svg'); background-size: 100px;">
    </div>

    {{-- 2. Ambient Lighting (Subtle Gold Haze) --}}
    <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-theme-primary/5 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10 space-y-40 md:space-y-64">

        @foreach($storyBlocks as $block)
            <div class="group relative flex flex-col {{ $loop->even ? 'md:flex-row-reverse' : 'md:flex-row' }} items-center gap-12 lg:gap-32">

                {{-- TYPE SECTION --}}
                <div class="w-full md:w-1/2 relative">
                    
                    {{-- A. The "Hype" Index Number (Massive, behind text) --}}
                    <div class="absolute -top-20 -left-10 md:-left-20 text-[12rem] leading-none font-heading font-black text-theme-surface select-none opacity-50 z-0 pointer-events-none transform translate-y-8">
                        0{{ $loop->iteration }}
                    </div>

                    <div class="relative z-10 space-y-8">
                        {{-- B. Meta Data Line (Looks like a clothing tag) --}}
                        <div class="flex items-center gap-4 text-xs font-body tracking-widest uppercase text-theme-primary">
                            <span class="w-2 h-2 bg-theme-primary rotate-45"></span>
                            <span>{{ $block->block_text }}</span>
                            <div class="h-[1px] w-24 bg-theme-primary/30"></div>
                        </div>

                        {{-- C. Editorial Heading --}}
                        <h2 class="font-heading text-5xl md:text-7xl lg:text-8xl text-theme-content leading-[0.9] uppercase tracking-tighter">
                            {{ $block->title }}
                            <span class="block text-3xl md:text-4xl font-accent normal-case italic text-theme-muted mt-2 tracking-normal opacity-70">
                                {{ $block->subtitle }}
                            </span>
                        </h2>

                        {{-- D. Description (Clean, high contrast) --}}
                        <div class="font-body text-theme-muted text-base md:text-lg leading-relaxed max-w-md border-l-2 border-theme-primary/20 pl-6">
                            {!! $block->content !!}
                        </div>

                        {{-- E. The "Stamp" (Replaces signature for a brand look) --}}
                        @if($block->signature_text)
                            <div class="pt-8 flex items-center gap-4 opacity-80 hover:opacity-100 transition-opacity">
                                <div class="border border-theme-primary/40 px-6 py-3 rounded-full flex items-center gap-3 bg-theme-page/50 backdrop-blur-sm">
                                    <div class="w-2 h-2 rounded-full bg-theme-primary animate-pulse"></div>
                                    <span class="font-body text-[10px] uppercase tracking-widest text-theme-content">
                                        {{ $block->signature_text }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- IMAGE SECTION --}}
                <div class="w-full md:w-1/2 relative">
                    {{-- Parallax floating visual anchor --}}
                    <div class="absolute -top-12 -right-12 w-24 h-24 border border-theme-primary/20 rounded-full flex items-center justify-center animate-spin-slow hidden md:flex">
                        <div class="w-2 h-2 bg-theme-primary rounded-full"></div>
                    </div>

                    {{-- Main Image Frame --}}
                    <div class="relative w-full aspect-[3/4] p-2 border border-theme-primary/10 bg-theme-surface/50">
                        
                        {{-- Corner Accents (The "Frame" Look) --}}
                        <div class="absolute top-0 left-0 w-4 h-4 border-l border-t border-theme-primary"></div>
                        <div class="absolute bottom-0 right-0 w-4 h-4 border-r border-b border-theme-primary"></div>

                        {{-- The Image --}}
                        <div class="w-full h-full overflow-hidden relative grayscale hover:grayscale-0 transition-all duration-1000 ease-out">
                            <img src="{{ asset('storage/' . $block->image_url) }}"
                                 alt="{{ $block->title }}"
                                 class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-1000" />
                             
                            {{-- Noise overlay on image specifically (Old School Magazine feel) --}}
                            <div class="absolute inset-0 bg-theme-deep/10 mix-blend-multiply"></div>
                        </div>

                        {{-- Floating Label --}}
                        <div class="absolute bottom-6 left-0 bg-theme-deep border-r border-t border-theme-primary/30 px-6 py-3">
                            <span class="font-heading text-xs text-theme-primary tracking-widest uppercase">
                                Fig. {{ $block->fig_label }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        @endforeach

    </div>
</section>



<section class="w-full bg-[#050505] py-24 border-t border-white/5 relative overflow-hidden">

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-amber-600/5 blur-[100px] rounded-full pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-12 relative z-10 text-center md:text-left">

        @foreach($stats as $stat)
            <div class="group cursor-default">
                <div class="flex flex-col gap-2">
                    <span class="font-serif text-6xl md:text-7xl text-white md:group-hover:text-amber-400 transition-colors duration-500">
                        {{ $stat->number_value }}
                    </span>
                    <div class="h-[1px] w-12 bg-white/20 md:group-hover:w-full md:group-hover:bg-amber-400 transition-all duration-700 ease-out mb-2 mx-auto md:mx-0"></div>
                    
                    <h3 class="font-mono text-xs uppercase tracking-[0.2em] text-white/50 md:group-hover:text-white">
                        {{ $stat->title }}
                    </h3>
                    
                    <p class="font-sans text-xs text-white/40 leading-relaxed max-w-xs mt-2 mx-auto md:mx-0 
                              opacity-100 translate-y-0 
                              md:opacity-0 md:translate-y-2 md:group-hover:opacity-100 md:group-hover:translate-y-0 
                              transition-all duration-500">
                        {!! nl2br(e($stat->description)) !!}
                    </p>
                </div>
            </div>
        @endforeach

    </div>
</section>

<section class="w-full bg-[#050505] py-32 text-center border-t border-white/5">
    
<div class="max-w-2xl mx-auto px-6">
    <p class="font-serif text-2xl md:text-3xl text-white italic mb-6">
        {!! $vision->quote !!}
    </p>
    
    <p class="font-sans text-xs text-white/40 leading-relaxed mb-10">
        {!! $vision->description !!}
    </p>

    <div class="flex flex-col items-center justify-center gap-2 opacity-50">
        <span class="font-heading text-4xl text-white font-bold tracking-tighter">
            {!! $vision->initials !!}
        </span>
        <span class="font-mono text-[9px] uppercase tracking-widest text-amber-500">
            {!! $vision->footer_text !!}
        </span>
    </div>
</div>


</section>

@endsection


@push("script")
@vite('resources/js/user/pages/about.js')
@endpush