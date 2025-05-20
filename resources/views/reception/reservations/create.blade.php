<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LA MI CASA - Nouvelle réservation</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
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

<!-- @if(session('error'))
    <div class="bg-red-100 text-red-700 p-3 rounded">{{ session('error') }}</div>
@endif -->

  <!-- Sidebar -->
  @include('reception.partials.sidebar')

  <!-- Main Content -->
  <div class="main-content min-h-screen">
    @include('reception.partials.header', ['pageTitle' => 'Nouvelle réservation'])

    <div class="p-6 md:p-8 pt-20 md:pt-8">
      <!-- <form method="POST" action="{{ route('reception.reservations.store') }}" x-data="reservationForm()" class="space-y-8"> -->
      <form method="POST" action="{{ route('reception.reservations.store') }}" x-data="reservationForm()" x-init="init()" class="space-y-8">
      <!-- <form method="POST" action="{{ route('reception.reservations.store') }}" x-data="reservationForm()" x-init="init()" @submit="submitForm" class="space-y-8"> -->
        @csrf

        <!-- Étape 1: Chambres et dates -->
        <div class="bg-white rounded-xl shadow-md p-6">
          <h3 class="text-lg font-bold text-[#6d4927] mb-6">Dates et chambre</h3>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="date-debut" class="block text-sm font-medium mb-1">Date d'arrivée</label>
              <input
                type="date"
                id="date-debut"
                name="dateDeb"
                x-model="dateDeb"
                @change="calculerDuree(); calculerPrixTotal()"
                :min="new Date().toISOString().split('T')[0]"
                class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                required
              >
            </div>

            <div>
              <label for="date-fin" class="block text-sm font-medium mb-1">Date de départ</label>
              <input
                type="date"
                id="date-fin"
                name="dateFin"
                x-model="dateFin"
                @change="calculerDuree(); calculerPrixTotal()"
                :min="dateDeb || new Date().toISOString().split('T')[0]"
                class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                required
              >
            </div>
          </div>

          <div class="mt-4">
            <p class="text-sm text-gray-500 mb-2">Durée du séjour: <span x-text="duree"></span> nuit(s)</p>
          </div>

          <div class="mt-6">
            <label for="chambre" class="block text-sm font-medium mb-1">Chambre</label>
            <select
              id="chambre"
              name="chambre_id"
              x-model="chambreId"
              @change="getChambreDetails(); calculerPrixTotal()"
              class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
              required
            >
              <option value="">Sélectionnez une chambre</option>
              @foreach($chambresDisponibles as $chambre)
                <option value="{{ $chambre->id }}">Chambre {{ $chambre->NumCh }} - {{ $chambre->capacite }} pers. - {{ $chambre->prixNuit }}€/nuit</option>
              @endforeach
            </select>
          </div>

          <div class="mt-4" x-show="chambreDetails.NumCh">
            <div class="p-4 bg-gray-50 rounded-lg">
              <h4 class="font-medium text-[#6d4927] mb-2" x-text="'Chambre ' + chambreDetails.NumCh"></h4>
              <p class="text-sm text-gray-600 mb-2" x-text="'Étage: ' + chambreDetails.NumEtg"></p>
              <p class="text-sm text-gray-600 mb-2" x-text="'Capacité: ' + chambreDetails.capacite + ' personne(s)'"></p>
              <p class="text-sm text-gray-600 mb-2" x-text="'Prix par nuit: ' + chambreDetails.prixNuit + '€'"></p>
              <p class="text-sm font-medium text-[#6d4927]" x-text="'Prix total pour ' + duree + ' nuit(s): ' + prixTotal + '€'"></p>
            </div>
          </div>
        </div>

        <!-- Étape 2: Informations client -->
        <div class="bg-white rounded-xl shadow-md p-6">
          <h3 class="text-lg font-bold text-[#6d4927] mb-6">Informations client</h3>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Client existant ?</label>
            <div class="flex space-x-4">
              <label class="inline-flex items-center">
                <input type="radio" name="client_type" value="existant" x-model="clientType" class="form-radio text-[#95714F]">
                <span class="ml-2">Oui</span>
              </label>
              <label class="inline-flex items-center">
                <input type="radio" name="client_type" value="nouveau" x-model="clientType" class="form-radio text-[#95714F]">
                <span class="ml-2">Non</span>
              </label>
            </div>
          </div>

          <!-- Client existant -->
          <div x-show="clientType === 'existant'" x-transition>
            <div class="mb-4">
              <label for="client_search" class="block text-sm font-medium mb-1">Rechercher un client</label>
              <div class="relative">
                <input
                  type="text"
                  id="client_search"
                  placeholder="Nom, email ou téléphone"
                  x-model="searchQuery"
                  @input="searchClients"
                  class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                >
                <div
                  x-show="searchResults.length > 0"
                  class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-y-auto"
                >
                  <ul>
                    <template x-for="client in searchResults" :key="client.id">
                      <li
                        @click="selectClient(client)"
                        class="px-4 py-2 cursor-pointer hover:bg-gray-100"
                      >
                        <p class="font-medium" x-text="client.nom + ' ' + client.prenom"></p>
                        <p class="text-xs text-gray-500" x-text="client.email + ' • ' + client.telephone"></p>
                      </li>
                    </template>
                  </ul>
                </div>
              </div>
            </div>

            <div x-show="selectedClient.id" class="p-4 bg-gray-50 rounded-lg">
              <h4 class="font-medium text-[#6d4927] mb-2" x-text="selectedClient.nom + ' ' + selectedClient.prenom"></h4>
              <p class="text-sm text-gray-600 mb-1" x-text="'Téléphone: ' + selectedClient.telephone"></p>
              <p class="text-sm text-gray-600 mb-1" x-text="'Pays: ' + selectedClient.pays"></p>
              <p class="text-sm text-gray-600 mb-1" x-text="'Région: ' + selectedClient.region"></p>
              <input type="hidden" name="client_id" :value="selectedClient.id">
            </div>
          </div>

          <!-- Nouveau client -->
          <!-- <div x-show="clientType === 'nouveau'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="nom" class="block text-sm font-medium mb-1">Nom</label>
              <input
                type="text"
                id="nom"
                name="nom"
                class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                x-bind:required="clientType === 'nouveau'"
              >
            </div>

            <div>
              <label for="prenom" class="block text-sm font-medium mb-1">Prénom</label>
              <input
                type="text"
                id="prenom"
                name="prenom"
                class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                x-bind:required="clientType === 'nouveau'"
              >
            </div>

            <div>
              <label for="email" class="block text-sm font-medium mb-1">Email</label>
              <input
                type="email"
                id="email"
                name="email"
                class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                x-bind:required="clientType === 'nouveau'"
              >
            </div>

            <div>
              <label for="telephone" class="block text-sm font-medium mb-1">Téléphone</label>
              <input
                type="tel"
                id="telephone"
                name="telephone"
                class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                x-bind:required="clientType === 'nouveau'"
              >
            </div>

            <div>
              <label for="dateNaissance" class="block text-sm font-medium mb-1">Date de naissance</label>
              <input
                type="date"
                id="dateNaissance"
                name="dateNaissance"
                class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                x-bind:required="clientType === 'nouveau'"
              >
            </div>

            <div>
              <label for="cin" class="block text-sm font-medium mb-1">CIN</label>
              <input
                type="text"
                id="cin"
                name="cin"
                class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
              >
            </div>

            <div>
              <label for="passeport" class="block text-sm font-medium mb-1">Numéro de passeport</label>
              <input
                type="text"
                id="passeport"
                name="passeport"
                class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
              >
            </div>
          </div> -->

            <template x-if="clientType === 'nouveau'">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-data="{ typeIdentite: 'cin' }">
                    <div>
                    <label for="nom" class="block text-sm font-medium mb-1">Nom</label>
                    <input type="text" id="nom" name="nom"
                    class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                    x-bind:required="clientType === 'nouveau'">
                </div>

                <div>
                    <label for="prenom" class="block text-sm font-medium mb-1">Prénom</label>
                    <input type="text" id="prenom" name="prenom"
                    class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                    x-bind:required="clientType === 'nouveau'">
                </div>

                <div>
                    <label for="pays" class="block text-sm font-medium mb-1">Pays</label>
                    <input type="text" id="pays" name="pays"
                    class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                    x-bind:required="clientType === 'nouveau'">
                </div>

                <div>
                    <label for="region" class="block text-sm font-medium mb-1">Région</label>
                    <input type="text" id="region" name="region"
                    class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                    x-bind:required="clientType === 'nouveau'">
                </div>

                <div>
                    <label for="telephone" class="block text-sm font-medium mb-1">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone"
                    class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                    x-bind:required="clientType === 'nouveau'">
                </div>


                <div>
                    <label class="block text-sm font-medium mb-1">Type d'identité</label>
                    <select x-model="typeIdentite" name="typeIdentite"
                    class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                    <option value="cin">CIN</option>
                    <option value="passeport">Passeport</option>
                    </select>
                </div>

                <!-- <div x-show="typeIdentite === 'cin'">
                    <label for="cin" class="block text-sm font-medium mb-1">CIN</label>
                    <input type="text" id="cin" name="cin"
                    class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                    :required="typeIdentite === 'cin'">
                </div> -->
                <!-- <div x-show="typeIdentite === 'cin'">
                    <label for="cin" class="block text-sm font-medium mb-1">CIN</label>
                    <input type="text" id="cin" name="cin"
                        class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                        :required="typeIdentite === 'cin'"
                        :disabled="typeIdentite !== 'cin'">
                </div> -->
                <!-- <template x-if="typeIdentite === 'cin'">
                    <div>
                        <label for="cin" class="block text-sm font-medium mb-1">CIN</label>
                        <input type="text" id="cin" name="cin"
                        class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                        required>
                    </div>
                </template> -->
                <div x-show="typeIdentite === 'cin'">
                    <label for="cin" class="block text-sm font-medium mb-1">CIN</label>
                    <input type="text" id="cin" name="cin"
                        class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                        :required="typeIdentite === 'cin'">
                </div>



                <!-- <div x-show="typeIdentite === 'passeport'">
                    <label for="passeport" class="block text-sm font-medium mb-1">Numéro de passeport</label>
                    <input type="text" id="passeport" name="passeport"
                    class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                    x-bind:required="typeIdentite === 'passeport'">
                </div> -->

                <!-- <div x-show="typeIdentite === 'passeport'">
                    <label for="passeport" class="block text-sm font-medium mb-1">Numéro de passeport</label>
                    <input type="text" id="passeport" name="passeport"
                        class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                        :required="typeIdentite === 'passeport'"
                        :disabled="typeIdentite !== 'passeport'">
                </div> -->
                <!-- <template x-if="typeIdentite === 'passeport'">
                    <div>
                        <label for="passeport" class="block text-sm font-medium mb-1">Numéro de passeport</label>
                        <input type="text" id="passeport" name="passeport"
                        class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                        required>
                    </div>
                </template> -->

                <div x-show="typeIdentite === 'passeport'">
                    <label for="passeport" class="block text-sm font-medium mb-1">Numéro de passeport</label>
                    <input type="text" id="passeport" name="passeport"
                        class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                        :required="typeIdentite === 'passeport'">
                </div>
                </div>
            </template>
          <!-- <div x-show="clientType === 'nouveau'" x-transition x-data="{ typeIdentite: 'cin' }" class="grid grid-cols-1 md:grid-cols-2 gap-4">



          </div> -->

        </div>

        <!-- Étape 3: Résumé et paiement -->
        <div class="bg-white rounded-xl shadow-md p-6">
          <h3 class="text-lg font-bold text-[#6d4927] mb-6">Résumé et paiement</h3>

          <div class="mb-6">
            <h4 class="font-medium text-[#6d4927] mb-2">Détails du séjour</h4>
            <div class="p-4 bg-gray-50 rounded-lg">
              <p class="text-sm text-gray-600 mb-2">
                Dates: <span x-text="formatDate(dateDeb) + ' au ' + formatDate(dateFin)"></span> (<span x-text="duree"></span> nuit(s))
              </p>
              <p class="text-sm text-gray-600 mb-2" x-show="chambreDetails.NumCh">
                Chambre: <span x-text="'N°' + chambreDetails.NumCh"></span>
              </p>
              <p class="text-sm font-medium text-[#6d4927]">
                Prix total: <span x-text="prixTotal + '€'"></span>
              </p>


              <!-- <div class="mt-4">
                <h4 class="font-medium text-[#6d4927] mb-2">Client</h4>
                <template x-if="clientType === 'existant' && selectedClient.id">
                    <p class="text-sm text-gray-600">
                    <span x-text="selectedClient.nom + ' ' + selectedClient.prenom"></span>,
                    <span x-text="selectedClient.telephone"></span>,
                    <span x-text="selectedClient.pays + ', ' + selectedClient.region"></span>
                    </p>
                </template>
                <template x-if="clientType === 'nouveau'">
                    <p class="text-sm text-gray-600">
                    <span x-text="$refs.nom?.value + ' ' + $refs.prenom?.value"></span>,
                    <span x-text="$refs.telephone?.value"></span>,
                    <span x-text="$refs.pays?.value + ', ' + $refs.region?.value"></span>
                    </p>
                </template>
              </div> -->


            </div>
          </div>

          <!-- <div class="mb-6">
            <label for="paiement" class="block text-sm font-medium mb-1">Méthode de paiement</label>
            <select
              id="paiement"
              name="methodePaiement"
              class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
              required
            >
              <option value="especes">Espèces</option>
              <option value="carte">Carte bancaire</option>
            </select>
          </div> -->

          <!-- <div class="mb-6">
            <label for="notes" class="block text-sm font-medium mb-1">Notes additionnelles</label>
            <textarea
              id="notes"
              name="notes"
              rows="3"
              class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
            ></textarea>
          </div> -->

          <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors">
              Confirmer la réservation
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Overlay mobile -->
  <div class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
       x-show="sidebarOpen"
       @click="sidebarOpen = false"></div>

  <script>
    function reservationForm() {
      return {
        clientType: 'nouveau',
        dateDeb: "{{ request()->get('date_debut', '') }}",
        dateFin: "{{ request()->get('date_fin', '') }}",
        duree: 0,
        chambreId: "{{ request()->get('chambre', '') }}",
        chambreDetails: {},
        prixTotal: 0,
        searchQuery: '',
        searchResults: [],
        selectedClient: {},

        init() {
          if (this.chambreId) {
            this.getChambreDetails();
          }
          if (this.dateDeb && this.dateFin) {
            this.calculerDuree();
          }
        },
        // submitForm(event) {
        //     event.preventDefault();

        //     const form = event.target;
        //     const formData = new FormData(form);

        //     fetch(form.action, {
        //         method: 'POST',
        //         body: formData,
        //         headers: {
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        //         }
        //     })
        //     .then(response => response.json())
        //     .then(data => {
        //         if (data.success) {
        //         alert('SUCCÈS: ' + data.message + '\n\nRéservation ID: ' + data.reservation_id + '\nHistorique ID: ' + data.historique_id);
        //         setTimeout(() => {
        //             window.location.href = data.redirect_url;
        //         }, 2000);
        //         } else {
        //         alert('ERREUR: ' + data.message + '\n\nDétails: ' + JSON.stringify(data.error_details, null, 2));
        //         }
        //     })
        //     .catch(error => {
        //         console.error('Erreur:', error);
        //         alert('Erreur de réseau: ' + error);
        //     });
        // },

        // calculerDuree() {
        //   if (!this.dateDeb || !this.dateFin) return;

        //   const debut = new Date(this.dateDeb);
        //   const fin = new Date(this.dateFin);
        //   const diffTime = Math.abs(fin - debut);
        //   this.duree = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        // },
        calculerDuree() {
            if (!this.dateDeb || !this.dateFin) return;

            const debut = new Date(this.dateDeb);
            const fin = new Date(this.dateFin);

            // Utilisez cette formule pour obtenir un résultat positif
            const diffTime = fin - debut;

            if (diffTime < 0) {
                this.duree = 0;
                return;
            }

            this.duree = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        },

        getChambreDetails() {
          if (!this.chambreId) return;

          fetch(`/reception/api/chambres/${this.chambreId}`)
            .then(response => response.json())
            .then(data => {
              this.chambreDetails = data;
              this.calculerPrixTotal();
            })
            .catch(error => {
              console.error('Erreur lors de la récupération des détails de la chambre:', error);
            });
        },

        calculerPrixTotal() {
          if (!this.duree || !this.chambreDetails.prixNuit) return;

          this.prixTotal = this.duree * this.chambreDetails.prixNuit;
        },

        searchClients() {
          if (this.searchQuery.length < 2) {
            this.searchResults = [];
            return;
          }

          fetch(`/reception/api/clients?q=${this.searchQuery}`)
            .then(response => response.json())
            .then(data => {
              this.searchResults = data;
            })
            .catch(error => {
              console.error('Erreur lors de la recherche de clients:', error);
            });
        },

        selectClient(client) {
          this.selectedClient = client;
          this.searchQuery = client.nom + ' ' + client.prenom;
          this.searchResults = [];
        },

        formatDate(dateString) {
          if (!dateString) return '';

          const date = new Date(dateString);
          return date.toLocaleDateString('fr-FR');
        }
      }
    }
  </script>
  <script>
