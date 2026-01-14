@extends("user.layouts.master-layouts.plain")

@section('title', 'Welcome | Luxorix')




@section('content')
<section class="relative w-full min-h-screen bg-[#0f0f0f] text-white flex flex-col lg:flex-row overflow-hidden border-b border-[#1a1a1a]">

    <div class="relative w-full lg:w-[60%] h-[60vh] lg:h-auto bg-[#1a1a1a] group overflow-hidden">
        
        <img 
          src="https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?q=80&w=1974&auto=format&fit=crop" 
          alt="Drop Hero" 
          class="w-full h-full object-cover opacity-90 transition-transform duration-[2000ms] ease-out group-hover:scale-105"
        />

        <div class="absolute inset-0 bg-gradient-to-t from-[#0f0f0f]/80 via-transparent to-transparent lg:bg-gradient-to-r lg:from-transparent lg:to-[#0f0f0f]/50"></div>

        <div class="absolute top-6 left-6 backdrop-blur-md bg-white/5 border border-white/10 px-4 py-2">
            <span class="font-mono text-xs tracking-widest text-[#D4AF37] uppercase">
                // Exclusive Preview
            </span>
        </div>
    </div>

    <div class="w-full lg:w-[40%] flex flex-col justify-center px-6 py-12 lg:px-16 bg-[#0f0f0f] relative z-10 border-l border-[#1a1a1a]">
        
        <div class="flex flex-col space-y-1 mb-8 opacity-80">
            <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span class="font-mono text-xs text-[#9CA3AF] tracking-widest">LIVE STATUS: ACTIVE</span>
            </div>
            <p class="font-mono text-xs text-[#D4AF37] tracking-widest">
                // DROP_ID: 001_GENESIS
            </p>
        </div>

        <h1 class="font-heading text-5xl lg:text-7xl leading-[0.9] text-white mb-6 uppercase tracking-tight">
            Apex <br />
            <span class="text-[#D4AF37] italic">Chrono</span>
        </h1>

        <p class="font-accent text-[#9CA3AF] text-lg italic mb-10 border-l-2 border-[#D4AF37]/30 pl-4">
            "Engineered for the few. The silence of precision meeting the chaos of time."
        </p>

        <div class="grid grid-cols-2 gap-4 border-t border-[#1a1a1a] py-6 mb-8">
            <div>
                <span class="block font-mono text-[10px] text-[#9CA3AF] uppercase mb-1">Acquisition Cost</span>
                <span class="font-body text-2xl font-bold text-white">Rs. 12,499</span>
            </div>
            <div class="text-right">
                <span class="block font-mono text-[10px] text-[#9CA3AF] uppercase mb-1">Inventory</span>
                <span class="font-mono text-sm text-[#D4AF37]">
                    14 <span class="text-[#9CA3AF]">/ 50 UNITS</span>
                </span>
            </div>
        </div>

        <a href="#" class="group relative block w-full bg-[#D4AF37] text-black font-body font-bold py-5 tracking-widest uppercase overflow-hidden text-center transition-all hover:bg-[#AA8C2C]">
            <span class="relative z-10 flex items-center justify-center gap-4">
                Secure Acquisition 
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </span>
            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
        </a>

        <div class="mt-6 text-center lg:text-left">
             <span class="font-mono text-[10px] text-[#9CA3AF] decoration-[#D4AF37]/50 underline underline-offset-4 cursor-pointer hover:text-white transition-colors">
                VIEW TECHNICAL SPECS +
             </span>
        </div>

    </div>
</section>


