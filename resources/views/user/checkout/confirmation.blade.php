


@extends("user.layouts.master-layouts.unique")

@section('title', 'Order Confirmation | Luxorix')

@push("script")
<script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0f172a',
                        secondary: '#1e293b',
                        accent: '#00f2fe',
                        accent2: '#ec4899',
                        accent3: '#4f46e5',
                        dark: '#0f172a',
                        light: '#f8fafc',
                        neon: '#00f2fe',
                        pulse: '#ec4899'
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                        rajdhani: ['Rajdhani', 'sans-serif']
                    },
                    animation: {
                        float: 'float 3s ease-in-out infinite',
                        pulse: 'pulse 1.5s infinite',
                        'fade-in': 'fadeIn 0.3s ease-in forwards',
                        'fade-out': 'fadeOut 0.3s ease-out forwards'
                    },
                    keyframes: {
                        float: {
                            '0%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-15px)' },
                            '100%': { transform: 'translateY(0px)' }
                        },
                        pulse: {
                            '0%': { transform: 'scale(1)', boxShadow: '0 0 0 0 rgba(236, 72, 153, 0.7)' },
                            '70%': { transform: 'scale(1.05)', boxShadow: '0 0 0 10px rgba(236, 72, 153, 0)' },
                            '100%': { transform: 'scale(1)', boxShadow: '0 0 0 0 rgba(236, 72, 153, 0)' }
                        },
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        fadeOut: {
                            '0%': { opacity: '1', transform: 'translateY(0)' },
                            '100%': { opacity: '0', transform: 'translateY(10px)' }
                        }
                    }
                }
            }
        }
    </script>
@endpush


