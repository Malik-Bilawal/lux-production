<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
  <title>@yield('title', 'Luxorix')</title>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="manifest" href="/manifest.json">
  <meta name="theme-color" content="#000000">
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous">


  <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  

  <!-- SweetAlert2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">



  <!-- Favicon -->
  <link rel="icon" href="{{ asset('logo/logo.png') }}" type="image/png">
  <link rel="shortcut icon" href="{{ asset('logo/logo.png') }}" type="image/png">

  <!-- Page-specific styles -->
  @vite( 'resources/css/app.css')

  @stack('style')
</head>

<body class="min-h-screen">

  <div id="loader">
    <div class="spinner"></div>
  </div>
  <!-- Navbar -->
  @include('user.layouts.partials.navbar')

  <!-- Main Content -->
  @yield('content')

  <!-- Footer -->
  @include('user.layouts.partials.footer')

  <!-- Global JS Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js" defer></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">
  <!-- Main Vite CSS -->
  @vite( 'resources/js/app.js')

  <script type="module" src="{{ asset('navbar.js') }}"></script>


  <!-- Page-specific scripts -->
  @stack('script')


</body>






</html>