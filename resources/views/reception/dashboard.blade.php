<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LA MI CASA - Réception</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
  <style>
    .sidebar {
      width: 256px;
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      z-index: 40;
    }
    .main-content {
      margin-left: 256px;
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
    [x-cloak] { display: none !important; }
  </style>
</head>
<body class="bg-[#F8F7F4] text-[#95714F] font-['Times_New_Roman']" x-data="{ sidebarOpen: false }">

  <!-- Sidebar -->
  @include('reception.partials.sidebar')

  <!-- Main Content -->
  <div class="main-content min-h-screen">
    @include('reception.partials.header')

    <div class="p-6 md:p-8 pt-20 md:pt-8">
      <!-- Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Card: Chambres disponibles -->
         <div class="bg-white rounded-xl shadow-md p-6">
        <!-- <a href="{{ route('reception.chambres.disponibles') }}" class="bg-white rounded-xl shadow-md p-6 transition-transform hover:scale-105"> -->
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-[#6d4927]">Chambres disponibles</h3>
            <div class="w-12 h-12 rounded-full bg-[#EADED0] flex items-center justify-center text-[#95714F]">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
          </div>
          <p class="text-3xl font-bold mb-1">{{ $chambresDisponibles }}</p>
          <p class="text-sm text-gray-500">Disponibles aujourd'hui</p>

        <!-- </a> -->
         </div>

        <!-- Card: Réservations du jour -->
         <div class="bg-white rounded-xl shadow-md p-6">
        <!-- <a href="{{ route('reception.reservations') }}?date=today" class="bg-white rounded-xl shadow-md p-6 transition-transform hover:scale-105"> -->
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-[#6d4927]">Arrivées du jour</h3>
            <div class="w-12 h-12 rounded-full bg-[#EADED0] flex items-center justify-center text-[#95714F]">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
          </div>
          <p class="text-3xl font-bold mb-1">{{ $arriveesDuJour }}</p>
          <p class="text-sm text-gray-500">Check-ins aujourd'hui</p>
        <!-- </a> -->
         </div>

        <!-- Card: Départs du jour -->
         <div class="bg-white rounded-xl shadow-md p-6">
        <!-- <a href="{{ route('reception.reservations') }}?date=checkout" class="bg-white rounded-xl shadow-md p-6 transition-transform hover:scale-105"> -->
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-[#6d4927]">Départs du jour</h3>
            <div class="w-12 h-12 rounded-full bg-[#EADED0] flex items-center justify-center text-[#95714F]">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
              </svg>
            </div>
          </div>
          <p class="text-3xl font-bold mb-1">{{ $departsDuJour }}</p>
          <p class="text-sm text-gray-500">Check-outs aujourd'hui</p>
        <!-- </a> -->
         </div>
      </div>

      <!-- Réservations récentes -->
      <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-bold text-[#6d4927]">Réservations récentes</h3>
          <a href="{{ route('reception.reservations') }}" class="text-sm text-[#95714F] flex items-center">
            <span>Voir toutes</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </a>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CHAMBRE</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CLIENT</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CHECK-IN</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CHECK-OUT</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STATUT</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ACTIONS</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @php
              $couleurs = [
                  'confirmée' => 'bg-green-100 text-green-800',
                  'en_attente' => 'bg-yellow-100 text-yellow-800',
                  'annulée' => 'bg-red-100 text-red-800',
              ];
              @endphp
              @forelse($reservationsRecentes as $reservation)
                <tr class="hover:bg-gray-50">
                  <td class="px-4 py-3 whitespace-nowrap">{{ $reservation->chambre->NumCh ?? 'N/A' }}</td>
                  <td class="px-4 py-3 whitespace-nowrap">{{ $reservation->client->nom ?? 'N/A' }} {{ $reservation->client->prenom ?? '' }}</td>
                  <td class="px-4 py-3 whitespace-nowrap">{{ $reservation->dateDeb->format('d/m/Y') }}</td>
                  <td class="px-4 py-3 whitespace-nowrap">{{ $reservation->dateFin->format('d/m/Y') }}</td>
                  <td class="px-4 py-3 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs rounded-full {{ $couleurs[$reservation->statut] ?? 'bg-gray-100 text-gray-800' }}">
                      {{ ucfirst($reservation->statut) }}
                    </span>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap">
                    <div class="flex space-x-2">
                      <a href="{{ route('reception.reservations.show', $reservation->id) }}" class="text-blue-600 hover:text-blue-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                      </a>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="px-4 py-3 text-center text-gray-500">Aucune réservation récente</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Actions rapides -->
      <!-- <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-bold text-[#6d4927] mb-6">Actions rapides</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <a href="{{ route('reception.reservations.create') }}" class="flex items-center p-4 bg-[#EADED0] rounded-lg hover:bg-[#C7AF94] transition-colors">
            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-[#95714F] mr-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
            </div>
            <span>Nouvelle réservation</span>
          </a>

          <a href="{{ route('reception.chambres.disponibles') }}" class="flex items-center p-4 bg-[#EADED0] rounded-lg hover:bg-[#C7AF94] transition-colors">
            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-[#95714F] mr-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
              </svg>
            </div>
            <span>Voir disponibilités</span>
          </a>

          <a href="{{ route('reception.reservations') }}?status=checkin" class="flex items-center p-4 bg-[#EADED0] rounded-lg hover:bg-[#C7AF94] transition-colors">
            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-[#95714F] mr-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <span>Check-in clients</span>
          </a>
        </div>
      </div> -->
    </div>
  </div>

  <!-- Overlay mobile -->
  <div class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
       x-show="sidebarOpen"
       @click="sidebarOpen = false"></div>
</body>
</html>
