<!-- resources/views/admin/partials/sidebar.blade.php -->
 <!-- sidebar for dahsboard -->
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
      <p class="text-sm mb-8">Panel d'administration</p>

      <nav class="space-y-1">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-3 rounded-lg bg-[#95714F] text-white">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          <span>Tableau de bord</span>
        </a>

        <a href="{{ route('admin.rooms') }}" class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors hover:bg-[#C7AF94] hover:text-white">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
          <span>Chambres</span>
        </a>

        <a href="{{ route('admin.staff') }}" class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors hover:bg-[#C7AF94] hover:text-white">
            <!-- <div class="w-12 h-12 rounded-full bg-[#EADED0] flex items-center justify-center text-[#95714F]"> -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            <!-- </div> -->
          <span>Personnel</span>
        </a>

        <a href="{{ route('admin.reservations') }}" class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors hover:bg-[#C7AF94] hover:text-white">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <span>Réservations</span>
        </a>

        <!-- Lien vers Services supplémentaires -->
            <!-- <a href="{{ route('admin.services') }}"
            class="flex items-center px-6 py-2.5 {{ request()->routeIs('admin.services') ? 'bg-[#95714F] border-r-4 border-[#6d4927] text-white' : 'text-[#95714F] hover:bg-[#EADED0]' }} transition-colors"> -->
            <a href="{{ route('admin.services') }}"
            class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors hover:bg-[#C7AF94] hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
            </svg>
            Services supplémentaires
            </a>

        <!-- <a href="#" class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors hover:bg-[#C7AF94] hover:text-white">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          <span>Paramètres</span>
        </a> -->
      </nav>
    </div>

    <div class="mt-auto p-4 border-t border-[#C7AF94]">
      <div class="flex items-center space-x-3 mb-4">
        <div class="w-10 h-10 rounded-full bg-[#95714F] flex items-center justify-center text-white">
          <span>{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</span>
        </div>
        <div>
          <h4 class="font-medium">{{ strtoupper(Auth::user()->name ?? 'Admin') }}</h4>
          <p class="text-xs">Administrateur</p>
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
