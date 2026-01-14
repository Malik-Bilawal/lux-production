{{-- resources/views/user/customer-support/templates/header-faq.blade.php --}}
<header class="relative pt-32 pb-12 px-6 md:px-12">
    <div class="max-w-4xl mx-auto text-center">
        <div class="inline-flex items-center gap-3 mb-6">
            <div class="w-12 h-px bg-gradient-to-r from-transparent to-theme-primary"></div>
            <span class="text-theme-primary font-mono text-xs uppercase tracking-[0.4em]">
                Knowledge Base
            </span>
            <div class="w-12 h-px bg-gradient-to-l from-transparent to-theme-primary"></div>
        </div>

        <h1 class="text-5xl md:text-6xl font-heading text-theme-content uppercase tracking-tight mb-8">
            {{ $page->title }}
        </h1>
        
        @if($page->hero_text)
            <p class="text-xl text-theme-muted max-w-2xl mx-auto leading-relaxed">
                {{ $page->hero_text }} 
            </p>
        @endif
        
        {{-- Search Bar --}}
        <div class="mt-12 max-w-2xl mx-auto">
            <div class="relative">
                <input type="text" 
                       placeholder="Search questions..." 
                       class="w-full px-6 py-4 bg-white/5 border border-white/10 rounded-xl text-theme-content placeholder-gray-500 focus:outline-none focus:border-theme-primary transition-colors">
                <button class="absolute right-4 top-1/2 transform -translate-y-1/2 text-theme-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>