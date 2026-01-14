{{-- resources/views/components/sections/hero_split.blade.php --}}
@props(['section', 'items', 'index', 'page' => null]) {{-- ADD PAGE PROP --}}
@php $item = $items->first(); @endphp

<section class="min-h-[90vh] w-full flex flex-col lg:flex-row-reverse {{ $section->css_classes }}">
    {{-- RIGHT SIDE: VISUAL --}}
    <div class="relative w-full lg:w-[45%] h-[50vh] lg:h-auto overflow-hidden group">
        @if($item && $item->image_url)
            <div class="absolute inset-0 overflow-hidden">
                <img src="{{ $item->image_url }}" 
                     class="w-full h-full object-cover transition-all duration-[2s] ease-out group-hover:scale-105 
                            filter grayscale brightness-90 group-hover:grayscale-0 group-hover:brightness-100"
                     alt="{{ $item->title }}">
            </div>
        @endif
        
        {{-- Overlay Gradient --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60"></div>
        
        {{-- Tech Details --}}
        <div class="absolute bottom-6 left-6 right-6 flex justify-between items-end z-20">
            <div class="font-mono text-[10px] uppercase leading-tight text-white/50">
                @if($item->extra_attributes['exif'] ?? false)
                    {{ $item->extra_attributes['exif'] }}
                @else
                    LUXORIX_VISUAL<br>
                    PREMIUM_ASSET<br>
                    RESOLUTION_MAX
                @endif
            </div>
            <div class="h-px w-full max-w-[100px] bg-white/30 relative overflow-hidden">
                <div class="absolute inset-0 bg-theme-primary w-1/2 animate-[loading_2s_ease-in-out_infinite]"></div>
            </div>
        </div>
    </div>

    {{-- LEFT SIDE: CONTENT --}}
    <div class="relative w-full lg:w-[55%] flex flex-col justify-center px-6 md:px-20 py-16 lg:py-0 bg-theme-page">
        <div class="relative z-10">
            {{-- Subheading --}}
            @if($section->subheading)
                <div class="flex items-center gap-4 mb-8">
                    <span class="text-theme-primary font-mono text-xs uppercase tracking-[0.3em]">
                        {{ $section->subheading }}
                    </span>
                    <div class="h-px w-12 bg-white/10"></div>
                </div>
            @endif

            {{-- Heading --}}
            <h2 class="font-heading text-5xl md:text-7xl leading-[0.9] uppercase tracking-tighter mb-8 text-theme-content">
                {{ $section->heading }}
            </h2>

            @if($item)
                {{-- Content --}}
                @if($item->content)
                    <div class="text-theme-muted text-lg leading-relaxed mb-10 max-w-xl">
                        {!! $item->content !!}
                    </div>
                @endif

                {{-- CTA --}}
                @if($item->cta_link)
                    <a href="{{ $item->cta_link }}" 
                       class="group relative inline-flex items-center justify-between w-full max-w-xs py-4 border-b border-white/20 hover:border-theme-primary transition-colors duration-500">
                        <span class="font-mono text-xs font-bold uppercase tracking-widest group-hover:pl-2 transition-all duration-300">
                            {{ $item->cta_label ?? 'Explore' }}
                        </span>
                        <div class="relative overflow-hidden w-6 h-6">
                            <span class="absolute inset-0 flex items-center justify-center transform group-hover:translate-x-full group-hover:-translate-y-full transition-transform duration-300">↗</span>
                            <span class="absolute inset-0 flex items-center justify-center transform -translate-x-full translate-y-full group-hover:translate-x-0 group-hover:translate-y-0 transition-transform duration-300 text-theme-primary">↗</span>
                        </div>
                    </a>
                @endif
            @endif
        </div>
    </div>
</section>

<style>
    @keyframes loading {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(200%); }
    }
</style>
