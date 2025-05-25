<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi Casa - Gestion des chambres</title>
  <!-- <link rel="stylesheet" href="./output.css"> -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <!-- CDN TailWindCSS -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
  <!-- Ajout du méta tag CSRF -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
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


    [x-cloak] { display: none !important; }
  </style>
</head>

<!-- <body class="bg-[#F8F7F4] text-[#95714F] font-['Times_New_Roman']" x-data="{ sidebarOpen: false, addRoomModal: false, editRoomModal: false, deleteRoomModal: false, currentRoom: null }"> -->
<!-- <body class="bg-[#F8F7F4] text-[#95714F] font-['Times_New_Roman']" x-data="roomEditor()"> -->
<body x-data="roomEditor()" x-init="searchQuery = '', typeFilter = '', statusFilter = ''" class="bg-[#F8F7F4] text-[#95714F] font-['Times_New_Roman']" x-init="editRoomModal = false; deleteRoomModal = false; addRoomModal = false">
  <!-- Sidebar -->
  @include('admin.partials.sidebar_rooms')
  <!-- Main Content -->
  <div class="main-content min-h-screen">
    @include('admin.partials.header_rooms')

      <!-- Liste des chambres -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Chambre  -->
        @foreach ($chambres as $chambre)
        <!-- <div
        x-show="'{{ strtolower($chambre->NumCh) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($chambre->NumEtg) }}'.includes(searchQuery.toLowerCase())"
        > -->
        <!-- <div
            x-show="(
                ('{{ $chambre->NumCh }}'.toLowerCase().includes(searchQuery.toLowerCase()) ||
                '{{ $chambre->NumEtg }}'.toLowerCase().includes(searchQuery.toLowerCase()))
                &&
                (typeFilter === '' || typeFilter == '{{ $chambre->categorie_id }}')
                &&
                (statusFilter === '' || statusFilter == '{{ $chambre->status }}')
            )"
            > -->
            <!-- x-show="(
                ('{{ $chambre->NumCh }}'.toLowerCase().includes(searchQuery.toLowerCase()) ||
                '{{ $chambre->NumEtg }}'.toLowerCase().includes(searchQuery.toLowerCase()))
                &&
                (typeFilter === '' || typeFilter == '{{ $chambre->categorie_id }}')
                &&
                (statusFilter === '' || statusFilter == '{{ $chambre->status }}')
            )" -->
            <div  class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="relative h-48">
            <!-- <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Suite Prestige" class="w-full h-full object-cover"> -->
            <img src="{{ asset('images/' . $chambre->image) }}" alt="{{ $chambre->titre }}" class="w-full h-full object-cover">
                <div class="absolute top-0 right-0 p-2 bg-[#95714F] text-white text-sm font-bold">
                {{ $chambre->prixNuit }}€ / nuit
                </div>
            </div>
            <div class="p-4">
                <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-bold text-[#6d4927]">Chambre {{ $chambre->NumCh }}</h3>
                <span class="px-2 py-1 text-xs rounded-full
                    {{ $chambre->status == 1 ? 'bg-green-100 text-green-800' : ($chambre->status == 2 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                    {{ $chambre->status == 1 ? 'Disponible' : ($chambre->status == 2 ? 'Occupée' : 'Maintenance') }}
                </span>
                </div>
                <!-- <p class="text-sm text-gray-600 mb-4">Étage : {{ $chambre->NumEtg }} | Capacité : {{ $chambre->capacite }} personne(s)</p> -->
                <p class="text-sm text-gray-600 mb-4">Étage : {{ $chambre->NumEtg }}</p>
                <div class="flex justify-between text-sm mb-4">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#95714F] mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Catégorie ID : {{ $chambre->categorie_id }}</span>
                </div>
                <!-- <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#95714F] mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                    <span>45m²</span>
                </div> -->
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#95714F] mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>{{ $chambre->capacite }} pers.</span>
                </div>
                </div>
                <div class="flex space-x-2">
                <!-- <button @click="currentRoom = {id: '101', name: 'Suite Prestige 101', type: 'suite', price: '189', status: 'available'}; editRoomModal = true" class="flex-1 bg-[#EADED0] hover:bg-[#C7AF94] text-[#6d4927] text-center py-2 rounded-lg transition-colors text-sm">
                    Modifier
                </button> -->

                    <button
                        @click="currentRoom = {
                            id: {{ $chambre->id }},
                            NumCh: '{{ $chambre->NumCh }}',
                            NumEtg: '{{ $chambre->NumEtg }}',
                            status: '{{ $chambre->status }}',
                            prixNuit: '{{ $chambre->prixNuit }}',
                            categorie_id: '{{ $chambre->categorie_id }}',
                            capacite: '{{ $chambre->capacite }}',
                            name: '{{ $chambre->titre }}',
                            image: '{{ $chambre->image ?? '' }}'
                        }; editRoomModal = true"
                        class="flex-1 bg-[#EADED0] hover:bg-[#C7AF94] text-[#6d4927] text-center py-2 rounded-lg transition-colors text-sm"
                    >
                        Modifier
                    </button>


                <!-- <button @click="currentRoom = {id: '101', name: 'Suite Prestige 101'}; deleteRoomModal = true" class="flex-1 border border-red-300 hover:bg-red-50 text-red-600 text-center py-2 rounded-lg transition-colors text-sm">
                    Supprimer
                </button> -->
                    <!-- <button type="button" @click="deleteRoom" class="flex-1 border border-red-300 hover:bg-red-50 text-red-600 text-center py-2 rounded-lg transition-colors text-sm">
                    Supprimer
                    </button> -->
                    <!-- <button
                        type="button"
                        @click="currentRoom = {
                            id: {{ $chambre->id }},
                            name: '{{ $chambre->titre }}'
                        }; deleteRoomModal = true"
                        class="flex-1 border border-red-300 hover:bg-red-50 text-red-600 text-center py-2 rounded-lg transition-colors text-sm"
                        >
                        Supprimer
                    </button> -->
                    <button
                        @click="currentRoom = {
                            id: {{ $chambre->id }},
                            name: '{{ $chambre->titre }}'
                        }; deleteRoomModal = true"
                        class="flex-1 border border-red-300 hover:bg-red-50 text-red-600 text-center py-2 rounded-lg transition-colors text-sm"
                    >
                        Supprimer
                    </button>



                </div>
                <!-- <div class="flex space-x-2">
                    <button class="flex-1 bg-[#EADED0] hover:bg-[#C7AF94] text-[#6d4927] py-2 rounded-lg transition-colors text-sm">
                        Modifier
                    </button>
                    <button class="flex-1 border border-red-300 hover:bg-red-50 text-red-600 py-2 rounded-lg transition-colors text-sm">
                        Supprimer
                    </button>
                </div> -->
            </div>
            <!-- </div> -->
        </div>
        @endforeach
        <!-- <div x-show="!Array.from($el.parentElement.children).some(el => el.style.display !== 'none')" class="col-span-full text-center text-gray-500 py-8">
            Aucune chambre ne correspond à votre recherche.
        </div> -->
        <!-- <div x-show="filteredCount === 0 && searchTriggered" class="text-center text-gray-500 col-span-1 md:col-span-2 lg:col-span-3"> -->
        <!-- <div class="text-center text-gray-500 col-span-1 md:col-span-2 lg:col-span-3">
            Aucune chambre trouvée.
        </div> -->
        @if ($chambres->isEmpty())
        <div class="col-span-full flex justify-center items-center py-16">
            <p class="text-center text-gray-500 text-lg">Aucune chambre trouvée.</p>
        </div>
        @endif
        </div>




  <!-- Overlay mobile -->
  <div class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
       x-show="sidebarOpen"
       @click="sidebarOpen = false"></div>

  <!-- Modale Ajout de chambre -->
  <div x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
       x-show="addRoomModal"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0">
    <div class="bg-white rounded-xl shadow-xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto"
         x-show="addRoomModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         @click.away="addRoomModal = false">
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-bold text-[#6d4927]">Ajouter une chambre</h3>
          <button type="button" @click="storeRoom" class="text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <form @submit.prevent="storeRoom" enctype="multipart/form-data" class="space-y-4">
            <!-- Ligne 1 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                <label for="room-number" class="block text-sm font-medium text-[#6d4927] mb-1">Numéro de chambre</label>
                <input type="text" id="room-number" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                </div>

                <div>
                <label for="room-etg" class="block text-sm font-medium text-[#6d4927] mb-1">Numéro d'étage</label>
                <input type="number" id="room-etg" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                </div>
            </div>

             <!-- Ligne 2 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                <label for="room-type" class="block text-sm font-medium text-[#6d4927] mb-1">Type de chambre</label>
                <select id="room-type" class="w-full p-2 border border-[#C7AF94] rounded-lg">
                    <option value="1">Standard</option>
                    <option value="2">Deluxe</option>
                    <option value="3">Suite Prestige</option>
                </select>
                </div>

                <div>
                <label for="room-status" class="block text-sm font-medium text-[#6d4927] mb-1">Statut</label>
                <select id="room-status" class="w-full p-2 border border-[#C7AF94] rounded-lg">
                    <option value="1">Disponible</option>
                    <option value="2">Occupée</option>
                    <option value="0">En maintenance</option>
                </select>
                </div>
            </div>

            <!-- Ligne 3 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                <label for="room-price" class="block text-sm font-medium text-[#6d4927] mb-1">Prix par nuit</label>
                <input type="number" id="room-price" class="w-full p-2 border border-[#C7AF94] rounded-lg">
                </div>

                <div>
                <label for="room-capacity" class="block text-sm font-medium text-[#6d4927] mb-1">Capacité</label>
                <select id="room-capacity" class="w-full p-2 border border-[#C7AF94] rounded-lg">
                    <option value="1">1 personne</option>
                    <option value="2">2 personnes</option>
                    <option value="3">3 personnes</option>
                    <option value="4">4 personnes</option>
                </select>
                </div>
            </div>

            <!-- Image -->
            <!-- <div>
                <label for="file-upload" class="block text-sm font-medium text-[#6d4927] mb-1">Photos</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-[#C7AF94] rounded-lg">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                    <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-[#95714F] hover:text-[#6d4927]">
                        <span>Télécharger des fichiers</span>
                        <input id="file-upload" name="image" type="file" class="sr-only">
                    </label>
                    <p class="pl-1">ou glisser-déposer</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF jusqu'à 10MB</p>
                </div>
                </div>
            </div> -->
            <div>
                <label for="image-name" class="block text-sm font-medium text-[#6d4927] mb-1">Nom de l’image</label>
                <input type="text" id="image-name" name="image" placeholder="Ex: chambre_1.jpg" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
            </div>

            <!-- Boutons -->
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" @click="addRoomModal = false" class="px-4 py-2 border border-[#C7AF94] text-[#95714F] rounded-lg hover:bg-[#EADED0] transition-colors">
                Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors">
                Ajouter la chambre
                </button>
            </div>
        </form>

      </div>
    </div>
  </div>

  <!-- Modale Modification de chambre -->
<div x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
     x-show="editRoomModal"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
  <div class="bg-white rounded-xl shadow-xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto"
       x-show="editRoomModal"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0 transform scale-95"
       x-transition:enter-end="opacity-100 transform scale-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100 transform scale-100"
       x-transition:leave-end="opacity-0 transform scale-95"
       @click.away="editRoomModal = false">
    <div class="p-6">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-[#6d4927]" x-text="'Modifier ' + (currentRoom ? (currentRoom.name || 'Chambre ' + currentRoom.NumCh) : '')"></h3>
        <button @click="editRoomModal = false" class="text-gray-400 hover:text-gray-600">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

        <form method="POST" action="{{ route('admin.chambres.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="id" :value="currentRoom ? currentRoom.id : ''">

            <!-- Numéro de chambre -->
            <div>
            <label class="block text-sm font-medium text-[#6d4927] mb-1">Numéro de chambre</label>
            <input type="text" name="NumCh" class="w-full p-2 border border-[#C7AF94] rounded-lg"
                    :value="currentRoom ? currentRoom.NumCh : ''">
            </div>

            <!-- Étage -->
            <div>
            <label class="block text-sm font-medium text-[#6d4927] mb-1">Numéro d'étage</label>
            <input type="number" name="NumEtg" class="w-full p-2 border border-[#C7AF94] rounded-lg"
                    :value="currentRoom ? currentRoom.NumEtg : ''">
            </div>

            <!-- Statut -->
            <div>
            <label class="block text-sm font-medium text-[#6d4927] mb-1">Statut</label>
            <select name="status" class="w-full p-2 border border-[#C7AF94] rounded-lg">
                <option value="1" x-bind:selected="currentRoom && currentRoom.status == 1">Disponible</option>
                <option value="2" x-bind:selected="currentRoom && currentRoom.status == 2">Occupée</option>
                <option value="0" x-bind:selected="currentRoom && currentRoom.status == 0">Maintenance</option>
            </select>
            </div>

            <!-- Prix par nuit -->
            <div>
            <label class="block text-sm font-medium text-[#6d4927] mb-1">Prix par nuit (€)</label>
            <input type="number" name="prixNuit" class="w-full p-2 border border-[#C7AF94] rounded-lg"
                    :value="currentRoom ? (currentRoom.prixNuit || currentRoom.price) : ''">
            </div>

            <!-- ID Catégorie -->
            <div>
            <label class="block text-sm font-medium text-[#6d4927] mb-1">Catégorie</label>
            <input type="number" name="categorie_id" class="w-full p-2 border border-[#C7AF94] rounded-lg"
                    :value="currentRoom ? currentRoom.categorie_id : ''">
            </div>

            <!-- Capacité -->
            <div>
            <label class="block text-sm font-medium text-[#6d4927] mb-1">Capacité</label>
            <input type="number" name="capacite" class="w-full p-2 border border-[#C7AF94] rounded-lg"
                    :value="currentRoom ? currentRoom.capacite : ''">
            </div>

            <!-- Image -->
            <div>
            <label class="block text-sm font-medium text-[#6d4927] mb-1">Nom de l'image</label>
            <input type="text" name="image" placeholder="ex: chambre_1.jpg" class="w-full p-2 border border-[#C7AF94] rounded-lg"
                    :value="currentRoom ? currentRoom.image : ''">
            </div>

            <!-- Boutons -->
            <div class="flex justify-end space-x-3 pt-4">
            <button type="button" @click="editRoomModal = false" class="px-4 py-2 border border-[#C7AF94] text-[#95714F] rounded-lg hover:bg-[#EADED0]">
                Annuler
            </button>
            <button type="submit" class="px-4 py-2 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927]">
                Enregistrer
            </button>
            </div>
        </form>
    </div>
  </div>
</div>

  <!-- Modale Confirmation de suppression -->
  <div x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
       x-show="deleteRoomModal"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4"
         x-show="deleteRoomModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         @click.away="deleteRoomModal = false">
      <div class="p-6">
        <div class="text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Confirmez la suppression</h3>
          <p class="text-gray-600 mb-6" x-text="'Êtes-vous sûr de vouloir supprimer ' + (currentRoom ? currentRoom.name : '') + ' ? Cette action est irréversible.'"></p>

          <div class="flex justify-center space-x-3">
            <button type="button" @click="deleteRoomModal = false" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
              Annuler
            </button>
            <button type="button" @click="deleteRoom" class="px-4 py-2 border border-transparent rounded-lg text-white bg-red-600 hover:bg-red-700">
                Supprimer
            </button>

          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    function roomEditor() {
        return {
            sidebarOpen: false,
            addRoomModal: false,
            editRoomModal: false,
            deleteRoomModal: false,
            currentRoom: null,
            searchQuery: '',
            searchTriggered: false,
            // updateRoom() {
            //     console.log('Début de updateRoom avec:', this.currentRoom);

            //     if (!this.currentRoom || !this.currentRoom.id) {
            //         alert('Erreur: Impossible de trouver les informations de la chambre');
            //         console.error('currentRoom invalide:', this.currentRoom);
            //         return;
            //     }

            //     const formData = new FormData();
            //     formData.append('_method', 'PUT');
            //     formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            //     // Assurer la cohérence des noms de champs
            //     formData.append('NumCh', this.currentRoom.NumCh);
            //     formData.append('NumEtg', this.currentRoom.NumEtg);
            //     formData.append('status', this.currentRoom.status);
            //     formData.append('prixNuit', this.currentRoom.price || this.currentRoom.prixNuit); // Accepter les deux noms
            //     formData.append('categorie_id', this.currentRoom.categorie_id);
            //     formData.append('capacite', this.currentRoom.capacite);

            //     // Ajouter l'image si elle existe
            //     if (this.currentRoom.image && this.currentRoom.image.trim() !== '') {
            //         formData.append('image', this.currentRoom.image);
            //     }

            //     // Log des données envoyées
            //     console.log('Données envoyées:');
            //     for (let pair of formData.entries()) {
            //         console.log(pair[0] + ': ' + pair[1]);
            //     }

            //     // Faire la requête avec l'ID correct
            //     const url = `/admin/chambres/${this.currentRoom.id}`;
            //     console.log('URL de requête:', url);

            //     fetch(url, {
            //         method: 'POST',
            //         body: formData
            //     })
            //     .then(response => {
            //         console.log('Statut de réponse:', response.status);
            //         if (!response.ok) {
            //             return response.text().then(text => {
            //                 throw new Error('Erreur serveur: ' + text);
            //             });
            //         }
            //         return response.text();
            //     })
            //     .then(data => {
            //         console.log('Réponse:', data);
            //         this.editRoomModal = false;

            //         // Afficher un message de succès
            //         alert('✅ Chambre modifiée avec succès');

            //         // Recharger la page pour voir les changements
            //         setTimeout(() => window.location.reload(), 300);
            //     })
            //     .catch(error => {
            //         console.error('Erreur:', error);
            //         alert('❌ Erreur lors de la mise à jour: ' + error.message);
            //     });
            // },

//             updateRoom() {
//     if (!this.currentRoom || !this.currentRoom.id) {
//         alert('Erreur: Impossible de trouver les informations de la chambre');
//         return;
//     }

//     const formData = new FormData();
//     formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
//     formData.append('_method', 'PUT'); // Simuler une requête PUT
//     formData.append('id', this.currentRoom.id); // Ajouter l'ID de la chambre

//     // Récupérer les valeurs des champs
//     formData.append('NumCh', this.currentRoom.NumCh);
//     formData.append('NumEtg', this.currentRoom.NumEtg);
//     formData.append('status', this.currentRoom.status);
//     formData.append('prixNuit', this.currentRoom.prixNuit || this.currentRoom.price);
//     formData.append('categorie_id', this.currentRoom.categorie_id);
//     formData.append('capacite', this.currentRoom.capacite);

//     // Ajouter l'image si elle existe
//     if (this.currentRoom.image) {
//         formData.append('image', this.currentRoom.image);
//     }

//     // Faire la requête avec la nouvelle route
//     fetch('/admin/chambres/update', {
//         method: 'POST',
//         headers: {
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//         },
//         body: formData
//     })
//     .then(response => {
//         if (!response.ok) {
//             throw new Error('Erreur lors de la mise à jour');
//         }
//         return response.text();
//     })
//     .then(() => {
//         this.editRoomModal = false;
//         // Afficher un message de succès
//         alert('✅ Chambre modifiée avec succès');
//         // Recharger la page pour voir les changements
//         setTimeout(() => window.location.reload(), 10);
//     })
//     .catch(error => {
//         console.error('Erreur:', error);
//         alert('❌ Erreur lors de la mise à jour: ' + error.message);
//     });
// }
// ********************* Code qui marche que si je fais un filtre *********************
            updateRoom() {
                if (!this.currentRoom || !this.currentRoom.id) {
                    alert('Erreur: Impossible de trouver les informations de la chambre');
                    return;
                }

                const formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');

                // Récupérer les valeurs des champs
                const numChInput = document.querySelector('input[name=NumCh]');
                const numEtgInput = document.querySelector('input[name=NumEtg]');
                const statusSelect = document.querySelector('select[name=status]');
                const prixNuitInput = document.querySelector('input[name=prixNuit]');
                const categorieInput = document.querySelector('input[name=categorie_id]');
                const capaciteInput = document.querySelector('input[name=capacite]');
                const imageInput = document.querySelector('input[name=image]');

                // Vérifier que tous les champs requis existent
                if (!numChInput || !numEtgInput || !statusSelect || !prixNuitInput || !categorieInput || !capaciteInput) {
                    alert('Erreur: Certains champs obligatoires sont manquants');
                    return;
                }

                // Ajouter les données au formData
                formData.append('NumCh', numChInput.value);
                formData.append('NumEtg', numEtgInput.value);
                formData.append('status', statusSelect.value);
                formData.append('prixNuit', prixNuitInput.value);
                formData.append('categorie_id', categorieInput.value);
                formData.append('capacite', capaciteInput.value);

                // Ajouter l'image si elle existe et n'est pas vide
                if (imageInput && imageInput.value.trim() !== '') {
                    formData.append('image', imageInput.value.trim());
                }

                // Faire la requête AJAX
                // fetch(`/admin/chambres/${this.currentRoom.id}`, {
                fetch(`/admin/chambres/update`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(res => {
                    if (!res.ok) throw new Error('Erreur serveur: ' + res.status);
                    return res.text();
                })
                .then(() => {
                    // Fermer les modales et rafraîchir la page
                    this.resetUI();
                    // Attendre un court instant avant de rafraîchir
                    setTimeout(() => location.reload(), 10);
                })
                .catch((error) => {
                    console.error('Erreur lors de la mise à jour:', error);
                    alert('❌ Erreur lors de la mise à jour: ' + error.message);
                });
            },

            deleteRoom() {
                fetch(`/admin/chambres/${this.currentRoom.id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: new URLSearchParams({
                        '_method': 'DELETE'
                    })
                })
                .then(res => {
                    if (!res.ok) throw new Error('Erreur serveur');
                    return res.text();
                })
                .then(() => {
                    this.resetUI();
                    setTimeout(() => location.reload(), 10);
                })
                .catch(() => alert('❌ Erreur lors de la suppression'));
            },

            storeRoom() {
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('NumCh', document.querySelector('#room-number').value);
                formData.append('NumEtg', document.querySelector('#room-etg').value); // à ajouter si pas existant
                formData.append('prixNuit', document.querySelector('#room-price').value);
                formData.append('capacite', document.querySelector('#room-capacity').value);
                formData.append('status', document.querySelector('#room-status').value);
                formData.append('categorie_id', 1); // à adapter dynamiquement

                // const imageInput = document.querySelector('#file-upload');
                // if (imageInput && imageInput.files[0]) {
                //     formData.append('image', imageInput.files[0]);
                // }
                formData.append('image', document.querySelector('#image-name').value);

                fetch('/admin/chambres', {
                    method: 'POST',
                    body: formData
                })
                .then(res => {
                    if (!res.ok) throw new Error('Erreur serveur');
                    return res.text();
                })
                .then(() => {
                    this.addRoomModal = false;
                    this.currentRoom = null;
                    setTimeout(() => location.reload(), 10);
                })
                .catch(() => alert('❌ Erreur lors de l\'ajout de la chambre'));
            },
            //Pour appliquer le filtre.
            applyFilters() {
                this.searchQuery = this.searchQuery.trim(); // pour nettoyer l’espace
                this.searchTriggered = true;
            },
            get filteredCount() {
                return document.querySelectorAll('[x-show]').length - document.querySelectorAll('[x-show="false"]').length;
            },


            resetUI() {
                this.sidebarOpen = false;
                this.editRoomModal = false;
                this.deleteRoomModal = false;
                this.addRoomModal = false;
                this.currentRoom = null;
            }
        }


    }
</script>





</body>
</html>
