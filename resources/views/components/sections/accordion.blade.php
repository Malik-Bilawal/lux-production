@props(['section', 'items', 'index', 'page' => null])

<section class="py-24 md:py-32 bg-theme-page {{ $section->css_classes }}" x-data="{ active: null }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header stays untouched --}}
        <div class="mb-20 text-center relative">
            <div class="absolute inset-0 flex items-center justify-center opacity-10">
                <div class="w-20 h-20 md:w-24 md:h-24 border border-theme-primary/30 rounded-full flex items-center justify-center">
                    <div class="w-14 h-14 md:w-16 md:h-16 border border-theme-primary/20 rounded-full flex items-center justify-center">
                        <div class="w-8 h-8 md:w-10 md:h-10 border border-theme-primary/10 rounded-full"></div>
                    </div>
                </div>
            </div>

            @if($section->subheading)
                <span class="text-theme-primary font-mono text-xs uppercase tracking-[0.4em] block mb-6">
                    <span class="text-theme-primary/60">§</span> {{ $section->subheading }}
                </span>
            @endif
            
            @if($section->heading)
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-heading text-theme-content uppercase tracking-tight mb-6">
                    {{ $section->heading }}
                </h2>
            @endif
            
            <div class="w-32 h-px bg-gradient-divider mx-auto"></div>
        </div>
<div class="space-y-4 md:space-y-6 max-w-4xl mx-auto px-4">
    @foreach($items as $itemIndex => $item)
        <div x-data="{ isOpen: {{ $loop->first ? 'true' : 'false' }} }" 
             class="group relative border-b border-theme-primary/10 transition-all duration-700 ease-in-out hover:border-theme-primary/30">

            {{-- Header --}}
            <button @click="isOpen = !isOpen" 
                    class="w-full flex items-start gap-4 md:gap-8 py-6 md:py-10 text-left focus:outline-none">
                
                {{-- Minimal Number --}}
                <div class="flex-shrink-0 pt-1">
                    <span class="text-xs md:text-sm font-mono tracking-tighter text-theme-primary/40 group-hover:text-theme-primary transition-colors duration-500">
                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                    </span>
                </div>

                {{-- Title & Subheading --}}
                <div class="flex-1">
                    <h3 class="text-xl md:text-2xl font-light tracking-tight text-theme-content/90 group-hover:text-theme-content transition-colors duration-500">
                        {{ $item->title }}
                    </h3>
                    @if($item->subheading)
                        <p class="mt-2 text-[10px] md:text-xs text-theme-muted font-mono uppercase tracking-[0.2em] opacity-60 group-hover:opacity-100 transition-opacity duration-700">
                            {{ $item->subheading }}
                        </p>
                    @endif
                </div>

                {{-- Minimalist Toggle (Rotating Line) --}}
                <div class="flex-shrink-0 relative w-6 h-6 mt-1 md:mt-2">
                    <span class="absolute inset-0 m-auto w-full h-[1px] bg-theme-primary/40 group-hover:bg-theme-primary transition-all duration-500"></span>
                    <span class="absolute inset-0 m-auto w-full h-[1px] bg-theme-primary/40 group-hover:bg-theme-primary transition-all duration-500" 
                          :class="isOpen ? 'rotate-0' : 'rotate-90'"></span>
                </div>
            </button>

            {{-- Content --}}
            <div x-show="isOpen" 
                 x-collapse.duration.700ms 
                 class="overflow-hidden">
                <div class="pb-8 md:pb-12 pl-8 md:pl-16 pr-4">
                    <div class="prose prose-invert prose-sm md:prose-base max-w-2xl text-theme-muted/80 leading-relaxed font-light">
                        {!! $item->content !!}
                    </div>

                    {{-- Metadata Footer --}}
                    <div class="mt-8 flex flex-wrap gap-6 items-center border-t border-theme-primary/5 pt-6">
                        @if($item->extra_attributes['reference'] ?? false)
                            <span class="text-[10px] md:text-xs font-mono text-theme-primary/50 uppercase tracking-widest">
                                Ref. {{ $item->extra_attributes['reference'] }}
                            </span>
                        @endif

                        @if($item->cta_link)
                            <a href="{{ $item->cta_link }}" class="group/link flex items-center gap-2">
                                <span class="text-[10px] md:text-xs font-mono uppercase tracking-widest text-theme-primary/40 group-hover/link:text-theme-primary transition-colors">
                                    {{ $item->cta_label ?? 'Explore' }}
                                </span>
                                <span class="w-4 h-[1px] bg-theme-primary/20 group-hover/link:w-8 group-hover/link:bg-theme-primary transition-all duration-500"></span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>


        {{-- Footer Note --}}
        <div class="mt-12 md:mt-16 text-center">
            <div class="inline-flex items-center gap-3 md:gap-4 px-4 md:px-6 py-2 md:py-3 bg-theme-surface/30 rounded-full ">
                <svg class="w-4 h-4 md:w-5 md:h-5 text-theme-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <span class="text-xs md:text-sm font-mono uppercase tracking-wider text-theme-muted">
                    Last Updated: {{ now()->format('F j, Y') }} • Version 2.1
                </span>
            </div>
        </div>
    </div>
</section>
