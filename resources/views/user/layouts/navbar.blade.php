<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Luxorix | Premium Tech for Youth</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Rajdhani:wght@500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #4f46e5;
      --secondary: #10b981;
      --accent: #f59e0b;
      --dark: #0f172a;
      --light: #f8fafc;
      --neon: #00f2fe;
      --pulse: #ec4899;
    }

    .gradient-text {
      background: linear-gradient(to right, var(--primary), var(--neon));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .cart-badge {
      position: absolute;
      top: -6px;
      right: -8px;
      background: var(--pulse);
      color: white;
      font-size: 12px;
      padding: 2px 6px;
      border-radius: 9999px;
    }

    .nav-link {
      @apply hover:text-yellow-400 transition duration-300;
    }

    .navbar {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(99, 102, 241, 0.2);
        }
        
        .nav-link {
            position: relative;
            padding: 0.5rem 1rem;
            transition: color 0.3s;
        }
        
        .nav-link:hover {
            color: #00f2fe;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #00f2fe, #ec4899);
            transition: width 0.3s;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ec4899;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            animation: pulse 1.5s infinite;
        }

        /* Mobile menu */
        .mobile-menu {
          max-height: 0;
          overflow: hidden;
          transition: max-height 0.5s ease-out;
      }
      
      .mobile-menu.open {
          max-height: 500px;
      }
  </style>
</head>
<body class="bg-gray-900 text-white font-[Poppins]">

  <!-- Navbar -->
  <nav class="bg-gray-800/80 backdrop-blur-md w-full z-50 py-4 px-6 fixed top-0 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
      <!-- Logo -->
      <a href="#" class="text-2xl font-bold flex items-center">
        <i class="fas fa-microchip text-cyan-400 mr-2"></i>
        <span class="gradient-text">Luxorix</span>
      </a>

      <!-- Desktop Navigation -->
      <div class="hidden md:flex space-x-8 text-[16px] font-medium">
        <a href="index.php" class="nav-link">Home</a>
        <a href="watches.php" class="nav-link">Watches</a>
        <a href="earpods.php" class="nav-link">Earpods</a>
        <a href="headphones.php" class="nav-link">Headphones</a>
        <a href="about.php" class="nav-link">About</a>
        <a href="contact.php" class="nav-link">Contact</a>
      </div>

      <!-- Icons + Hamburger -->
      <div class="flex items-center space-x-6">
        <a href="cart.php" class="relative">
          <i class="fas fa-shopping-cart text-xl"></i>
          <span class="cart-badge">3</span>
        </a>
      <!-- User Icon with Dropdown -->
<div class="relative">
  <button id="userMenuButton" class="relative focus:outline-none">
    <i class="fas fa-user text-xl" ></i>
  </button>

<!-- Dropdown Menu -->
<div id="userDropdown" class="absolute right-0 mt-2 w-40 bg-gray-800 text-white rounded-lg shadow-lg border border-gray-700 hidden z-50">
    @if(Auth::check())
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-700">Logout</button>
        </form>
    @else
        <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-gray-700">Login</a>
        <a href="{{ route('register') }}" class="block px-4 py-2 hover:bg-gray-700">Sign Up</a>
    @endif
</div>


</div>

        <!-- Mobile Toggle Button -->
        <button id="mobile-menu-button" class="md:hidden">
          <i class="fas fa-bars text-2xl"></i>
        </button>
      </div>
    </div>

    <!-- Mobile Navigation -->
    <div id="mobile-menu" class="md:hidden mt-4 hidden transition-all duration-300 px-4">
      <div class="bg-gray-800/90 backdrop-blur-md rounded-xl p-5 flex flex-col space-y-4 shadow-lg border border-gray-600">
        <a href="index.php" class="nav-link flex items-center gap-2">
          <i class="fas fa-home text-yellow-400"></i> Home
        </a>
        <a href="watches.php" class="nav-link flex items-center gap-2">
          <i class="fas fa-clock text-green-400"></i> Watches
        </a>
        <a href="earpods.php" class="nav-link flex items-center gap-2">
          <i class="fas fa-headphones text-pink-400"></i> Earpods
        </a>
        <a href="headphones.php" class="nav-link flex items-center gap-2">
          <i class="fas fa-headphones text-blue-400"></i> Headphones
        </a>
        <a href="about.php" class="nav-link flex items-center gap-2">
          <i class="fas fa-info-circle text-indigo-400"></i> About
        </a>
        <a href="contact.php" class="nav-link flex items-center gap-2">
          <i class="fas fa-phone text-red-400"></i> Contact
        </a>
      </div>
    </div>
  </nav>

  <script>

const userBtn = document.getElementById('userMenuButton');
  const dropdown = document.getElementById('userDropdown');

  userBtn.addEventListener('click', () => {
    dropdown.classList.toggle('hidden');
  });

  // Optional: close dropdown when clicking outside
  window.addEventListener('click', function(e) {
    if (!userBtn.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.add('hidden');
    }
  });


  
    const btn = document.getElementById('mobile-menu-button');
    const menu = document.getElementById('mobile-menu');

    btn.addEventListener('click', () => {
      menu.classList.toggle('hidden');
    });
  </script>

</body>
</html>
