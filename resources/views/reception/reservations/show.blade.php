<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LA MI CASA - Détails de la réservation</title>
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
    @include('reception.partials.header', ['pageTitle' => 'Détails de la réservation'])

    <div class="p-6 md:p-8 pt-20 md:pt-8">
      <div class="mb-6 flex justify-between">
        <a href="{{ route('reception.reservations') }}" class="flex items-center text-[#95714F] hover:text-[#6d4927]">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          <span>Retour aux réservations</span>
        </a>

        @if($reservation->statut !== 'annulée')
          <div class="flex space-x-2" x-data="{ confirmCancel: false }">
            @if($reservation->statut === 'en_attente')
              <form method="POST" action="{{ route('reception.reservations.update-status', $reservation->id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="statut" value="confirmée">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  <span>Confirmer</span>
                </button>
              </form>
            @endif

            <button @click="confirmCancel = true" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
              <span>Annuler réservation</span>
            </button>

            <!-- Modal de confirmation d'annulation -->
            <div x-show="confirmCancel" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
              <div class="fixed inset-0 bg-black opacity-50"></div>
              <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md z-10">
                <h4 class="text-lg font-bold text-gray-900 mb-4">Annuler la réservation</h4>
                <p class="text-gray-700 mb-6">Êtes-vous sûr de vouloir annuler cette réservation ? Cette action est irréversible.</p>
                <div class="flex justify-end space-x-3">
                  <button @click="confirmCancel = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Annuler</button>
                  <form method="POST" action="{{ route('reception.reservations.cancel', $reservation->id) }}">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Confirmer</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        @endif
      </div>

      <!-- Entête réservation -->
      <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
          <div>
            <h3 class="text-xl font-bold text-[#6d4927]">Réservation #{{ $reservation->id }}</h3>
            <p class="text-sm text-gray-500">Créée le {{ $reservation->created_at->format('d/m/Y à H:i') }}</p>
          </div>
          <div class="mt-4 md:mt-0">
            <span class="px-3 py-1 rounded-full text-sm font-medium
              @if($reservation->statut === 'confirmée') bg-green-100 text-green-800
              @elseif($reservation->statut === 'en_attente') bg-yellow-100 text-yellow-800
              @else bg-red-100 text-red-800 @endif">
              {{ ucfirst($reservation->statut) }}
            </span>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h4 class="font-medium text-[#6d4927] mb-2">Détails du séjour</h4>
            <div class="p-4 bg-gray-50 rounded-lg">
              <p class="text-sm text-gray-600 mb-2">Arrivée: {{ $reservation->dateDeb->format('d/m/Y') }}</p>
              <p class="text-sm text-gray-600 mb-2">Départ: {{ $reservation->dateFin->format('d/m/Y') }}</p>
              <p class="text-sm text-gray-600 mb-2">Durée: {{ $reservation->dateDeb->diffInDays($reservation->dateFin) }} nuit(s)</p>
              <p class="text-sm text-gray-600">Chambre: N°{{ $reservation->chambre->NumCh ?? 'N/A' }}</p>
            </div>
          </div>

          <div>
            <h4 class="font-medium text-[#6d4927] mb-2">Informations de paiement</h4>
            <div class="p-4 bg-gray-50 rounded-lg">
              <p class="text-sm text-gray-600 mb-2">Prix total: {{ number_format($reservation->soldePayer, 2, ',', ' ') }}€</p>
              <p class="text-sm text-gray-600 mb-2">Méthode: {{ $reservation->methodePaiement ?? 'Non spécifiée' }}</p>
              <p class="text-sm text-gray-600">Statut paiement: {{ $reservation->statut === 'confirmée' ? 'Payé' : 'En attente' }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Informations client -->
      <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <h3 class="text-lg font-bold text-[#6d4927] mb-6">Informations client</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h4 class="font-medium text-[#6d4927] mb-2">Coordonnées</h4>
            <div class="p-4 bg-gray-50 rounded-lg">
              <p class="text-sm text-gray-600 mb-2">Nom: {{ $reservation->client->nom ?? 'N/A' }} {{ $reservation->client->prenom ?? '' }}</p>
              <p class="text-sm text-gray-600 mb-2">Email: {{ $reservation->client->email ?? 'N/A' }}</p>
              <p class="text-sm text-gray-600 mb-2">Téléphone: {{ $reservation->client->telephone ?? 'N/A' }}</p>
              <p class="text-sm text-gray-600">Date de naissance: {{ optional($reservation->client->dateNaissance)->format('d/m/Y') ?? 'N/A' }}</p>
            </div>
          </div>

          <div>
            <h4 class="font-medium text-[#6d4927] mb-2">Pièces d'identité</h4>
            <div class="p-4 bg-gray-50 rounded-lg">
              <p class="text-sm text-gray-600 mb-2">CIN: {{ $reservation->client->cin ?? 'Non renseigné' }}</p>
              <p class="text-sm text-gray-600">Passeport: {{ $reservation->client->passeport ?? 'Non renseigné' }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Notes et observations -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-bold text-[#6d4927] mb-6">Notes et observations</h3>

        <div class="mb-6">
          <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600">
              {{ $reservation->notes ?? 'Aucune note pour cette réservation.' }}
            </p>
          </div>
        </div>

        @if($reservation->statut !== 'annulée')
          <div x-data="{ noteText: '' }">
            <form method="POST" action="{{ route('reception.reservations.add-note', $reservation->id) }}" class="space-y-4">
              @csrf
              <div>
                <label for="note" class="block text-sm font-medium mb-1">Ajouter une note</label>
                <textarea
                  id="note"
                  name="note"
                  x-model="noteText"
                  rows="3"
                  class="w-full p-2 border border-[#C7AF94] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                  placeholder="Ajoutez une note ou observation..."
                ></textarea>
              </div>

              <div class="flex justify-end">
                <button
                  type="submit"
                  class="px-4 py-2 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors"
                  :disabled="!noteText.trim()"
                  :class="{'opacity-50 cursor-not-allowed': !noteText.trim()}"
                >
                  Ajouter
                </button>
              </div>
            </form>
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Overlay mobile -->
  <div class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
       x-show="sidebarOpen"
       @click="sidebarOpen = false"></div>
</body>
</html>
