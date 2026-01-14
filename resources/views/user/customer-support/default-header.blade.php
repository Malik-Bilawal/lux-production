{{-- resources/views/user/customer-support/headers/default.blade.php --}}
<header class="relative px-6 md:px-12 pt-40 pb-28 overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[900px] h-[450px] bg-theme-primary/10 blur-[140px] opacity-25"></div>
    </div>

    <div class="relative max-w-4xl mx-auto text-center">
        <div class="flex justify-center items-center gap-4 mb-6">
            <span class="h-px w-12 bg-theme-primary/70"></span>
            <span class="font-mono text-[11px] uppercase tracking-[0.45em] text-theme-primary">
                Luxorix Excellence
            </span>
            <span class="h-px w-12 bg-theme-primary/70"></span>
        </div>

        <h1 class="font-heading text-theme-content uppercase tracking-tight
                   text-[clamp(3rem,6vw,5.5rem)] leading-[0.9] mb-6">
            {{ $page->title }}
        </h1>

        <div class="mt-5 flex justify-center items-center gap-4">
            <div class="h-[1px] w-16 bg-theme-muted"></div>
            <div class="w-3 h-3 rotate-45 border border-theme-primary"></div>
            <div class="h-[1px] w-16 bg-theme-muted"></div>
        </div>

        @if($page->hero_text)
            <p class="mt-8 text-theme-muted text-xl leading-relaxed max-w-2xl mx-auto">
                {{ $page->hero_text }}
            </p>
        @endif
    </div>
</header>