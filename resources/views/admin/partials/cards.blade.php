<!-- resources/views/admin/partials/cards.blade.php -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
  <!-- Card: Réservations -->
  <div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-bold text-[#6d4927]">Réservations</h3>
      <div class="w-12 h-12 rounded-full bg-[#EADED0] flex items-center justify-center text-[#95714F]">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
      </div>
    </div>
    <p class="text-3xl font-bold mb-1">{{ $totalReservations }}</p>
    <p class="text-sm text-gray-500">Total des réservations</p>
    <div class="mt-4">
      <a href="#" class="text-sm text-[#95714F] flex items-center">
        <span>Voir toutes</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </a>
    </div>
  </div>


  <!-- Card: Chambres -->
  <div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-bold text-[#6d4927]">Chambres</h3>
      <div class="w-12 h-12 rounded-full bg-[#EADED0] flex items-center justify-center text-[#95714F]">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
      </div>
    </div>
    <p class="text-3xl font-bold mb-1">{{ $totalChambres }}</p>
    <p class="text-sm text-gray-500">Total des chambres</p>
    <div class="mt-4">
      <a href="#" class="text-sm text-[#95714F] flex items-center">
        <span>Gérer les chambres</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </a>
    </div>
  </div>

  <!-- Card: Personnel -->
  <div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-bold text-[#6d4927]">Personnel</h3>
      <div class="w-12 h-12 rounded-full bg-[#EADED0] flex items-center justify-center text-[#95714F]">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
      </div>
    </div>
    <p class="text-3xl font-bold mb-1">{{ $totalEmployes }}</p>
    <p class="text-sm text-gray-500">Employés actifs</p>
    <div class="mt-4">
      <a href="#" class="text-sm text-[#95714F] flex items-center">
        <span>Gérer le personnel</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </a>
    </div>
  </div>

  <!-- Card: Clients -->
  <div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-bold text-[#6d4927]">Clients</h3>
      <div class="w-12 h-12 rounded-full bg-[#EADED0] flex items-center justify-center text-[#95714F]">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
      </div>
    </div>
    <p class="text-3xl font-bold mb-1">{{ $totalClients }}</p>
    <p class="text-sm text-gray-500">Clients enregistrés</p>
    <div class="mt-4">
      <a href="#" class="text-sm text-[#95714F] flex items-center">
        <span>Voir les clients</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </a>
    </div>
  </div>
</div>
