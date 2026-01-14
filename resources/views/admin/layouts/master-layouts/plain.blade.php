<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Page')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    
    {{-- Tailwind build file --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    {{-- External fonts/icons --}}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.tiny.cloud/1/378ael60p63vnx8jdimqm42xgtuykuhsnh6k0rnxtdquz1mr/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>


<!-- Load Tailwind -->



    @stack('style')
</head>
<body>
    
<!-- 
  This is the main wrapper for your entire page.
  It controls the 'sidebarOpen' state.
-->
<div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

@include("admin.layouts.partials.sidebar")


    <!-- Overlay (for mobile) -->
    <div 
        x-show="sidebarOpen" 
        @click="sidebarOpen = false" 
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-30 bg-black bg-opacity-50 md:hidden"
    ></div>

    <!-- 
      Main Content Area
      This pushes over to make room for the sidebar on desktop (md:ml-64).
    -->
    <div class="flex-1 overflow-y-auto bg-gradient-to-br from-gray-50 to-gray-100 p-6 md:ml-64">

    
        <!-- Hamburger Button (mobile only) -->
        <button 
            @click.stop="sidebarOpen = !sidebarOpen" 
            class="md:hidden p-2 rounded-md text-gray-800 hover:bg-gray-200"
        >
            <i class="fas fa-bars text-2xl"></i>
        </button>
    @yield('content')

    </div>
</div>

    
   {{-- Put this at the top of your body or inside your main container --}}
@if ($errors->any())
    <div class="alert alert-danger" style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 10px;">
        <strong>Whoops! Something went wrong:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])



    @stack('script')
</body>
</html>
