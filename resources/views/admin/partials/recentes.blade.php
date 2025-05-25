<!-- resources/views/admin/partials/recentes.blade.php -->
<div class="bg-white rounded-xl shadow-md p-6 mb-8">
  <div class="flex items-center justify-between mb-6">
    <h3 class="text-lg font-bold text-[#6d4927]">Réservations récentes</h3>
    <a href="{{ route('admin.reservations') }}" class="text-sm text-[#95714F] flex items-center">
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
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CLIENT</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CHECK-IN</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CHECK-OUT</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MONTANT</th>
          <!-- <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STATUT</th> -->
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Détails</th>
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
        @forelse($recentReservations as $reservation)
          <tr>
            <td class="px-4 py-3 whitespace-nowrap">#{{ $reservation->id }}</td>
            <td class="px-4 py-3 whitespace-nowrap">{{ $reservation->client->nom ?? 'N/A' }} {{ $reservation->client->prenom ?? '' }}</td>
            <td class="px-4 py-3 whitespace-nowrap">{{ $reservation->dateDeb->format('d/m/Y') }}</td>
            <td class="px-4 py-3 whitespace-nowrap">{{ $reservation->dateFin->format('d/m/Y') }}</td>
            <td class="px-4 py-3 whitespace-nowrap">{{ number_format($reservation->soldePayer, 2, ',', ' ') }} €</td>
            <!-- <td class="px-4 py-3 whitespace-nowrap">
              <span class="px-2 py-1 text-xs rounded-full {{ $couleurs[$reservation->statut] ?? 'bg-gray-100 text-gray-800' }}">
                {{ ucfirst($reservation->statut) }}
              </span>
            </td> -->
            <td class="px-4 py-3 whitespace-nowrap">
              <a href="{{ route('admin.reservations') }}?id={{ $reservation->id }}" class="text-[#95714F] hover:text-[#6d4927]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-4 py-3 text-center text-gray-500">Aucune réservation récente</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
