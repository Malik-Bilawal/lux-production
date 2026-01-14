<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <title>@yield('title', 'Luxorix')</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link 
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" 
        rel="stylesheet"
    >

    <!-- Font Awesome -->
    <link 
        rel="stylesheet" 
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
        crossorigin="anonymous"
    >

    <!-- SweetAlert2 CSS -->
    <link 
        href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" 
        rel="stylesheet"
    >

    <!-- Main Vite CSS -->
    @vite([
        'resources/css/app.css', 
        'resources/css/components/navbar.css',
        'resources/js/app.js'
    ])

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('logo/logo.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('logo/logo.png') }}" type="image/png">

    <!-- Page-specific styles -->
    @stack('style')
    @yield('styles')
</head>
<body class="h-full">


    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->

    <!-- Global JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js" defer></script>


    <!-- Page-specific scripts -->
    @stack('script')
    @yield('scripts')

</body>
</html>
