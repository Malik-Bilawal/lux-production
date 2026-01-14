{{-- resources/views/components/sections/grid_4_col.blade.php --}}
@props(['section', 'items', 'index', 'page' => null]) {{-- ADD PAGE PROP --}}

<section class="py-20 md:py-32 {{ $section->css_classes }}">
    <div class="max-w-7xl mx-auto px-6">
        @if($section->heading || $section->subheading)
            <div class="text-center mb-16">
                @if($section->subheading)
                    <span class="text-theme-primary font-mono text-xs uppercase tracking-[0.4em] block mb-4">
                        {{ $section->subheading }}
                    </span>
                @endif
                @if($section->heading)
                    <h2 class="text-3xl md:text-4xl font-heading text-theme-content uppercase tracking-tighter">
                        {{ $section->heading }}
                    </h2>
                @endif
            </div>
            <div class="w-32 h-px bg-gradient-divider mx-auto"></div>

        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
            @foreach($items as $item)
                <div class="group text-center">
                    {{-- Icon/Image Container --}}
                    <div class="w-16 h-16 mx-auto mb-6 rounded-xl bg-theme-surface border border-white/10 group-hover:border-theme-primary/30 transition-colors duration-300 flex items-center justify-center">
                        @if($item->icon && Str::startsWith($item->icon, '<svg'))
                            <div class="w-8 h-8 text-theme-primary group-hover:scale-110 transition-transform duration-300">
                                {!! $item->icon !!}
                            </div>
                        @elseif($item->image_url)
                            <img src="{{ $item->image_url }}" 
                                 class="w-8 h-8 object-contain group-hover:scale-110 transition-transform duration-300"
                                 alt="{{ $item->title }}">
                        @elseif($item->icon)
                            <i class="{{ $item->icon }} text-xl text-theme-primary group-hover:scale-110 transition-transform duration-300"></i>
                        @endif
                    </div>

                    {{-- Title --}}
                    @if($item->title)
                        <h3 class="font-heading text-theme-content mb-3 group-hover:text-theme-primary transition-colors duration-300">
                            {{ $item->title }}
                        </h3>
                    @endif

                    {{-- Content --}}
                    @if($item->content)
                        <div class="text-theme-muted text-sm leading-relaxed">
                            {!! $item->content !!}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>