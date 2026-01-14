{{-- resources/views/components/sections/contact_form.blade.php --}}
@props(['section', 'items', 'index', 'page' => null])

<section class="py-20 md:py-32 bg-theme-page overflow-hidden {{ $section->css_classes }}" x-data="contactForm()">
    <div class="max-w-4xl mx-auto px-6 relative">
        
        {{-- Header: Left Aligned for a more "Dossier" feel --}}
        <div class="mb-16 md:mb-24 max-w-2xl">
            @if($section->subheading)
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-2 h-2 rounded-full bg-theme-primary animate-pulse"></span>
                    <span class="text-theme-primary font-mono text-[10px] md:text-xs uppercase tracking-[0.4em]">
                        {{ $section->subheading }}
                    </span>
                </div>
            @endif

            @if($section->heading)
                <h2 class="text-3xl md:text-5xl font-light tracking-tight text-theme-content leading-tight mb-6 uppercase">
                    {{ $section->heading }}
                </h2>
            @endif

            @if($section->description ?? false)
                <p class="text-theme-muted/70 text-sm md:text-base leading-relaxed font-light">
                    {{ $section->description }}
                </p>
            @endif
            <div class="w-32 h-px bg-gradient-divider mx-auto"></div>

        </div>

        {{-- Form: No card background, just clean layout --}}
        <form @submit.prevent="submitForm" class="relative">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10 md:gap-y-16">
                
                {{-- Inputs --}}
                @foreach([
                    ['label' => 'Identification', 'name' => 'name', 'type' => 'text', 'placeholder' => 'Full Name', 'step' => '01'],
                    ['label' => 'Secure Email', 'name' => 'email', 'type' => 'email', 'placeholder' => 'Email Address', 'step' => '02']
                ] as $field)
                <div class="group relative">
                    <label class="block text-[10px] font-mono text-theme-primary/40 uppercase tracking-[0.2em] mb-2 group-focus-within:text-theme-primary transition-colors">
                        [{{ $field['step'] }}] {{ $field['label'] }}
                    </label>
                    <input type="{{ $field['type'] }}" 
                           x-model="form.{{ $field['name'] }}"
                           placeholder="{{ $field['placeholder'] }}"
                           class="w-full bg-transparent border-b border-theme-primary/10 py-3 text-theme-content placeholder-theme-muted/20 
                                  focus:outline-none focus:border-theme-primary transition-all duration-500 font-light">
                </div>
                @endforeach

                {{-- Inquiry Type: Minimal Select --}}
                <div class="md:col-span-2 group">
                    <label class="block text-[10px] font-mono text-theme-primary/40 uppercase tracking-[0.2em] mb-4">
                        [03] Inquiry Classification
                    </label>
                    <div class="flex flex-wrap gap-4">
                        @foreach(['Privacy', 'Terms', 'Legal', 'General'] as $type)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="type" value="{{ strtolower($type) }}" class="sr-only peer" x-model="form.inquiry_type">
                                <span class="px-5 py-2 text-[11px] font-mono uppercase tracking-widest border border-theme-primary/10 text-theme-muted/50
                                             peer-checked:border-theme-primary peer-checked:text-theme-primary peer-checked:bg-theme-primary/5
                                             hover:border-theme-primary/30 transition-all duration-300">
                                    {{ $type }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Message --}}
                <div class="md:col-span-2 group">
                    <label class="block text-[10px] font-mono text-theme-primary/40 uppercase tracking-[0.2em] mb-2">
                        [04] Transmission Details
                    </label>
                    <textarea x-model="form.message" rows="4" 
                              placeholder="Describe the nature of your inquiry..."
                              class="w-full bg-theme-primary/[0.02] border border-theme-primary/10 p-4 text-theme-content placeholder-theme-muted/20 
                                     focus:outline-none focus:border-theme-primary transition-all duration-500 font-light resize-none"></textarea>
                </div>

                {{-- Submit Section --}}
                <div class="md:col-span-2 pt-6 flex flex-col md:flex-row items-center justify-between gap-8">
                    <p class="text-[10px] font-mono text-theme-muted/40 uppercase tracking-widest leading-loose max-w-xs text-center md:text-left">
                        Encryption enabled. Transmission subject to privacy protocols.
                    </p>

                    <button type="submit" :disabled="submitting"
                            class="group/btn relative px-12 py-4 overflow-hidden border border-theme-primary/30 bg-transparent transition-all duration-500 hover:border-theme-primary">
                        {{-- Button Hover Fill --}}
                        <div class="absolute inset-0 w-0 bg-theme-primary transition-all duration-500 group-hover/btn:w-full opacity-10"></div>
                        
                        <span class="relative z-10 text-[11px] font-mono uppercase tracking-[0.3em] text-theme-content group-hover/btn:text-theme-primary" x-show="!submitting">
                            Initiate Transmission
                        </span>
                        <span x-show="submitting" class="relative z-10 flex items-center gap-3 text-[11px] font-mono uppercase tracking-[0.3em]">
                            <svg class="w-3 h-3 animate-spin" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" class="opacity-25"></circle><path d="M4 12a8 8 0 018-8" fill="currentColor"></path></svg>
                            Syncing...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>