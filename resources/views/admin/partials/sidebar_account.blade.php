<!-- Sidebar -->
<aside class="sidebar bg-[#EADED0]" :class="{'open': sidebarOpen}">
    <div class="flex flex-col h-full">
      <div class="p-6">
        <div class="flex items-center justify-between mb-2">
          <h1 class="text-2xl font-bold text-[#6d4927]">Mi Casa</h1>
        </div>
        <p class="text-sm mb-8">Panel d'administration</p>

        <nav class="space-y-1">
          <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors hover:bg-[#C7AF94] hover:text-white">
            <span>Tableau de bord</span>
          </a>

          <a href="{{ route('admin.rooms') }}" class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors hover:bg-[#C7AF94] hover:text-white">
            <span>Chambres</span>
          </a>

          <a href="{{ route('admin.staff') }}" class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors hover:bg-[#C7AF94] hover:text-white">
            <span>Personnel</span>
          </a>

          <a href="{{ route('admin.reservations') }}" class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors hover:bg-[#C7AF94] hover:text-white">
            <span>Réservations</span>
          </a>

          <a href="{{ route('admin.services') }}" class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors hover:bg-[#C7AF94] hover:text-white">
            <span>Services supplémentaires</span>
          </a>

          <!-- Mon compte - État actif -->
          <a href="{{ route('admin.account') }}" class="flex items-center space-x-3 px-3 py-3 rounded-lg bg-[#95714F] text-white">
            <span>Mon compte</span>
          </a>
        </nav>
      </div>

      <div class="mt-auto p-4 border-t border-[#C7AF94]">
        <form method="POST" action="{{ route('logout.post') }}">
          @csrf
          <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-white rounded-lg shadow-sm hover:bg-[#F3ECE3] transition-colors">
            <span>Déconnexion</span>
          </button>
        </form>
      </div>
    </div>
  </aside>
