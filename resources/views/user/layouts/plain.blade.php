<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Page')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- Tailwind build file --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- External fonts/icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    @stack('style')
</head>
<body>
    

<!-- Navbar -->
@include("user.layouts.navbar");

    @yield('content')

<!-- Footer -->
@include("user.layouts.footer");

    @stack('script')
</body>
</html>
