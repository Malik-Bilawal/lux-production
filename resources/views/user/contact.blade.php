@extends("user.layouts.master-layouts.plain")

@section('title', 'Contact | Luxorix')



@section("content")


<section class="relative isolate min-h-screen w-full flex flex-col items-center justify-center overflow-hidden bg-[#050505] py-20">

    <div class="absolute inset-0 z-0 opacity-[0.06] pointer-events-none mix-blend-overlay"
        style="background-image: url('https://grainy-gradients.vercel.app/noise.svg');">
    </div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-amber-500/10 blur-[120px] rounded-full pointer-events-none animate-pulse duration-[4000ms]"></div>
    <div class="absolute bottom-0 left-0 w-full h-40 bg-gradient-to-t from-[#050505] via-[#050505]/80 to-transparent z-10"></div>


    <div class="relative z-20 text-center px-4 flex flex-col items-center w-full max-w-4xl">

        <div class="mb-8 flex flex-col items-center gap-4 animate-fade-in-up">
            <div class="h-16 w-[1px] bg-gradient-to-b from-transparent via-amber-400/50 to-transparent"></div>
            <span class="font-mono text-[9px] tracking-[0.4em] uppercase text-white/40">
                Luxorix // Contact
            </span>
        </div>

        <h1 class="font-serif text-6xl md:text-8xl lg:text-[9rem] leading-[0.8] tracking-tighter mb-10 
               bg-gradient-to-b from-white via-amber-100 to-amber-700 bg-clip-text text-transparent 
               drop-shadow-2xl mix-blend-hard-light opacity-90">
            DIALOGUE
        </h1>

        <div class="flex flex-col items-center gap-8 max-w-xl mx-auto mb-12">
            <p class="font-sans text-xs md:text-sm text-white/50 font-light leading-relaxed tracking-wide">
                <span class="text-amber-100">Time is the ultimate currency.</span><br>
                We engage solely with those who understand the weight of intent. 
                For acquisitions, private commissions, or archival access.
            </p>
        </div>

        <form class="w-full max-w-md flex flex-col gap-10 mt-4 relative">
    
        <div class="group relative z-10">
    <input type="text" id="name" name="name" required placeholder=" " autocomplete="off"
        class="peer block w-full appearance-none bg-transparent border-b border-white/10 py-4 
               text-amber-50 font-serif text-xl caret-amber-500
               focus:outline-none focus:ring-0 focus:border-transparent
               transition-all duration-300 placeholder-shown:border-white/10" />
    
    <label for="name"
        class="absolute left-0 top-4 text-white/20 text-[10px] font-mono uppercase tracking-[0.2em]
               pointer-events-none transition-all duration-500 ease-out
               peer-focus:-top-4 peer-focus:text-amber-500 peer-focus:tracking-[0.3em]
               peer-not-placeholder-shown:-top-4 peer-not-placeholder-shown:text-amber-500">
        Identity
    </label>

    <div class="absolute bottom-0 left-1/2 w-0 h-[1px] bg-amber-500 
                transition-all duration-500 ease-out -translate-x-1/2 peer-focus:w-full"></div>
</div>


<div class="group relative z-10">
    <input type="email" id="email" name="email" required placeholder=" " autocomplete="off"
        class="peer block w-full appearance-none bg-transparent border-b border-white/10 py-4 
               text-amber-50 font-serif text-xl caret-amber-500
               focus:outline-none focus:ring-0 focus:border-transparent
               transition-all duration-300" />
    
    <label for="email"
        class="absolute left-0 top-4 text-white/20 text-[10px] font-mono uppercase tracking-[0.2em]
               pointer-events-none transition-all duration-500 ease-out
               peer-focus:-top-4 peer-focus:text-amber-500 peer-focus:tracking-[0.3em]
               peer-not-placeholder-shown:-top-4 peer-not-placeholder-shown:text-amber-500">
        Coordinates
    </label>

    <div class="absolute bottom-0 left-1/2 w-0 h-[1px] bg-amber-500 
                transition-all duration-500 ease-out -translate-x-1/2 peer-focus:w-full"></div>
</div>

<div class="group relative z-10">
    <textarea id="message" name="message" rows="1" required placeholder=" "
        class="peer block w-full appearance-none bg-transparent border-b border-white/10 py-4 
               text-amber-50 font-serif text-xl caret-amber-500 resize-none min-h-[60px]
               focus:outline-none focus:ring-0 focus:border-transparent
               transition-all duration-300"></textarea>
    
    <label for="message"
        class="absolute left-0 top-4 text-white/20 text-[10px] font-mono uppercase tracking-[0.2em]
               pointer-events-none transition-all duration-500 ease-out
               peer-focus:-top-4 peer-focus:text-amber-500 peer-focus:tracking-[0.3em]
               peer-not-placeholder-shown:-top-4 peer-not-placeholder-shown:text-amber-500">
        Intent
    </label>

    <div class="absolute bottom-0 left-1/2 w-0 h-[1px] bg-amber-500 
                transition-all duration-500 ease-out -translate-x-1/2 peer-focus:w-full"></div>
</div>

    <div class="mt-12 mb-12 flex justify-center w-full">
        <button type="submit" 
            class="relative group overflow-hidden px-12 py-5 border border-amber-500/30 transition-all duration-300 hover:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-black">
            
            <div class="absolute inset-0 w-full h-full bg-amber-500 transform scale-x-0 origin-left transition-transform duration-500 ease-out group-hover:scale-x-100"></div>
            
            <span class="relative  z-10 font-mono text-[10px] uppercase tracking-[0.3em] text-amber-500 transition-colors duration-300 group-hover:text-[#050505]">
                Transmit Signal
            </span>
            
        </button>
    </div>
</form>


        <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-white/5 pt-8 max-w-xl mx-auto">
            
            <div class="flex flex-col gap-2 group cursor-default">
                <span class="font-mono text-[9px] uppercase tracking-widest text-amber-500/60">Concierge</span>
                <a href="mailto:private@luxorix.com" class="font-serif text-xl text-white/80 group-hover:text-amber-100 transition-colors">
                    private@luxorix.com
                </a>
                <span class="text-[10px] text-white/30 font-light">Response within 24 hours</span>
            </div>

            <div class="flex flex-col gap-2 group cursor-default">
                <span class="font-mono text-[9px] uppercase tracking-widest text-amber-500/60">The Atelier</span>
                <p class="font-serif text-xl text-white/80">
                    Karachi, Pk
                </p>
                <span class="text-[10px] text-white/30 font-light">By Appointment Only</span>
            </div>
        </div>

    </div>

    <div class="absolute bottom-0 inset-x-0 border-b border-white/10 w-full z-0 pointer-events-none"></div>

</section>


@endsection


@push("script")
@vite('resources/js/user/pages/contact.js')
@endpush