// function reservationForm() {
//   return {
//     clientType: 'nouveau',
//     dateDeb: "{{ request()->get('date_debut', '') }}",
//     dateFin: "{{ request()->get('date_fin', '') }}",
//     duree: 0,
//     chambreId: "{{ request()->get('chambre', '') }}",
//     chambreDetails: {},
//     prixTotal: 0,
//     searchQuery: '',
//     searchResults: [],
//     selectedClient: {},

//     init() {
//       if (this.chambreId) {
//         this.getChambreDetails();
//       }
//       if (this.dateDeb && this.dateFin) {
//         this.calculerDuree();
//       }
//     },

//     submitForm(event) {
//       event.preventDefault();

//       const form = event.target;
//       const formData = new FormData(form);

//       fetch(form.action, {
//         method: 'POST',
//         body: formData
//       })
//       .then(response => response.json())
//       .then(data => {
//         if (data.success) {
//           // Afficher une alerte de succès
//           alert('SUCCÈS: ' + data.message + '\n\nRéservation ID: ' + data.reservation_id + '\nHistorique ID: ' + data.historique_id);

//           // Rediriger après 2 secondes
//           setTimeout(() => {
//             window.location.href = data.redirect_url;
//           }, 2000);
//         } else {
//           // Afficher une alerte d'erreur
//           alert('ERREUR: ' + data.message + '\n\nDétails: ' + JSON.stringify(data.error_details, null, 2));
//         }
//       })
//       .catch(error => {
//         console.error('Erreur:', error);
//         alert('Erreur de réseau: ' + error);
//       });
//     },

//     // ... restant de vos méthodes Alpine.js ...
//   }
// }
</script>
</body>
</html>
