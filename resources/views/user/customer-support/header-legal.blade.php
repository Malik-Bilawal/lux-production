<header class="relative pt-32 pb-16 px-6 md:px-12 border-b border-theme-primary/10">
    <div class="max-w-screen-2xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="h-[1px] w-12 bg-theme-primary"></span>
                <span class="text-theme-primary font-mono text-xs uppercase tracking-[0.4em]">
                    Legal Protocol
                </span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <span class="font-mono text-xs text-gray-400 uppercase tracking-widest">
                    v3.0 • Secure
                </span>
            </div>
        </div>

        <h1 class="text-5xl md:text-7xl font-heading text-theme-content uppercase tracking-tight leading-[0.9] mb-6">
            {{ $page->title }}
        </h1>
        
        @if($page->hero_text)
            <div class="flex justify-end">
                <p class="text-theme-muted max-w-2xl text-lg leading-relaxed border-l border-theme-primary/30 pl-6">
                    {{ $page->hero_text }}
                </p>
            </div>
        @endif
    </div>
</header>