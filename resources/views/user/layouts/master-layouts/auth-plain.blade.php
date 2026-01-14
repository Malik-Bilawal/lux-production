<!DOCTYPE html>
<html lang="en" class="h-full">
<style>
    /* Full-page overlay */
#loader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255, 255, 255, 0.9); 
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

.spinner {
  border: 8px solid #f3f3f3; 
  border-top: 8px solid #3498db;
  border-radius: 50%;
  width: 60px;
  height: 60px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}






  </style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Page')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          crossorigin="anonymous">

    <!-- Swiper CSS (only if used globally) -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
          crossorigin="anonymous">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Main App Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Page-specific CSS -->
    @stack('style')
</head>
<body class="h-full">
<div id="loader">
        <div class="loading-container">
            <div class="spinner-container">
                <div class="spinner-outer"></div>
                <div class="spinner-middle"></div>
                <div class="spinner-inner"></div>
                <div class="spinner-center"></div>
            </div>
            <div class="progress-container">
                <div class="progress-bar" id="progress-bar"></div>
            </div>
            <div class="loading-text">Loading</div>
        </div>
        <div class="brand">VIP MODERN</div>
    </div>
    @yield('content')

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

    <!-- Swiper JS (only if used globally) -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>

    <!-- Page-specific JS -->
    @stack('script')
    @yield('script')
<script>
    window.addEventListener('load', () => {
        // Just for testing: add delay before hiding loader
        setTimeout(() => {
            document.getElementById('loader').style.display = 'none';
        }, 3000); // 3000ms = 3 seconds
    });



    </script>
</body>
</html>
