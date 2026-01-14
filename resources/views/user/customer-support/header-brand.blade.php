{{-- resources/views/user/customer-support/header-brand.blade.php --}}
<header class="relative pt-40 pb-20 px-6 md:px-12 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-theme-primary/5 via-transparent to-transparent"></div>
    
    <div class="max-w-screen-2xl mx-auto relative">
        <div class="inline-flex items-center gap-3 mb-8 px-6 py-3 rounded-full border border-theme-primary/20 bg-black/30 backdrop-blur-sm">
            <svg class="w-4 h-4 text-theme-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            <span class="text-theme-primary font-mono text-xs uppercase tracking-[0.4em]">
                Brand Guidelines
            </span>
        </div>

        <h1 class="text-6xl md:text-8xl font-heading text-theme-content uppercase tracking-tighter leading-[0.85] mb-8">
            {{ $page->title }}
        </h1>
        
        @if($page->hero_text)
            <div class="max-w-3xl">
                <p class="text-2xl font-light text-theme-muted leading-relaxed">
                    {{ $page->hero_text }}
                </p>
            </div>
        @endif
    </div>
</header>