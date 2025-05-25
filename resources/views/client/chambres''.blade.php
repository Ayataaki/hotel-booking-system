@extends('layouts.app')

@section('title', 'Nos Chambres')

@section('styles')
<style>
  .room-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .room-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  }

  .amenity-icon {
    transition: transform 0.2s ease;
  }

  .room-card:hover .amenity-icon {
    transform: scale(1.1);
  }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="relative h-[40vh] bg-cover bg-center flex items-center justify-center"
         style="background-image: url('https://picsum.photos/1900/1000?luxury-hotel-room')">
  <div class="absolute inset-0 bg-black/40"></div>
  <div class="relative z-10 text-center text-white px-4">
    <h1 class="text-5xl font-bold mb-2 font-['Playfair_Display']" data-aos="fade-up">Nos Chambres</h1>
    <p class="text-lg" data-aos="fade-up" data-aos-delay="100">Découvrez le confort et l'élégance de nos chambres</p>
  </div>
</section>

<!-- Filters and Category Selection -->
<section class="py-8 bg-[#F3ECE3]">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
      <div class="w-full md:w-auto">
        <h2 class="text-2xl font-semibold text-[#6d4927]">Filtrer par catégorie</h2>
      </div>

      <div class="flex flex-wrap justify-center gap-4">
        <a href="#" class="px-6 py-2 rounded-full bg-[#95714F] text-white hover:bg-[#6d4927] transition-colors duration-300">
          Toutes les chambres
        </a>
        <a href="#" class="px-6 py-2 rounded-full bg-white border border-[#95714F] text-[#95714F] hover:bg-[#F3ECE3] transition-colors duration-300">
          Standard
        </a>
        <a href="#" class="px-6 py-2 rounded-full bg-white border border-[#95714F] text-[#95714F] hover:bg-[#F3ECE3] transition-colors duration-300">
          Deluxe
        </a>
        <a href="#" class="px-6 py-2 rounded-full bg-white border border-[#95714F] text-[#95714F] hover:bg-[#F3ECE3] transition-colors duration-300">
          Suite
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Rooms Grid -->
<section class="py-16 px-4 bg-[#EADED0]">
  <div class="max-w-7xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- Room Card 1 -->
      <div class="room-card bg-white rounded-xl overflow-hidden shadow-lg" data-aos="fade-up">
        <div class="relative h-64">
          <img src="https://picsum.photos/600/400?room=1" alt="Chambre Standard" class="w-full h-full object-cover">
          <div class="absolute top-4 right-4 bg-[#95714F] text-white text-sm font-bold px-3 py-1 rounded-full">
            À partir de 99€
          </div>
        </div>

        <div class="p-6">
          <h3 class="text-xl font-bold text-[#6d4927] mb-2">Chambre Standard</h3>

          <p class="text-gray-600 mb-4">Notre chambre standard offre tout le confort nécessaire pour un séjour agréable, avec une décoration élégante et des équipements modernes.</p>

          <div class="flex flex-wrap gap-3 mb-6">
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
              </svg>
              Vue ville
            </span>
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
              </svg>
              Coffre-fort
            </span>
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
              </svg>
              Wi-Fi
            </span>
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
              </svg>
              Minibar
            </span>
          </div>

          <div class="flex items-center justify-between mt-auto">
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#95714F]" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
              </svg>
              <span class="ml-1 text-sm">Max. 2 personnes</span>
            </div>

            <a href="{{ route('reservation') }}" class="px-4 py-2 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors duration-300 no-underline">
              Réserver
            </a>
          </div>
        </div>
      </div>

      <!-- Room Card 2 -->
      <div class="room-card bg-white rounded-xl overflow-hidden shadow-lg" data-aos="fade-up" data-aos-delay="100">
        <div class="relative h-64">
          <img src="https://picsum.photos/600/400?room=2" alt="Chambre Deluxe" class="w-full h-full object-cover">
          <div class="absolute top-4 right-4 bg-[#95714F] text-white text-sm font-bold px-3 py-1 rounded-full">
            À partir de 149€
          </div>
        </div>

        <div class="p-6">
          <h3 class="text-xl font-bold text-[#6d4927] mb-2">Chambre Deluxe</h3>

          <p class="text-gray-600 mb-4">Profitez d'un espace généreux et d'un confort supérieur dans notre chambre Deluxe, avec des prestations haut de gamme pour un séjour exceptionnel.</p>

          <div class="flex flex-wrap gap-3 mb-6">
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
              </svg>
              Vue panoramique
            </span>
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
              </svg>
              Climatisation
            </span>
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
              </svg>
              Wi-Fi haut débit
            </span>
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" />
              </svg>
              TV connectée
            </span>
          </div>

          <div class="flex items-center justify-between mt-auto">
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#95714F]" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
              </svg>
              <span class="ml-1 text-sm">Max. 2 personnes</span>
            </div>

            <a href="{{ route('reservation') }}" class="px-4 py-2 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors duration-300 no-underline">
              Réserver
            </a>
          </div>
        </div>
      </div>

      <!-- Room Card 3 -->
      <div class="room-card bg-white rounded-xl overflow-hidden shadow-lg" data-aos="fade-up" data-aos-delay="200">
        <div class="relative h-64">
          <img src="https://picsum.photos/600/400?room=3" alt="Suite Prestige" class="w-full h-full object-cover">
          <div class="absolute top-4 right-4 bg-[#95714F] text-white text-sm font-bold px-3 py-1 rounded-full">
            À partir de 249€
          </div>
        </div>

        <div class="p-6">
          <h3 class="text-xl font-bold text-[#6d4927] mb-2">Suite Prestige</h3>

          <p class="text-gray-600 mb-4">Notre suite prestige vous offre une expérience incomparable avec un espace spacieux, un salon séparé et des prestations exclusives pour un séjour inoubliable.</p>

          <div class="flex flex-wrap gap-3 mb-6">
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
              </svg>
              Vue sur mer
            </span>
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
              </svg>
              Jacuzzi privé
            </span>
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
              </svg>
              Wi-Fi premium
            </span>
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
              </svg>
              Mini bar
            </span>
          </div>

          <div class="flex items-center justify-between mt-auto">
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#95714F]" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
              </svg>
              <span class="ml-1 text-sm">Max. 4 personnes</span>
            </div>

            <a href="{{ route('reservation') }}" class="px-4 py-2 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors duration-300 no-underline">
              Réserver
            </a>
          </div>
        </div>
      </div>

      <!-- Room Card 4 -->
      <div class="room-card bg-white rounded-xl overflow-hidden shadow-lg" data-aos="fade-up">
        <div class="relative h-64">
          <img src="https://picsum.photos/600/400?room=4" alt="Chambre Familiale" class="w-full h-full object-cover">
          <div class="absolute top-4 right-4 bg-[#95714F] text-white text-sm font-bold px-3 py-1 rounded-full">
            À partir de 189€
          </div>
        </div>

        <div class="p-6">
          <h3 class="text-xl font-bold text-[#6d4927] mb-2">Chambre Familiale</h3>

          <p class="text-gray-600 mb-4">Idéale pour les familles, cette chambre spacieuse peut accueillir jusqu'à 4 personnes et offre tout le confort nécessaire pour votre séjour en famille.</p>

          <div class="flex flex-wrap gap-3 mb-6">
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
              </svg>
              Vue jardin
            </span>
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
              </svg>
              Climatisation
            </span>
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
              </svg>
              Wi-Fi
            </span>
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" />
              </svg>
              TV connectée
            </span>
          </div>

          <div class="flex items-center justify-between mt-auto">
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#95714F]" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
              </svg>
              <span class="ml-1 text-sm">Max. 4 personnes</span>
            </div>

            <a href="{{ route('reservation') }}" class="px-4 py-2 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors duration-300 no-underline">
              Réserver
            </a>
          </div>
        </div>
      </div>

      <!-- Room Card 5 -->
      <div class="room-card bg-white rounded-xl overflow-hidden shadow-lg" data-aos="fade-up" data-aos-delay="100">
        <div class="relative h-64">
          <img src="https://picsum.photos/600/400?room=5" alt="Chambre Executive" class="w-full h-full object-cover">
          <div class="absolute top-4 right-4 bg-[#95714F] text-white text-sm font-bold px-3 py-1 rounded-full">
            À partir de 179€
          </div>
        </div>

        <div class="p-6">
          <h3 class="text-xl font-bold text-[#6d4927] mb-2">Chambre Executive</h3>

          <p class="text-gray-600 mb-4">Conçue pour les voyageurs d'affaires, cette chambre combine élégance et fonctionnalité avec un espace de travail dédié et des services adaptés.</p>

          <div class="flex flex-wrap gap-3 mb-6">
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
              </svg>
              Vue ville
            </span>
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zM8 11a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
              </svg>
              Bureau de travail
            </span>
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
              </svg>
              Wi-Fi haut débit
            </span>
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z" />
                <path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z" />
                <path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z" />
              </svg>
              Machine à café
            </span>
          </div>

          <div class="flex items-center justify-between mt-auto">
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#95714F]" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
              </svg>
              <span class="ml-1 text-sm">Max. 2 personnes</span>
            </div>

            <a href="{{ route('reservation') }}" class="px-4 py-2 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors duration-300 no-underline">
              Réserver
            </a>
          </div>
        </div>
      </div>

      <!-- Room Card 6 -->
      <div class="room-card bg-white rounded-xl overflow-hidden shadow-lg" data-aos="fade-up" data-aos-delay="200">
        <div class="relative h-64">
          <img src="https://picsum.photos/600/400?room=6" alt="Suite Royale" class="w-full h-full object-cover">
          <div class="absolute top-4 right-4 bg-[#95714F] text-white text-sm font-bold px-3 py-1 rounded-full">
            À partir de 399€
          </div>
        </div>

        <div class="p-6">
          <h3 class="text-xl font-bold text-[#6d4927] mb-2">Suite Royale</h3>

          <p class="text-gray-600 mb-4">Le summum du luxe et du confort, notre Suite Royale offre un espace de vie spacieux, une décoration raffinée et des services exclusifs pour un séjour d'exception.</p>

          <div class="flex flex-wrap gap-3 mb-6">
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
              </svg>
              Vue panoramique
            </span>
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
              </svg>
              Jacuzzi et sauna
            </span>
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
              </svg>
              Service de majordome
            </span>
            <span class="flex items-center text-sm text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-[#95714F] amenity-icon" viewBox="0 0 20 20" fill="currentColor">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
              </svg>
              Bar et cave à vins
            </span>
          </div>

          <div class="flex items-center justify-between mt-auto">
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#95714F]" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
              </svg>
              <span class="ml-1 text-sm">Max. 4 personnes</span>
            </div>

            <a href="{{ route('reservation') }}" class="px-4 py-2 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors duration-300 no-underline">
              Réserver
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Room Service & Amenities -->
<section class="py-16 px-4 bg-white">
  <div class="max-w-7xl mx-auto">
    <h2 class="text-3xl font-bold text-[#6d4927] text-center mb-12" data-aos="fade-up">Nos Services Inclus</h2>

    <div class="grid md:grid-cols-3 gap-8">
      <div class="text-center" data-aos="fade-up">
        <div class="w-16 h-16 bg-[#F3ECE3] rounded-full flex items-center justify-center mx-auto mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#95714F]" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-[#6d4927] mb-2">Wi-Fi Gratuit</h3>
        <p class="text-gray-600">Restez connecté pendant votre séjour avec notre Wi-Fi haut débit gratuit dans toutes les chambres et espaces communs.</p>
      </div>

      <div class="text-center" data-aos="fade-up" data-aos-delay="100">
        <div class="w-16 h-16 bg-[#F3ECE3] rounded-full flex items-center justify-center mx-auto mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#95714F]" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-[#6d4927] mb-2">Service de Conciergerie</h3>
        <p class="text-gray-600">Notre équipe de conciergerie est disponible 24h/24 pour répondre à toutes vos demandes et vous aider à organiser votre séjour.</p>
      </div>

      <div class="text-center" data-aos="fade-up" data-aos-delay="200">
        <div class="w-16 h-16 bg-[#F3ECE3] rounded-full flex items-center justify-center mx-auto mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#95714F]" viewBox="0 0 20 20" fill="currentColor">
            <path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z" />
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-[#6d4927] mb-2">Petit-déjeuner Gourmet</h3>
        <p class="text-gray-600">Commencez votre journée avec notre petit-déjeuner gourmet, disponible en option, qui propose une large sélection de produits frais et locaux.</p>
      </div>
    </div>
  </div>
</section>

<!-- Testimonials -->
<section class="py-16 px-4 bg-[#F3ECE3]">
  <div class="max-w-5xl mx-auto text-center">
    <h2 class="text-3xl font-bold text-[#6d4927] mb-12" data-aos="fade-up">Ce que disent nos clients</h2>

    <div class="grid md:grid-cols-2 gap-8">
      <div class="bg-white p-6 rounded-xl shadow-md" data-aos="fade-up">
        <div class="flex items-center justify-center mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#95714F]" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#95714F]" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#95714F]" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#95714F]" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#95714F]" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
        </div>
        <p class="text-gray-600 italic mb-4">"Un séjour parfait du début à la fin. Les chambres sont spacieuses et confortables, le personnel est attentionné et le petit-déjeuner est délicieux. Je reviendrai sans hésiter !"</p>
        <p class="font-semibold text-[#6d4927]">Sophie M.</p>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-md" data-aos="fade-up" data-aos-delay="100">
        <div class="flex items-center justify-center mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#95714F]" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#95714F]" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#95714F]" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#95714F]" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#95714F]" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
        </div>
        <p class="text-gray-600 italic mb-4">"Une perle rare au cœur de la ville. J'ai séjourné dans la suite prestige et c'était absolument magnifique. Le service est impeccable et l'emplacement idéal pour explorer la région."</p>
        <p class="font-semibold text-[#6d4927]">Thomas L.</p>
      </div>
    </div>

    <a href="#" class="inline-block mt-8 px-6 py-3 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors duration-300 no-underline" data-aos="fade-up">
      Voir tous les avis
    </a>
  </div>
</section>

<!-- CTA -->
<section class="py-16 px-4 bg-cover bg-center relative text-white" style="background-image: url('https://picsum.photos/1920/600?hotel-pool')">
  <div class="absolute inset-0 bg-black/50"></div>
  <div class="max-w-4xl mx-auto text-center relative z-10">
    <h2 class="text-3xl md:text-4xl font-bold mb-4" data-aos="fade-up">Réservez dès maintenant</h2>
    <p class="text-lg mb-8" data-aos="fade-up" data-aos-delay="100">Profitez de nos offres spéciales et vivez une expérience inoubliable à Mi Casa.</p>
    <a href="{{ route('reservation') }}" class="inline-block px-8 py-4 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors duration-300 text-lg font-medium no-underline" data-aos="fade-up" data-aos-delay="200">
      Réserver votre chambre
    </a>
  </div>
</section>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialisation d'AOS si nécessaire (déjà fait dans app.blade.php)
    // AOS.init();

    // Vous pouvez ajouter ici d'autres scripts spécifiques à la page des chambres
  });
</script>
@endsection
