<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LA MI CASA - Gestion des services supplémentaires</title>
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
    [x-cloak] {
        display: none !important;
    }
  </style>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-[#F8F7F4] text-[#95714F] font-['Times_New_Roman']" x-data="servicesManager()" x-init="init()">
  <!-- Sidebar -->
  @include('admin.partials.sidebar_supp')

  <!-- Main Content -->
  <div class="main-content min-h-screen">
    @include('admin.partials.header')

    <!-- Content -->
    <div class="px-6 py-8">
      <!-- Header -->
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-[#6d4927]">Gestion des services supplémentaires</h1>
        <button @click="addServiceModal = true" class="bg-[#95714F] text-white px-6 py-3 rounded-lg hover:bg-[#6d4927] transition-colors flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Ajouter un service
        </button>
      </div>

      <!-- Filtres -->
      <!-- <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label for="searchService" class="block text-sm font-medium text-[#6d4927] mb-1">Rechercher</label>
            <input type="text" id="searchService" x-model="filters.search" @input="filterServices"
                   placeholder="Service, tarif..."
                   class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
          </div>
          <div>
            <label for="priceFilter" class="block text-sm font-medium text-[#6d4927] mb-1">Tarif max</label>
            <input type="number" id="priceFilter" x-model="filters.maxPrice" @input="filterServices"
                   class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
          </div>
          <div>
            <label for="sortBy" class="block text-sm font-medium text-[#6d4927] mb-1">Trier par</label>
            <select id="sortBy" x-model="filters.sortBy" @change="filterServices"
                    class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
              <option value="libelle">Nom du service</option>
              <option value="tarif">Tarif</option>
              <option value="recent">Plus récents</option>
            </select>
          </div>
          <div>
            <label for="order" class="block text-sm font-medium text-[#6d4927] mb-1">Ordre</label>
            <select id="order" x-model="filters.order" @change="filterServices"
                    class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
              <option value="asc">Croissant</option>
              <option value="desc">Décroissant</option>
            </select>
          </div>
        </div>
      </div> -->

      <!-- Tableau des services -->
      <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#F3ECE3]">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">ID</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Service</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Tarif</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Date création</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Date modification</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-[#6d4927] uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach ($services as $service)
                <tr class="hover:bg-[#F8F7F4] transition-colors">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ $service->id }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-[#6d4927]">{{ $service->libelle }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ $service->tarif }}€
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ $service->created_at }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ $service->updated_at }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                      <!-- Bouton Modifier -->
                      <button @click="currentService = {{ json_encode($service) }}; editServiceModal = true"
                              class="text-[#95714F] hover:text-[#6d4927]" title="Modifier">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                      </button>
                      <!-- Bouton Supprimer -->
                      <button @click="currentService = {{ json_encode($service) }}; deleteServiceModal = true"
                              class="text-red-500 hover:text-red-700" title="Supprimer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div class="flex items-center justify-between mt-4">
        <div class="text-sm text-gray-500">
          Affichage de {{ ($page - 1) * 10 + 1 }} à {{ min($page * 10, $total) }} sur {{ $total }} résultats
        </div>
        <div class="flex space-x-1">
          @if ($page <= 1)
            <span class="px-3 py-1 border border-gray-300 rounded-md text-gray-400 bg-gray-100">Précédent</span>
          @else
            <a href="{{ url('/admin/services?page='.($page-1)) }}" class="px-3 py-1 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Précédent</a>
          @endif

          @for($i = 1; $i <= $totalPages; $i++)
            @if($i == $page)
              <span class="px-3 py-1 border border-[#95714F] bg-[#95714F] text-white rounded-md">{{ $i }}</span>
            @else
              <a href="{{ url('/admin/services?page='.$i) }}" class="px-3 py-1 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">{{ $i }}</a>
            @endif
          @endfor

          @if ($page >= $totalPages)
            <span class="px-3 py-1 border border-gray-300 rounded-md text-gray-400 bg-gray-100">Suivant</span>
          @else
            <a href="{{ url('/admin/services?page='.($page+1)) }}" class="px-3 py-1 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Suivant</a>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Modale Ajout de service -->
  <div x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
       x-show="addServiceModal"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0">
    <div class="bg-white rounded-xl shadow-xl max-w-lg w-full mx-4"
         x-show="addServiceModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         @click.away="addServiceModal = false">
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-bold text-[#6d4927]">Ajouter un service</h3>
          <button @click="addServiceModal = false" class="text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <form @submit.prevent="storeService" class="space-y-4">
          <div>
            <label for="libelle" class="block text-sm font-medium text-[#6d4927] mb-1">Libellé du service</label>
            <input type="text" id="libelle" name="libelle" required
                   class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
          </div>

          <div>
            <label for="tarif" class="block text-sm font-medium text-[#6d4927] mb-1">Tarif (€)</label>
            <input type="number" id="tarif" name="tarif" step="0.01" required
                   class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
          </div>

          <div class="flex justify-end space-x-3 pt-4">
            <button type="button" @click="addServiceModal = false"
                    class="px-4 py-2 border border-[#C7AF94] text-[#95714F] rounded-lg hover:bg-[#EADED0] transition-colors">
              Annuler
            </button>
            <button type="submit"
                    class="px-4 py-2 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors">
              Ajouter le service
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modale Modification de service -->
  <div x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
       x-show="editServiceModal"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0">
    <div class="bg-white rounded-xl shadow-xl max-w-lg w-full mx-4"
         x-show="editServiceModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         @click.away="editServiceModal = false">
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-bold text-[#6d4927]">Modifier le service</h3>
          <button @click="editServiceModal = false" class="text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <form @submit.prevent="updateService" x-show="currentService" class="space-y-4">
          <input type="hidden" :value="currentService?.id" name="id">

          <div>
            <label for="editLibelle" class="block text-sm font-medium text-[#6d4927] mb-1">Libellé du service</label>
            <input type="text" id="editLibelle" name="libelle" :value="currentService?.libelle" required
                   class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
          </div>

          <div>
            <label for="editTarif" class="block text-sm font-medium text-[#6d4927] mb-1">Tarif (€)</label>
            <input type="number" id="editTarif" name="tarif" :value="currentService?.tarif" step="0.01" required
                   class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
          </div>

          <div class="flex justify-end space-x-3 pt-4">
            <button type="button" @click="editServiceModal = false"
                    class="px-4 py-2 border border-[#C7AF94] text-[#95714F] rounded-lg hover:bg-[#EADED0] transition-colors">
              Annuler
            </button>
            <button type="submit"
                    class="px-4 py-2 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors">
              Modifier le service
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modale Suppression de service -->
  <div x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
       x-show="deleteServiceModal"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4"
         x-show="deleteServiceModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         @click.away="deleteServiceModal = false">
      <div class="p-6">
        <div class="text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Confirmer la suppression</h3>
          <p class="text-gray-600 mb-6" x-text="'Êtes-vous sûr de vouloir supprimer le service \"' + (currentService?.libelle || '') + '\" ?'"></p>

          <div class="flex justify-center space-x-3">
            <button type="button" @click="deleteServiceModal = false"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50">
              Annuler
            </button>
            <button type="button" @click="deleteService"
                    class="px-4 py-2 border border-transparent rounded-lg text-white bg-red-600 hover:bg-red-700">
              Supprimer
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

<script>
function servicesManager() {
  return {
    sidebarOpen: false,
    addServiceModal: false,
    editServiceModal: false,
    deleteServiceModal: false,
    currentService: null,
    filters: {
      search: '',
      maxPrice: '',
      sortBy: 'libelle',
      order: 'asc'
    },

    init() {
      // Initialisation si nécessaire
    },

    filterServices() {
      // Implémentation du filtrage
      let url = new URL(window.location.href);

      if (this.filters.search) {
        url.searchParams.set('search', this.filters.search);
      } else {
        url.searchParams.delete('search');
      }

      if (this.filters.maxPrice) {
        url.searchParams.set('max_price', this.filters.maxPrice);
      } else {
        url.searchParams.delete('max_price');
      }

      url.searchParams.set('sort', this.filters.sortBy);
      url.searchParams.set('order', this.filters.order);

      window.location.href = url.toString();
    },

    storeService(event) {
      const form = event.target;
      const formData = new FormData(form);
      formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

      fetch('/admin/services', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        this.addServiceModal = false;
        // alert('✅ Service ajouté avec succès!');
        window.location.reload();
      })
      .catch(error => {
        console.error('Erreur:', error);
        alert('❌ Erreur lors de l\'ajout du service');
      });
    },

    updateService(event) {
      const form = event.target;
      const formData = new FormData(form);
      formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
      formData.append('_method', 'PUT');

      fetch(`/admin/services/${this.currentService.id}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        this.editServiceModal = false;
        // alert('✅ Service modifié avec succès!');
        window.location.reload();
      })
      .catch(error => {
        console.error('Erreur:', error);
        alert('❌ Erreur lors de la modification du service');
      });
    },

    deleteService() {
      const formData = new FormData();
      formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
      formData.append('_method', 'DELETE');

      fetch(`/admin/services/${this.currentService.id}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        this.deleteServiceModal = false;
        // alert('✅ Service supprimé avec succès!');
        window.location.reload();
      })
      .catch(error => {
        console.error('Erreur:', error);
        alert('❌ Erreur lors de la suppression du service');
      });
    }
  };
}
</script>
</html>