<div class="relative w-full bg-[#D4AF37] border-y border-[#AA8C2C] overflow-hidden py-3">
    
    <div class="flex whitespace-nowrap animate-marquee">
        
        <div class="flex items-center mx-4">
            <span class="text-black font-mono font-bold text-sm tracking-[0.2em] uppercase">
                // INCOMING DROP 001
            </span>
            <span class="mx-8 text-black opacity-40">✦</span>
            <span class="text-black font-mono font-bold text-sm tracking-[0.2em] uppercase">
                LIMITED QUANTITIES
            </span>
            <span class="mx-8 text-black opacity-40">✦</span>
            <span class="text-black font-mono font-bold text-sm tracking-[0.2em] uppercase">
                WORLDWIDE SECURE SHIPPING
            </span>
            <span class="mx-8 text-black opacity-40">✦</span>
            <span class="text-black font-mono font-bold text-sm tracking-[0.2em] uppercase">
                NO RESTOCKS ONCE SOLD
            </span>
            <span class="mx-8 text-black opacity-40">✦</span>
        </div>

        <div class="flex items-center mx-4">
            <span class="text-black font-mono font-bold text-sm tracking-[0.2em] uppercase">
                // INCOMING DROP 001
            </span>
            <span class="mx-8 text-black opacity-40">✦</span>
            <span class="text-black font-mono font-bold text-sm tracking-[0.2em] uppercase">
                LIMITED QUANTITIES
            </span>
            <span class="mx-8 text-black opacity-40">✦</span>
            <span class="text-black font-mono font-bold text-sm tracking-[0.2em] uppercase">
                WORLDWIDE SECURE SHIPPING
            </span>
            <span class="mx-8 text-black opacity-40">✦</span>
            <span class="text-black font-mono font-bold text-sm tracking-[0.2em] uppercase">
                NO RESTOCKS ONCE SOLD
            </span>
            <span class="mx-8 text-black opacity-40">✦</span>
        </div>

           <div class="flex items-center mx-4">
            <span class="text-black font-mono font-bold text-sm tracking-[0.2em] uppercase">
                // INCOMING DROP 001
            </span>
            <span class="mx-8 text-black opacity-40">✦</span>
            <span class="text-black font-mono font-bold text-sm tracking-[0.2em] uppercase">
                LIMITED QUANTITIES
            </span>
            <span class="mx-8 text-black opacity-40">✦</span>
            <span class="text-black font-mono font-bold text-sm tracking-[0.2em] uppercase">
                WORLDWIDE SECURE SHIPPING
            </span>
            <span class="mx-8 text-black opacity-40">✦</span>
            <span class="text-black font-mono font-bold text-sm tracking-[0.2em] uppercase">
                NO RESTOCKS ONCE SOLD
            </span>
            <span class="mx-8 text-black opacity-40">✦</span>
        </div>

    </div>
</div>

<style>
    /* Define the keyframes for the scrolling effect 
       Moves from 0% to -100% to create the illusion of infinite scrolling
    */
    @keyframes marquee {
        0% { transform: translateX(0%); }
        100% { transform: translateX(-100%); }
    }

    /* Apply the animation */
    .animate-marquee {
        animation: marquee 20s linear infinite;
    }

    /* Optional: Pause on hover so users can read */
    .animate-marquee:hover {
        animation-play-state: paused;
    }
</style>



