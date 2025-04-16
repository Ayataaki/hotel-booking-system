@extends('layouts.app')

@section('title', 'Nos Chambres')

@section('content')
<!-- Hero Section -->
<section
  class="relative h-[60vh] bg-cover bg-center flex items-center justify-center"
  style="
    background-image: url('{{ asset('images/chambre_7.jpg') }}');
  "
>
  <div class="absolute inset-0 bg-black/40"></div>
  <div class="relative z-10 text-center text-white px-4">
    <h1 class="text-5xl font-bold" data-aos="fade-up">Nos Chambres</h1>
    <p class="text-lg mt-2" data-aos="fade-up" data-aos-delay="100">
      Confort élégant, design raffiné et vue exceptionnelle
    </p>
  </div>
</section>

<!-- Section des chambres améliorée -->
<!-- Section des chambres améliorée avec hauteur fixe -->
<section
  class="py-20 px-4 max-w-[100%] mx-auto mt-24 overflow-hidden card"
  x-data="{
  rooms: [
    {
      name: 'Chambre Deluxe',
      type: 'Deluxe',
      description: 'Lit king-size, vue panoramique, Wi-Fi, TV connectée, cafetière, climatisation.',
      price: '129€/nuit',
      rating: 4,
      features: ['King-size bed', 'Vue panoramique', 'Climatisation', 'Mini-bar', 'Wi-Fi premium'],
      image: '/images/chambre_standard_1.jpg',
      showDetails: false
    },
    {
      name: 'Suite Prestige',
      type: 'Prestige',
      description: 'Salon, baignoire marbre, mini-bar, balcon avec vue mer, dressing.',
      price: '189€/nuit',
      rating: 5,
      features: ['Salon privé', 'Baignoire en marbre', 'Vue sur mer', 'Dressing', 'Service en chambre 24/7'],
      image: '/images/chambre_prestige_1.jpg',
      showDetails: false
    },
    {
      name: 'Chambre Standard',
      type: 'Standard',
      description: 'Lit confortable, Wi-Fi, climatisation, espace fonctionnel, très bon rapport qualité/prix.',
      price: '99€/nuit',
      rating: 3,
      features: ['Lit queen-size', 'Wi-Fi gratuit', 'Climatisation', 'Bureau de travail', 'Petit-déjeuner inclus'],
      image: '/images/chambre_standard_2.jpg',
      showDetails: false
    },
    {
      name: 'Chambre Standard',
      type: 'Standard',
      description: 'Lit confortable, Wi-Fi, climatisation, espace fonctionnel, très bon rapport qualité/prix.',
      price: '99€/nuit',
      rating: 3,
      features: ['Lit queen-size', 'Wi-Fi gratuit', 'Climatisation', 'Bureau de travail', 'Petit-déjeuner inclus'],
      image: '/images/chambre_standard_2.jpg',
      showDetails: false
    }
  ],
  selectedRoom: null,
  isComparing: false,

  toggleDetails(index) {
    // Juste inverser l'état des détails pour cette chambre sans affecter les autres
    this.rooms[index].showDetails = !this.rooms[index].showDetails;
  },

  openRoomModal(index) {
    this.selectedRoom = index;
  },

  closeRoomModal() {
    this.selectedRoom = null;
  },

  toggleCompare() {
    this.isComparing = !this.isComparing;
  }
}"
>
  <h2
    class="text-3xl font-bold text-center text-[#6d4927] mb-6"
    data-aos="fade-up"
  >
    Choisissez votre expérience
  </h2>
  <p
    class="text-center text-gray-600 max-w-2xl mx-auto mb-16"
    data-aos="fade-up"
    data-aos-delay="100"
  >
    Découvrez nos chambres soigneusement conçues pour votre confort et votre
    plaisir. Chaque espace est unique et offre une expérience personnalisée.
  </p>

  <!-- Options de filtre et de tri -->
  <div
    class="flex flex-wrap justify-center gap-4 mb-10"
    data-aos="fade-up"
    data-aos-delay="150"
  >
    <button
      class="px-4 py-2 bg-white border border-[#95714F] text-[#95714F] rounded-full hover:bg-[#95714F] hover:text-white transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-[#95714F] focus:ring-opacity-50 active:bg-[#6d4927]"
    >
      Toutes les chambres
    </button>
    <button
      class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-full hover:border-[#95714F] hover:text-[#95714F] transition-colors duration-300 focus:outline-none"
    >
      Standard
    </button>
    <button
      class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-full hover:border-[#95714F] hover:text-[#95714F] transition-colors duration-300 focus:outline-none"
    >
      Deluxe
    </button>
    <button
      class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-full hover:border-[#95714F] hover:text-[#95714F] transition-colors duration-300 focus:outline-none"
    >
      Prestige
    </button>
    <button
      @click="toggleCompare"
      class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-full hover:border-[#95714F] hover:text-[#95714F] transition-colors duration-300 focus:outline-none flex items-center"
    >
      <span
        x-text="isComparing ? 'Annuler la comparaison' : 'Comparer les chambres'"
      ></span>
    </button>
  </div>

  <!-- Grille des chambres -->
  <div class="grid md:grid-cols-3 gap-12 max-w-[100%] card">
    <template x-for="(room, index) in rooms" :key="index">
      <div
        class="bg-white shadow-xl rounded-2xl overflow-hidden transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl h-[520px]"
        :class="{'ring-4 ring-[#95714F]': isComparing}"
        data-aos="fade-up"
        :data-aos-delay="index * 100"
      >
        <!-- Image et badge -->
        <div
          class="relative overflow-hidden group cursor-pointer h-64"
          @click="openRoomModal(index)"
        >
          <img
            :src="room.image"
            :alt="room.name"
            class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
          />
          <div
            class="absolute inset-0 bg-black bg-opacity-30 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center"
          >
            <span class="text-white font-semibold text-lg"
              >Voir en détail</span
            >
          </div>
          <div
            class="absolute top-4 right-4 bg-[#95714F] text-white text-xs font-semibold px-3 py-1 rounded-full shadow"
            x-text="room.type"
          ></div>
        </div>

        <!-- Contenu avec hauteur fixe et défilement -->
        <div class="p-6 flex flex-col h-[256px]">
          <h3
            class="text-xl font-bold text-[#95714F]"
            x-text="room.name"
          ></h3>

          <!-- Zone de contenu avec défilement si nécessaire -->
          <div
            class="flex-grow overflow-y-auto my-2"
            style="scrollbar-width: thin"
          >
            <!-- Description toujours visible -->
            <p class="text-sm text-gray-600" x-text="room.description"></p>

            <!-- Caractéristiques (visibles uniquement quand showDetails est true) -->
            <div
              x-show="room.showDetails"
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0"
              x-transition:enter-end="opacity-100"
              x-transition:leave="transition ease-in duration-300"
              x-transition:leave-start="opacity-100"
              x-transition:leave-end="opacity-0"
              class="mt-3"
            >
              <h4 class="font-medium text-[#6d4927] mb-2">
                Caractéristiques:
              </h4>
              <ul class="space-y-1">
                <template x-for="(feature, i) in room.features" :key="i">
                  <li class="text-sm text-gray-600 flex items-center">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-4 w-4 text-[#95714F] mr-2 flex-shrink-0"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    <span x-text="feature"></span>
                  </li>
                </template>
              </ul>
            </div>
          </div>

          <!-- Prix et notation (toujours visibles en bas) -->
          <div
            class="flex items-center justify-between mt-auto pt-2 border-t border-gray-100"
          >
            <div class="flex text-yellow-500">
              <template x-for="i in room.rating">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                  />
                </svg>
              </template>
              <template x-for="i in (5 - room.rating)">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4 text-gray-300"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                  />
                </svg>
              </template>
            </div>
            <span
              class="text-sm font-medium text-[#6d4927]"
              x-text="room.price"
            ></span>
          </div>

          <!-- Boutons d'action (toujours visibles en bas) -->
          <div class="flex gap-2 mt-4">
            <button
              @click="toggleDetails(index)"
              class="flex-1 text-center text-[#95714F] border border-[#95714F] hover:bg-[#95714F] hover:text-white font-medium py-2 px-2 rounded-lg transition-colors duration-300"
            >
              <span
                x-text="room.showDetails ? 'Masquer' : 'Détails'"
              ></span>
            </button>
            <a
              href="{{ route('reservation') }}"
              class="flex-1 text-center bg-[#95714F] hover:bg-[#6d4927] text-white font-medium py-2 px-2 rounded-lg transition-colors duration-300"
              >Réserver</a>
          </div>
        </div>
      </div>
    </template>
  </div>

  <!-- Modale pour afficher les détails d'une chambre -->
  <div
    x-show="selectedRoom !== null"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 max-w-[100%]"
    @click.self="selectedRoom = null"
  >
    <div
      x-show="selectedRoom !== null"
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0 transform scale-90"
      x-transition:enter-end="opacity-100 transform scale-100"
      class="bg-white rounded-xl overflow-hidden max-w-4xl w-full max-h-[90vh] flex flex-col md:flex-row shadow-2xl"
    >
      <!-- Galerie d'images (simulée) -->
      <div class="w-full md:w-1/2 relative h-64 md:h-auto">
        <template x-if="selectedRoom !== null">
          <img
            :src="rooms[selectedRoom]?.image"
            :alt="rooms[selectedRoom]?.name"
            class="w-full h-full object-cover"
          />
        </template>
        <!-- Bouton de fermeture -->
        <button
          @click="selectedRoom = null"
          class="absolute top-4 right-4 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 text-gray-800"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>

      <!-- Informations détaillées -->
      <div class="w-full md:w-1/2 p-6 overflow-y-auto max-w-[100%]">
        <template x-if="selectedRoom !== null">
          <div>
            <div class="flex items-center justify-between mb-4">
              <h3
                class="text-2xl font-bold text-[#95714F]"
                x-text="rooms[selectedRoom]?.name"
              ></h3>
              <span
                class="px-3 py-1 bg-[#95714F] text-white text-sm font-semibold rounded-full"
                x-text="rooms[selectedRoom]?.type"
              ></span>
            </div>

            <div class="flex items-center mb-4">
              <div class="flex text-yellow-500 mr-2">
                <template x-for="i in rooms[selectedRoom]?.rating">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                  >
                    <path
                      d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                    />
                  </svg>
                </template>
                <template x-for="i in (5 - rooms[selectedRoom]?.rating)">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 text-gray-300"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                  >
                    <path
                      d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                    />
                  </svg>
                </template>
              </div>
              <span class="text-gray-600"
                >Recommandée par 93% de nos clients</span
              >
            </div>

            <p
              class="text-gray-600 mb-6"
              x-text="rooms[selectedRoom]?.description"
            ></p>

            <div class="mb-6">
              <h4 class="font-semibold text-[#6d4927] mb-3">
                Équipements et services:
              </h4>
              <ul class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <template
                  x-for="(feature, i) in rooms[selectedRoom]?.features"
                  :key="i"
                >
                  <li class="flex items-center text-gray-700">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5 text-[#95714F] mr-2"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    <span x-text="feature"></span>
                  </li>
                </template>
              </ul>
            </div>

            <div class="border-t border-gray-200 pt-4 mb-6">
              <div class="flex items-center justify-between mb-2">
                <span class="text-gray-600">Prix par nuit:</span>
                <span
                  class="text-lg font-bold text-[#6d4927]"
                  x-text="rooms[selectedRoom]?.price"
                ></span>
              </div>
              <div class="text-sm text-gray-500">
                Taxes incluses, petit-déjeuner non inclus
              </div>
            </div>

            <div class="flex space-x-4">
                <a
                href="{{ route('reservation') }}"
                class="flex-1 text-center bg-[#95714F] hover:bg-[#6d4927] text-white font-semibold py-3 rounded-lg transition-colors duration-300"
              >
                Réserver maintenant
              </a>
              <button
                class="px-4 bg-white border border-[#95714F] text-[#95714F] hover:bg-gray-50 rounded-lg flex items-center justify-center transition-colors duration-300"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-5 w-5"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                  />
                </svg>
              </button>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>

  <!-- Tableau comparatif des chambres (apparaît lorsque la comparaison est activée) -->
  <div
    x-show="isComparing"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-4"
    class="mt-16 overflow-x-auto rounded-xl shadow-lg max-w-[100%] flex flex-wrap overflow-hidden"
  >
    <table class="w-full bg-white">
      <thead>
        <tr class="bg-[#95714F] text-white">
          <th class="py-3 px-4 text-left">Caractéristiques</th>
          <template x-for="(room, index) in rooms" :key="index">
            <th class="py-3 px-4 text-left" x-text="room.name"></th>
          </template>
        </tr>
      </thead>
      <tbody>
        <!-- Type -->
        <tr class="border-b border-gray-200">
          <td class="py-3 px-4 bg-gray-50 font-medium">Type</td>
          <template x-for="(room, index) in rooms" :key="index">
            <td class="py-3 px-4" x-text="room.type"></td>
          </template>
        </tr>

        <!-- Prix -->
        <tr class="border-b border-gray-200">
          <td class="py-3 px-4 bg-gray-50 font-medium">Prix</td>
          <template x-for="(room, index) in rooms" :key="index">
            <td class="py-3 px-4" x-text="room.price"></td>
          </template>
        </tr>

        <!-- Notation -->
        <tr class="border-b border-gray-200">
          <td class="py-3 px-4 bg-gray-50 font-medium">Notation</td>
          <template x-for="(room, index) in rooms" :key="index">
            <td class="py-3 px-4">
              <div class="flex text-yellow-500">
                <template x-for="i in room.rating">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                  >
                    <path
                      d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                    />
                  </svg>
                </template>
                <template x-for="i in (5 - room.rating)">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 text-gray-300"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                  >
                    <path
                      d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                    />
                  </svg>
                </template>
              </div>
            </td>
          </template>
        </tr>

        <!-- Caractéristiques -->
        <tr>
          <td class="py-3 px-4 bg-gray-50 font-medium">Équipements</td>
          <template x-for="(room, index) in rooms" :key="index">
            <td class="py-3 px-4">
              <ul class="space-y-1">
                <template x-for="(feature, i) in room.features" :key="i">
                  <li class="text-sm text-gray-700 flex items-start">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-4 w-4 text-[#95714F] mr-1 mt-0.5 flex-shrink-0"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd"
                      />
                    </svg>
<span x-text="feature"></span>
                  </li>
                </template>
              </ul>
            </td>
          </template>
        </tr>

        <!-- Action -->
        <tr>
          <td class="py-3 px-4"></td>
          <template x-for="(room, index) in rooms" :key="index">
            <td class="py-3 px-4">
                <a
                href="{{ route('reservation') }}"
                class="inline-block bg-[#95714F] hover:bg-[#6d4927] text-white font-medium py-2 px-4 rounded-lg transition-colors duration-300 text-center w-full"
              >
                Réserver
              </a>
            </td>
          </template>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Message de disponibilité -->
  <div class="mt-16 text-center" data-aos="fade-up">
    <div
      class="inline-flex items-center px-4 py-2 bg-green-50 text-green-700 rounded-full"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="h-5 w-5 mr-2"
        viewBox="0 0 20 20"
        fill="currentColor"
      >
        <path
          fill-rule="evenodd"
          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
          clip-rule="evenodd"
        />
      </svg>
      <span>Plusieurs chambres disponibles pour vos dates</span>
    </div>

    <p class="text-gray-600 mt-4 max-w-2xl mx-auto">
      Toutes nos chambres offrent une expérience unique. Pour toute demande
      spéciale ou question, n'hésitez pas à contacter notre équipe.
    </p>
  </div>
</section>
@endsection
