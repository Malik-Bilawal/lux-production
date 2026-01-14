{{-- resources/views/components/sections/grid_2_col.blade.php --}}
@props(['section', 'items', 'index', 'page' => null])
<section class="py-32 bg-gradient-to-b from-theme-page to-theme-deep {{ $section->css_classes }}">
    <div class="max-w-6xl mx-auto px-6">
        
        {{-- Section Header --}}
        <div class="mb-20 text-center">
            @if($section->subheading)
                <span class="text-theme-primary font-mono text-xs uppercase tracking-widest block mb-6">
                    <span class="text-theme-primary/60">◈</span> {{ $section->subheading }}
                </span>
            @endif
            
            @if($section->heading)
                <h2 class="text-5xl md:text-6xl font-heading text-theme-content uppercase tracking-tight mb-8">
                    {{ $section->heading }}
                </h2>
            @endif
            
            @if($section->description ?? false)
                <p class="text-theme-muted max-w-3xl mx-auto text-lg leading-relaxed">
                    {{ $section->description }}
                </p>
            @endif
            <div class="w-32 h-px bg-gradient-divider mx-auto"></div>

        </div>

       {{-- 2-Column Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-px bg-theme-primary/10 border border-theme-primary/10">
    @foreach($items as $item)
        <div class="group relative bg-theme-page overflow-hidden transition-all duration-700 hover:bg-theme-surface/40">
            
            {{-- Decorative Corner (Mysterious Accent) --}}
            <div class="absolute top-0 right-0 w-8 h-8 opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                <div class="absolute top-4 right-4 w-full h-[1px] bg-theme-primary/40 rotate-45"></div>
            </div>

            {{-- Card Content --}}
            <div class="p-8 md:p-12 flex flex-col h-full relative z-10">
                
                {{-- Icon/Image: Minimalist Container --}}
                @if($item->icon || $item->image_url)
                    <div class="mb-8 relative">
                        <div class="w-12 h-12 flex items-center justify-center border border-theme-primary/20 group-hover:border-theme-primary/60 transition-colors duration-500">
                            @if($item->icon)
                                <i class="{{ $item->icon }} text-theme-primary/60 group-hover:text-theme-primary transition-colors"></i>
                            @elseif($item->image_url)
                                <img src="{{ $item->image_url }}" class="w-6 h-6 object-contain opacity-60 group-hover:opacity-100 grayscale transition-all" alt="{{ $item->title }}">
                            @endif
                        </div>
                        {{-- Tiny ID tag --}}
                        <span class="absolute -bottom-4 left-0 text-[8px] font-mono text-theme-primary/30 uppercase tracking-[0.3em]">
                            Unit_{{ $loop->iteration }}
                        </span>
                    </div>
                @endif

                {{-- Title --}}
                @if($item->title)
                    <h3 class="text-xl md:text-2xl font-light tracking-tight text-theme-content mb-4 group-hover:translate-x-1 transition-transform duration-500">
                        {{ $item->title }}
                    </h3>
                @endif

                {{-- Content --}}
                @if($item->content)
                    <div class="text-theme-muted/70 text-sm md:text-base leading-relaxed font-light mb-8 max-w-sm">
                        {!! $item->content !!}
                    </div>
                @endif

                {{-- CTA: The "Ghost" Link --}}
                @if($item->cta_link)
                    <div class="mt-auto">
                        <a href="{{ $item->cta_link }}" 
                           class="inline-flex items-center gap-4 text-[10px] font-mono uppercase tracking-[0.3em] text-theme-primary/40 group-hover:text-theme-primary transition-all duration-500">
                            <span class="w-6 h-[1px] bg-theme-primary/20 group-hover:w-10 group-hover:bg-theme-primary transition-all duration-500"></span>
                            {{ $item->cta_label ?? 'View Details' }}
                        </a>
                    </div>
                @endif
            </div>

            {{-- Background Scanline Effect (Subtle Mystery) --}}
            <div class="absolute inset-0 bg-[linear-gradient(to_bottom,transparent_49%,rgba(var(--theme-primary-rgb),0.05)_50%,transparent_51%)] bg-[length:100%_4px] opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-700"></div>
        </div>
    @endforeach
</div>
    </div>
</section>
