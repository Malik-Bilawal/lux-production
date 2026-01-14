{{-- resources/views/components/sections/grid_3_col.blade.php --}}
@props(['section', 'items', 'index', 'page' => null])
<section class="py-32 bg-gradient-to-b from-theme-page to-theme-deep {{ $section->css_classes }}">
    <div class="max-w-7xl mx-auto px-6">
        
        {{-- Section Header --}}
        <div class="mb-20 text-center">
            @if($section->subheading)
                <span class="text-theme-primary font-mono text-xs uppercase tracking-widest block mb-6">
                    <span class="text-theme-primary/60">✦</span> {{ $section->subheading }}
                </span>
            @endif
            
            @if($section->heading)
                <h2 class="text-5xl md:text-6xl font-heading text-theme-content uppercase tracking-tight mb-8">
                    {{ $section->heading }}
                </h2>
            @endif

            <div class="w-32 h-px bg-gradient-divider mx-auto"></div>

        </div>

       {{-- 3-Column Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
    @foreach($items as $item)
        <div class="group relative">
            {{-- Ghost Border Effect --}}
            <div class="absolute -inset-[1px] bg-gradient-to-b from-theme-primary/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
            
            {{-- Card Container --}}
            <div class="relative h-full bg-theme-surface/10 backdrop-blur-md border border-theme-primary/5 p-8 md:p-10 flex flex-col transition-all duration-500 group-hover:bg-theme-surface/20">
                
                {{-- Top Detail: ID & Status --}}
                <div class="flex justify-between items-start mb-10">
                    <span class="text-[10px] font-mono text-theme-primary/30 uppercase tracking-[0.3em]">
                        ID://{{ str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}
                    </span>
                    <div class="w-1 h-1 rounded-full bg-theme-primary/20 group-hover:bg-theme-primary group-hover:shadow-[0_0_8px_rgba(var(--theme-primary-rgb),0.8)] transition-all duration-500"></div>
                </div>

                {{-- Icon Section --}}
                @if($item->icon || $item->image_url)
                    <div class="mb-8">
                        <div class="w-12 h-12 flex items-center justify-center relative">
                            {{-- Rotating frame on hover --}}
                            <div class="absolute inset-0 border border-theme-primary/10 scale-75 group-hover:scale-110 group-hover:rotate-45 transition-all duration-700"></div>
                            
                            @if($item->icon)
                                <i class="{{ $item->icon }} text-xl text-theme-primary/60 group-hover:text-theme-primary transition-colors duration-500"></i>
                            @elseif($item->image_url)
                                <img src="{{ $item->image_url }}" class="w-6 h-6 object-contain opacity-50 group-hover:opacity-100 grayscale transition-all duration-500" alt="{{ $item->title }}">
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Title --}}
                @if($item->title)
                    <h3 class="text-xl font-light tracking-wide text-theme-content mb-4 leading-tight group-hover:text-theme-primary transition-colors duration-500">
                        {{ $item->title }}
                    </h3>
                @endif

                {{-- Content --}}
                @if($item->content)
                    <div class="text-theme-muted/60 text-sm leading-relaxed font-light mb-8 line-clamp-4 group-hover:text-theme-muted/90 transition-colors duration-500">
                        {!! $item->content !!}
                    </div>
                @endif

                {{-- CTA: Terminal Style --}}
                @if($item->cta_link)
                    <div class="mt-auto pt-6">
                        <a href="{{ $item->cta_link }}" 
                           class="inline-flex items-center gap-3 text-[10px] font-mono uppercase tracking-[0.2em] text-theme-primary/40 group-hover:text-theme-primary transition-all duration-500">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-theme-primary opacity-20"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-theme-primary/40"></span>
                            </span>
                            {{ $item->cta_label ?? 'Execute_Access' }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>

        {{-- Footer Note --}}
        @if($section->description ?? false)
            <div class="mt-16 pt-8 border-t border-theme-white/5">
                <p class="text-center text-theme-muted/60 text-sm max-w-3xl mx-auto leading-relaxed">
                    {{ $section->description }}
                </p>
            </div>
        @endif
    </div>
</section>
