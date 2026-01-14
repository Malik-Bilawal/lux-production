@extends("admin.layouts.master-layouts.plain")

<title>About Us Management | luxorix</title>

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
        .table-row:hover {
            background-color: #f3f4f6;
        }
        .action-btn {
            transition: transform 0.2s ease;
        }
        .action-btn:hover {
            transform: translateY(-2px);
        }
        .image-preview {
            transition: transform 0.3s ease;
        }
        .image-preview:hover {
            transform: scale(1.03);
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal.active {
            display: flex;
        }
    </style>
@endpush

@section("content")

        <!-- Header -->
        <header class="bg-white shadow-sm">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center py-4 px-6 gap-4">
        
        <div class="w-full sm:w-auto">
            <h2 class="text-xl font-semibold text-gray-800">About Us Management</h2>
            <p class="text-sm text-gray-500">Manage your company's story, team, and values</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center justify-center action-btn w-full sm:w-auto">
                <i class="fas fa-sync-alt mr-2"></i>
                Save All Changes
            </button>
            @include("admin.components.dark-mode.dark-toggle")
        </div>
    </div>
</header>

<section class="px-6 py-4 bg-white shadow-sm mt-1">
    <div class="flex overflow-x-auto whitespace-nowrap border-b border-gray-200 mobile-tab-container">
    <button class="tab-button py-3 px-6 text-gray-600" data-tab="content-block">Content Block</button>
    <button class="tab-button py-3 px-6 text-gray-600" data-tab="stats">Stats</button>
        <button class="tab-button py-3 px-6 text-gray-600 active" data-tab="vision">Vision</button>
        <button class="tab-button py-3 px-6 text-gray-600" data-tab="our-values">Our Values</button>
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

        <!-- Our Story Tab -->
        <section id="content-block" class="tab-content p-6 flex-1 active">
@include('admin.components.about.content-block')
        </section>

<!-- Founder Tab -->
<section id="stats" class="tab-content p-6 flex-1">
@include("admin.components.about.stats")
</section>


        <!-- Our Journey Tab -->
        <section id="vision" class="tab-content p-6 flex-1">
@include("admin.components.about.vision")
        </section>



        <!-- Our Values Tab -->
        <section id="our-values" class="tab-content p-6 flex-1">
@include("admin.components.about.our-value")
        </section>
    </div>

    </div>
    </div>





@endsection


@push("script")
<script>



        // Tab functionality
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