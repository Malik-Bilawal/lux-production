@extends('admin.layouts.master-layouts.plain')

<title>Slider Managment | Admin Panel</title>

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
        .slider-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .slider-card:hover {
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
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .tab-button {
            transition: all 0.3s ease;
            position: relative;
        }
        .tab-button::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background-color: #3B82F6;
            transition: width 0.3s ease;
        }
        .tab-button.active {
            color: #3B82F6;
            font-weight: 600;
        }
        .tab-button.active::after {
            width: 100%;
        }
        .image-preview {
            transition: transform 0.3s ease;
        }
        .image-preview:hover {
            transform: scale(1.03);
        }
    </style>
@endpush

@section('content')

<header class="bg-white shadow-sm">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center py-4 px-6 gap-4">
        
        <div class="w-full sm:w-auto">
            <h2 class="text-xl font-semibold text-gray-800">Slider Management</h2>
            <p class="text-sm text-gray-500">Manage website banners and hero sections</p>
        </div>
        
        <div class="w-full sm:w-auto flex justify-end">
            @include("admin.components.dark-mode.dark-toggle")
        </div>
    </div>
</header>

<section class="px-6 py-4 bg-white shadow-sm mt-1">
    <div class="flex overflow-x-auto whitespace-nowrap border-b border-gray-200 mobile-tab-container">
        <button class="tab-button py-3 px-6 text-gray-600 active" data-tab="pages-header">Pages Header</button>
        <button class="tab-button py-3 px-6 text-gray-600" data-tab="video-section">Video Section</button>
        <button class="tab-button py-3 px-6 text-gray-600" data-tab="watches-hero">Watches Hero</button>
        <button class="tab-button py-3 px-6 text-gray-600" data-tab="neck-wrist">Neck & Wrist Hero</button>
    </div>

    <style>
      .mobile-tab-container::-webkit-scrollbar {
          display: none; /* Hide scrollbar for WebKit browsers */
      }
      .mobile-tab-container {
          -ms-overflow-style: none;  /* Hide scrollbar for IE and Edge */
          scrollbar-width: none;  /* Hide scrollbar for Firefox */
      }
    </style>
</section>



<section id="pages-header" class="tab-content p-6 flex-1 active">
   @include("admin.components.sliders.pages-header")
</section>





        <!-- Video Section Tab -->
<section id="video-section" class="tab-content p-6 flex-1">
@include("admin.components.sliders.video-section")
</section>

        <!-- Watch Bnaner Section Tab -->
        <section id="watches-hero" class="tab-content p-6 flex-1">
        @include("admin.components.sliders.watch-banner")
</section>

<section id="neck-wrist" class="tab-content p-6 flex-1">
@include("admin.components.sliders.neck-wrist-banner")

</section>



@endsection

@push("script")
<script>
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
                
                button.classList.add('active');
                const tabId = button.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });
        
   

    </script>
@endpush