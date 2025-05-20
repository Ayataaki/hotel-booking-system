@php use Illuminate\Support\Str; @endphp
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LA MI CASA - Gestion du personnel</title>
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

<body class="bg-[#F8F7F4] text-[#95714F] font-['Times_New_Roman']"
      x-data="staffEditor()"
      x-init="initStaffEditor()">
  <!-- Sidebar -->
  @include('admin.partials.sidebar_staff')

  <!-- Main Content -->
  <div class="main-content min-h-screen">
    @include('admin.partials.header_staff')

      <!-- Tableau du personnel -->
      <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#F3ECE3]">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Employé</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Email</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Téléphone</th>
                <!-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#6d4927] uppercase tracking-wider">Statut</th> -->
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-[#6d4927] uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <!-- Employé i -->
              @foreach ($receptionnistes as $rec)
              <tr class="hover:bg-[#F8F7F4] transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-[#C7AF94] flex items-center justify-center text-white font-bold">
                      {{ Str::upper(Str::substr($rec->prenomRec, 0, 1) . Str::substr($rec->nomRec, 0, 1)) }}
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-[#6d4927]">{{ $rec->prenomRec }} {{ $rec->nomRec }}</div>
                      <div class="text-xs text-gray-500">Depuis le {{ $rec->created_at->format('d/m/Y') }} ({{ $rec->created_at->diffForHumans() }})</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-600">{{ $rec->email }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-600">{{ $rec->numTel }}</div>
                </td>
                <!-- <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ $rec->statut }}</span>
                </td> -->
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end space-x-2">
                    <button
                        @click="currentStaff = {
                            id: '{{ $rec->id }}',
                            prenom: '{{ $rec->prenomRec }}',
                            nom: '{{ $rec->nomRec }}',
                            email: '{{ $rec->email }}',
                            phone: '{{ $rec->numTel }}',
                            cin: '{{ $rec->CIN }}',
                            hire_date: '{{ $rec->created_at->format("d/m/Y") }}',
                            address: '{{ $rec->adresse }}'
                        };
                        viewStaffModal = true"
                        class="text-[#95714F] hover:text-[#6d4927]"
                        title="Voir">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>

                    <button @click="currentStaff = {
                            id: '{{ $rec->id }}',
                            prenom: '{{ $rec->prenomRec }}',
                            nom: '{{ $rec->nomRec }}',
                            email: '{{ $rec->email }}',
                            phone: '{{ $rec->numTel }}',
                            cin: '{{ $rec->CIN }}',
                            hire_date: '{{ $rec->created_at->format('Y-m-d') }}',
                            address: '{{ $rec->adresse }}'
                        }; editStaffModal = true" class="text-[#95714F] hover:text-[#6d4927]" title="Modifier">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                      </svg>
                    </button>
                    <button @click="currentStaff = {
                            id: '{{ $rec->id }}',
                            prenom: '{{ $rec->prenomRec }}',
                            nom: '{{ $rec->nomRec }}'
                        }; deleteStaffModal = true" class="text-red-500 hover:text-red-700" title="Supprimer">
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

      <!-- Pagination personnalisée -->
      <div class="flex items-center justify-between mt-6 mx-6">
        <div class="text-sm text-gray-500">
          Affichage de {{ $receptionnistes->firstItem() ?? 0 }} à {{ $receptionnistes->lastItem() ?? 0 }} sur {{ $receptionnistes->total() }} résultats
        </div>
        <div class="flex space-x-2">
          <a href="{{ $receptionnistes->previousPageUrl() }}"
             class="{{ !$receptionnistes->onFirstPage() ? 'px-3 py-1 border border-[#95714F] rounded-md text-[#95714F] hover:bg-[#EADED0]' : 'px-3 py-1 border border-gray-300 rounded-md text-gray-400 bg-gray-100 cursor-not-allowed' }}"
             {{ !$receptionnistes->onFirstPage() ? '' : 'disabled' }}>
            Précédent
          </a>

          @for ($i = 1; $i <= $receptionnistes->lastPage(); $i++)
            <a href="{{ $receptionnistes->url($i) }}"
               class="{{ $i == $receptionnistes->currentPage() ? 'px-3 py-1 border border-[#95714F] bg-[#95714F] text-white rounded-md' : 'px-3 py-1 border border-[#95714F] text-[#95714F] rounded-md hover:bg-[#EADED0]' }}">
              {{ $i }}
            </a>
          @endfor

          <a href="{{ $receptionnistes->nextPageUrl() }}"
             class="{{ $receptionnistes->hasMorePages() ? 'px-3 py-1 border border-[#95714F] rounded-md text-[#95714F] hover:bg-[#EADED0]' : 'px-3 py-1 border border-gray-300 rounded-md text-gray-400 bg-gray-100 cursor-not-allowed' }}"
             {{ $receptionnistes->hasMorePages() ? '' : 'disabled' }}>
            Suivant
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Overlay mobile -->
  <div class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
       x-show="sidebarOpen"
       @click="sidebarOpen = false"></div>

  <!-- Modale Ajout d'employé -->
  <div x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
       x-show="addStaffModal"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0">
    <div class="bg-white rounded-xl shadow-xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto"
         x-show="addStaffModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         @click.away="addStaffModal = false">
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-bold text-[#6d4927]">Ajouter un employé</h3>
          <button @click="addStaffModal = false" class="text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <form @submit.prevent="storeStaff" enctype="multipart/form-data">
            @csrf
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="firstName" class="block text-sm font-medium text-[#6d4927] mb-1">Prénom</label>
              <input type="text" id="firstName" name="prenomRec" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
            </div>

            <div>
              <label for="lastName" class="block text-sm font-medium text-[#6d4927] mb-1">Nom</label>
              <input type="text" id="lastName" name="nomRec" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
            </div>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-[#6d4927] mb-1">Email</label>
            <input type="email" id="email" name="email" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="phone" class="block text-sm font-medium text-[#6d4927] mb-1">Téléphone</label>
                <input type="tel" id="phone" name="numTel" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
            </div>

            <!-- <div>
              <label for="status" class="block text-sm font-medium text-[#6d4927] mb-1">Statut</label>
              <select id="status" name="statut"  class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
                <option value="active">Actif</option>
                <option value="inactive">Inactif</option>
              </select>
            </div> -->
          </div>
          <div>
              <label for="cin" class="block text-sm font-medium text-[#6d4927] mb-1">CIN</label>
              <input type="text" id="cin" name="CIN" class="w-full p-2 border border-[#C7AF94] rounded-lg" required />
          </div>

          <div>
            <label for="dateEmbauche" class="block text-sm font-medium text-[#6d4927] mb-1">Date d'embauche</label>
            <input type="date" id="dateEmbauche"  name="created_at" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
          </div>

          <div>
            <label for="address" class="block text-sm font-medium text-[#6d4927] mb-1">Adresse</label>
            <textarea id="address" rows="2" name="adresse" class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"></textarea>
          </div>

          <div class="flex justify-end space-x-3 pt-4">
            <button type="button" @click="addStaffModal = false" class="px-4 py-2 border border-[#C7AF94] text-[#95714F] rounded-lg hover:bg-[#EADED0] transition-colors">
              Annuler
            </button>
            <button type="submit" class="px-4 py-2 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors">
              Ajouter
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modale Modification d'employé -->
  <div x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
       x-show="editStaffModal"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0">
    <div class="bg-white rounded-xl shadow-xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto"
         x-show="editStaffModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         @click.away="editStaffModal = false">
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-bold text-[#6d4927]" x-text="'Modifier ' + (currentStaff ? (currentStaff.prenom + ' ' + currentStaff.nom) : '')"></h3>
          <button @click="editStaffModal = false" class="text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <form method="POST" action="{{ route('admin.staff.update') }}" x-ref="editForm" class="space-y-4">
    @csrf
    <input type="hidden" name="id" :value="currentStaff.id">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-[#6d4927] mb-1">Prénom</label>
            <input type="text" name="prenomRec" :value="currentStaff.prenom" class="w-full p-2 border border-[#C7AF94] rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-[#6d4927] mb-1">Nom</label>
            <input type="text" name="nomRec" :value="currentStaff.nom" class="w-full p-2 border border-[#C7AF94] rounded-lg">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-[#6d4927] mb-1">Email</label>
        <input type="email" name="email" :value="currentStaff.email" class="w-full p-2 border border-[#C7AF94] rounded-lg">
    </div>

    <div>
        <label class="block text-sm font-medium text-[#6d4927] mb-1">Téléphone</label>
        <input type="tel" name="numTel" :value="currentStaff.phone" class="w-full p-2 border border-[#C7AF94] rounded-lg">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-[#6d4927] mb-1">CIN</label>
            <input type="text" name="CIN" :value="currentStaff.cin" class="w-full p-2 border border-[#C7AF94] rounded-lg">
        </div>
        <!-- <div>
            <label class="block text-sm font-medium text-[#6d4927] mb-1">Statut</label>
            <select name="statut" class="w-full p-2 border border-[#C7AF94] rounded-lg">
                <option value="active" x-bind:selected="currentStaff.status === 'active'">Actif</option>
                <option value="inactive" x-bind:selected="currentStaff.status === 'inactive'">Inactif</option>
            </select>
        </div> -->
    </div>

    <div>
        <label class="block text-sm font-medium text-[#6d4927] mb-1">Adresse</label>
        <textarea name="adresse" rows="2" class="w-full p-2 border border-[#C7AF94] rounded-lg" x-text="currentStaff.address"></textarea>
    </div>

    <div class="border-t border-gray-200 pt-4">
        <h4 class="text-sm font-medium text-[#6d4927] mb-2">Réinitialiser le mot de passe</h4>
        <p class="text-xs text-gray-500 mb-3">Laissez vide si vous ne souhaitez pas modifier le mot de passe actuel.</p>

        <div class="space-y-3">
            <div>
                <label class="block text-sm font-medium text-[#6d4927] mb-1">Nouveau mot de passe</label>
                <input type="password" name="password" class="w-full p-2 border border-[#C7AF94] rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#6d4927] mb-1">Confirmer mot de passe</label>
                <input type="password" name="password_confirmation" class="w-full p-2 border border-[#C7AF94] rounded-lg">
            </div>
        </div>
    </div>

    <div class="flex justify-end space-x-3 pt-4">
        <button type="button" @click="editStaffModal = false" class="px-4 py-2 border border-[#C7AF94] text-[#95714F] rounded-lg hover:bg-[#EADED0]">
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

  <!-- Modale Vue détaillée d'employé -->
<div x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
     x-show="viewStaffModal"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
  <div class="bg-white rounded-xl shadow-xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto"
       x-show="viewStaffModal"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0 transform scale-95"
       x-transition:enter-end="opacity-100 transform scale-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100 transform scale-100"
       x-transition:leave-end="opacity-0 transform scale-95"
       @click.away="viewStaffModal = false">
    <div class="p-6">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-[#6d4927]" x-text="currentStaff ? (currentStaff.prenom + ' ' + currentStaff.nom) : 'Détails employé'"></h3>
        <button @click="viewStaffModal = false" class="text-gray-400 hover:text-gray-600">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div class="space-y-6">
        <div class="flex justify-center">
          <div class="h-32 w-32 rounded-full bg-[#C7AF94] flex items-center justify-center text-white text-4xl font-bold" x-text="currentStaff ? (currentStaff.prenom.charAt(0).toUpperCase() + currentStaff.nom.charAt(0).toUpperCase()) : ''">
          </div>
        </div>

        <div class="border-t border-gray-200 pt-4">
          <dl class="space-y-4">
            <div class="grid grid-cols-3 gap-4">
              <dt class="text-sm font-medium text-gray-500">Prénom</dt>
              <dd class="text-sm text-gray-900 col-span-2" x-text="currentStaff ? currentStaff.prenom : ''"></dd>
            </div>
            <div class="grid grid-cols-3 gap-4">
              <dt class="text-sm font-medium text-gray-500">Nom</dt>
              <dd class="text-sm text-gray-900 col-span-2" x-text="currentStaff ? currentStaff.nom : ''"></dd>
            </div>
            <div class="grid grid-cols-3 gap-4">
              <dt class="text-sm font-medium text-gray-500">Email</dt>
              <dd class="text-sm text-gray-900 col-span-2" x-text="currentStaff ? currentStaff.email : ''"></dd>
            </div>
            <div class="grid grid-cols-3 gap-4">
              <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
              <dd class="text-sm text-gray-900 col-span-2" x-text="currentStaff ? currentStaff.phone : ''"></dd>
            </div>
            <div class="grid grid-cols-3 gap-4">
              <dt class="text-sm font-medium text-gray-500">CIN</dt>
              <dd class="text-sm text-gray-900 col-span-2" x-text="currentStaff ? currentStaff.cin : ''"></dd>
            </div>
            <!-- <div class="grid grid-cols-3 gap-4">
              <dt class="text-sm font-medium text-gray-500">Statut</dt>
              <dd class="text-sm text-gray-900 col-span-2">
                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800" x-text="currentStaff ? currentStaff.status : ''"></span>
              </dd>
            </div> -->
            <div class="grid grid-cols-3 gap-4">
              <dt class="text-sm font-medium text-gray-500">Date d'embauche</dt>
              <dd class="text-sm text-gray-900 col-span-2" x-text="currentStaff ? currentStaff.hire_date : ''"></dd>
            </div>
            <div class="grid grid-cols-3 gap-4">
              <dt class="text-sm font-medium text-gray-500">Adresse</dt>
              <dd class="text-sm text-gray-900 col-span-2" x-text="currentStaff ? currentStaff.address : ''"></dd>
            </div>
          </dl>
        </div>

        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
          <button
            type="button"
            @click="viewStaffModal = false; editStaffModal = true;"
            class="px-4 py-2 border border-[#C7AF94] text-[#95714F] rounded-lg hover:bg-[#EADED0] transition-colors">
            Modifier
          </button>
          <button type="button" @click="viewStaffModal = false" class="px-4 py-2 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors">
            Fermer
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

  <!-- Modale Confirmation de suppression -->
  <div x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
      x-show="deleteStaffModal"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0">
  <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4"
      x-show="deleteStaffModal"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0 transform scale-95"
      x-transition:enter-end="opacity-100 transform scale-100"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 transform scale-100"
      x-transition:leave-end="opacity-0 transform scale-95"
      @click.away="deleteStaffModal = false">
      <div class="p-6">
      <div class="text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
          <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Confirmez la suppression</h3>
          <p class="text-gray-600 mb-6" x-text="'Êtes-vous sûr de vouloir supprimer ' + (currentStaff ? currentStaff.prenom + ' ' + currentStaff.nom : '') + ' ? Cette action est irréversible.'"></p>

          <form method="POST" action="{{ route('admin.staff.delete') }}" id="deleteForm">
              @csrf
              <input type="hidden" name="id" :value="currentStaff ? currentStaff.id : ''">

              <div class="flex justify-center space-x-3">
                  <button type="button" @click="deleteStaffModal = false" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                      Annuler
                  </button>
                  <button type="submit" class="px-4 py-2 border border-transparent rounded-lg text-white bg-red-600 hover:bg-red-700">
                      Supprimer
                  </button>
              </div>
          </form>
      </div>
      </div>
  </div>
  </div>

  <script>
  function staffEditor() {
    return {
      sidebarOpen: false,
      addStaffModal: false,
      editStaffModal: false,
      deleteStaffModal: false,
      viewStaffModal: false,
      currentStaff: null,

      // Fonction d'initialisation pour s'assurer que les modals sont fermées au chargement
      initStaffEditor() {
        // Assurez-vous que toutes les modales sont fermées lors de l'initialisation
        this.addStaffModal = false;
        this.editStaffModal = false;
        this.deleteStaffModal = false;
        this.viewStaffModal = false;
        this.currentStaff = null;
      },

      storeStaff() {
        const form = document.querySelector('form[enctype="multipart/form-data"]');
        const formData = new FormData(form);

        fetch('{{ route('admin.staff.add') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Erreur lors de l\'ajout');
                });
            }
            return response.json();
        })
        .then(data => {
            this.addStaffModal = false;
            if (data.success) {
                // Récupérer la page actuelle
                const currentPage = new URLSearchParams(window.location.search).get('page') || 1;
                // Recharger la même page
                setTimeout(() => {
                    window.location.href = window.location.pathname + '?page=' + currentPage;
                }, 10);
            } else {
                alert('❌ ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('❌ Erreur lors de l\'ajout: ' + error.message);
        });
      },

      deleteStaff() {
        if (!this.currentStaff || !this.currentStaff.id) {
            alert('Erreur: Impossible de supprimer l\'employé');
            return;
        }

        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('id', this.currentStaff.id);

        fetch('{{ url('/admin/staff/delete') }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors de la suppression');
            }
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.includes("application/json")) {
                return response.json();
            } else {
                throw new Error('Réponse inattendue du serveur (pas JSON)');
            }
        })
        .then(data => {
            this.deleteStaffModal = false;
            if (data.success) {
                // Récupérer la page actuelle depuis l'URL
                const urlParams = new URLSearchParams(window.location.search);
                const currentPage = urlParams.get('page') || 1;

                // Recharger la même page de pagination
                setTimeout(() => {
                    window.location.href = '{{ url('/admin/staff') }}' + '?page=' + currentPage;
                }, 10);
            } else {
                alert('❌ ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('❌ Erreur lors de la suppression: ' + error.message);
        });
      }
    };
  }
</script>
</body>
</html>
