<!-- Navbar -->
<header class="sticky top-0 z-40 transition-all duration-300 bg-[#EADED0]"
        :class="{ 'bg-[#EADED0]/95 backdrop-blur-md shadow-md py-2': scrolled, 'py-4': !scrolled }"
        @scroll.window="scrolled = (window.pageYOffset > 50)">
  <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
    <!-- Logo -->
    <a href="{{ route('home') }}" class="text-2xl font-bold text-[#95714F]">LA MI CASA</a>

    <!-- Navigation desktop (centré) -->
    <nav class="hidden md:flex space-x-6 justify-center flex-1">
      <a href="{{ route('home') }}" class="relative {{ request()->routeIs('home') ? 'text-[#6d4927] font-semibold after:w-full' : 'hover:text-[#6d4927]' }} transition-colors duration-300 no-underline after:absolute after:h-0.5 after:bg-[#95714F] after:bottom-0 after:left-1/2 after:-translate-x-1/2 hover:after:w-full after:transition-all after:duration-300 after:w-{{ request()->routeIs('home') ? 'full' : '0' }}">Accueil</a>
      <a href="{{ route('reservation') }}" class="relative {{ request()->routeIs('reservation') ? 'text-[#6d4927] font-semibold after:w-full' : 'hover:text-[#6d4927]' }} transition-colors duration-300 no-underline after:absolute after:h-0.5 after:bg-[#95714F] after:bottom-0 after:left-1/2 after:-translate-x-1/2 hover:after:w-full after:transition-all after:duration-300 after:w-{{ request()->routeIs('reservation') ? 'full' : '0' }}">Réserver</a>
      <a href="{{ route('chambres') }}" class="relative {{ request()->routeIs('chambres') ? 'text-[#6d4927] font-semibold after:w-full' : 'hover:text-[#6d4927]' }} transition-colors duration-300 no-underline after:absolute after:h-0.5 after:bg-[#95714F] after:bottom-0 after:left-1/2 after:-translate-x-1/2 hover:after:w-full after:transition-all after:duration-300 after:w-{{ request()->routeIs('chambres') ? 'full' : '0' }}">Chambres</a>
    </nav>

    <!-- Register / Login et icônes -->
    <div class="flex items-center space-x-4">
      @guest
        <a href="{{ route('register') }}" class="text-[#95714F] hover:text-[#6d4927] transition-colors duration-300 no-underline text-sm">Register</a>
        <span class="text-[#95714F]">|</span>
        <a href="{{ route('login') }}" class="text-[#95714F] hover:text-[#6d4927] transition-colors duration-300 no-underline text-sm">Login</a>
      @else
        <span class="text-[#95714F] text-sm">Bonjour, {{ Auth::user()->name }}</span>
        <span class="text-[#95714F]">|</span>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-[#95714F] hover:text-[#6d4927] transition-colors duration-300 no-underline text-sm">
          Déconnexion
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
          @csrf
        </form>
      @endguest
    </div>

    <!-- Burger Menu -->
    <button class="md:hidden ml-4" @click="openMenu = !openMenu">
      <svg class="w-6 h-6 text-[#6d4927]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>
  </div>

  <!-- Mobile Menu -->
  <div x-show="openMenu"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0 -translate-y-5"
      x-transition:enter-end="opacity-100 translate-y-0"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 translate-y-0"
      x-transition:leave-end="opacity-0 -translate-y-5"
      class="md:hidden bg-white shadow-lg rounded-b-lg px-6 pb-4 space-y-2 absolute top-full left-0 right-0"
      x-cloak>
    <a href="{{ route('home') }}" class="block py-2 border-b border-gray-200 hover:text-[#6d4927] no-underline {{ request()->routeIs('home') ? 'font-semibold' : '' }}">Accueil</a>
    <a href="{{ route('reservation') }}" class="block py-2 border-b border-gray-200 hover:text-[#6d4927] no-underline {{ request()->routeIs('reservation') ? 'font-semibold' : '' }}">Réserver</a>
    <a href="{{ route('chambres') }}" class="block py-2 hover:text-[#6d4927] no-underline {{ request()->routeIs('chambres') ? 'font-semibold' : '' }}">Chambres</a>
  </div>
</header>

<!-- Overlay flou pour le menu mobile -->
<div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-30 transition-opacity duration-300"
     :class="openMenu ? 'opacity-100 visible' : 'opacity-0 invisible'"
     @click="openMenu = false"></div>
