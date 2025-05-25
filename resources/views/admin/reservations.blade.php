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
    [x-cloak] {
        display: none !important;
    }
  </style>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<!-- <body class="bg-[#F8F7F4] text-[#95714F] font-['Times_New_Roman']" x-data="{ sidebarOpen: false, addReservationModal: false, editReservationModal: false, deleteReservationModal: false, viewReservationModal: false, currentReservation: null }"> -->
<body class="bg-[#F8F7F4] text-[#95714F] font-['Times_New_Roman']" x-data="reservationsManager()" x-init="init()">
  <!-- Sidebar -->
  @include('admin.partials.sidebar_reservations')

  <!-- Main Content -->
  <div class="main-content min-h-screen">
    @include('admin.partials.header_reservations')

      <!-- Tableau des réservations -->
      <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#F3ECE3]">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Réf.</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Client</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Chambre</th>
                <!-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Check-in</th> -->
                <!-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Check-out</th> -->
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Prix</th>
                <!-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Statut</th> -->
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-[#6d4927] uppercase tracking-wider">Détails</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($reservations as $reservation)
                    <tr class="hover:bg-[#F8F7F4] transition-colors">
                    <!-- Référence -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        RES-{{ $reservation->id }}
                    </td>

                    <!-- Client -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-[#C7AF94] flex items-center justify-center text-white font-bold text-xs">
                            {{ strtoupper(substr($reservation->client->prenom, 0, 1)) }}{{ strtoupper(substr($reservation->client->nom, 0, 1)) }}
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-[#6d4927]">
                            {{ $reservation->client->prenom }} {{ $reservation->client->nom }}
                            </div>
                            <div class="text-xs text-gray-500">{{ $reservation->client->email }}</div>
                        </div>
                        </div>
                    </td>

                    <!-- Chambre -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <!-- {{ optional($reservation->historique->chambre)->NumCh ?? 'Non définie' }} -->
                        <!-- {{ optional(optional($reservation->historique)->chambre)->NumCh ?? 'Non définie' }} -->
                          {{ optional(optional($reservation->historique)->chambre)->NumCh ?? 'Non définie' }}

                        <!-- {{ data_get($reservation, 'historique.chambre.NumCh', 'Non définie') }} -->

                    </td>


                    <!-- Prix -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $reservation->totalPayer }}€
                    </td>

                    <!-- Actions -->
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                        <!-- Bouton Voir -->
                            <!-- <button
                               @click="reservationModal = true; currentReservation = {
                                    id: 'RES-{{ $reservation->id }}',
                                    client: '{{ $reservation->client->prenom }} {{ $reservation->client->nom }}',
                                    chambre: '{{ optional(optional($reservation->historique)->chambre)->NumCh ?? 'Non défini' }}',
                                    date_debut: '{{ optional($reservation->historique)->created_at ?? '---' }}',
                                    date_fin: '{{ optional($reservation->historique)->updated_at ?? '---' }}',
                                    prix_total: '{{  $reservation->totalPayer ?? 0 }}',
                                    phone: '{{ $reservation->client->numTel ?? '---' }}',
                                    pays: '{{ $reservation->client->pays ?? '---' }}',
                                    region: '{{ $reservation->client->region ?? '---' }}',
                                    identite: '{{ $reservation->client->CIN ?? $reservation->client->passeport ?? '---' }}',

                                }"

                                class="text-[#95714F] hover:text-[#6d4927]"
                                title="Voir Détail"> -->
                                <button
                                    @click="reservationModal = true; currentReservation = {
                                            id: 'RES-{{ $reservation->id }}',
                                            client: '{{ $reservation->client->prenom }} {{ $reservation->client->nom }}',
                                            chambre: '{{ optional(optional($reservation->historique)->chambre)->NumCh ?? 'Non défini' }}',
                                            date_debut: '{{ $reservation->dateDeb->format('d/m/Y') }}',
                                            date_fin: '{{ $reservation->dateFin->format('d/m/Y') }}',
                                            prix_total: '{{  $reservation->totalPayer ?? 0 }}',
                                            phone: '{{ $reservation->client->numTel ?? '---' }}',
                                            pays: '{{ $reservation->client->pays ?? '---' }}',
                                            region: '{{ $reservation->client->region ?? '---' }}',
                                            identite: '{{ $reservation->client->CIN ?? $reservation->client->passeport ?? '---' }}',
                                        }"

                                        class="text-[#95714F] hover:text-[#6d4927]"
                                        title="Voir Détail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                            </button>

                            <!-- <button
                                @click="currentReservation = {
                                    id: '{{ $reservation->id }}',
                                    client: '{{ $reservation->client->nom }} {{ $reservation->client->prenom }}',
                                    chambre: '{{ $reservation->historique->chambre->nom }}',
                                    dateDebut: '{{ $reservation->historique->date_debut }}',
                                    dateFin: '{{ $reservation->historique->date_fin }}',
                                    prix: '{{ $reservation->historique->prix_total }}'
                                }; viewReservationModal = true"
                                class="text-[#95714F] hover:text-[#6d4927]"
                                title="Voir">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button> -->

                        <!-- <button
                        @click="viewChambreDetails({{ optional(optional($reservation->historique)->chambre)->id ?? 'null' }})"
                        class="text-[#95714F] hover:text-[#6d4927]"
                        title="Voir la chambre">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button> -->
                        <!-- <button @click="openViewModal({
                            id: 'RES-{{ $reservation->id }}',
                            client: '{{ $reservation->client->prenom }} {{ $reservation->client->nom }}',
                            room: '{{ optional($reservation->chambre)->NumCh ?? "Non définie" }}',
                            checkin: '{{ $reservation->dateDeb }}',
                            checkout: '{{ $reservation->dateFin }}',
                            price: '{{ $reservation->totalPayer }}',
                            acompte: '{{ $reservation->soldePayer }}'
                        })" class="text-[#95714F] hover:text-[#6d4927]" title="Voir">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button> -->

                        <!-- Bouton Modifier -->
                         <!-- <button @click="editReservation({{ $reservation->id }})" class="text-[#95714F] hover:text-[#6d4927]" title="Modifier">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                          </button> -->

                          <!-- Bouton Supprimer -->
                           <!-- <button @click="deleteReservation({{ $reservation->id }}, '{{ $reservation->client->prenom }} {{ $reservation->client->nom }}')" class="text-red-500 hover:text-red-700" title="Annuler">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button> -->
                        </div>
                    </td>
                    </tr>
                @endforeach
                </tbody>

            <!-- <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($reservations as $reservation) -->
                    <!-- <tr class="hover:bg-[#F8F7F4] transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            RES-{{ $reservation->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-[#C7AF94] flex items-center justify-center text-white font-bold text-xs">
                                    {{ strtoupper(substr($reservation->client->prenom, 0, 1)) }}{{ strtoupper(substr($reservation->client->nom, 0, 1)) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-[#6d4927]">{{ $reservation->client->prenom }} {{ $reservation->client->nom }}</div>
                                    <div class="text-xs text-gray-500">{{ $reservation->client->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap"> -->
                            <!-- <div class="text-sm text-gray-600">{{ $reservation->chambre->image ?? 'Non définie' }}</div> -->
                            <!-- <div class="text-sm text-gray-600">{{ $reservation->chambre->NumCh ?? 'Non définie' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $reservation->dateDeb }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $reservation->dateFin }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $reservation->totalPayer }}€</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Confirmée</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2"> -->
                                <!-- Actions dynamiques plus tard ici -->
                                 <!-- Bouton Voir -->
                                <!-- <button @click="openViewModal({
                                    id: 'RES-{{ $reservation->id }}',
                                    client: '{{ $reservation->client->prenom }} {{ $reservation->client->nom }}',
                                    room: '{{ optional($reservation->chambre)->NumCh ?? "Aucune chambre" }}',
                                    checkin: '{{ $reservation->dateDeb }}',
                                    checkout: '{{ $reservation->dateFin }}',
                                    price: '{{ $reservation->totalPayer }}',
                                    solde: '{{ $reservation->soldePayer }}'
                                })"
                                class="text-[#95714F] hover:text-[#6d4927]" title="Voir">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button> -->

                                <!-- Bouton Modifier -->
                                <!-- <button @click="editReservation({{ $reservation->id }})" class="text-[#95714F] hover:text-[#6d4927]" title="Modifier">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button> -->

                                <!-- Bouton Supprimer -->
                                <!-- <button @click="deleteReservation({{ $reservation->id }}, '{{ $reservation->client->prenom }} {{ $reservation->client->nom }}')" class="text-red-500 hover:text-red-700" title="Annuler">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody> -->

            <!-- <tbody class="bg-white divide-y divide-gray-200"> -->
              <!-- Réservation 1 -->
              <!-- <tr class="hover:bg-[#F8F7F4] transition-colors">
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

            </tbody> -->
          </table>

        </div>
      </div>

      <!-- Pagination -->
       <div class="flex items-center justify-between mt-4">
    <div class="text-sm text-gray-500">
        Affichage de {{ ($page - 1) * 5 + 1 }} à {{ min($page * 5, $total) }} sur {{ $total }} résultats
    </div>

    <div class="flex space-x-1">
        {{-- Bouton Précédent --}}
        @if ($page <= 1)
            <span class="px-3 py-1 border border-gray-300 rounded-md text-gray-400 bg-gray-100">Précédent</span>
        @else
            <a href="{{ url('/admin/reservations?page='.($page-1)) }}" class="px-3 py-1 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Précédent</a>
        @endif

        {{-- Numéros de page --}}
        @for($i = 1; $i <= $totalPages; $i++)
            @if($i == $page)
                <span class="px-3 py-1 border border-[#95714F] bg-[#95714F] text-white rounded-md">{{ $i }}</span>
            @else
                <a href="{{ url('/admin/reservations?page='.$i) }}" class="px-3 py-1 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">{{ $i }}</a>
            @endif
        @endfor

        {{-- Bouton Suivant --}}
        @if ($page >= $totalPages)
            <span class="px-3 py-1 border border-gray-300 rounded-md text-gray-400 bg-gray-100">Suivant</span>
        @else
            <a href="{{ url('/admin/reservations?page='.($page+1)) }}" class="px-3 py-1 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Suivant</a>
        @endif
    </div>
</div>




      <!-- <div class="flex items-center justify-between">
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
      </div> -->
    </div>
  </div>

  <!-- Overlay mobile -->
  <div class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
       x-show="sidebarOpen"
       @click="sidebarOpen = false"></div>

  <!-- Modale Ajout de réservation -->
   <!-- Modale Ajout de réservation -->
<!-- <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
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

      <form @submit.prevent="storeReservation" id="addReservationForm" class="space-y-4">
        <div class="border-b border-gray-200 pb-4">
          <h4 class="text-md font-semibold text-[#6d4927] mb-3">Informations client</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="clientSelect" class="block text-sm font-medium text-[#6d4927] mb-1">Client existant</label>
              <select id="clientSelect" name="client_id" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                <option value="">Sélectionner un client</option>
                @foreach($clients as $client)
                  <option value="{{ $client->id }}">{{ $client->prenom }} {{ $client->nom }}</option>
                @endforeach
                <option value="new">+ Nouveau client</option>
              </select>
            </div>
          </div> -->

          <!-- Section pour nouveau client -->
          <!-- <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4" x-show="document.getElementById('clientSelect').value === 'new'">
            <div>
              <label for="prenom" class="block text-sm font-medium text-[#6d4927] mb-1">Prénom</label>
              <input type="text" id="prenom" name="prenom" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
            </div>
            <div>
              <label for="nom" class="block text-sm font-medium text-[#6d4927] mb-1">Nom</label>
              <input type="text" id="nom" name="nom" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
            </div>

            <div>
              <label for="pays" class="block text-sm font-medium text-[#6d4927] mb-1">Pays</label>
              <input type="text" id="pays" name="pays" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
            </div>
            <div>
              <label for="region" class="block text-sm font-medium text-[#6d4927] mb-1">Région</label>
              <input type="text" id="region" name="region" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
            </div>

            <div>
              <label for="numTel" class="block text-sm font-medium text-[#6d4927] mb-1">Téléphone</label>
              <input type="tel" id="numTel" name="numTel" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
            </div>
            <div>
              <label for="typeId" class="block text-sm font-medium text-[#6d4927] mb-1">Type d'identification</label>
              <select id="typeId" name="typeId" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                <option value="CIN">CIN</option>
                <option value="passeport">Passeport</option>
              </select>
            </div>

            <div>
              <label for="CIN" class="block text-sm font-medium text-[#6d4927] mb-1">Numéro CIN</label>
              <input type="text" id="CIN" name="CIN" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
            </div>
            <div>
              <label for="passeport" class="block text-sm font-medium text-[#6d4927] mb-1">Numéro Passeport</label>
              <input type="text" id="passeport" name="passeport" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
            </div>
          </div>
        </div>

        <div class="border-b border-gray-200 pb-4">
          <h4 class="text-md font-semibold text-[#6d4927] mb-3">Détails de la réservation</h4>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
            <div>
              <label for="chambre_id" class="block text-sm font-medium text-[#6d4927] mb-1">Chambre disponible</label>
              <select id="chambre_id" name="chambre_id" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                <option value="">Sélectionner</option>
                @foreach($chambres as $chambre)
                  <option value="{{ $chambre->id }}" data-prix="{{ $chambre->prixNuit }}">Chambre {{ $chambre->NumCh }} - Étage {{ $chambre->NumEtg }} ({{ $chambre->prixNuit }}€)</option>
                @endforeach
              </select>
            </div>
            <div>
              <label for="statut" class="block text-sm font-medium text-[#6d4927] mb-1">Statut</label>
              <select id="statut" name="statut" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                <option value="confirmée">Confirmée</option>
                <option value="en_attente">En attente</option>
                <option value="annulée">Annulée</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
            <div>
              <label for="dateDeb" class="block text-sm font-medium text-[#6d4927] mb-1">Date d'arrivée</label>
              <input type="date" id="dateDeb" name="dateDeb" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]" @change="calculateTotal()">
            </div>
            <div>
              <label for="dateFin" class="block text-sm font-medium text-[#6d4927] mb-1">Date de départ</label>
              <input type="date" id="dateFin" name="dateFin" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]" @change="calculateTotal()">
            </div>
          </div>
        </div>

        <div class="border-b border-gray-200 pb-4">
          <h4 class="text-md font-semibold text-[#6d4927] mb-3">Paiement</h4>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
            <div>
              <label for="totalPayer" class="block text-sm font-medium text-[#6d4927] mb-1">Prix total</label>
              <div class="relative">
                <input type="number" id="totalPayer" name="totalPayer" value="0" class="w-full p-2 pl-8 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]" readonly>
                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">€</span>
              </div>
            </div>
            <div>
              <label for="soldePayer" class="block text-sm font-medium text-[#6d4927] mb-1">Acompte payé</label>
              <div class="relative">
                <input type="number" id="soldePayer" name="soldePayer" value="0" class="w-full p-2 pl-8 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">€</span>
              </div>
            </div>
          </div>

          <div>
            <label for="receptionniste_id" class="block text-sm font-medium text-[#6d4927] mb-1">Réceptionniste</label>
            <select id="receptionniste_id" name="receptionniste_id" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
              <option value="">Sélectionner</option>
              @foreach($receptionnistes as $receptionniste)
                <option value="{{ $receptionniste->id }}">{{ $receptionniste->prenomRec }} {{ $receptionniste->nomRec }}</option>
              @endforeach
            </select>
          </div>
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
</div> -->


  <!-- Modale Modification de réservation -->
  <div x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
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
   <!-- Modale Détails de la réservation -->
 <!-- Modale Voir Détails -->
    <div x-cloak x-show="reservationModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-3xl" @click.away="reservationModal = false">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h2 class="text-2xl font-bold text-[#6d4927]">
                    Détails Réservation <span x-text="currentReservation.id"></span>
                </h2>
                <button @click="reservationModal = false" class="text-gray-500 hover:text-red-600 text-xl">×</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Bloc Réservation -->
                <div class="space-y-2">
                    <h3 class="font-semibold text-[#6d4927] text-lg">Informations réservation</h3>
                    <p><strong>Client :</strong> <span x-text="currentReservation.client"></span></p>
                    <p><strong>Chambre :</strong> <span x-text="currentReservation.chambre"></span></p>
                    <p><strong>Date arrivée :</strong> <span x-text="currentReservation.date_debut"></span></p>
                    <p><strong>Date départ :</strong> <span x-text="currentReservation.date_fin"></span></p>
                    <p><strong>Total payé :</strong> <span x-text="currentReservation.prix_total + ' €'"></span></p>
                </div>

                <!-- Bloc Client -->
                <div class="space-y-2">
                    <h3 class="font-semibold text-[#6d4927] text-lg">Informations client</h3>
                    <p><strong>Pays :</strong> <span x-text="currentReservation.pays ?? '---'"></span></p>
                    <p><strong>Région :</strong> <span x-text="currentReservation.region ?? '---'"></span></p>
                    <p><strong>Identité :</strong> <span x-text="currentReservation.identite ?? '---'"></span></p>
                    <p><strong>Téléphone :</strong> <span x-text="currentReservation.phone"></span></p>
                </div>
            </div>

            <div class="mt-6 text-right">
                <button @click="reservationModal = false"
                    class="bg-[#95714F] text-white px-4 py-2 rounded-lg hover:bg-[#6d4927]">Fermer</button>
            </div>
        </div>
    </div>



  <!-- <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
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
  </div> -->

  <!-- Modale Confirmation d'annulation -->
  <div x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
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
<script>
function reservationsManager() {
  return {
    sidebarOpen: false,
    // addReservationModal: false,
    editReservationModal: false,
    deleteReservationModal: false,
    viewReservationModal: false,
    currentReservation: null,
    chambreModal: false,
    currentChambre: null,
    reservationModal: false,

    // Initialisation des données
    init() {
      // Gestion du changement de client
      document.getElementById('clientSelect').addEventListener('change', function() {
        const isNewClient = this.value === 'new';
        document.querySelectorAll('[x-show="document.getElementById(\'clientSelect\').value === \'new\'"]').forEach(el => {
          if (isNewClient) {
            el.style.display = 'block';
          } else {
            el.style.display = 'none';
          }
        });
      });

      // Gestion du changement de chambre
      document.getElementById('chambre_id').addEventListener('change', this.calculateTotal);
    },

    // Calculer le prix total
    calculateTotal() {
      const chambreSelect = document.getElementById('chambre_id');
      const selectedOption = chambreSelect.options[chambreSelect.selectedIndex];

      if (!selectedOption || selectedOption.value === '') {
        return;
      }

      const prixNuit = parseFloat(selectedOption.getAttribute('data-prix')) || 0;
      const dateDeb = new Date(document.getElementById('dateDeb').value);
      const dateFin = new Date(document.getElementById('dateFin').value);

      if (isNaN(dateDeb.getTime()) || isNaN(dateFin.getTime())) {
        return;
      }

      // Calcul du nombre de nuits
      const timeDiff = Math.abs(dateFin.getTime() - dateDeb.getTime());
      const numberOfNights = Math.ceil(timeDiff / (1000 * 3600 * 24));

      if (numberOfNights > 0) {
        const totalPrice = prixNuit * numberOfNights;
        document.getElementById('totalPayer').value = totalPrice.toFixed(2);
      }
    },

    // viewChambreDetails(chambreId) {
    //     if (!chambreId) {
    //         alert('Aucune chambre associée à cette réservation.');
    //         return;
    //     }

    //     // Redirection vers la page de détails de la chambre
    //     window.location.href = `/admin/chambres/${chambreId}`;
    // },

    // Remplacez la fonction de redirection existante par celle-ci
    // viewChambreDetails(chambreId) {
    //     if (!chambreId) {
    //         alert('Aucune chambre associée à cette réservation.');
    //         return;
    //     }

    //     // Récupérer les détails de la chambre via AJAX
    //     fetch(`/admin/chambres/${chambreId}`)
    //         .then(response => {
    //         if (!response.ok) {
    //             throw new Error('Erreur lors de la récupération des détails de la chambre');
    //         }
    //         return response.json();
    //         })
    //         .then(data => {
    //         // Mettre à jour l'objet currentChambre avec les données reçues
    //         this.currentChambre = data;
    //         // Afficher la modale
    //         this.chambreModal = true;
    //         })
    //         .catch(error => {
    //         console.error('Erreur:', error);
    //         alert('Erreur lors de la récupération des détails de la chambre');
    //         });
    // }

    // viewChambreDetails(chambreId) {
    //     if (!chambreId) {
    //         alert('Aucune chambre associée à cette réservation.');
    //         return;
    //     }

    //     // Récupérer les détails de la chambre via AJAX
    //     fetch(`/admin/chambres/${chambreId}`)  // Modifié pour correspondre à votre route
    //         .then(response => {
    //         if (!response.ok) {
    //             throw new Error('Erreur lors de la récupération des détails de la chambre');
    //         }
    //         return response.json();
    //         })
    //         .then(data => {
    //         // Mettre à jour l'objet currentChambre avec les données reçues
    //         this.currentChambre = data;
    //         // Afficher la modale
    //         this.chambreModal = true;
    //         })
    //         .catch(error => {
    //         console.error('Erreur:', error);
    //         alert('Erreur lors de la récupération des détails de la chambre');
    //         });
    // }



    viewChambreDetails(chambreId) {
        if (!chambreId) {
            alert('Aucune chambre associée à cette réservation.');
            return;
        }

        // Déboguer - afficher l'ID de la chambre
        console.log("Récupération des détails de la chambre ID:", chambreId);

        // Récupérer les détails de la chambre via AJAX
        fetch(`/admin/chambres/${chambreId}`)
            .then(response => {
            // Vérifier si la réponse est OK (statut 200-299)
            if (!response.ok) {
                console.error('Erreur HTTP:', response.status, response.statusText);
                return response.json().then(data => {
                throw new Error(data.error || 'Erreur lors de la récupération des détails de la chambre');
                });
            }
            return response.json();
            })
            .then(data => {
            console.log("Données reçues:", data);
            // Mettre à jour l'objet currentChambre avec les données reçues
            this.currentChambre = data;
            // Afficher la modale
            this.chambreModal = true;
            })
            .catch(error => {
            console.error('Erreur complète:', error);
            alert('Erreur lors de la récupération des détails de la chambre: ' + error.message);
            });
    }

    // Ajouter une réservation
    // storeReservation(event) {
        // event.preventDefault();

        // const form = event.target;
        // const formData = new FormData(form);

        // // Ajouter le token CSRF
        // formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        // // Vérifier si c'est un client existant ou nouveau
        // const clientSelect = document.getElementById('clientSelect');
        // const isNewClient = clientSelect.value === 'new';

        // // Si ce n'est pas un nouveau client, supprimer les champs relatifs au nouveau client
        // if (!isNewClient) {
        //     formData.delete('prenom');
        //     formData.delete('nom');
        //     formData.delete('pays');
        //     formData.delete('region');
        //     formData.delete('numTel');
        //     formData.delete('typeId');
        //     formData.delete('CIN');
        //     formData.delete('passeport');
        // } else {
        //     // Si c'est un nouveau client, supprimer le champ client_id
        //     formData.delete('client_id');
        // }

        // // Afficher les données dans la console pour debug
        // console.log("Données du formulaire:");
        // for (let [key, value] of formData.entries()) {
        //     console.log(`${key}: ${value}`);
        // }

        // fetch('/admin/reservations/store', {
        //     method: 'POST',
        //     headers: {
        //     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        //     'Accept': 'application/json',
        //     'X-Requested-With': 'XMLHttpRequest'
        //     },
        //     body: formData
        // })
        // .then(response => {
        //     if (!response.ok) {
        //     return response.json().then(data => {
        //         if (data.errors) {
        //         // Formater et afficher les erreurs de validation
        //         let errorMsg = "Erreurs de validation:\n";
        //         Object.entries(data.errors).forEach(([field, messages]) => {
        //             errorMsg += `- ${field}: ${messages.join(', ')}\n`;
        //         });
        //         throw new Error(errorMsg);
        //         } else {
        //         throw new Error(data.message || 'Erreur lors de la création de la réservation');
        //         }
        //     });
        //     }
        //     return response.json();
        // })
        // .then(data => {
        //     this.addReservationModal = false;
        //     alert('✅ Réservation créée avec succès!');
        //     window.location.reload();
        // })
        // .catch(error => {
        //     console.error('Erreur:', error);
        //     alert('❌ ' + error.message);
        // });
        // }

    // storeReservation(event) {
    //   event.preventDefault();

    //   const form = event.target;
    //   const formData = new FormData(form);

    //   // Ajouter le token CSRF
    //   formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    //   // Si c'est un nouveau client, gérer différemment
    //   const clientSelect = document.getElementById('clientSelect');
    //   const isNewClient = clientSelect.value === 'new';

    //   if (!isNewClient) {
    //     // Supprimer les champs de nouveau client non nécessaires
    //     formData.delete('prenom');
    //     formData.delete('nom');
    //     formData.delete('pays');
    //     formData.delete('region');
    //     formData.delete('numTel');
    //     formData.delete('typeId');
    //     formData.delete('CIN');
    //     formData.delete('passeport');
    //   } else {
    //     // Supprimer client_id car nous allons créer un nouveau client
    //     formData.delete('client_id');
    //   }

    //   // Afficher les données dans la console pour debug
    //   console.log("Données du formulaire:");
    //   for (let [key, value] of formData.entries()) {
    //     console.log(`${key}: ${value}`);
    //   }

    //   fetch('/admin/reservations/store', {
    //     method: 'POST',
    //     headers: {
    //       'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    //       'Accept': 'application/json',
    //       'X-Requested-With': 'XMLHttpRequest'
    //     },
    //     body: formData
    //   })
    //   .then(response => {
    //     if (!response.ok) {
    //       return response.json().then(data => {
    //         throw new Error(data.message || 'Erreur lors de la création de la réservation');
    //       });
    //     }
    //     return response.json();
    //   })
    //   .then(data => {
    //     this.addReservationModal = false;
    //     alert('✅ Réservation créée avec succès!');
    //     window.location.reload();
    //   })
    //   .catch(error => {
    //     console.error('Erreur:', error);
    //     alert('❌ ' + error.message);
    //   });
    // }
  };
}
</script>
</html>
