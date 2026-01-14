@extends("admin.layouts.master-layouts.plain")

<title>Ofer & Sale Management</title>

@push("script")
<script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#10B981',
                        dark: '#1F2937',
                        light: '#F9FAFB'
                    }
                }
            }
        }
    </script>
@endpush


@push("style")
<style>
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .upload-area {
            border: 2px dashed #cbd5e0;
            border-radius: 0.75rem;
            padding: 2rem;
            text-align: center;
            background-color: #f9fafb;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .upload-area:hover {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        .upload-area.active {
            border-color: #10b981;
            background-color: #ecfdf5;
        }
        .timer-input {
            max-width: 80px;
            text-align: center;
            padding: 0.5rem;
        }
        .timer-display {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 1.5rem;
            letter-spacing: 2px;
        }
        .offer-preview {
            background: linear-gradient(135deg, #3B82F6 0%, #10B981 100%);
            border-radius: 16px;
            overflow: hidden;
        }
        .discount-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            font-weight: bold;
            padding: 5px 12px;
            border-radius: 20px;
        }
        .countdown-item {
            min-width: 60px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            padding: 10px 5px;
        }
    </style>
@endpush
@section("content")

      <!-- 
  =================================================
  FIX 1: "NEXT LEVEL" HEADER
  - Stacks vertically on mobile (flex-col)
  - All buttons/actions stack vertically and go full-width
  - Padding is reduced on mobile
  =================================================
-->
<header class="bg-white shadow rounded-md mb-10 px-4 py-4 sm:px-8 sm:py-6 flex flex-col lg:flex-row justify-between gap-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Offer & Sale Management</h1>
        <p class="text-gray-600 mt-1 text-sm sm:text-base">Create and manage limited-time offers and sales</p>
    </div>

    <!-- Actions container -->
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 w-full lg:w-auto">
        
        <!-- Status (stacks nicely) -->
        <div class="flex items-center justify-between sm:justify-start space-x-2 p-2 bg-gray-50 rounded-lg w-full sm:w-auto">
            <span class="text-sm font-semibold text-gray-700">Current Status:</span>
            <span id="offerStatus" class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                Active
            </span>
        </div>

        <!-- Buttons (stack and go full-width on mobile) -->
        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
  

            <a href="{{ $saleExists ? '#' : route('admin.sales.create') }}"
               class="inline-flex items-center justify-center px-6 py-3 rounded-lg text-white font-semibold shadow-lg transition-colors duration-300 w-full sm:w-auto
               {{ $saleExists ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700' }}"
               {{ $saleExists ? 'onclick=return false;' : '' }}>
                <i class="fas fa-plus mr-3"></i> Add Sale
            </a>
        </div>
        
        <div class="hidden lg:block">
            @include("admin.components.dark-mode.dark-toggle")
        </div>
    </div>
</header>

<!-- Main grid is already responsive, no changes needed here -->
<section class="grid grid-cols-1 lg:grid-cols-2 gap-12">
    
    <!-- CURRENT SALES CARD -->
    <section class="bg-white rounded-xl shadow-md p-6 sm:p-8 flex flex-col">
        <!-- 
          =================================================
          FIX 2: RESPONSIVE CARD HEADER
          - Stacks on mobile, row on sm+
          =================================================
        -->
        <header class="flex flex-col sm:flex-row sm:justify-between sm:items-center border-b border-gray-200 pb-4 mb-6 gap-4">
            <h2 class="text-2xl font-semibold text-gray-900">Current Sales</h2>

            <div class="flex space-x-3 w-full sm:w-auto">
                @foreach($sales as $sale)
                    <a href="{{ route('admin.sales.edit', $sale->id) }}" 
                       class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm text-center">
                        Edit
                    </a>

                    <form action="{{ route('admin.sales.destroy', $sale->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" class="flex-1 sm:flex-none">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm">
                            Delete
                        </button>
                    </form>
                @endforeach
            </div>
        </header>

        <!-- Sales Timers -->
        @foreach($sales as $sale)
        <div class="sale-item mb-6 p-4 border rounded">
            <h3 class="text-xl font-semibold mb-3">{{ $sale->title }}</h3>

            <!-- 
              =================================================
              FIX 3: "NEXT LEVEL" RESPONSIVE TIMER
              - uses justify-around for better spacing
              - text-3xl on mobile, text-5xl on md+
              - no horizontal space on mobile
              =================================================
            -->
            
            <div class="sale-timer flex justify-around space-x-0 md:space-x-8 bg-gray-900 rounded-lg py-6 text-white font-mono text-center select-none"
                 data-endtime="{{ $sale->end_time ? $sale->end_time->toIso8601String() : '' }}">
                
                <div>
                    <div class="text-3xl md:text-5xl font-bold sale-days">00</div>
                    <div class="text-xs">Days</div>
                </div>
                <div>
                    <div class="text-3xl md:text-5xl font-bold sale-hours">00</div>
                    <div class="text-xs">Hours</div>
                </div>
                <div>
                    <div class="text-3xl md:text-5xl font-bold sale-minutes">00</div>
                    <div class="text-xs">Minutes</div>
                </div>
                <div>
                    <div class="text-3xl md:text-5xl font-bold sale-seconds">00</div>
                    <div class="text-xs">Seconds</div>
                </div>
            </div>
        </div>
        @endforeach
    </section>


</section>
@endsection

@push('script')
<script>
document.addEventListener('DOMContentLoaded', () => {

function updateAllTimers(timerSelector, daysClass, hoursClass, minutesClass, secondsClass) {
  const timers = document.querySelectorAll(timerSelector);

  timers.forEach(timer => {
    const endTimeStr = timer.dataset.endtime;
    if (!endTimeStr) return;

    const endTime = new Date(endTimeStr);
    if (isNaN(endTime)) return;

    const daysEl = timer.querySelector(daysClass);
    const hoursEl = timer.querySelector(hoursClass);
    const minutesEl = timer.querySelector(minutesClass);
    const secondsEl = timer.querySelector(secondsClass);

    if (!daysEl || !hoursEl || !minutesEl || !secondsEl) return;

    const now = new Date();
    let diff = (endTime - now) / 1000;
    if (diff < 0) diff = 0;

    const days = Math.floor(diff / 86400);
    diff -= days * 86400;

    const hours = Math.floor(diff / 3600);
    diff -= hours * 3600;

    const minutes = Math.floor(diff / 60);
    const seconds = Math.floor(diff % 60);

    daysEl.textContent = String(days).padStart(2, '0');
    hoursEl.textContent = String(hours).padStart(2, '0');
    minutesEl.textContent = String(minutes).padStart(2, '0');
    secondsEl.textContent = String(seconds).padStart(2, '0');
  });
}

// Initial update
updateAllTimers('.offer-timer', '.offer-days', '.offer-hours', '.offer-minutes', '.offer-seconds');
updateAllTimers('.sale-timer', '.sale-days', '.sale-hours', '.sale-minutes', '.sale-seconds');

// Run every second
setInterval(() => {
  updateAllTimers('.offer-timer', '.offer-days', '.offer-hours', '.offer-minutes', '.offer-seconds');
  updateAllTimers('.sale-timer', '.sale-days', '.sale-hours', '.sale-minutes', '.sale-seconds');
}, 1000);

});
</script>




@endpush

