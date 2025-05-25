<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi Casa - Gestion des réservations</title>
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
<body class="bg-[#F8F7F4] text-[#95714F] font-['Times_New_Roman']" x-data="{
    sidebarOpen: false,
    editReservationModal: false,
    deleteReservationModal: false,
    reservationModal: false,
    currentReservation: null
}">

  <!-- Sidebar -->
  @include('reception.partials.sidebar')

  <!-- Main Content -->
  <div class="main-content min-h-screen">
    @include('reception.partials.header', ['pageTitle' => 'Gestion des réservations'])








    <div class="p-6 md:p-8 pt-20 md:pt-8">
        <!-- Section Filtres simplifiée -->
        <div class="bg-white rounded-xl shadow-md mb-6 p-4">
            <form method="GET" action="{{ route('reception.reservations') }}" class="flex flex-wrap gap-4 items-end">
                <!-- Recherche client -->
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-[#6d4927] mb-1">Rechercher client</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nom, CIN ou passeport..."
                        class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                </div>

                <!-- Date d'arrivée -->
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-[#6d4927] mb-1">Date arrivée</label>
                    <input type="date" name="date_debut" value="{{ request('date_debut') }}"
                        class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                </div>

                <!-- Date de départ -->
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-[#6d4927] mb-1">Date départ</label>
                    <input type="date" name="date_fin" value="{{ request('date_fin') }}"
                        class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                </div>

                <!-- Boutons -->
                <div class="flex gap-2">
                    <button type="submit" class="bg-[#95714F] text-white px-6 py-2 rounded-lg hover:bg-[#6d4927] transition-colors">
                        Rechercher
                    </button>

                    @if(request()->hasAny(['search', 'date_debut', 'date_fin']))
                        <a href="{{ route('reception.reservations') }}"
                        class="border border-[#C7AF94] text-[#95714F] px-6 py-2 rounded-lg hover:bg-[#EADED0] transition-colors">
                            Effacer
                        </a>
                    @endif
                </div>
            </form>
        </div>



      <!-- Liste des réservations -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-lg font-bold text-[#6d4927]">Réservations</h3>
          <!-- Décommenté pour les étapes suivantes -->
          <!-- <a href="{{ route('reception.reservations.create') }}" class="px-4 py-2 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Nouvelle réservation</span>
          </a> -->
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CLIENT</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CHAMBRE</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ARRIVÉE</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DÉPART</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MONTANT</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ACTIONS</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @forelse($reservations as $reservation)
                <tr class="hover:bg-gray-50">
                  <td class="px-4 py-3 whitespace-nowrap">#{{ $reservation->id }}</td>
                  <td class="px-4 py-3 whitespace-nowrap">{{ $reservation->client->nom ?? 'N/A' }} {{ $reservation->client->prenom ?? '' }}</td>
                  <td class="px-4 py-3 whitespace-nowrap">{{ optional(optional($reservation->historique)->chambre)->NumCh ?? 'N/A' }}</td>
                  <td class="px-4 py-3 whitespace-nowrap">{{ $reservation->dateDeb->format('d/m/Y') }}</td>
                  <td class="px-4 py-3 whitespace-nowrap">{{ $reservation->dateFin->format('d/m/Y') }}</td>
                  <td class="px-4 py-3 whitespace-nowrap">{{ number_format($reservation->soldePayer, 2, ',', ' ') }}€</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex space-x-2">
                            <!-- Bouton Voir (comme vous l'avez déjà) -->
                            <!-- <button
                            @click="reservationModal = true; currentReservation = {
                                    id: 'RES-{{ $reservation->id }}',
                                    client: '{{ $reservation->client->prenom }} {{ $reservation->client->nom }}',
                                    chambre: '{{ optional(optional($reservation->historique)->chambre)->NumCh ?? 'Non défini' }}',
                                    prix_total: '{{  $reservation->totalPayer ?? 0 }}'
                                }"
                                class="text-[#95714F] hover:text-[#6d4927]"
                                title="Voir">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                            </button> -->

                            <!-- Bouton Modifier -->
                            <!-- <button @click="editReservationModal = true; currentReservation = {
                                        id: '{{ $reservation->id }}',
                                        client: '{{ $reservation->client->prenom }} {{ $reservation->client->nom }}',
                                        chambre: '{{ optional(optional($reservation->historique)->chambre)->NumCh ?? 'Non défini' }}',
                                        dateDeb: '{{ $reservation->dateDeb->format('d/m/Y') }}',
                                        dateFin: '{{ $reservation->dateFin->format('Y-m-d') }}'
                                    }"
                                    class="text-[#95714F] hover:text-[#6d4927]" title="Modifier"> -->
                                    <!-- <a href="{{ route('reception.reservations.edit', $reservation->id) }}"
   class="text-[#95714F] hover:text-[#6d4927]" title="Modifier"></a> -->
                                <a href="{{ route('reception.reservations.create', ['reservation' => $reservation->id]) }}"
                                    class="text-[#95714F] hover:text-[#6d4927]" title="Modifier">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                </a>
                            <!-- </button> -->

                            <!-- Bouton Supprimer -->
                            <!-- <button @click="deleteReservationModal = true; currentReservation = {
                                        id: '{{ $reservation->id }}',
                                        client: '{{ $reservation->client->prenom }} {{ $reservation->client->nom }}'
                                    }"
                                    class="text-red-600 hover:text-red-900" title="Supprimer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button> -->
                        </div>
                    </td>

                </tr>
              @empty
                <tr>
                  <td colspan="6" class="px-4 py-3 text-center text-gray-500">Aucune réservation trouvée</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <!-- <div class="mt-6">
          {{ $reservations->links() }}
        </div> -->
      </div>
        <!-- Pagination -->
        <div class="flex items-center justify-between mt-4">
            <div class="text-sm text-gray-500">
                Affichage de {{ ($reservations->currentPage() - 1) * $reservations->perPage() + 1 }} à {{ min($reservations->currentPage() * $reservations->perPage(), $reservations->total()) }} sur {{ $reservations->total() }} résultats
            </div>

            <div class="flex space-x-1">
                {{-- Bouton Précédent --}}
                @if ($reservations->currentPage() <= 1)
                    <span class="px-3 py-1 border border-gray-300 rounded-md text-gray-400 bg-gray-100">Précédent</span>
                @else
                    <!-- <a href="{{ $reservations->previousPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Précédent</a> -->
                    <a href="{{ $reservations->appends(request()->query())->previousPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Précédent</a>
                @endif

                {{-- Numéros de page --}}
                @for($i = 1; $i <= $reservations->lastPage(); $i++)
                    @if($i == $reservations->currentPage())
                        <span class="px-3 py-1 border border-[#95714F] bg-[#95714F] text-white rounded-md">{{ $i }}</span>
                    @else
                        <!-- <a href="{{ $reservations->url($i) }}" class="px-3 py-1 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">{{ $i }}</a> -->
                        <a href="{{ $reservations->appends(request()->query())->url($i) }}" class="px-3 py-1 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">{{ $i }}</a>
                    @endif
                @endfor

                {{-- Bouton Suivant --}}
                @if ($reservations->currentPage() >= $reservations->lastPage())
                    <span class="px-3 py-1 border border-gray-300 rounded-md text-gray-400 bg-gray-100">Suivant</span>
                @else
                    <!-- <a href="{{ $reservations->nextPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Suivant</a> -->
                    <a href="{{ $reservations->appends(request()->query())->nextPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Suivant</a>
                @endif
            </div>
        </div>
    </div>
  </div>

  <!-- Overlay mobile -->
  <div class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
       x-show="sidebarOpen"
       @click="sidebarOpen = false"></div>


  <!-- Modale Modification de réservation -->
<div x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
     x-show="editReservationModal"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-[#6d4927]" x-text="'Modifier la date de fin RES-' + (currentReservation ? currentReservation.id : '')"></h3>
                <button @click="editReservationModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('reception.reservations.update') }}">
                @csrf
                @method('PUT')

                <input type="hidden" name="id" :value="currentReservation ? currentReservation.id : ''">

                <div class="mb-4">
                    <!-- <label class="block text-sm font-medium text-[#6d4927] mb-1">Date d'arrivée (lecture seule)</label> -->
                    <label class="block text-sm font-medium text-[#6d4927] mb-1">Date d'arrivée</label>
                    <input type="text" :value="currentReservation ? currentReservation.dateDeb : ''"
                           class="w-full p-2 border border-[#C7AF94] rounded-lg bg-gray-100" readonly>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-[#6d4927] mb-1">Nouvelle date de départ</label>
                    <input type="date" name="dateFin" :value="currentReservation ? currentReservation.dateFin : ''"
                           class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]" required>
                    <p class="text-sm text-gray-500 mt-1">Le prix sera automatiquement ajusté</p>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" @click="editReservationModal = false"
                            class="px-4 py-2 border border-[#C7AF94] text-[#95714F] rounded-lg hover:bg-[#EADED0] transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modale Confirmation de suppression -->
<div x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
     x-show="deleteReservationModal"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4"
         @click.away="deleteReservationModal = false">
        <div class="p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Confirmer la suppression</h3>
                <p class="text-gray-600 mb-6" x-text="'Êtes-vous sûr de vouloir supprimer la réservation de ' + (currentReservation ? currentReservation.client : '') + ' ?'"></p>

                <div class="flex justify-center space-x-3">
                    <button type="button" @click="deleteReservationModal = false"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                        Annuler
                    </button>
                    <form method="POST" action="{{ route('reception.reservations.destroy') }}" class="inline">
                        @csrf
                        @method('DELETE')

                        <!-- Ajoutez l'ID caché -->
                        <input type="hidden" name="id" :value="currentReservation ? currentReservation.id : ''">

                        <button type="submit"
                                class="px-4 py-2 border border-transparent rounded-lg text-white bg-red-600 hover:bg-red-700">
                            Confirmer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
