@props(['section', 'items', 'index', 'page' => null])

<section class="py-16 {{ $section->css_classes }}">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Heading / Subheading --}}
        @if($section->heading || $section->subheading)
            <div class="text-center mb-12">
                @if($section->subheading)
                    <span class="text-theme-primary font-mono text-xs uppercase tracking-[0.4em] block mb-2">
                        {{ $section->subheading }}
                    </span>
                @endif
                @if($section->heading)
                    <h2 class="text-3xl md:text-4xl font-heading text-theme-content uppercase tracking-tight">
                        {{ $section->heading }}
                    </h2>
                @endif
            </div>
        @endif

        {{-- Carousel --}}
        <div x-data="{ current: 0 }" class="relative">
            
            {{-- Slides --}}
            <div class="overflow-hidden">
                <div class="flex transition-transform duration-500" 
                     :style="'transform: translateX(-' + (current * 100) + '%)'">
                    @foreach($items as $item)
                        <div class="flex-none w-full md:w-1/2 lg:w-1/3 px-4">
                            <div class="bg-theme-surface p-6 rounded-2xl shadow hover:shadow-lg transition-all duration-300">
                                
                                {{-- Quote --}}
                                @if($item->content)
                                    <p class="text-theme-muted italic mb-4">{!! $item->content !!}</p>
                                @endif

                                {{-- Author / Title --}}
                                <div class="flex items-center gap-4 mt-4">
                                    @if($item->image_url)
                                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-12 h-12 rounded-full object-cover">
                                    @endif
                                    <div>
                                        @if($item->title)
                                            <h4 class="font-semibold text-theme-content">{{ $item->title }}</h4>
                                        @endif
                                        @if($item->subheading)
                                            <p class="text-xs text-theme-primary">{{ $item->subheading }}</p>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Controls --}}
            <div class="absolute inset-y-0 left-0 flex items-center">
                <button @click="current = (current > 0 ? current - 1 : {{ count($items)-1 }})" 
                        class="p-2 bg-theme-primary/20 rounded-full hover:bg-theme-primary transition">
                    &larr;
                </button>
            </div>
            <div class="absolute inset-y-0 right-0 flex items-center">
                <button @click="current = (current < {{ count($items)-1 }} ? current + 1 : 0)" 
                        class="p-2 bg-theme-primary/20 rounded-full hover:bg-theme-primary transition">
                    &rarr;
                </button>
            </div>

        </div>

    </div>
</section>
