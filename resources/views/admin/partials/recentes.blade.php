<!-- resources/views/admin/partials/recentes.blade.php -->
<div class="bg-white rounded-xl shadow-md p-6 mb-8">
  <div class="flex items-center justify-between mb-6">
    <h3 class="text-lg font-bold text-[#6d4927]">Réservations récentes</h3>
    <a href="#" class="text-sm text-[#95714F] flex items-center">
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
        @foreach($recentReservations as $reservation)
          <tr>
            <td class="px-4 py-3 whitespace-nowrap">{{ $reservation->chambre->categorie->nom ?? 'Non spécifiée' }}</td>
            <td class="px-4 py-3 whitespace-nowrap">{{ $reservation->dateDeb->format('Y-m-d') }}</td>
            <td class="px-4 py-3 whitespace-nowrap">{{ $reservation->dateFin->format('Y-m-d') }}</td>
            <!-- <td class="px-4 py-3 whitespace-nowrap">
              <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Confirmée</span>
            </td> -->
            <td class="px-4 py-3 whitespace-nowrap">
              <span class="px-2 py-1 text-xs rounded-full {{ $couleurs[$reservation->statut] ?? 'bg-gray-100 text-gray-800' }}">
                {{ ucfirst($reservation->statut) }}
              </span>
            </td>
            <td class="px-4 py-3 whitespace-nowrap">
              <a href="#" class="text-[#95714F] hover:text-[#6d4927]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
              </a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
