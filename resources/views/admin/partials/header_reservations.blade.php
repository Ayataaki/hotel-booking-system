<!-- Mobile Header avec hamburger menu -->
    <div class="md:hidden fixed top-0 left-0 right-0 bg-white p-4 flex items-center z-30 shadow-sm">
      <button @click="sidebarOpen = true" class="text-[#6d4927] mr-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
      <h1 class="text-xl font-bold text-[#6d4927]">Gestion des réservations</h1>
    </div>

    <!-- Content area -->
    <div class="p-6 md:p-8 pt-20 md:pt-8">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-[#6d4927] hidden md:block">Gestion des réservations</h1>
        <!-- <div class="flex items-center space-x-4 ml-auto">
          <a href="#" class="text-[#95714F]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
          </a>
          <a href="#" class="text-[#95714F]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
          </a>
        </div> -->
      </div>

      <!-- Filtres et bouton d'ajout -->
      <!-- <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">
          <div class="relative w-full md:w-64">
            <input type="text" placeholder="Rechercher une réservation..." class="w-full pl-10 pr-4 py-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>

          <select class="w-full md:w-auto p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
            <option value="">Toutes les chambres</option>
            <option value="standard">Standard</option>
            <option value="deluxe">Deluxe</option>
            <option value="suite">Suite Prestige</option>
          </select>

          <select class="w-full md:w-auto p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
            <option value="">Tous les statuts</option>
            <option value="confirmed">Confirmée</option>
            <option value="pending">En attente</option>
            <option value="cancelled">Annulée</option>
          </select>

          <div class="relative w-full md:w-auto">
            <input type="date" class="w-full md:w-40 p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
          </div>
        </div> -->

        <!-- <button @click="addReservationModal = true" class="w-full md:w-auto bg-[#95714F] hover:bg-[#6d4927] text-white font-semibold py-2 px-4 rounded-lg transition-colors flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          <span>Nouvelle réservation</span>
        </button> -->
      <!-- </div> -->
