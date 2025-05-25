<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi Casa - Disponibilité des chambres</title>
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
    @include('reception.partials.header', ['pageTitle' => 'Disponibilité des chambres'])

    <div class="p-6 md:p-8 pt-20 md:pt-8">
      <!-- Filtres -->
      <div class="bg-white rounded-xl shadow-md p-6 mb-8" x-data="{
        dateDebut: '',
        dateFin: '',
        categorie: '',
        capacite: '',
        filteredChambres: []
      }">
        <h3 class="text-lg font-bold text-[#6d4927] mb-6">Rechercher des chambres disponibles</h3>

        <!-- <form @submit.prevent="checkAvailability" class="grid grid-cols-1 md:grid-cols-4 gap-4"> -->
         <!-- <form method="GET" action="{{ route('reception.chambres.disponibles') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4"> -->
        <form method="GET" action="{{ route('reception.chambres.disponibles') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <!-- <div>
            <label for="date-debut" class="block text-sm font-medium mb-1">Date d'arrivée</label>
            <input
              type="date"
              id="date-debut"
              name="dateDebut"
              :min="new Date().toISOString().split('T')[0]"
              class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
              required
            >
          </div> -->
            <div>
                <label for="date-debut" class="block text-sm font-medium mb-1">Date d'arrivée</label>
                <input
                    type="date"
                    id="date-debut"
                    name="dateDebut"
                    value="{{ request('dateDebut') }}"
                    class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                    required
                >
            </div>

          <!-- <div>
            <label for="date-fin" class="block text-sm font-medium mb-1">Date de départ</label>
            <input
              type="date"
              id="date-fin"
              name="dateFin"
              :min="dateDebut || new Date().toISOString().split('T')[0]"
              class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
              required
            >
          </div> -->
            <div>
                <label for="date-fin" class="block text-sm font-medium mb-1">Date de départ</label>
                <input
                    type="date"
                    id="date-fin"
                    name="dateFin"
                    value="{{ request('dateFin') }}"
                    class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                    required
                >
            </div>

          <div>
            <label for="categorie" class="block text-sm font-medium mb-1">Catégorie</label>
            <select
              name="categorie"
              id="categorie"
              class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
            >
                <option value="">Toutes</option>
                <option value="1" {{ request('categorie') == 1 ? 'selected' : '' }}>Standard</option>
                <option value="2" {{ request('categorie') == 2 ? 'selected' : '' }}>Deluxe</option>
                <option value="3" {{ request('categorie') == 3 ? 'selected' : '' }}>Suite Prestige</option>
            </select>
          </div>

          <div>
            <label for="capacite" class="block text-sm font-medium mb-1">Capacité</label>
            <select
              name="capacite"
              id="capacite"
              class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
            >
                <option value="">Toutes</option>
                <option value="1" {{ request('capacite') == 1 ? 'selected' : '' }}>1 personne</option>
                <option value="2" {{ request('capacite') == 2 ? 'selected' : '' }}>2 personnes</option>
                <option value="3" {{ request('capacite') == 3 ? 'selected' : '' }}>3 personnes</option>
                <option value="4" {{ request('capacite') == 4 ? 'selected' : '' }}>4 personnes</option>
            </select>
          </div>

          <div class="md:col-span-4 flex justify-end">
            <button type="submit" class="px-4 py-2 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors">
              Rechercher
            </button>
          </div>
        </form>
      </div>

      <!-- Liste des chambres -->
      <!-- <div class="bg-white rounded-xl shadow-md p-6" x-data="chambresDisponibles()"> -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <!-- <div class="flex justify-between items-center mb-6">
          <h3 class="text-lg font-bold text-[#6d4927]">Chambres disponibles</h3>
          <span class="text-sm text-gray-500" x-text="'Nombre total: ' + chambres.length"></span>
        </div> -->
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-[#6d4927]">Chambres disponibles</h3>
            <span class="text-sm text-gray-500">Nombre total: {{ count($chambresDisponibles) }}</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- <template x-for="chambre in chambres" :key="chambre.id">
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
              <div class="relative h-48">
                <img :src="'/images/' + chambre.image" :alt="'Chambre ' + chambre.NumCh" class="w-full h-full object-cover">
                <div class="absolute top-0 right-0 p-2 bg-[#95714F] text-white text-sm font-bold">
                  <span x-text="chambre.prixNuit + ' € / nuit'"></span>
                </div>
              </div>
              <div class="p-4">
                <div class="flex justify-between items-center mb-2">
                  <h3 class="text-lg font-bold text-[#6d4927]" x-text="'Chambre ' + chambre.NumCh"></h3>
                  <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                    Disponible
                  </span>
                </div>
                <p class="text-sm text-gray-600 mb-4">
                  <span x-text="'Étage : ' + chambre.NumEtg"></span> |
                  <span x-text="'Capacité : ' + chambre.capacite + ' personne(s)'"></span>
                </p>
                <div class="flex justify-between text-sm mb-4">
                  <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#95714F] mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span x-text="'Catégorie: ' + (chambre.categorie_id == 1 ? 'Standard' : chambre.categorie_id == 2 ? 'Deluxe' : 'Suite Prestige')"></span>
                  </div>
                </div>
                <div class="flex space-x-2">
                  <a
                    :href="'/reception/reservations/create?chambre=' + chambre.id"
                    class="flex-1 bg-[#EADED0] hover:bg-[#C7AF94] text-[#6d4927] text-center py-2 rounded-lg transition-colors text-sm"
                  >
                    Réserver
                  </a>
                </div>
              </div>
            </div>
          </template> -->
          @foreach ($chambresDisponibles as $chambre)
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="relative h-48">
                <img src="{{ asset('images/' . $chambre->image) }}" alt="Chambre {{ $chambre->NumCh }}" class="w-full h-full object-cover">
                <div class="absolute top-0 right-0 p-2 bg-[#95714F] text-white text-sm font-bold">
                    {{ $chambre->prixNuit }} € / nuit
                </div>
                </div>
                <div class="p-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-lg font-bold text-[#6d4927]">Chambre {{ $chambre->NumCh }}</h3>
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Disponible</span>
                </div>
                <p class="text-sm text-gray-600 mb-4">
                    Étage : {{ $chambre->NumEtg }} | Capacité : {{ $chambre->capacite }} personne(s)
                </p>
                <div class="flex justify-between text-sm mb-4">
                    <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#95714F] mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Catégorie :
                        @if($chambre->categorie_id == 1) Standard
                        @elseif($chambre->categorie_id == 2) Deluxe
                        @else Suite Prestige
                        @endif
                    </span>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('reception.reservations.create', [
                        'chambre' => $chambre->id,
                        'date_debut' => request('dateDebut'),
                        'date_fin' => request('dateFin')
                    ]) }}" class="flex-1 bg-[#EADED0] hover:bg-[#C7AF94] text-[#6d4927] text-center py-2 rounded-lg transition-colors text-sm">Réserver</a>
                </div>
                </div>
            </div>
            @endforeach


          <!-- Message si aucune chambre disponible -->
          <div
            x-show="chambres.length === 0"
            class="col-span-full flex justify-center items-center py-16"
          >
            <p class="text-center text-gray-500 text-lg">Aucune chambre disponible pour les critères sélectionnés.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Overlay mobile -->
  <div class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
       x-show="sidebarOpen"
       @click="sidebarOpen = false"></div>

  <script>
    function chambresDisponibles() {
      return {
        // chambres: @json($chambresDisponibles ?? []),
        chambres: JSON.parse(document.getElementById('data-chambres').textContent),
        checkAvailability() {
          const dateDebut = document.getElementById('date-debut').value;
          const dateFin = document.getElementById('date-fin').value;
          const categorie = document.getElementById('categorie').value;
          const capacite = document.getElementById('capacite').value;

          fetch(`/reception/api/chambres/disponibles?dateDebut=${dateDebut}&dateFin=${dateFin}&categorie=${categorie}&capacite=${capacite}`)
            .then(response => response.json())
            .then(data => {
              this.chambres = data;
            })
            .catch(error => {
              console.error('Erreur lors de la recherche de chambres disponibles:', error);
              alert('Une erreur est survenue lors de la recherche. Veuillez réessayer.');
            });
        }
      }
    }
  </script>

  <script type="application/json" id="data-chambres">
  {!! json_encode($chambresDisponibles ?? []) !!}
</script>

</body>
</html>