<section class="w-full bg-[#0f0f0f] py-20 overflow-hidden">

    <div class="max-w-7xl mx-auto px-6">

        <div class="mb-24 text-center"> 
            <h2 class="font-heading text-4xl text-white uppercase mb-2">The <span class="text-[#D4AF37] italic">Selection</span></h2>
            <p class="font-mono text-xs text-[#9CA3AF] tracking-widest">// SCROLL TO DISCOVER</p>
        </div>

        <!-- RANK 02 -->
        <div class="flex flex-col md:flex-row items-center gap-12 mb-32 group">
            
            <div class="w-full md:w-1/2 relative">
                <div class="absolute -top-4 -left-4 w-24 h-24 border-t border-l border-[#D4AF37]/30 transition-all group-hover:w-full group-hover:h-full"></div>
                
                <img src="https://images.unsplash.com/photo-1523170335258-f5ed11844a49?q=80&w=2000&auto=format&fit=crop" 
                     alt="Rank 2" 
                     class="w-full h-auto shadow-[0_20px_50px_rgba(0,0,0,0.5)] grayscale group-hover:grayscale-0 transition-all duration-700 ease-out" />
            </div>

            <div class="w-full md:w-1/2 md:pl-10 text-center md:text-left">
                <span class="font-mono text-xs text-[#D4AF37] tracking-[0.2em] mb-4 block">// RANK 02</span>
                <h3 class="font-heading text-4xl md:text-5xl text-white leading-tight mb-6">
                    Midnight <br/> <span class="italic text-[#9CA3AF] group-hover:text-white transition-colors">Runner</span>
                </h3>
                <p class="font-body text-[#9CA3AF] text-sm leading-relaxed mb-8 max-w-md">
                    Engineered for the urban shadow. Features a matte ceramic finish and our signature silent movement.
                </p>
                <div class="flex flex-col md:flex-row gap-6 items-center">
                    <span class="font-mono text-xl text-white">Rs. 8,999</span>
                    <button class="text-white border-b border-[#D4AF37] pb-1 font-mono text-xs uppercase hover:text-[#D4AF37] transition-colors">
                        Secure Acquisition ->
                    </button>
                </div>
            </div>
        </div>

        <!-- RANK 03 -->
        <div class="flex flex-col md:flex-row-reverse items-center gap-12 mb-32 group">
            
            <div class="w-full md:w-1/2 relative">
                <div class="absolute -bottom-4 -right-4 w-24 h-24 border-b border-r border-[#D4AF37]/30 transition-all group-hover:w-full group-hover:h-full"></div>

                <img src="https://images.unsplash.com/photo-1505740106531-4243f3831c78?q=80&w=2000&auto=format&fit=crop" 
                     alt="Rank 3" 
                     class="w-full h-auto shadow-[0_20px_50px_rgba(0,0,0,0.5)] opacity-80 group-hover:opacity-100 transition-opacity duration-700" />
            </div>

            <div class="w-full md:w-1/2 md:pr-10 text-center md:text-right">
                <span class="font-mono text-xs text-[#D4AF37] tracking-[0.2em] mb-4 block">// RANK 03</span>
                <h3 class="font-heading text-4xl md:text-5xl text-white leading-tight mb-6">
                    Sonic <span class="italic text-[#D4AF37]">Link</span>
                </h3>
                <p class="font-body text-[#9CA3AF] text-sm leading-relaxed mb-8 max-w-md ml-auto">
                    High-fidelity audio meets industrial design. Titanium casing with noise-cancellation architecture.
                </p>
                <div class="flex flex-col md:flex-row-reverse gap-6 items-center">
                    <span class="font-mono text-xl text-white">Rs. 4,500</span>
                    <button class="text-white border-b border-[#D4AF37] pb-1 font-mono text-xs uppercase hover:text-[#D4AF37] transition-colors">
                        Secure Acquisition ->
                    </button>
                </div>
            </div>
        </div>

        <div class="relative w-full h-[60vh] md:h-[70vh] overflow-hidden mb-0 group border-t border-[#1a1a1a]">
    
    <img src="https://images.unsplash.com/photo-1444312645910-ffa973656eba?q=80&w=2070&auto=format&fit=crop" 
         alt="The Archive" 
         class="w-full h-full object-cover object-center grayscale brightness-50 group-hover:scale-105 group-hover:grayscale-0 transition-all duration-[2000ms] ease-out" />
    
    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>

    <div class="absolute bottom-12 left-6 md:left-12 max-w-2xl">
         
         <span class="font-mono text-[#D4AF37] text-xs tracking-[0.3em] uppercase mb-4 block">
            // ACCESS GRANTED
         </span>
         
         <h3 class="font-heading text-5xl md:text-7xl text-white mb-6 leading-none">
            The <span class="italic text-[#9CA3AF] group-hover:text-white transition-colors">Archive</span>
         </h3>
         
         <p class="font-body text-[#9CA3AF] text-sm md:text-base leading-relaxed mb-8 border-l border-[#D4AF37] pl-4">
            The drop is just the beginning. Explore our permanent collection of horology, objects, and artifacts.
         </p>

         <a href="/shop-all" class="inline-flex items-center gap-4 text-white font-mono text-sm uppercase tracking-widest group-hover:text-[#D4AF37] transition-colors">
            Enter The Secret Room
            <span class="text-xl">→</span>
         </a>
    </div>

    <div class="absolute inset-0 opacity-[0.05] pointer-events-none bg-[url('https://grainy-gradients.vercel.app/noise.svg')]"></div>

</div>

    </div>
</section>

@endsection

@section('style')
    <link href="{{ asset('css/user/new-drops.css') }}" rel="stylesheet">