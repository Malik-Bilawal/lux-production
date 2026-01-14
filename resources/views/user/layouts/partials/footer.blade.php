<footer class="bg-theme-page text-theme-content relative border-t border-theme-content/10 font-sans overflow-hidden">
    
    {{-- CSS for Animations --}}
    <style>
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            animation: marquee 30s linear infinite;
        }
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-spin-slow {
            animation: spin-slow 12s linear infinite;
        }
    </style>

    {{-- 1. THE INFINITE SCROLL BAR --}}
    <div class="border-b border-theme-content/10 bg-theme-content/5 backdrop-blur-sm overflow-hidden whitespace-nowrap py-3">
        <div class="inline-flex animate-marquee">
            @for ($i = 0; $i < 10; $i++)
                <span class="text-[10px] md:text-xs font-bold uppercase tracking-[0.3em] mx-8 text-theme-muted/70 font-mono">
                    <i class="fas fa-bolt text-[8px] align-middle text-theme-primary mr-3"></i> Precision Engineering
                    <span class="mx-4 text-theme-content/20">//</span>
                    Global Shipping
                    <span class="mx-4 text-theme-content/20">//</span>
                    Lifetime Warranty
                    <span class="mx-4 text-theme-content/20">//</span>
                    Luxorix Exclusive
                </span>
            @endfor
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 h-full">

        {{-- 2. THE BRAND MANIFESTO (Left Side - 7 Cols) --}}
        {{-- REPLACED EMAIL FORM WITH LUXURY QUOTE --}}
        <div class="lg:col-span-7 border-b lg:border-b-0 lg:border-r border-theme-content/10 p-8 md:p-16 flex flex-col justify-between relative overflow-hidden min-h-[400px]">
            
            {{-- Background Blur Orb --}}
            <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-theme-primary/5 blur-[120px] rounded-full pointer-events-none"></div>

            <div class="relative z-10 flex flex-col justify-center h-full">
                
                {{-- Decorative Tag --}}
                <div class="flex items-center gap-4 mb-8 opacity-70">
                     <span class="text-theme-primary font-mono text-[10px] tracking-widest border border-theme-primary/30 px-2 py-1 rounded-sm">VISION 01</span>
                     <div class="h-px w-20 bg-theme-content/20"></div>
                </div>

              {{-- The Quote / Manifesto --}}
<h2 class="text-3xl md:text-5xl lg:text-6xl font-heading font-black uppercase leading-[1.1] tracking-tighter
           bg-gradient-to-br from-theme-content via-theme-primary-light to-theme-primary
           bg-clip-text text-transparent drop-shadow-sm">
    "True luxury is the <br>
    <span class="italic pr-2">absence</span> of friction."
