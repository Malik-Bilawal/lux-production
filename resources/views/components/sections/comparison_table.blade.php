@props(['section', 'items', 'index', 'page' => null])

@php
    use App\Models\ShippingMethod;
    use App\Models\ShippingCountry;

    // Get shipping methods
    $shippingMethods = ShippingMethod::where('status', 1)->get();
    
    // Get countries where shipping_rate is 0 or null (not fixed)
    $availableCountries = ShippingCountry::where(function($query) {
        $query->whereNull('shipping_rate')
              ->orWhere('shipping_rate', 0);
    })->get();
@endphp

<section class="py-24 bg-theme-bg overflow-hidden {{ $section->css_classes }}">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Section Header --}}
        @if($section->heading || $section->subheading)
        <div class="mb-16 border-l-2 border-theme-primary pl-6">
            @if($section->subheading)
                <span class="text-theme-primary font-mono text-[10px] uppercase tracking-[0.5em] block mb-3">
                    Protocol // {{ $section->subheading }}
                </span>
            @endif
            @if($section->heading)
                <h2 class="text-3xl md:text-5xl font-heading text-theme-content uppercase tracking-tighter italic leading-none">
                    {{ $section->heading }}
                </h2>
            @endif
        </div>
        @endif

        <div class="relative">
            
            {{-- 1. Mobile View --}}
            <div class="md:hidden space-y-6">
                @foreach($shippingMethods as $method)
                <div class="border-b border-theme-white/10 pb-6 group">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="block text-theme-primary font-mono text-[10px] uppercase tracking-widest mb-1">Service Tier</span>
                            <h3 class="text-xl font-heading uppercase italic text-theme-content">{{ $method->name }}</h3>
                        </div>
                        <div class="text-right">
                            <span class="block text-theme-primary font-mono text-[10px] uppercase tracking-widest mb-1">Cost</span>
                            <span class="text-theme-content font-mono">Rs.{{ number_format($method->cost, 0) }}</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4 bg-theme-surface/20 p-4 rounded-sm">
                        <div>
                            <span class="block text-theme-muted font-mono text-[8px] uppercase tracking-tighter mb-1">Transit</span>
                            <span class="text-xs font-mono text-theme-content">T+ {{ $method->delivery_time_days }} DAYS</span>
                        </div>
                        <div>
                            <span class="block text-theme-muted font-mono text-[8px] uppercase tracking-tighter mb-1">Complimentary</span>
                            <span class="text-xs font-mono text-theme-primary">
                                {{ $method->free_threshold ? '> PKR.' . number_format($method->free_threshold, 0) : 'N/A' }}
                            </span>
                        </div>
                    </div>

                    {{-- Show filtered countries --}}
                    <div class="flex flex-wrap gap-2">
                    @foreach($availableCountries as $country)
    <span class="inline-flex items-center gap-1.5 text-[9px] font-mono text-theme-muted uppercase border border-theme-white/10 px-2 py-0.5 rounded-full">
        
        <img
            src="https://flagcdn.com/w20/{{ strtolower($country->code) }}.png"
            alt="{{ $country->code }} flag"
            class="w-3 h-2 object-cover rounded-sm"
            loading="lazy"
        />

        {{ $country->code }}
    </span>
@endforeach

                    </div>
                </div>
                @endforeach
            </div>

            {{-- 2. Desktop Table --}}
            <div class=" md:block overflow-hidden">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b border-theme-primary/20 bg-transparent">
                            <th class="py-6 pr-6 text-[10px] uppercase tracking-[0.3em] text-theme-primary font-mono">Service Tier</th>
                            <th class="p-6 text-[10px] uppercase tracking-[0.3em] text-theme-primary font-mono">Transit Window</th>
                            <th class="p-6 text-[10px] uppercase tracking-[0.3em] text-theme-primary font-mono text-center">Base Rate</th>
                            <th class="p-6 text-[10px] uppercase tracking-[0.3em] text-theme-primary font-mono text-center">Complimentary Above</th>
                            <th class="py-6 pl-6 text-[10px] uppercase tracking-[0.3em] text-theme-primary font-mono text-right">Jurisdictions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-theme-white/5">
                        @foreach($shippingMethods as $method)
                        <tr class="group/row hover:bg-theme-primary/[0.03] transition-all duration-500">
                            <td class="py-8 pr-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-2 h-2 rounded-full bg-theme-primary/20 group-hover/row:bg-theme-primary transition-all duration-500"></div>
                                    <span class="text-lg font-heading tracking-wide uppercase group-hover/row:text-theme-primary transition-colors italic">
                                        {{ $method->name }}
                                    </span>
                                </div>
                            </td>

                            <td class="p-6">
                                <div class="font-mono text-sm text-theme-content/80">
                                    <span class="text-theme-primary mr-1">T+</span> 
                                    {{ $method->delivery_time_days }} 
                                    <span class="text-[9px] opacity-40 ml-1 tracking-tighter font-sans uppercase">Business Days</span>
                                </div>
                            </td>

                            <td class="p-6 text-center">
                                <span class="font-mono text-sm text-theme-content">
                                    PKR.{{ number_format($method->cost, 0) }}
                                </span>
                            </td>

                            <td class="p-6 text-center">
                                @if($method->free_threshold)
                                    <span class="font-mono text-sm text-theme-primary tracking-tighter">
                                        > PKR..{{ number_format($method->free_threshold, 0) }}
                                    </span>
                                @else
                                    <span class="font-mono text-[9px] opacity-20 italic uppercase">Excluded</span>
                                @endif
                            </td>

                            <td class="py-8 pl-6">
                                <div class="flex flex-wrap gap-1.5 justify-end">
                                    @foreach($availableCountries as $country)
                                    <div class="group/flag relative flex items-center gap-2 px-2 py-1 bg-theme-white/5 rounded-sm hover:border-theme-primary/40 transition-all">
    
    <img
        src="https://flagcdn.com/w20/{{ strtolower($country->code) }}.png"
        alt="{{ $country->code }} flag"
        class="w-4 h-3 rounded-sm object-cover"
        loading="lazy"
    />

    <span class="text-[9px] font-mono tracking-tighter opacity-60 group-hover/flag:opacity-100 uppercase text-theme-content">
        {{ $country->code }}
    </span>

</div>

                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer Note --}}
        <div class="mt-12 flex flex-col md:flex-row gap-4 justify-between items-center border-t border-theme-white/5 pt-8 opacity-40 font-mono text-[9px] uppercase tracking-[0.2em]">
            <div class="flex items-center gap-3">
                <span class="w-1 h-1 bg-theme-primary animate-pulse"></span>
                <span>Precision Logistics Network v2.0</span>
            </div>
            <span>Est. 2026 Luxorix Global Protocol</span>
        </div>
    </div>
</section>