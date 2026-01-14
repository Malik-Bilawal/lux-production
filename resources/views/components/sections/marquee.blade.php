@props(['section', 'items', 'index', 'page' => null])

<section class="py-10 {{ $section->css_classes }}">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Heading / Subheading --}}
        @if($section->heading || $section->subheading)
            <div class="text-center mb-8">
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

        {{-- Marquee / Scrolling items --}}
        <div class="overflow-hidden relative">
            <div class="flex animate-marquee whitespace-nowrap gap-8">
                @foreach($items as $item)
                    <div class="inline-block bg-theme-surface px-6 py-3 rounded-lg shadow hover:shadow-lg transition-all duration-300">
                        @if($item->icon)
                            <span class="inline-block mr-2">{!! $item->icon !!}</span>
                        @endif
                        @if($item->title)
                            <span class="font-semibold text-theme-content">{{ $item->title }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</section>

{{-- Marquee Animation --}}
<style>
@keyframes marquee {
    0% { transform: translateX(100%); }
    100% { transform: translateX(-100%); }
}
.animate-marquee {
    animation: marquee 20s linear infinite;
}
</style>