</h2>


                <p class="mt-8 text-theme-muted text-sm md:text-base max-w-lg font-light leading-relaxed border-l-2 border-theme-primary/50 pl-6">
                    We engineer devices that disappear into your life, leaving only performance. <br>
                    <span class="text-theme-content font-medium mt-2 block">— The Luxorix Design Lab</span>
                </p>

            </div>

            {{-- Rotating "Seal of Quality" Badge --}}
            <div class="absolute bottom-10 right-10 hidden md:block opacity-20 pointer-events-none">
                 <div class="relative w-32 h-32 flex items-center justify-center animate-spin-slow">
                    <svg viewBox="0 0 100 100" width="100" height="100">
                        <defs>
                            <path id="circle" d="M 50, 50 m -37, 0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0" />
                        </defs>
                        <text font-size="10" font-family="monospace" letter-spacing="2" font-weight="bold" fill="currentColor" class="text-theme-content">
                            <textPath xlink:href="#circle">
                                LUXORIX • AUTHENTIC • DIGITAL • GEAR •
                            </textPath>
                        </text>
                    </svg>
                    <i class="fas fa-star text-theme-primary absolute text-xl"></i>
                 </div>
            </div>
        </div>

        {{-- 3. THE LINKS GRID (Right Side - 5 Cols) --}}
        <div class="lg:col-span-5 flex flex-col h-full">
            
            {{-- Row 1: Nav Links --}}
            <div class="flex-1 grid grid-cols-2">
                <div class="p-8 border-r border-b border-theme-content/10 hover:bg-theme-content/5 transition-colors duration-500 group">
                    <span class="block text-[10px] text-theme-primary uppercase mb-6 font-mono tracking-widest">02 / Shop</span>
                    <ul class="space-y-4">
                        <li><a href="watches.php" class="text-xs font-bold uppercase tracking-widest hover:text-theme-primary transition-colors flex items-center gap-2"><i class="fas fa-arrow-right opacity-0 -ml-4 group-hover:opacity-100 group-hover:ml-0 transition-all text-[8px] text-theme-primary"></i> Watches</a></li>
                        <li><a href="earpods.php" class="text-xs font-bold uppercase tracking-widest hover:text-theme-primary transition-colors flex items-center gap-2"><i class="fas fa-arrow-right opacity-0 -ml-4 group-hover:opacity-100 group-hover:ml-0 transition-all text-[8px] text-theme-primary"></i> Earpods</a></li>
                        <li><a href="headphones.php" class="text-xs font-bold uppercase tracking-widest hover:text-theme-primary transition-colors flex items-center gap-2"><i class="fas fa-arrow-right opacity-0 -ml-4 group-hover:opacity-100 group-hover:ml-0 transition-all text-[8px] text-theme-primary"></i> Headphones</a></li>
                    </ul>
                </div>
                <div class="p-8 border-b border-theme-content/10 hover:bg-theme-content/5 transition-colors duration-500 group">
                    <span class="block text-[10px] text-theme-primary uppercase mb-6 font-mono tracking-widest">03 / Info</span>
                    <ul class="space-y-4">
                         <li><a href="{{ route('user.contact') }}" class="text-xs font-bold uppercase tracking-widest hover:text-theme-primary transition-colors flex items-center gap-2"><i class="fas fa-arrow-right opacity-0 -ml-4 group-hover:opacity-100 group-hover:ml-0 transition-all text-[8px] text-theme-primary"></i> Support</a></li>
                         <li><a href="{{ route('order.tracking.form') }}" class="text-xs font-bold uppercase tracking-widest hover:text-theme-primary transition-colors flex items-center gap-2"><i class="fas fa-arrow-right opacity-0 -ml-4 group-hover:opacity-100 group-hover:ml-0 transition-all text-[8px] text-theme-primary"></i> Tracking</a></li>
                         @foreach(\App\Models\Page::where('status', 1)->take(3)->get() as $footerPage)
                            <li><a href="{{ route('pages.show', $footerPage->slug) }}" class="text-xs font-bold uppercase tracking-widest hover:text-theme-primary transition-colors flex items-center gap-2"><i class="fas fa-arrow-right opacity-0 -ml-4 group-hover:opacity-100 group-hover:ml-0 transition-all text-[8px] text-theme-primary"></i> {{ $footerPage->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Row 2: Raw Data Info --}}
            <div class="p-8 h-full flex flex-col justify-end bg-theme-surface/30">
                <div class="mb-8">
                    <h3 class="text-xl font-heading font-black uppercase tracking-widest mb-1">Luxorix<span class="text-theme-primary">®</span></h3>
                    <p class="text-[10px] text-theme-muted uppercase font-mono tracking-widest">Est. 2024 — Karachi, PK</p>
                </div>
                
                <div class="grid grid-cols-1 gap-3 font-mono text-[10px] text-theme-muted uppercase tracking-widest">
                    <div class="flex justify-between border-b border-theme-content/10 pb-2">
                        <span>Support Line</span>
                        <span class="text-theme-content hover:text-theme-primary cursor-pointer transition-colors"><a href="tel:+923197870060">+92 319 787 0060</a></span>
                    </div>
                    <div class="flex justify-between border-b border-theme-content/10 pb-2">
                        <span>Server Time</span>
                        <span class="text-theme-content">{{ date('H:i:s T') }}</span>
                    </div>
                    <div class="flex justify-between pt-1">
                        <span>System Status</span>
                        <span class="text-green-500 flex items-center gap-2"><span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Online</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- 4. ULTRA MINIMAL BOTTOM BAR --}}
    <div class="border-t border-theme-content/10 bg-theme-surface z-20 relative">
        <div class="max-w-[1920px] mx-auto px-8 py-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-[10px] uppercase tracking-widest text-theme-muted/50 font-bold">
                © {{ date('Y') }} Luxorix International. All Rights Reserved.
            </p>
            
            <div class="flex gap-4 text-theme-content/30 text-lg">
                <i class="fab fa-cc-visa hover:text-theme-content transition-colors"></i>
                <i class="fab fa-cc-mastercard hover:text-theme-content transition-colors"></i>
                <i class="fab fa-apple-pay hover:text-theme-content transition-colors"></i>
                <i class="fab fa-bitcoin hover:text-theme-content transition-colors"></i>
            </div>
        </div>
    </div>
</footer>