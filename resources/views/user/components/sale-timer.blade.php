@if($saleTimer)
<section
    class="sale-timer relative overflow-hidden 
           py-16 sm:py-24 
           bg-theme-page text-theme-inverted border-y border-theme-content/5
           flex flex-col justify-center text-center group/section"
    data-end-time="{{ $saleTimer->end_time->toIso8601String() }}">

    <!-- Background blur circle -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-3xl h-full blur-[100px] rounded-full pointer-events-none"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 z-10 py-16">

        <!-- Heading & Divider Wrapper -->
        <div class="flex flex-col items-center justify-center space-y-4 sm:space-y-6 mb-16">


            <!-- Small label -->
            <span class="font-body text-theme-primary text-[10px] sm:text-xs tracking-[0.4em] uppercase
                 border-b border-theme-primary/30 pb-2">
                Limited Time Event
            </span>

            <!-- Main heading -->
            <h2 class="font-serif text-3xl md:text-3xl lg:text-6xl leading-none 
        text-transparent bg-clip-text bg-gradient-to-b from-white via-white to-white/40
        uppercase tracking-widest font-extralight mix-blend-screen
        transition-transform duration-1000 ease-out group-hover/header:translate-x-4">
        {{ $saleTimer->title }}
                </h2>

                <h2 class="absolute top-0 left-4 md:left-8 font-serif text-3xl md:text-8xl lg:text-6xl leading-none 
        text-white/10 uppercase tracking-widest pointer-events-none blur-md
        transition-transform duration-1000 ease-out group-hover/header:-translate-x-4">
        {{ $saleTimer->title }}
                </h2>

            @php
            $description = $saleTimer->description ?? 'Experience the pinnacle of luxury.';
            $discount = $saleTimer->discount ?? 0;
            // Remove existing % from description to avoid duplication
            $description = preg_replace('/\d+%/', '', $description);
            @endphp

            <!-- Description & discount -->
            <div class="flex flex-col sm:flex-row items-center gap-3 font-body text-theme-muted
                        text-base sm:text-lg font-medium leading-relaxed tracking-wide text-center">
                <span>{!! $description !!}</span>
                @if($discount > 0)
                <span class="hidden sm:inline-block w-1 h-1 rounded-full bg-theme-primary"></span>
                <span class="text-theme-content font-medium">
                    Exclusive <span class="text-theme-primary">{{ $discount }}% OFF</span>
                </span>
                @endif
            </div>

            <!-- Decorative Divider -->
            <div class="mt-8 flex justify-center items-center gap-4">
                <div class="h-[1px] w-16 bg-theme-muted/30"></div>
                <div class="w-3 h-3 rotate-45 border border-theme-primary"></div>
                <div class="h-[1px] w-16 bg-theme-muted/30"></div>
            </div>

        </div> <!-- END Heading & Divider Wrapper -->

        <!-- Countdown Timer Grid -->
        <div class="grid grid-cols-4 gap-3 sm:gap-6 md:gap-8 max-w-[340px] sm:max-w-3xl mx-auto mt-12">

            <!-- Days -->
            <div class="group flex flex-col items-center justify-center 
                        aspect-square sm:aspect-auto h-auto sm:h-32 w-full
                        bg-theme-surface/50 backdrop-blur-sm 
                        border border-theme-content/10 rounded-sm
                        transition-all duration-500 hover:border-theme-primary/40 hover:bg-theme-surface/80">
                <span id="days" class="font-heading text-3xl sm:text-5xl text-theme-content group-hover:text-theme-primary transition-colors duration-500">00</span>
                <span class="text-[9px] sm:text-[10px] font-body uppercase tracking-[0.2em] text-theme-muted/60 mt-2">Days</span>
            </div>

            <!-- Hours -->
            <div class="group flex flex-col items-center justify-center 
                        aspect-square sm:aspect-auto h-auto sm:h-32 w-full
                        bg-theme-surface/50 backdrop-blur-sm 
                        border border-theme-content/10 rounded-sm
                        transition-all duration-500 hover:border-theme-primary/40 hover:bg-theme-surface/80">
                <span id="hours" class="font-heading text-3xl sm:text-5xl text-theme-content group-hover:text-theme-primary transition-colors duration-500">00</span>
                <span class="text-[9px] sm:text-[10px] font-body uppercase tracking-[0.2em] text-theme-muted/60 mt-2">Hours</span>
            </div>

            <!-- Minutes -->
            <div class="group flex flex-col items-center justify-center 
                        aspect-square sm:aspect-auto h-auto sm:h-32 w-full
                        bg-theme-surface/50 backdrop-blur-sm 
                        border border-theme-content/10 rounded-sm
                        transition-all duration-500 hover:border-theme-primary/40 hover:bg-theme-surface/80">
                <span id="minutes" class="font-heading text-3xl sm:text-5xl text-theme-content group-hover:text-theme-primary transition-colors duration-500">00</span>
                <span class="text-[9px] sm:text-[10px] font-body uppercase tracking-[0.2em] text-theme-muted/60 mt-2">Mins</span>
            </div>

            <!-- Seconds -->
            <div class="group flex flex-col items-center justify-center 
                        aspect-square sm:aspect-auto h-auto sm:h-32 w-full
                        bg-theme-surface/50 backdrop-blur-sm 
                        border border-theme-content/10 rounded-sm
                        transition-all duration-500 hover:border-theme-primary/40 hover:bg-theme-surface/80">
                <span id="seconds" class="font-heading text-3xl sm:text-5xl text-theme-primary transition-colors duration-500">00</span>
                <span class="text-[9px] sm:text-[10px] font-body uppercase tracking-[0.2em] text-theme-muted/60 mt-2">Secs</span>
            </div>

        </div>

        <!-- CTA Button -->
        <div class="mt-10 sm:mt-12 opacity-0 animate-fade-in-up" style="animation-delay: 0.5s;">
            <a href="#shop" class="inline-block px-8 py-3 border border-theme-primary/30 text-theme-primary font-body text-xs uppercase tracking-[0.2em] hover:bg-theme-primary hover:text-theme-inverted transition-all duration-300">
                Access Event
            </a>
        </div>

    </div>
</section>

@endif
<script>
    const section = document.querySelector('.sale-timer');
    if (section) {
        const endTimeStr = section.dataset.endTime;
        if (!endTimeStr) {
            console.warn('No end time set');
        } else {
            const endTime = new Date(endTimeStr).getTime();
            if (isNaN(endTime)) {
                console.error('Invalid end time:', endTimeStr);
            } else {
                const daysEl = section.querySelector('#days');
                const hoursEl = section.querySelector('#hours');
                const minutesEl = section.querySelector('#minutes');
                const secondsEl = section.querySelector('#seconds');

                function updateTimer() {
                    const now = Date.now();
                    const diff = endTime - now;

                    if (diff <= 0) {
                        daysEl.textContent = hoursEl.textContent = minutesEl.textContent = secondsEl.textContent = '00';
                        clearInterval(timer);
                        return;
                    }

                    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                    daysEl.textContent = String(days).padStart(2, '0');
                    hoursEl.textContent = String(hours).padStart(2, '0');
                    minutesEl.textContent = String(minutes).padStart(2, '0');
                    secondsEl.textContent = String(seconds).padStart(2, '0');
                }

                const timer = setInterval(updateTimer, 1000);
                updateTimer();
            }
        }
    }
</script>