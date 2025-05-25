<aside class="sidebar bg-[#EADED0]" :class="{'open': sidebarOpen}">
  <div class="flex flex-col h-full">
    <div class="p-6">
      <div class="flex items-center justify-between mb-2">
        <h1 class="text-2xl font-bold text-[#6d4927]">Mi Casa</h1>
        <button @click="sidebarOpen = false" class="md:hidden text-[#6d4927]">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <p class="text-sm mb-8">Espace Réception</p>

      <nav class="space-y-1">
        <!-- <a href="{{ route('reception.dashboard') }}" class="flex items-center space-x-3 px-3 py-3 rounded-lg {{ request()->routeIs('reception.dashboard') ? 'bg-[#95714F] text-white' : 'hover:bg-[#C7AF94] hover:text-white' }}">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          <span>Tableau de bord</span>
        </a> -->

        <a href="{{ route('reception.chambres.disponibles') }}" class="flex items-center space-x-3 px-3 py-3 rounded-lg {{ request()->routeIs('reception.chambres.*') ? 'bg-[#95714F] text-white' : 'hover:bg-[#C7AF94] hover:text-white' }}">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
          <span>Disponibilité chambres</span>
        </a>

        <!-- <a href="{{ route('reception.reservations.create') }}" class="flex items-center space-x-3 px-3 py-3 rounded-lg {{ request()->routeIs('reception.reservations.create') ? 'bg-[#95714F] text-white' : 'hover:bg-[#C7AF94] hover:text-white' }}">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <span>Nouvelle réservation</span>
        </a> -->

        <a href="{{ route('reception.reservations') }}" class="flex items-center space-x-3 px-3 py-3 rounded-lg {{ request()->routeIs('reception.reservations') && !request()->routeIs('reception.reservations.create') ? 'bg-[#95714F] text-white' : 'hover:bg-[#C7AF94] hover:text-white' }}">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <span>Gérer réservations</span>
        </a>
      </nav>
    </div>

    <div class="mt-auto p-4 border-t border-[#C7AF94]">
      <div class="flex items-center space-x-3 mb-4">
        <div class="w-10 h-10 rounded-full bg-[#95714F] flex items-center justify-center text-white">
          <span>{{ strtoupper(substr(Auth::user()->name ?? 'R', 0, 1)) }}</span>
        </div>
        <div>
          <h4 class="font-medium">{{ Auth::user()->name ?? 'Réceptionniste' }}</h4>
          <p class="text-xs">Réceptionniste</p>
        </div>
      </div>
      <form method="POST" action="{{ route('logout.post') }}">
        @csrf
        <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-white rounded-lg shadow-sm hover:bg-[#F3ECE3] transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          <span>Déconnexion</span>
        </button>
      </form>
    </div>
  </div>
</aside>
