{{-- resources/views/user/customer-support/info-pages.blade.php --}}
@extends('user.layouts.master-layouts.plain')

@section('title', $page->meta_title ?? $page->title)
@section('meta_description', $page->meta_description)

@section('content')
<main class="relative w-full min-h-screen bg-theme-page text-theme-content overflow-x-hidden">

    <div class="fixed inset-0 pointer-events-none opacity-[0.02] z-50 mix-blend-overlay"
        style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1MTIiIGhlaWdodD0iNTEyIj48ZmlsdGVyIGlkPSJub2lzZSI+PGZlVHVyYnVsZW5jZSB0eXBlPSJmcmFjdGFsTm9pc2UiIGJhc2VGcmVxdWVuY3k9IjAuNjUiIG51bU9jdGF2ZXM9IjMiIHN0aXRjaFRpbGVzPSJzdGl0Y2giLz48L2ZpbHRlcj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWx0ZXI9InVybCgjbW9pc2UpIiBvcGFjaXR5PSIwLjAyIi8+PC9zdmc+');">
    </div>
    @unless($page->extra_attributes['hide_title'] ?? false)
    <header class="relative px-6 md:px-12 pt-40 pb-28 overflow-hidden">

        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[900px] h-[450px] bg-theme-primary/10 blur-[140px] opacity-25"></div>
        </div>

        <div class="relative max-w-4xl mx-auto text-center">

            <div class="flex justify-center items-center gap-4 mb-6">
                <span class="h-px w-12 bg-theme-primary/70"></span>
                <span class="font-mono text-[11px] uppercase tracking-[0.45em] text-theme-primary">
                    {{ $page->template === 'legal' ? 'Legal Protocol' : 'Luxorix' }}
                </span>
                <span class="h-px w-12 bg-theme-primary/70"></span>
            </div>

            <h1 class="font-heading text-theme-content uppercase tracking-tight
                   text-[clamp(3rem,6vw,5.5rem)] leading-[0.9]">
                {{ $page->title }}
            </h1>

            <div class="mt-5 flex justify-center items-center gap-4">
                <div class="h-[2px] w-16 bg-theme-primary"></div>
                <div class="w-4 h-4 rotate-45 border-2 border-theme-primary"></div>
                <div class="h-[2px] w-16 bg-theme-primary"></div>
            </div>

            @if($page->hero_text)
            <p class="mt-6 text-theme-muted text-lg leading-relaxed max-w-2xl mx-auto">
                {{ $page->hero_text }}
            </p>
            @endif

            @if($page->template === 'legal')
            <div class="mt-10 flex justify-center items-center gap-2">
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-60 animate-ping"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-400"></span>
                </span>
                <span class="font-mono text-[11px] text-gray-400 uppercase tracking-widest">
                    Secure & Verified
                </span>
            </div>
            @endif

        </div>
    </header>
    @endunless




    <div class="relative">
        @foreach($page->activeSections as $sectionIndex => $section)
        @php
        $themeClass = match($section->background_theme) {
        'light' => 'bg-white text-black',
        'gold' => 'bg-gradient-to-br from-[#D4AF37]/10 via-black to-[#D4AF37]/5',
        'gradient' => 'bg-gradient-to-br from-theme-surface via-theme-page to-theme-deep',
        default => '',
        };
        @endphp

        <div class="{{ $themeClass }} {{ $section->css_classes }}" id="section-{{ $sectionIndex }}">
            <x-dynamic-component
                :component="'sections.' . $section->layout_type"
                :section="$section"
                :items="$section->activeItems"
                :index="$sectionIndex"
                :page="$page" {{-- PASS PAGE TO COMPONENT --}} />
        </div>
        @endforeach
    </div>

    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
        class="fixed bottom-8 right-8 w-12 h-12 border border-white/10 bg-black/50 backdrop-blur-sm rounded-full flex items-center justify-center hover:border-theme-primary hover:text-theme-primary transition-all duration-300 group z-40">
        <svg class="w-5 h-5 transform group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

</main>

@if($page->template === 'legal')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
</script>
@endif

@if($page->template === 'faq')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endif
@endsection