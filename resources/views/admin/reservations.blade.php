<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LA MI CASA - Gestion des réservations</title>
  <!-- <link rel="stylesheet" href="./output.css"> -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <!-- CDN TailWindCSS -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
  <style>
    /* Styles spécifiques pour éviter les chevauchements */
    .sidebar {
      width: 256px; /* 16rem */
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      z-index: 40;
    }

    .main-content {
      margin-left: 256px; /* Même largeur que la sidebar */
    }

    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;
      }

      .sidebar.open {
        transform: translateX(0);
      }

      .main-content {
        margin-left: 0;
      }
    }
  </style>
</head>

<body class="bg-[#F8F7F4] text-[#95714F] font-['Times_New_Roman']" x-data="{ sidebarOpen: false, addReservationModal: false, editReservationModal: false, deleteReservationModal: false, viewReservationModal: false, currentReservation: null }">
  <!-- Sidebar -->
  <aside class="sidebar bg-[#EADED0]" :class="{'open': sidebarOpen}">
    <div class="flex flex-col h-full">
      <div class="p-6">
        <div class="flex items-center justify-between mb-2">
          <h1 class="text-2xl font-bold text-[#6d4927]">LA MI CASA</h1>
          <!-- Bouton X pour fermer sur mobile -->
          <button @click="sidebarOpen = false" class="md:hidden text-[#6d4927]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <p class="text-sm mb-8">Panel d'administration</p>

        <nav class="space-y-1">
          <a href="admin_dashboard.html" class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors hover:bg-[#C7AF94] hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Tableau de bord</span>
          </a>

          <a href="admin_rooms.html" class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors hover:bg-[#C7AF94] hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span>Chambres</span>
          </a>

          <a href="admin_staff.html" class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors hover:bg-[#C7AF94] hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span>Personnel</span>
          </a>

          <a href="admin_reservations.html" class="flex items-center space-x-3 px-3 py-3 rounded-lg bg-[#95714F] text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>Réservations</span>
          </a>

          <a href="admin_settings.html" class="flex items-center space-x-3 px-3 py-3 rounded-lg transition-colors hover:bg-[#C7AF94] hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Paramètres</span>
          </a>
        </nav>
      </div>

      <div class="mt-auto p-4 border-t border-[#C7AF94]">
        <div class="flex items-center space-x-3 mb-4">
          <div class="w-10 h-10 rounded-full bg-[#95714F] flex items-center justify-center text-white">
            <span>A</span>
          </div>
          <div>
            <h4 class="font-medium">Admin Nom</h4>
            <p class="text-xs">Administrateur</p>
          </div>
        </div>
        <a href="admin_login.html" class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-white rounded-lg shadow-sm hover:bg-[#F3ECE3] transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          <span>Déconnexion</span>
        </a>
      </div>
    </div>
  </aside>

  <!-- Main Content -->
  <div class="main-content min-h-screen">
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
        <div class="flex items-center space-x-4 ml-auto">
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
        </div>
      </div>

      <!-- Filtres et bouton d'ajout -->
      <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
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
        </div>

        <button @click="addReservationModal = true" class="w-full md:w-auto bg-[#95714F] hover:bg-[#6d4927] text-white font-semibold py-2 px-4 rounded-lg transition-colors flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          <span>Nouvelle réservation</span>
        </button>
      </div>

      <!-- Tableau des réservations -->
      <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#F3ECE3]">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Réf.</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Client</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Chambre</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Check-in</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Check-out</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Prix</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Statut</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-[#6d4927] uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <!-- Réservation 1 -->
              <tr class="hover:bg-[#F8F7F4] transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  RES-2025-001
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-[#C7AF94] flex items-center justify-center text-white font-bold text-xs">
                      MD
                    </div>
                    <div class="ml-3">
                      <div class="text-sm font-medium text-[#6d4927]">Marie Dupont</div>
                      <div class="text-xs text-gray-500">marie.dupont@email.com</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-600">Suite Prestige 101</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-600">20/04/2025</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-600">25/04/2025</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-600">945€</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Confirmée</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end space-x-2">
                    <button @click="currentReservation = {id: 'RES-2025-001', client: 'Marie Dupont'}; viewReservationModal = true" class="text-[#95714F] hover:text-[#6d4927]" title="Voir">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </button>
                    <button @click="currentReservation = {id: 'RES-2025-001', client: 'Marie Dupont', room: 'Suite Prestige 101', checkin: '20/04/2025', checkout: '25/04/2025', status: 'confirmed'}; editReservationModal = true" class="text-[#95714F] hover:text-[#6d4927]" title="Modifier">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                      </svg>
                    </button>
                    <button @click="currentReservation = {id: 'RES-2025-001', client: 'Marie Dupont'}; deleteReservationModal = true" class="text-red-500 hover:text-red-700" title="Annuler">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>

              <!-- Réservation 2 -->
              <tr class="hover:bg-[#F8F7F4] transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  RES-2025-002
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-[#C7AF94] flex items-center justify-center text-white font-bold text-xs">
                      JM
                    </div>
                    <div class="ml-3">
                      <div class="text-sm font-medium text-[#6d4927]">Jean Martin</div>
                      <div class="text-xs text-gray-500">jean.martin@email.com</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-600">Chambre Deluxe 202</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-600">22/04/2025</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-600">24/04/2025</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-600">258€</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end space-x-2">
                    <button @click="currentReservation = {id: 'RES-2025-002', client: 'Jean Martin'}; viewReservationModal = true" class="text-[#95714F] hover:text-[#6d4927]" title="Voir">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </button>
                    <button @click="currentReservation = {id: 'RES-2025-002', client: 'Jean Martin', room: 'Chambre Deluxe 202', checkin: '22/04/2025', checkout: '24/04/2025', status: 'pending'}; editReservationModal = true" class="text-[#95714F] hover:text-[#6d4927]" title="Modifier">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                      </svg>
                    </button>
                    <button @click="currentReservation = {id: 'RES-2025-002', client: 'Jean Martin'}; deleteReservationModal = true" class="text-red-500 hover:text-red-700" title="Annuler">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>

              <!-- Réservation 3 -->
              <tr class="hover:bg-[#F8F7F4] transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  RES-2025-003
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-[#C7AF94] flex items-center justify-center text-white font-bold text-xs">
                      IL
                    </div>
                    <div class="ml-3">
                      <div class="text-sm font-medium text-[#6d4927]">Isabelle Laurent</div>
                      <div class="text-xs text-gray-500">isabelle.laurent@email.com</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-600">Chambre Standard 303</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-600">19/04/2025</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-600">21/04/2025</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-600">198€</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Confirmée</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end space-x-2">
                    <button @click="currentReservation = {id: 'RES-2025-003', client: 'Isabelle Laurent'}; viewReservationModal = true" class="text-[#95714F] hover:text-[#6d4927]" title="Voir">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </button>
                    <button @click="currentReservation = {id: 'RES-2025-003', client: 'Isabelle Laurent', room: 'Chambre Standard 303', checkin: '19/04/2025', checkout: '21/04/2025', status: 'confirmed'}; editReservationModal = true" class="text-[#95714F] hover:text-[#6d4927]" title="Modifier">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                      </svg>
                    </button>
                    <button @click="currentReservation = {id: 'RES-2025-003', client: 'Isabelle Laurent'}; deleteReservationModal = true" class="text-red-500 hover:text-red-700" title="Annuler">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>

              <!-- Réservation 4 -->
              <tr class="hover:bg-[#F8F7F4] transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  RES-2025-004
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-[#C7AF94] flex items-center justify-center text-white font-bold text-xs">
                      TM
                    </div>
                    <div class="ml-3">
                      <div class="text-sm font-medium text-[#6d4927]">Thomas Mercier</div>
                      <div class="text-xs text-gray-500">thomas.mercier@email.com</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-600">Suite Prestige 102</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-600">25/04/2025</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-600">28/04/2025</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-600">567€</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Annulée</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end space-x-2">
                    <button @click="currentReservation = {id: 'RES-2025-004', client: 'Thomas Mercier'}; viewReservationModal = true" class="text-[#95714F] hover:text-[#6d4927]" title="Voir">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </button>
                    <button @click="currentReservation = {id: 'RES-2025-004', client: 'Thomas Mercier', room: 'Suite Prestige 102', checkin: '25/04/2025', checkout: '28/04/2025', status: 'cancelled'}; editReservationModal = true" class="text-[#95714F] hover:text-[#6d4927]" title="Modifier">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                      </svg>
                    </button>
                    <button @click="currentReservation = {id: 'RES-2025-004', client: 'Thomas Mercier'}; deleteReservationModal = true" class="text-gray-400 cursor-not-allowed" title="Déjà annulée">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div class="flex items-center justify-between">
        <div class="text-sm text-gray-500">
          Affichage de 1 à 4 sur 24 résultats
        </div>
        <div class="flex space-x-2">
          <button disabled class="px-3 py-1 border border-gray-300 rounded-md text-gray-400 bg-gray-100">
            Précédent
          </button>
          <button class="px-3 py-1 border border-[#95714F] bg-[#95714F] text-white rounded-md">
            1
          </button>
          <button class="px-3 py-1 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
            2
          </button>
          <button class="px-3 py-1 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
            3
          </button>
          <button class="px-3 py-1 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
            Suivant
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Overlay mobile -->
  <div class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
       x-show="sidebarOpen"
       @click="sidebarOpen = false"></div>

  <!-- Modale Ajout de réservation -->
  <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
       x-show="addReservationModal"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto"
         x-show="addReservationModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         @click.away="addReservationModal = false">
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-bold text-[#6d4927]">Nouvelle réservation</h3>
          <button @click="addReservationModal = false" class="text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <form class="space-y-4">
          <div class="border-b border-gray-200 pb-4">
            <h4 class="text-md font-semibold text-[#6d4927] mb-3">Informations client</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label for="clientSelect" class="block text-sm font-medium text-[#6d4927] mb-1">Client existant</label>
                <select id="clientSelect" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                  <option value="">Sélectionner un client</option>
                  <option value="1">Marie Dupont</option>
                  <option value="2">Jean Martin</option>
                  <option value="3">Isabelle Laurent</option>
                  <option value="4">Thomas Mercier</option>
                  <option value="new">+ Nouveau client</option>
                </select>
              </div>
              <div>
                <label for="clientType" class="block text-sm font-medium text-[#6d4927] mb-1">Type de client</label>
                <select id="clientType" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                  <option value="individual">Particulier</option>
                  <option value="company">Entreprise</option>
                </select>
              </div>
            </div>

            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label for="firstName" class="block text-sm font-medium text-[#6d4927] mb-1">Prénom</label>
                <input type="text" id="firstName" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
              </div>
              <div>
                <label for="lastName" class="block text-sm font-medium text-[#6d4927] mb-1">Nom</label>
                <input type="text" id="lastName" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
              </div>
            </div>

            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label for="email" class="block text-sm font-medium text-[#6d4927] mb-1">Email</label>
                <input type="email" id="email" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
              </div>
              <div>
                <label for="phone" class="block text-sm font-medium text-[#6d4927] mb-1">Téléphone</label>
                <input type="tel" id="phone" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
              </div>
            </div>
          </div>

          <div class="border-b border-gray-200 pb-4">
            <h4 class="text-md font-semibold text-[#6d4927] mb-3">Détails de la réservation</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
              <div>
                <label for="roomType" class="block text-sm font-medium text-[#6d4927] mb-1">Type de chambre</label>
                <select id="roomType" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                  <option value="">Sélectionner</option>
                  <option value="standard">Standard</option>
                  <option value="deluxe">Deluxe</option>
                  <option value="suite">Suite Prestige</option>
                </select>
              </div>
              <div>
                <label for="roomNumber" class="block text-sm font-medium text-[#6d4927] mb-1">Chambre disponible</label>
                <select id="roomNumber" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                  <option value="">Sélectionner</option>
                  <option value="101">Suite Prestige 101</option>
                  <option value="203">Chambre Deluxe 203</option>
                  <option value="304">Chambre Standard 304</option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
              <div>
                <label for="checkin" class="block text-sm font-medium text-[#6d4927] mb-1">Date d'arrivée</label>
                <input type="date" id="checkin" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
              </div>
              <div>
                <label for="checkout" class="block text-sm font-medium text-[#6d4927] mb-1">Date de départ</label>
                <input type="date" id="checkout" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label for="adults" class="block text-sm font-medium text-[#6d4927] mb-1">Adultes</label>
                <select id="adults" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                  <option value="1">1</option>
                  <option value="2" selected>2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                </select>
              </div>
              <div>
                <label for="children" class="block text-sm font-medium text-[#6d4927] mb-1">Enfants</label>
                <select id="children" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                  <option value="0" selected>0</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                </select>
              </div>
              <div>
                <label for="status" class="block text-sm font-medium text-[#6d4927] mb-1">Statut</label>
                <select id="status" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                  <option value="confirmed">Confirmée</option>
                  <option value="pending">En attente</option>
                </select>
              </div>
            </div>
          </div>

          <div class="border-b border-gray-200 pb-4">
            <h4 class="text-md font-semibold text-[#6d4927] mb-3">Paiement</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
              <div>
                <label for="pricePerNight" class="block text-sm font-medium text-[#6d4927] mb-1">Prix par nuit</label>
                <div class="relative">
                  <input type="number" id="pricePerNight" value="189" class="w-full p-2 pl-8 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                  <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">€</span>
                </div>
              </div>
              <div>
                <label for="totalPrice" class="block text-sm font-medium text-[#6d4927] mb-1">Prix total</label>
                <div class="relative">
                  <input type="number" id="totalPrice" value="567" class="w-full p-2 pl-8 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                  <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">€</span>
                </div>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label for="paymentMethod" class="block text-sm font-medium text-[#6d4927] mb-1">Méthode de paiement</label>
                <select id="paymentMethod" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                  <option value="card">Carte bancaire</option>
                  <option value="cash">Espèces</option>
                  <option value="transfer">Virement bancaire</option>
                </select>
              </div>
              <div>
                <label for="depositPaid" class="block text-sm font-medium text-[#6d4927] mb-1">Acompte payé</label>
                <div class="relative">
                  <input type="number" id="depositPaid" value="170" class="w-full p-2 pl-8 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                  <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">€</span>
                </div>
              </div>
            </div>
          </div>

          <div>
            <label for="notes" class="block text-sm font-medium text-[#6d4927] mb-1">Notes</label>
            <textarea id="notes" rows="3" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]" placeholder="Demandes spéciales ou commentaires..."></textarea>
          </div>

          <div class="flex justify-end space-x-3 pt-4">
            <button type="button" @click="addReservationModal = false" class="px-4 py-2 border border-[#C7AF94] text-[#95714F] rounded-lg hover:bg-[#EADED0] transition-colors">
              Annuler
            </button>
            <button type="submit" class="px-4 py-2 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors">
              Créer la réservation
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modale Modification de réservation -->
  <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
       x-show="editReservationModal"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto"
         x-show="editReservationModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         @click.away="editReservationModal = false">
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-bold text-[#6d4927]" x-text="'Modifier la réservation ' + (currentReservation ? currentReservation.id : '')"></h3>
          <button @click="editReservationModal = false" class="text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <form class="space-y-4">
          <div class="border-b border-gray-200 pb-4">
            <h4 class="text-md font-semibold text-[#6d4927] mb-3">Informations client</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label for="editClientName" class="block text-sm font-medium text-[#6d4927] mb-1">Client</label>
                <input type="text" id="editClientName" :value="currentReservation ? currentReservation.client : ''" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]" disabled>
              </div>
              <div>
                <label for="editClientType" class="block text-sm font-medium text-[#6d4927] mb-1">Type de client</label>
                <select id="editClientType" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                  <option value="individual" selected>Particulier</option>
                  <option value="company">Entreprise</option>
                </select>
              </div>
            </div>

            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label for="editEmail" class="block text-sm font-medium text-[#6d4927] mb-1">Email</label>
                <input type="email" id="editEmail" value="marie.dupont@email.com" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
              </div>
              <div>
                <label for="editPhone" class="block text-sm font-medium text-[#6d4927] mb-1">Téléphone</label>
                <input type="tel" id="editPhone" value="+33 6 12 34 56 78" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
              </div>
            </div>
          </div>

          <div class="border-b border-gray-200 pb-4">
            <h4 class="text-md font-semibold text-[#6d4927] mb-3">Détails de la réservation</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
              <div>
                <label for="editRoomType" class="block text-sm font-medium text-[#6d4927] mb-1">Type de chambre</label>
                <select id="editRoomType" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                  <option value="standard">Standard</option>
                  <option value="deluxe">Deluxe</option>
                  <option value="suite" selected>Suite Prestige</option>
                </select>
              </div>
              <div>
                <label for="editRoomNumber" class="block text-sm font-medium text-[#6d4927] mb-1">Chambre</label>
                <input type="text" id="editRoomNumber" :value="currentReservation ? currentReservation.room : ''" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
              <div>
                <label for="editCheckin" class="block text-sm font-medium text-[#6d4927] mb-1">Date d'arrivée</label>
                <input type="text" id="editCheckin" :value="currentReservation ? currentReservation.checkin : ''" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
              </div>
              <div>
                <label for="editCheckout" class="block text-sm font-medium text-[#6d4927] mb-1">Date de départ</label>
                <input type="text" id="editCheckout" :value="currentReservation ? currentReservation.checkout : ''" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label for="editAdults" class="block text-sm font-medium text-[#6d4927] mb-1">Adultes</label>
                <select id="editAdults" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                  <option value="1">1</option>
                  <option value="2" selected>2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                </select>
              </div>
              <div>
                <label for="editChildren" class="block text-sm font-medium text-[#6d4927] mb-1">Enfants</label>
                <select id="editChildren" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                  <option value="0" selected>0</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                </select>
              </div>
              <div>
                <label for="editStatus" class="block text-sm font-medium text-[#6d4927] mb-1">Statut</label>
                <select id="editStatus" :value="currentReservation ? currentReservation.status : ''" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                  <option value="confirmed">Confirmée</option>
                  <option value="pending">En attente</option>
                  <option value="cancelled">Annulée</option>
                </select>
              </div>
            </div>
          </div>

          <div class="border-b border-gray-200 pb-4">
            <h4 class="text-md font-semibold text-[#6d4927] mb-3">Paiement</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
              <div>
                <label for="editPricePerNight" class="block text-sm font-medium text-[#6d4927] mb-1">Prix par nuit</label>
                <div class="relative">
                  <input type="number" id="editPricePerNight" value="189" class="w-full p-2 pl-8 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                  <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">€</span>
                </div>
              </div>
              <div>
                <label for="editTotalPrice" class="block text-sm font-medium text-[#6d4927] mb-1">Prix total</label>
                <div class="relative">
                  <input type="number" id="editTotalPrice" value="945" class="w-full p-2 pl-8 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                  <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">€</span>
                </div>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label for="editPaymentMethod" class="block text-sm font-medium text-[#6d4927] mb-1">Méthode de paiement</label>
                <select id="editPaymentMethod" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                  <option value="card" selected>Carte bancaire</option>
                  <option value="cash">Espèces</option>
                  <option value="transfer">Virement bancaire</option>
                </select>
              </div>
              <div>
                <label for="editDepositPaid" class="block text-sm font-medium text-[#6d4927] mb-1">Acompte payé</label>
                <div class="relative">
                  <input type="number" id="editDepositPaid" value="300" class="w-full p-2 pl-8 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                  <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">€</span>
                </div>
              </div>
            </div>
          </div>

          <div>
            <label for="editNotes" class="block text-sm font-medium text-[#6d4927] mb-1">Notes</label>
            <textarea id="editNotes" rows="3" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">Le client souhaite une vue sur la ville. Prévoir un lit d'appoint pour un enfant.</textarea>
          </div>

          <div class="flex justify-end space-x-3 pt-4">
            <button type="button" @click="editReservationModal = false" class="px-4 py-2 border border-[#C7AF94] text-[#95714F] rounded-lg hover:bg-[#EADED0] transition-colors">
              Annuler
            </button>
            <button type="submit" class="px-4 py-2 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors">
              Enregistrer les modifications
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modale Vue détaillée d'une réservation -->
  <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
       x-show="viewReservationModal"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto"
         x-show="viewReservationModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         @click.away="viewReservationModal = false">
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-bold text-[#6d4927]" x-text="'Réservation ' + (currentReservation ? currentReservation.id : '')"></h3>
          <button @click="viewReservationModal = false" class="text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div class="space-y-6">
          <div class="flex justify-between items-center bg-[#F3ECE3] p-4 rounded-lg">
            <div>
              <span class="text-xs text-gray-500">Statut</span>
              <p class="font-medium">
                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Confirmée</span>
              </p>
            </div>
            <div>
              <span class="text-xs text-gray-500">Date de réservation</span>
              <p class="font-medium">15/03/2025</p>
            </div>
            <div>
              <span class="text-xs text-gray-500">Référence</span>
              <p class="font-medium">RES-2025-001</p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
              <div class="border-b border-gray-200 pb-3">
                <h4 class="text-md font-semibold text-[#6d4927] mb-2">Client</h4>
                <dl class="grid grid-cols-3 gap-2 text-sm">
                  <dt class="text-gray-500">Nom</dt>
                  <dd class="col-span-2 font-medium">Marie Dupont</dd>

                  <dt class="text-gray-500">Email</dt>
                  <dd class="col-span-2">marie.dupont@email.com</dd>

                  <dt class="text-gray-500">Téléphone</dt>
                  <dd class="col-span-2">+33 6 12 34 56 78</dd>

                  <dt class="text-gray-500">Adresse</dt>
                  <dd class="col-span-2">123 Rue de Paris, 75001 Paris, France</dd>
                </dl>
              </div>

              <div class="border-b border-gray-200 pb-3">
                <h4 class="text-md font-semibold text-[#6d4927] mb-2">Détails du séjour</h4>
                <dl class="grid grid-cols-3 gap-2 text-sm">
                  <dt class="text-gray-500">Chambre</dt>
                  <dd class="col-span-2 font-medium">Suite Prestige 101</dd>

                  <dt class="text-gray-500">Arrivée</dt>
                  <dd class="col-span-2">20/04/2025 (à partir de 14h00)</dd>

                  <dt class="text-gray-500">Départ</dt>
                  <dd class="col-span-2">25/04/2025 (jusqu'à 11h00)</dd>

                  <dt class="text-gray-500">Durée</dt>
                  <dd class="col-span-2">5 nuits</dd>

                  <dt class="text-gray-500">Occupants</dt>
                  <dd class="col-span-2">2 adultes</dd>
                </dl>
              </div>
            </div>

            <div class="space-y-4">
              <div class="border-b border-gray-200 pb-3">
                <h4 class="text-md font-semibold text-[#6d4927] mb-2">Informations de paiement</h4>
                <dl class="grid grid-cols-3 gap-2 text-sm">
                  <dt class="text-gray-500">Prix/nuit</dt>
                  <dd class="col-span-2">189€</dd>

                  <dt class="text-gray-500">Total séjour</dt>
                  <dd class="col-span-2 font-medium">945€</dd>

                  <dt class="text-gray-500">Acompte</dt>
                  <dd class="col-span-2">300€ (payé le 15/03/2025)</dd>

                  <dt class="text-gray-500">Reste à payer</dt>
                  <dd class="col-span-2 font-medium text-[#95714F]">645€</dd>

                  <dt class="text-gray-500">Méthode</dt>
                  <dd class="col-span-2">Carte bancaire</dd>
                </dl>
              </div>

              <div class="border-b border-gray-200 pb-3">
                <h4 class="text-md font-semibold text-[#6d4927] mb-2">Notes</h4>
                <p class="text-sm text-gray-600">Le client souhaite une vue sur la ville. Prévoir un lit d'appoint pour un enfant.</p>
              </div>

              <div>
                <h4 class="text-md font-semibold text-[#6d4927] mb-2">Historique</h4>
                <ul class="space-y-2">
                  <li class="text-sm flex items-start">
                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full mr-2 whitespace-nowrap">15/03/2025</span>
                    <span>Réservation créée</span>
                  </li>
                  <li class="text-sm flex items-start">
                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full mr-2 whitespace-nowrap">15/03/2025</span>
                    <span>Acompte de 300€ payé par carte bancaire</span>
                  </li>
                  <li class="text-sm flex items-start">
                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full mr-2 whitespace-nowrap">16/03/2025</span>
                    <span>Email de confirmation envoyé</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
            <a href="#" target="_blank" class="px-4 py-2 border border-[#C7AF94] text-[#95714F] rounded-lg hover:bg-[#EADED0] transition-colors flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
              </svg>
              Imprimer
            </a>
            <button type="button" @click="viewReservationModal = false; editReservationModal = true" class="px-4 py-2 border border-[#C7AF94] text-[#95714F] rounded-lg hover:bg-[#EADED0] transition-colors flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
              </svg>
              Modifier
            </button>
            <button type="button" @click="viewReservationModal = false" class="px-4 py-2 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors">
              Fermer
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modale Confirmation d'annulation -->
  <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
       x-show="deleteReservationModal"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4"
         x-show="deleteReservationModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         @click.away="deleteReservationModal = false">
      <div class="p-6">
        <div class="text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Confirmez l'annulation</h3>
          <p class="text-gray-600 mb-6" x-text="'Êtes-vous sûr de vouloir annuler la réservation ' + (currentReservation ? currentReservation.id : '') + ' de ' + (currentReservation ? currentReservation.client : '') + ' ? Cette action est irréversible.'"></p>

          <div class="flex justify-center space-x-3">
            <button type="button" @click="deleteReservationModal = false" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
              Non, retour
            </button>
            <button type="button" @click="deleteReservationModal = false" class="px-4 py-2 border border-transparent rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
              Oui, annuler
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