@push("style")
<style type="text/css">
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
            overflow-x: hidden;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        }
        
        .glow {
            text-shadow: 0 0 10px rgba(79, 70, 229, 0.7), 0 0 20px rgba(79, 70, 229, 0.5);
        }
        
        .neon-border {
            border: 2px solid rgba(0, 242, 254, 0.3);
            box-shadow: inset 0 0 10px rgba(0, 242, 254, 0.2), 0 0 20px rgba(0, 242, 254, 0.1);
        }
        
        .neon-text {
            text-shadow: 0 0 5px #00f2fe, 0 0 10px #00f2fe;
        }
        
        .gradient-text {
            background: linear-gradient(90deg, #00f2fe 0%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .holographic {
            background: linear-gradient(45deg, 
                rgba(16, 185, 129, 0.1) 0%, 
                rgba(79, 70, 229, 0.1) 25%, 
                rgba(236, 72, 153, 0.1) 50%, 
                rgba(245, 158, 11, 0.1) 75%, 
                rgba(0, 242, 254, 0.1) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .shine-effect {
            position: relative;
            overflow: hidden;
        }
        
        .shine-effect::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -60%;
            width: 20%;
            height: 200%;
            background: rgba(255, 255, 255, 0.13);
            background: linear-gradient(
                to right, 
                rgba(255, 255, 255, 0.13) 0%,
                rgba(255, 255, 255, 0.13) 77%,
                rgba(255, 255, 255, 0.5) 92%,
                rgba(255, 255, 255, 0.0) 100%
            );
            transform: rotate(30deg);
            transition: all 0.7s ease;
        }
        
        .shine-effect:hover::after {
            left: 120%;
            transition: all 0.7s ease;
        }
        
        .grid-bg {
            background-image: 
                linear-gradient(rgba(255,255,255,0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 30px 30px;
        }
        
        .cyber-button {
            background: linear-gradient(90deg, #4f46e5, #7c3aed);
            border: 1px solid rgba(0, 242, 254, 0.5);
            box-shadow: 0 0 15px rgba(79, 70, 229, 0.5);
            transition: all 0.3s ease;
        }
        
        .cyber-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.7);
            background: linear-gradient(90deg, #7c3aed, #4f46e5);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #0f172a;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #4f46e5;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #6366f1;
        }
        
        /* Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }
            
            .section-container {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }
        
        .confirmation-icon {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            margin: 0 auto;
            background: linear-gradient(135deg, #00f2fe, #ec4899);
            animation: pulse 2s infinite;
        }
        
        .tracking-bar {
            height: 6px;
            background: #334155;
            border-radius: 3px;
            position: relative;
            margin: 30px 0;
        }
        
        .tracking-progress {
            height: 100%;
            background: linear-gradient(90deg, #00f2fe, #ec4899);
            border-radius: 3px;
            width: 33%;
        }
        
        .tracking-milestone {
            position: absolute;
            top: -15px;
            transform: translateX(-50%);
            text-align: center;
            width: 120px;
        }
        
        .tracking-milestone.active .milestone-icon {
            background: linear-gradient(135deg, #00f2fe, #ec4899);
            color: white;
        }
        
        .milestone-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #1e293b;
            border: 2px solid #334155;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            transition: all 0.3s;
        }
        
        .order-item {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border-left: 3px solid transparent;
        }
        
        .order-item:hover {
            transform: translateY(-5px);
            border-left: 3px solid #00f2fe;
            box-shadow: 0 10px 25px -5px rgba(0, 242, 254, 0.15);
        }
        
        .download-btn {
            background: linear-gradient(90deg, #4f46e5, #7c3aed);
            transition: all 0.3s;
        }
        
        .download-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
        }
        
        .continue-btn {
            background: linear-gradient(90deg, #00f2fe, #ec4899);
            transition: all 0.3s;
        }
        
        .continue-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 242, 254, 0.3);
        }
    </style>
@endpush


@section("content")
<div class="grid-bg">
    <!-- Main Content -->
    <div class="pt-20">

        <!-- Order Confirmation -->
        <section class="py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="confirmation-icon mb-8">
                    <i class="fas fa-check"></i>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    <span class="gradient-text">ORDER CONFIRMED!</span>
                </h1>
                <p class="text-xl text-slate-400 mb-8">
                    Thank you for your purchase. Your order is being processed.
                </p>
                
                <div class="bg-slate-800/50 rounded-2xl border border-slate-700 p-6 mb-12 inline-block">
                <div class="text-2xl font-bold">
    TRCKING CODE : <span class="text-accent">{{ $order->tracking_code }}</span>
</div>
<div class="text-slate-400">
    Placed on {{ $order->placed_at->format('F d, Y \a\t h:i A') }}
</div>
<div class="text-slate-400 mt-2">
     ORDER ID: <span class="font-semibold">{{ $order->order_code }}</span>
</div>

                    <div class="text-slate-400 mt-2">
                        Status: <span class="font-semibold capitalize">{{ $order->status }}</span>
                    </div>
                </div>
            </div>
        </section>
        <script>
    (function(history){
        var pushState = history.pushState;
        history.pushState = function(state) {
            pushState.apply(history, arguments);
            window.dispatchEvent(new Event('pushstate'));
            window.dispatchEvent(new Event('locationchange'));
        };
        window.addEventListener('popstate', function() {
            window.location.href = '{{ route("user.welcome") }}';
        });
    })(window.history);
</script>
    </div>
</div>
@endsection



@push("script")
<script>
        document.querySelectorAll('.order-item').forEach((item, index) => {
            setTimeout(() => {
                item.style.opacity = "0";
                item.style.transform = "translateY(20px)";
                item.style.animation = "fadeIn 0.5s forwards";
            }, index * 200);
        });
        
        document.querySelector('.download-btn').addEventListener('click', function() {
            this.classList.add('animate-pulse');
            setTimeout(() => {
                this.classList.remove('animate-pulse');
                alert('Invoice downloaded successfully!');
            }, 1000);
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            const progressBar = document.querySelector('.tracking-progress');
            setTimeout(() => {
                progressBar.style.width = '66%';
            }, 2000);
        });



  

    </script>
@endpush

