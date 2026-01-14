{{-- resources/views/components/sections/text_block.blade.php --}}
@props(['section', 'items', 'index', 'page' => null])

<section class="py-20 md:py-32 bg-theme-page relative overflow-hidden {{ $section->css_classes }}">
    {{-- Subtle Background Glow for Mystery --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-theme-primary/5 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-6 relative">
        
        {{-- Content Container --}}
        <div class="border-l border-theme-primary/20 md:border-l-0">
            
            {{-- Header Section --}}
            <header class="mb-12 md:mb-20 pl-6 md:pl-0">
                @if($section->subheading)
                    <div class="flex items-center gap-3 mb-4">
                        <span class="h-[1px] w-8 bg-theme-primary/40"></span>
                        <span class="text-theme-primary font-mono text-[10px] md:text-xs uppercase tracking-[0.4em]">
                            {{ $section->subheading }}
                        </span>
                    </div>
                @endif
                
                @if($section->heading)
                    <h2 class="text-3xl md:text-5xl font-light tracking-tight text-theme-content leading-tight">
                        {{ $section->heading }}
                    </h2>
                @endif
            </header>
            
            {{-- Main Content Loop --}}
            <div class="space-y-16 md:space-y-24 pl-6 md:pl-0">
                @forelse($items as $item)
                    <article class="group">
                        @if($item->title)
                            <h3 class="text-lg md:text-xl font-mono text-theme-primary/80 mb-6 uppercase tracking-wider flex items-baseline gap-4">
                                <span class="text-[10px] opacity-40">0{{ $loop->iteration }}</span>
                                {{ $item->title }}
                            </h3>
                        @endif
                        
                        @if($item->content)
                            <div class="prose prose-invert prose-sm md:prose-base max-w-2xl text-theme-muted/90 leading-relaxed font-light">
                                {!! $item->content !!}
                            </div>
                        @endif
                        
                        {{-- Minimal Statute Box --}}
                        @if($item->extra_attributes['statutes'] ?? false)
                            <div class="mt-8 pt-6 border-t border-theme-primary/10 flex items-start gap-4">
                                <span class="text-[10px] font-mono text-theme-primary uppercase">Statute</span>
                                <p class="text-[11px] md:text-xs font-mono text-theme-muted/60 leading-relaxed italic">
                                    {{ $item->extra_attributes['statutes'] }}
                                </p>
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="py-12 border border-dashed border-theme-primary/10 text-center rounded-sm">
                        <p class="text-xs font-mono text-theme-muted/40 uppercase tracking-widest">Awaiting Documentation</p>
                    </div>
                @endforelse
            </div>

            {{-- Footer / Metadata --}}
            <footer class="mt-20 md:mt-32 pt-8 border-t border-theme-primary/10 flex flex-col md:flex-row justify-between gap-6 pl-6 md:pl-0">
                <div class="flex items-center gap-4 opacity-40 grayscale">
                     <span class="w-2 h-2 rounded-full bg-theme-primary animate-pulse"></span>
                     <span class="text-[10px] font-mono uppercase tracking-[0.2em] text-theme-content">Secure Access Level 3</span>
                </div>

                <div class="text-[10px] font-mono text-theme-muted/40 uppercase tracking-widest leading-loose">
                    <p>Revision: 3.1.0 // Auth: Legal_Dept</p>
                    <p>© {{ now()->year }} Proprietary Information</p>
                </div>
            </footer>
        </div>
    </div>
</section>