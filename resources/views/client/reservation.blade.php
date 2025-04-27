@extends('layouts.app')

@section('title', 'Réservation')

@section('styles')
<style>
  [x-cloak] { display: none !important; }

  .transition-opacity {
    transition-property: opacity;
  }

  .modal-overlay {
    will-change: opacity;
    backface-visibility: hidden;
  }

  .fade-in {
    animation: fadeIn 0.8s ease forwards;
    opacity: 0;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .delay-1 { animation-delay: 0.1s; }
  .delay-2 { animation-delay: 0.2s; }
  .delay-3 { animation-delay: 0.3s; }

  /* Style pour le formulaire par étapes */
  .step-indicator {
    height: 2px;
    transition: width 0.3s ease;
  }
</style>
@endsection

@section('content')
<!-- Hero Section - Réservation -->
<section class="relative h-[40vh] bg-cover bg-center flex items-center justify-center" style="background-image: url('{{ asset('images/luxury_hotel.jpg') }}')">
  <div class="absolute inset-0 bg-black/50"></div>
  <div class="relative z-10 text-center text-white px-4">
    <h1 class="text-5xl font-bold" data-aos="fade-up">Réservation</h1>
    <p class="text-lg mt-2" data-aos="fade-up" data-aos-delay="100">Planifiez votre séjour chez LA MI CASA</p>
  </div>
</section>

<!-- Section principale de réservation -->
<section x-data="reservationForm({
    roomsData: {{ json_encode($rooms) }},
    extrasData: {{ json_encode($services) }},
    categoriesMap: {{ json_encode($categoriesMap) }}
  })">

  <script>
    document.addEventListener('alpine:init', () => {
  Alpine.data('reservationForm', function({ roomsData, extrasData, categoriesMap }) {
    return {
      step: 1,
      startDate: '',
      endDate: '',
      adultsCount: 1,
      childrenCount: 0,
      selectedRooms: [],
      firstName: '',
      lastName: '',
      phone: '',
      typeId: '',
      CIN: '',
      passeport: '',
      specialRequests: '',
      
      // Dynamiser les chambres
      roomOptions: Array.isArray(roomsData) ? roomsData.map(room => {
        const categorie = categoriesMap[room.categorie_id] || null;
        return {
          id: room.id,
          name: `  ${categorie ? categorie.typeChambre : 'Chambre Standard'}`,
          type: categorie ? categorie.typeChambre : 'Standard',
          price: room.prixNuit || 99,
          image: room.image ? `/images/${room.image}` : '/images/default-room.jpg',
          description: categorie ? categorie.description : 'Chambre confortable avec tous les équipements nécessaires pour votre séjour.',
          capacity: room.capacite || 2
        };
      }) : [],

      // Dynamiser les services
      extraOptions: Array.isArray(extrasData) ? extrasData.map(extra => ({
          id: extra.id,
          name: extra.libelle || extra.nom,
          price: extra.tarif || extra.prix,
          description: extra.description || '',
          selected: false
      })) : [],
      
      init() {
        console.log('Formulaire de réservation initialisé');
        console.log('Chambres:', this.roomOptions);
        console.log('Services:', this.extraOptions);
      },
      
      // Fonction formatPrice unique pour éviter les problèmes
      formatPrice(price) {
        return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(price);
      },

      // Basculer la sélection d'une chambre
      toggleRoomSelection(roomId) {
        const index = this.selectedRooms.indexOf(roomId);
        if (index === -1) {
          this.selectedRooms.push(roomId);
          console.log(`Chambre ${roomId} ajoutée à la sélection`);
        } else {
          this.selectedRooms.splice(index, 1);
          console.log(`Chambre ${roomId} retirée de la sélection`);
        }
        console.log('Chambres actuellement sélectionnées:', this.selectedRooms);
      },

      // Basculer la sélection d'un extra/service
      toggleExtra(id) {
        const extra = this.extraOptions.find(e => e.id === id);
        if (extra) {
          extra.selected = !extra.selected;
          console.log(`Service ${id} ${extra.selected ? 'sélectionné' : 'désélectionné'}`);
        }
      },

      // Calculer le nombre de nuits
      calculateNights() {
        if (!this.startDate || !this.endDate) return 0;
        
        try {
          const start = new Date(this.startDate);
          const end = new Date(this.endDate);
          
          if (isNaN(start.getTime()) || isNaN(end.getTime())) {
            console.error('Dates invalides', this.startDate, this.endDate);
            return 0;
          }
          
          const diffTime = Math.abs(end - start);
          const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
          
          return diffDays;
        } catch (e) {
          console.error('Erreur de calcul des nuits:', e);
          return 0;
        }
      },

      // Calculer le prix total
      calculateTotalPrice() {
        if (this.selectedRooms.length === 0) return 0;

        const nights = this.calculateNights();
        let total = 0;

        // Calculer le prix pour toutes les chambres sélectionnées
        this.selectedRooms.forEach(roomId => {
          const room = this.roomOptions.find(r => r.id === roomId);
          if (room) {
            total += room.price * nights;
          }
        });

        // Ajouter le prix des extras
        this.extraOptions.forEach(extra => {
          if (extra.selected) {
            total += extra.price * nights;
          }
        });

        return total;
      },

      // Validation des étapes
      validateStep1() {
        if (!this.startDate || !this.endDate) {
          alert('Veuillez sélectionner des dates de séjour');
          return false;
        }

        const start = new Date(this.startDate);
        const end = new Date(this.endDate);
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (start < today) {
          alert('La date d\'arrivée doit être aujourd\'hui ou après');
          return false;
        }

        if (end <= start) {
          alert('La date de départ doit être après la date d\'arrivée');
          return false;
        }

        if (this.adultsCount < 1) {
          alert('Il doit y avoir au moins 1 adulte');
          return false;
        }

        return true;
      },

      validateStep2() {
        if (this.selectedRooms.length === 0) {
          alert('Veuillez sélectionner au moins une chambre');
          return false;
        }

        const totalPeople = this.adultsCount + this.childrenCount;
        let totalCapacity = 0;

        this.selectedRooms.forEach(roomId => {
          const room = this.roomOptions.find(r => r.id === roomId);
          if (room) {
            totalCapacity += room.capacity;
          }
        });

        if (totalPeople > totalCapacity) {
          alert(`Les chambres sélectionnées ne peuvent accueillir que ${totalCapacity} personnes. Veuillez sélectionner des chambres supplémentaires ou réduire le nombre de voyageurs.`);
          return false;
        }

        return true;
      },

      validateStep3() {
        if (!this.firstName || !this.lastName || !this.phone) {
          alert('Veuillez compléter tous les champs obligatoires');
          return false;
        }

        return true;
      },

      validateStep4() {
        const termsCheckbox = document.getElementById('terms');
        if (!termsCheckbox || !termsCheckbox.checked) {
          alert('Veuillez accepter les conditions générales');
          return false;
        }

        return true;
      },

      // Navigation entre les étapes
      nextStep() {
        console.log('Étape actuelle:', this.step);
        if (this.step === 1 && !this.validateStep1()) return;
        if (this.step === 2 && !this.validateStep2()) return;
        if (this.step === 3 && !this.validateStep3()) return;
        if (this.step < 4) {
          this.step++;
          console.log('Passage à l\'étape:', this.step);
        }
      },

      prevStep() {
        if (this.step > 1) {
          this.step--;
          console.log('Retour à l\'étape:', this.step);
        }
      },

      submitReservation() {
        if (!this.validateStep4()) return;

        const form = document.getElementById('reservationForm');
        if (!form) {
          console.error('Formulaire non trouvé');
          return;
        }
        
        // Supprimer toute entrée hidden existante
        const existingRooms = form.querySelectorAll('input[name^="chambresIds"]');
        existingRooms.forEach(input => input.remove());
        
        const existingServices = form.querySelectorAll('input[name^="services"]');
        existingServices.forEach(input => input.remove());
        
        // Ajouter les IDs de chambres
        this.selectedRooms.forEach((roomId, index) => {
          const roomInput = document.createElement('input');
          roomInput.type = 'hidden';
          roomInput.name = `chambresIds[${index}]`;
          roomInput.value = roomId;
          form.appendChild(roomInput);
        });
        
        // Ajouter les services
        const selectedServices = this.extraOptions.filter(extra => extra.selected);
        selectedServices.forEach((extra, index) => {
          const extraInput = document.createElement('input');
          extraInput.type = 'hidden';
          extraInput.name = 'services[]';
          extraInput.value = extra.id;
          form.appendChild(extraInput);
        });
        
        // Ajouter adultes et enfants
        const adultsInput = document.createElement('input');
        adultsInput.type = 'hidden';
        adultsInput.name = 'adultsCount';
        adultsInput.value = this.adultsCount;
        form.appendChild(adultsInput);
        
        const childrenInput = document.createElement('input');
        childrenInput.type = 'hidden';
        childrenInput.name = 'childrenCount';
        childrenInput.value = this.childrenCount;
        form.appendChild(childrenInput);
        
        // Ajouter total
        const totalInput = document.createElement('input');
        totalInput.type = 'hidden';
        totalInput.name = 'totalPayer';
        totalInput.value = this.calculateTotalPrice();
        form.appendChild(totalInput);
        
        console.log('Soumission du formulaire avec les données:', {
          chambres: this.selectedRooms,
          dates: [this.startDate, this.endDate],
          personnes: {adultes: this.adultsCount, enfants: this.childrenCount},
          services: selectedServices.map(s => ({ id: s.id, name: s.name, price: s.price })),
          total: this.calculateTotalPrice()
        });
        
        form.submit();
      }
    };
  });
});
  </script>

  <!-- Indicateur d'étape -->
  {{-- <div class="mb-10">
    <div class="flex justify-between mb-2">
      <div class="text-center flex-1" :class="step >= 1 ? 'text-[#95714F] font-medium' : 'text-gray-400'">
        <div class="flex items-center justify-center mb-2">
          <div class="w-8 h-8 rounded-full flex items-center justify-center"
            :class="step >= 1 ? 'bg-[#95714F] text-white' : 'bg-gray-200 text-gray-500'">
            1
          </div>
        </div>
        <span class="text-sm hidden md:block">Dates</span>
      </div>

      <div class="text-center flex-1" :class="step >= 2 ? 'text-[#95714F] font-medium' : 'text-gray-400'">
        <div class="flex items-center justify-center mb-2">
          <div class="w-8 h-8 rounded-full flex items-center justify-center"
            :class="step >= 2 ? 'bg-[#95714F] text-white' : 'bg-gray-200 text-gray-500'">
            2
          </div>
        </div>
        <span class="text-sm hidden md:block">Chambre</span>
      </div>

      <div class="text-center flex-1" :class="step >= 3 ? 'text-[#95714F] font-medium' : 'text-gray-400'">
        <div class="flex items-center justify-center mb-2">
          <div class="w-8 h-8 rounded-full flex items-center justify-center"
            :class="step >= 3 ? 'bg-[#95714F] text-white' : 'bg-gray-200 text-gray-500'">
            3
          </div>
        </div>
        <span class="text-sm hidden md:block">Informations</span>
      </div>

      <div class="text-center flex-1" :class="step >= 4 ? 'text-[#95714F] font-medium' : 'text-gray-400'">
        <div class="flex items-center justify-center mb-2">
          <div class="w-8 h-8 rounded-full flex items-center justify-center"
            :class="step >= 4 ? 'bg-[#95714F] text-white' : 'bg-gray-200 text-gray-500'">
            4
          </div>
        </div>
        <span class="text-sm hidden md:block">Paiement</span>
      </div>
    </div>

    <div class="relative h-2 bg-gray-200 rounded-full overflow-hidden">
      <div class="absolute left-0 top-0 h-full bg-[#95714F] step-indicator"
           :style="'width: ' + (step * 25) + '%'"></div>
    </div>
  </div> --}}
  <div class="mb-10 max-w-4xl mx-auto">
    <div class="flex justify-between mb-2">
      <div class="text-center flex-1" :class="step >= 1 ? 'text-[#95714F] font-medium' : 'text-gray-400'">
        <div class="flex items-center justify-center mb-2">
          <div class="w-8 h-8 rounded-full flex items-center justify-center"
            :class="step >= 1 ? 'bg-[#95714F] text-white' : 'bg-gray-200 text-gray-500'">
            1
          </div>
        </div>
        <span class="text-sm hidden md:block">Dates</span>
      </div>
  
      <div class="text-center flex-1" :class="step >= 2 ? 'text-[#95714F] font-medium' : 'text-gray-400'">
        <div class="flex items-center justify-center mb-2">
          <div class="w-8 h-8 rounded-full flex items-center justify-center"
            :class="step >= 2 ? 'bg-[#95714F] text-white' : 'bg-gray-200 text-gray-500'">
            2
          </div>
        </div>
        <span class="text-sm hidden md:block">Chambre</span>
      </div>
  
      <div class="text-center flex-1" :class="step >= 3 ? 'text-[#95714F] font-medium' : 'text-gray-400'">
        <div class="flex items-center justify-center mb-2">
          <div class="w-8 h-8 rounded-full flex items-center justify-center"
            :class="step >= 3 ? 'bg-[#95714F] text-white' : 'bg-gray-200 text-gray-500'">
            3
          </div>
        </div>
        <span class="text-sm hidden md:block">Informations</span>
      </div>
  
      <div class="text-center flex-1" :class="step >= 4 ? 'text-[#95714F] font-medium' : 'text-gray-400'">
        <div class="flex items-center justify-center mb-2">
          <div class="w-8 h-8 rounded-full flex items-center justify-center"
            :class="step >= 4 ? 'bg-[#95714F] text-white' : 'bg-gray-200 text-gray-500'">
            4
          </div>
        </div>
        <span class="text-sm hidden md:block">Paiement</span>
      </div>
    </div>
  
    <div class="relative h-2 bg-gray-200 rounded-full overflow-hidden">
      <div class="absolute left-0 top-0 h-full bg-[#95714F] step-indicator"
           :style="'width: ' + (step * 25) + '%'"></div>
    </div>
  </div>

  
<form id="reservationForm" action="{{ route('reservation.store.online') }}" method="POST">
  @csrf

  <!-- Contenu du formulaire -->
  <div class="bg-white shadow-xl rounded-2xl p-6 md:p-8 max-w-4xl mx-auto">
    <!-- Étape 1: Dates et nombre de personnes -->
        <div x-show="step === 1" class="fade-in">
      <h2 class="text-2xl font-bold text-[#6d4927] mb-6">Choisissez vos dates et le nombre de personnes</h2>

      <div class="grid md:grid-cols-2 gap-6 mb-8">
        <div>
          <label for="arrival-date" class="block text-sm font-medium mb-2">Date d'arrivée</label>
          <input type="date" id="arrival-date" name="dateDeb" x-model="startDate"
               class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
               :min="new Date().toISOString().split('T')[0]">
        </div>

        <div>
          <label for="departure-date" class="block text-sm font-medium mb-2">Date de départ</label>
          <input type="date" id="departure-date" name="dateFin" x-model="endDate"
               class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"
               :min="startDate || new Date().toISOString().split('T')[0]">
        </div>
      </div>

      <div class="grid md:grid-cols-2 gap-6 mb-12">
        <div>
          <label for="adults" class="block text-sm font-medium mb-2">Adultes</label>
          <div class="flex items-center">
            <button type="button" @click="adultsCount > 1 ? adultsCount-- : adultsCount" class="w-10 h-10 bg-gray-100 rounded-l-lg flex items-center justify-center hover:bg-gray-200">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
              </svg>
            </button>
            <input type="number" id="adults" x-model.number="adultsCount" min="1" max="10" class="w-full text-center py-2 border-t border-b border-gray-300">
            <button type="button" @click="adultsCount < 10 ? adultsCount++ : adultsCount" class="w-10 h-10 bg-gray-100 rounded-r-lg flex items-center justify-center hover:bg-gray-200">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>

        <div>
          <label for="children" class="block text-sm font-medium mb-2">Enfants</label>
          <div class="flex items-center">
            <button type="button" @click="childrenCount > 0 ? childrenCount-- : childrenCount" class="w-10 h-10 bg-gray-100 rounded-l-lg flex items-center justify-center hover:bg-gray-200">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
              </svg>
            </button>
            <input type="number" id="children" x-model.number="childrenCount" min="0" max="10" class="w-full text-center py-2 border-t border-b border-gray-300">
            <button type="button" @click="childrenCount < 10 ? childrenCount++ : childrenCount" class="w-10 h-10 bg-gray-100 rounded-r-lg flex items-center justify-center hover:bg-gray-200">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <div class="flex justify-end">
        <button type="button" @click="nextStep" class="px-6 py-3 bg-[#95714F] text-white font-medium rounded-lg hover:bg-[#6d4927] transition-colors duration-300 flex items-center">
          Continuer
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
        </div>

    <!-- Étape 2: Sélection de chambre -->
        <div x-show="step === 2" class="fade-in" x-cloak>
      <h2 class="text-2xl font-bold text-[#6d4927] mb-6">Choisissez votre chambre</h2>

      <p class="text-gray-600 mb-6" x-show="calculateNights() > 0">
        <span class="font-medium">Durée du séjour:</span> <span x-text="calculateNights()"></span> nuit<span x-show="calculateNights() > 1">s</span>
      </p>

     {{--  <div class="space-y-4 mb-12">
        <template x-for="room in roomOptions" :key="room.id">
          <div class="border rounded-xl overflow-hidden cursor-pointer transition-all duration-300 hover:shadow-lg"
               :class="selectedRoom === room.id ? 'border-[#95714F] ring-2 ring-[#95714F] shadow-lg' : 'border-gray-200'"
               @click="selectRoom(room.id)">
            <div class="flex flex-col md:flex-row">
              <div class="w-full md:w-1/3 h-48">
                <img :src="room.image" :alt="room.name" class="w-full h-full object-cover">
              </div>

              <div class="w-full md:w-2/3 p-4 md:p-6 flex flex-col">
                <div class="flex items-center justify-between mb-2">
                  <h3 class="text-xl font-bold text-[#95714F]" x-text="room.name"></h3>
                  <span class="px-3 py-1 bg-[#F3ECE3] text-[#95714F] text-sm font-semibold rounded-full" x-text="room.type"></span>
                </div>

                <p class="text-gray-600 text-sm mb-4" x-text="room.description"></p>

                
                <div class="mt-auto flex items-center justify-between">
                    <div>
                      <span class="text-sm text-gray-600">Capacité max.: </span>
                      <span class="font-medium" x-text="room.capacity"></span>
                      <span class="text-sm text-gray-600"> personnes</span>
                    </div>
                  
                    <div class="text-right">
                      <span class="block text-lg font-bold text-[#6d4927]" x-text="formatPrice(room.price)"></span>
                      <span class="text-xs text-gray-500">par nuit</span>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </template>
      </div> --}}

      <div class="space-y-4 mb-12">
        <template x-for="room in roomOptions" :key="room.id">
          <div class="border rounded-xl overflow-hidden cursor-pointer transition-all duration-300 hover:shadow-lg"
               :class="selectedRooms.includes(room.id) ? 'border-[#95714F] ring-2 ring-[#95714F] shadow-lg' : 'border-gray-200'"
               @click="toggleRoomSelection(room.id)">
            <div class="flex flex-col md:flex-row">
              <div class="w-full md:w-1/3 h-48 relative">
                <img :src="room.image" :alt="room.name" class="w-full h-full object-cover">
                <!-- Badge de sélection -->
                <div x-show="selectedRooms.includes(room.id)" class="absolute top-2 right-2 bg-[#95714F] text-white p-1 rounded-full">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
              </div>
      
              <div class="w-full md:w-2/3 p-4 md:p-6 flex flex-col">
                <div class="flex items-center justify-between mb-2">
                  <h3 class="text-xl font-bold text-[#95714F]" x-text="room.name"></h3>
                  <span class="px-3 py-1 bg-[#F3ECE3] text-[#95714F] text-sm font-semibold rounded-full" x-text="room.type"></span>
                </div>
      
                <p class="text-gray-600 text-sm mb-4" x-text="room.description"></p>
      
                <div class="mt-auto flex items-center justify-between">
                  <div>
                    <span class="text-sm text-gray-600">Capacité max.: </span>
                    <span class="font-medium" x-text="room.capacity"></span>
                    <span class="text-sm text-gray-600"> personnes</span>
                  </div>
      
                  <div class="flex items-center">
                    <div class="mr-3">
                      <label class="flex items-center cursor-pointer">
                        <input type="checkbox" class="h-5 w-5 text-[#95714F] focus:ring-[#95714F] rounded"
                               :checked="selectedRooms.includes(room.id)"
                               @click.stop="toggleRoomSelection(room.id)">
                        <span class="ml-2 text-sm font-medium text-gray-700">Sélectionner</span>
                      </label>
                    </div>
                    <div class="text-right">
                      <span class="block text-lg font-bold text-[#6d4927]" x-text="formatPrice(room.price)"></span>
                      <span class="text-xs text-gray-500">par nuit</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>
      </div>

      <div class="flex justify-between">
        <button type="button" @click="prevStep" class="px-6 py-3 border border-[#95714F] text-[#95714F] font-medium rounded-lg hover:bg-[#F3ECE3] transition-colors duration-300 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
          Retour
        </button>

        <button type="button" @click="nextStep" class="px-6 py-3 bg-[#95714F] text-white font-medium rounded-lg hover:bg-[#6d4927] transition-colors duration-300 flex items-center">
          Continuer
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
        </div>

    <!-- Étape 3: Information personnelles + extras -->
        <div x-show="step === 3" class="fade-in" x-cloak>
      <h2 class="text-2xl font-bold text-[#6d4927] mb-6">Vos informations et options</h2>

      <div class="grid md:grid-cols-2 gap-6 divmb-8">
        <div>
          <label for="first-name" class="block text-sm font-medium mb-2">Prénom*</label>
          <input type="text" id="first-name" name="prenom" x-model="firstName" placeholder="Votre prénom"
               class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
        </div>

        <div>
          <label for="last-name" class="block text-sm font-medium mb-2">Nom*</label>
          <input type="text" id="last-name" name="nom" x-model="lastName" placeholder="Votre nom"
               class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
        </div>
        
        <div class="col-md-6">
          <label for="typeId" class="form-label">Type ID :</label>
          <select name="typeId" id="typeId" x-model="typeId" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
              <option value="">Sélectionnez un type</option>
              <option value="CIN">CIN</option>
              <option value="passeport">Passeport</option>
          </select>
        </div>
  
        <!-- Champ pour CIN -->
        <div class="col-md-6 mt-3" x-show="typeId === 'CIN'">
            <label class="form-label">Numéro CIN :</label>
            <input type="text" id="CIN" name="CIN" x-model="CIN" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
        </div>
    
        <!-- Champ pour Passeport -->
        <div class="col-md-6 mt-3" x-show="typeId === 'passeport'">
            <label class="form-label">Numéro Passeport :</label>
            <input type="text" id="Passeport" name="passeport" x-model="passeport" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
        </div>

        <!--j'ai enlevé le champ email parce que ça sera stocké par le form de l'authentification-->

        <div>
          <label for="phone" class="block text-sm font-medium mb-2">Téléphone*</label>
          <input type="tel" id="phone" name="numTel" x-model="phone" placeholder="Votre numéro de téléphone"
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
        </div>

        <div>
          <label for="pays" class="block text-sm font-medium mb-2">Pays*</label>
          <input type="text" id="pays" name="pays"  placeholder="Votre pays"
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
        </div>
        
        <div>
          <label for="region" class="block text-sm font-medium mb-2">Région*</label>
          <input type="text" id="region" name="region"  placeholder="Votre région"
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]">
        </div>
      </div>

      <!--to check later-->
      <div class="mb-8">
        <label for="special-requests" class="block text-sm font-medium mb-2">Demandes spéciales</label>
        <textarea id="special-requests" x-model="specialRequests" rows="3" placeholder="Précisez toute demande particulière..."
             class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]"></textarea>
      </div>

      <h3 class="text-xl font-medium text-[#6d4927] mb-4">Services supplémentaires</h3>

      <div class="grid md:grid-cols-2 gap-4 mb-12">
        <template x-for="extra in extraOptions" :key="extra.id">
          <div class="border rounded-lg p-4 cursor-pointer transition-all duration-300"
               :class="extra.selected ? 'border-[#95714F] bg-[#F3ECE3]' : 'border-gray-200 hover:border-gray-300'"
               @click="toggleExtra(extra.id)">
            <div class="flex items-start">
              <div class="flex-shrink-0 mt-0.5">
                <div class="w-5 h-5 border rounded flex items-center justify-center"
                     :class="extra.selected ? 'bg-[#95714F] border-[#95714F]' : 'border-gray-300'">
                  <svg x-show="extra.selected" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </div>
              </div>
      
              <div class="ml-3 flex-1">
                <div class="flex justify-between">
                  <span class="font-medium" x-text="extra.name"></span>
                  <span class="font-medium text-[#6d4927]" x-text="formatPrice(extra.price)"></span>
                </div>
                <p class="text-sm text-gray-600 mt-1" x-text="extra.description"></p>
              </div>
            </div>
          </div>
        </template>
      </div>

      <div class="flex justify-between">
        <button type="button" @click="prevStep" class="px-6 py-3 border border-[#95714F] text-[#95714F] font-medium rounded-lg hover:bg-[#F3ECE3] transition-colors duration-300 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            Retour
          </button>
  
          <button type="button" @click="nextStep" class="px-6 py-3 bg-[#95714F] text-white font-medium rounded-lg hover:bg-[#6d4927] transition-colors duration-300 flex items-center">
            Continuer
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
        </div>
  

        <!-- Étape 4: Paiement et récapitulatif -->
        <div x-show="step === 4" class="fade-in" x-cloak>
            <div class="grid md:grid-cols-2 gap-8">
            <!-- Récapitulatif de la réservation -->
            <div class="order-2 md:order-1">
                <h2 class="text-2xl font-bold text-[#6d4927] mb-6">Récapitulatif de la réservation</h2>
                
                <div class="bg-[#F3ECE3] rounded-xl p-6 mb-6">
                <template x-if="selectedRooms.length > 0">
                    <div>
                    <!-- Entête du récapitulatif -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-lg text-[#6d4927]">Détails de votre réservation</h3>
                        <div class="bg-[#95714F] text-white px-3 py-1 rounded-full text-sm">
                        <span x-text="selectedRooms.length"></span> chambre<span x-show="selectedRooms.length > 1">s</span>
                        </div>
                    </div>
        
                    <!-- Dates et informations générales -->
                    <div class="space-y-2 text-sm border-b border-gray-300 pb-4 mb-4">
                        <div class="flex justify-between">
                        <span>Arrivée:</span>
                        <span class="font-medium" x-text="startDate ? new Date(startDate).toLocaleDateString('fr-FR', {day: 'numeric', month: 'long', year: 'numeric'}) : '-'"></span>
                        </div>
                        <div class="flex justify-between">
                        <span>Départ:</span>
                        <span class="font-medium" x-text="endDate ? new Date(endDate).toLocaleDateString('fr-FR', {day: 'numeric', month: 'long', year: 'numeric'}) : '-'"></span>
                        </div>
                        <div class="flex justify-between">
                        <span>Durée:</span>
                        <span class="font-medium" x-text="calculateNights() + ' nuit' + (calculateNights() > 1 ? 's' : '')"></span>
                        </div>
                        <div class="flex justify-between">
                        <span>Voyageurs:</span>
                        <span class="font-medium" x-text="adultsCount + ' adulte' + (adultsCount > 1 ? 's' : '') + (childrenCount > 0 ? ', ' + childrenCount + ' enfant' + (childrenCount > 1 ? 's' : '') : '')"></span>
                        </div>
                    </div>
        
                    <!-- Liste des chambres sélectionnées -->
                    <div class="space-y-4 mb-6">
                        <h4 class="font-medium text-[#6d4927]">Chambres:</h4>
        
                        <template x-for="roomId in selectedRooms" :key="roomId">
                        <div class="bg-white rounded-lg p-3 shadow-sm border border-[#E5D5C0] flex justify-between items-center">
                            <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#95714F] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <div>
                                <div class="font-medium" x-text="roomOptions.find(r => r.id === roomId)?.name"></div>
                                <div class="text-xs text-gray-500">
                                Capacité: <span x-text="roomOptions.find(r => r.id === roomId)?.capacity"></span> personne<span x-show="roomOptions.find(r => r.id === roomId)?.capacity > 1">s</span>
                                </div>
                            </div>
                            </div>
                            <div class="font-bold text-[#6d4927]" x-text="formatPrice((roomOptions.find(r => r.id === roomId)?.price || 0) * calculateNights())"></div>
                        </div>
                        </template>
                    </div>
        
                    <!-- Services supplémentaires -->
                    <template x-if="extraOptions.some(e => e.selected)">
                        <div class="space-y-4 mb-6">
                        <h4 class="font-medium text-[#6d4927]">Services supplémentaires:</h4>
        
                        <template x-for="extra in extraOptions.filter(e => e.selected)" :key="extra.id">
                            <div class="bg-[#F8F4F0] rounded-lg p-3 shadow-sm border border-[#E5D5C0] flex justify-between items-center">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#95714F] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                                <div>
                                <div class="font-medium" x-text="extra.name"></div>
                                <template x-if="extra.id === 1">
                                    <div class="text-xs text-gray-500">
                                    <span x-text="adultsCount + childrenCount"></span> personne<span x-show="(adultsCount + childrenCount) !== 1">s</span>
                                    × <span x-text="calculateNights()"></span> jour<span x-show="calculateNights() > 1">s</span>
                                    </div>
                                </template>
                                </div>
                            </div>
                            <div class="font-bold text-[#6d4927]" x-text="formatPrice(extra.id === 1 ? extra.price * (adultsCount + childrenCount) * calculateNights() : extra.price)"></div>
                            </div>
                        </template>
                        </div>
                    </template>
        
                    <!-- Total -->
                    <div class="flex justify-between items-center bg-[#95714F] text-white p-4 rounded-lg">
                        <span class="text-lg font-medium">Total</span>
                        <span class="text-xl font-bold" x-text="formatPrice(calculateTotalPrice())"></span>
                        <input type="hidden" name="totalPayer" x-bind:value="calculateTotalPrice()">
                    </div>
                    </div>
                </template>
        
                <template x-if="selectedRooms.length === 0">
                    <div class="text-center py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <p class="text-gray-600 mb-4">Veuillez sélectionner au moins une chambre à l'étape 2</p>
                    <button type="button" @click="step = 2" class="px-4 py-2 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors duration-300">
                        Retourner à la sélection de chambres
                    </button>
                    </div>
                </template>
                </div>
            </div>
        
            <!-- Formulaire de paiement -->
            {{-- <div class="order-1 md:order-2">
                <h2 class="text-2xl font-bold text-[#6d4927] mb-6">Informations de paiement</h2>
        
                <div class="mb-6">
                <!-- Simulateur d'éléments de paiement -->
                <div id="stripe-element-container" class="p-4 border border-gray-300 rounded-lg">
                    <label class="block text-sm font-medium mb-2">Carte bancaire*</label>
                    <div id="card-element" class="p-3 bg-white border border-gray-200 rounded">
                    <div class="grid gap-4">
                        <div>
                        <label class="text-sm text-gray-600">Numéro de carte</label>
                        <input type="text" placeholder="4242 4242 4242 4242" class="w-full p-2 border border-gray-300 rounded mt-1">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-600">Date d'expiration</label>
                            <input type="text" placeholder="MM/AA" class="w-full p-2 border border-gray-300 rounded mt-1">
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">CVC</label>
                            <input type="text" placeholder="123" class="w-full p-2 border border-gray-300 rounded mt-1">
                        </div>
                        </div>
                    </div>
                    </div>
                    <div id="card-errors" class="text-red-500 text-sm mt-2" role="alert"></div>
                </div>
        
                <p class="text-xs text-gray-500 mt-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#95714F]" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                    Paiement sécurisé - Vos données bancaires sont protégées
                </p>
                </div>
        
                <div class="flex items-start mb-6">
                <div class="flex items-center h-5">
                    <input id="terms" type="checkbox" value="" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-[#95714F]">
                </div>
                <label for="terms" class="ml-2 text-sm text-gray-600">
                    J'accepte les <a href="#" class="text-[#95714F] hover:underline">conditions générales</a> et la <a href="#" class="text-[#95714F] hover:underline">politique de confidentialité</a>.
                </label>
                </div>
            </div> --}}

            <!-- Formulaire de paiement -->
            <div class="order-1 md:order-2">
              <h2 class="text-2xl font-bold text-[#6d4927] mb-6">Informations de paiement</h2>

              <div class="mb-6">
                  <!-- Nom sur la carte -->
                  <div class="mb-4">
                      <label for="cardholder-name" class="block text-sm font-medium text-gray-700 mb-1">Nom sur la carte <span class="text-red-500">*</span></label>
                      <input type="text" id="cardholder-name" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]" required>
                  </div>

                  <!-- Élément Stripe pour la carte -->
                  <div>
                      <label for="card-element" class="block text-sm font-medium text-gray-700 mb-1">Informations de la carte <span class="text-red-500">*</span></label>
                      <div id="card-element" class="p-3 border border-gray-300 rounded-lg StripeElement"></div>
                      <div id="card-errors" class="text-red-500 text-sm mt-2" role="alert"></div>
                  </div>

                  <p class="text-xs text-gray-500 mt-2 flex items-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#95714F]" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                      </svg>
                      Paiement sécurisé - Vos données bancaires sont protégées
                  </p>
              </div>

              <div class="flex items-start mb-6">
                  <div class="flex items-center h-5">
                      <input id="terms" name="terms" type="checkbox" required class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-[#95714F]">
                  </div>
                  <label for="terms" class="ml-2 text-sm text-gray-600">
                      J'accepte les <a href="#" class="text-[#95714F] hover:underline">conditions générales</a> et la <a href="#" class="text-[#95714F] hover:underline">politique de confidentialité</a>.
                  </label>
              </div>

              <!-- Champ caché pour le token Stripe -->
              <input type="hidden" name="stripeToken" id="stripe-token-field">
            </div>

            </div>
        
            <div class="flex justify-between mt-8">
            <button type="button" @click="prevStep" class="px-6 py-3 border border-[#95714F] text-[#95714F] font-medium rounded-lg hover:bg-[#F3ECE3] transition-colors duration-300 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Retour
            </button>
        
            <button type="submit" @click="submitReservation" class="px-6 py-3 bg-[#95714F] text-white font-medium rounded-lg hover:bg-[#6d4927] transition-colors duration-300">
                Confirmer et payer
            </button>
            </div>
        </div>

  </div>

</form>
</section>
  
  <!-- Section FAQ -->
  <section class="py-16 px-4 max-w-5xl mx-auto bg-[#F3ECE3] rounded-2xl my-16">
    <h2 class="text-3xl font-bold text-center text-[#6d4927] mb-12" data-aos="fade-up">Questions fréquentes</h2>
  
    <div class="grid md:grid-cols-2 gap-8" x-data="{ activeTab: null }">
      <div data-aos="fade-up" data-aos-delay="100">
        <div class="mb-4 border-b border-[#C7AF94] pb-4"
             @click="activeTab = activeTab === 1 ? null : 1"
             :class="activeTab === 1 ? 'border-[#95714F]' : ''"
             class="cursor-pointer">
          <div class="flex justify-between items-center">
            <h3 class="font-medium text-[#6d4927]">Quelle est l'heure d'arrivée et de départ?</h3>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform" :class="activeTab === 1 ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </div>
          <div x-show="activeTab === 1" class="mt-2 text-gray-700 text-sm">
            L'enregistrement se fait à partir de 15h00 et le départ est prévu à 11h00. Si vous souhaitez arriver plus tôt ou partir plus tard, veuillez nous contacter pour vérifier la disponibilité.
          </div>
        </div>
  
        <div class="mb-4 border-b border-[#C7AF94] pb-4"
             @click="activeTab = activeTab === 2 ? null : 2"
             :class="activeTab === 2 ? 'border-[#95714F]' : ''"
             class="cursor-pointer">
          <div class="flex justify-between items-center">
            <h3 class="font-medium text-[#6d4927]">Le petit-déjeuner est-il inclus?</h3>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform" :class="activeTab === 2 ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </div>
          <div x-show="activeTab === 2" class="mt-2 text-gray-700 text-sm">
            Le petit-déjeuner n'est pas automatiquement inclus dans le tarif de la chambre. Vous pouvez l'ajouter comme option supplémentaire lors de votre réservation pour 15€ par personne et par jour.
          </div>
        </div>
  
        <div class="mb-4 border-b border-[#C7AF94] pb-4"
             @click="activeTab = activeTab === 3 ? null : 3"
             :class="activeTab === 3 ? 'border-[#95714F]' : ''"
             class="cursor-pointer">
          <div class="flex justify-between items-center">
            <h3 class="font-medium text-[#6d4927]">Y a-t-il un parking à l'hôtel?</h3>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform" :class="activeTab === 3 ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </div>
          <div x-show="activeTab === 3" class="mt-2 text-gray-700 text-sm">
            Oui, nous disposons d'un parking sécurisé pour nos clients. Le service de parking est disponible en option pour 12€ par jour.
          </div>
        </div>
      </div>
  
      <div data-aos="fade-up" data-aos-delay="200">
        <div class="mb-4 border-b border-[#C7AF94] pb-4"
             @click="activeTab = activeTab === 4 ? null : 4"
             :class="activeTab === 4 ? 'border-[#95714F]' : ''"
             class="cursor-pointer">
          <div class="flex justify-between items-center">
            <h3 class="font-medium text-[#6d4927]">Puis-je annuler ma réservation?</h3>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform" :class="activeTab === 4 ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </div>
          <div x-show="activeTab === 4" class="mt-2 text-gray-700 text-sm">
            Les réservations peuvent être annulées gratuitement jusqu'à 48 heures avant la date d'arrivée. Après cette période, le montant de la première nuit sera facturé.
          </div>
        </div>
  
        <div class="mb-4 border-b border-[#C7AF94] pb-4"
             @click="activeTab = activeTab === 5 ? null : 5"
             :class="activeTab === 5 ? 'border-[#95714F]' : ''"
             class="cursor-pointer">
          <div class="flex justify-between items-center">
            <h3 class="font-medium text-[#6d4927]">Les animaux sont-ils acceptés?</h3>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform" :class="activeTab === 5 ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </div>
          <div x-show="activeTab === 5" class="mt-2 text-gray-700 text-sm">
            Les petits animaux de compagnie sont acceptés dans certaines chambres avec un supplément de 20€ par nuit. Veuillez nous informer à l'avance si vous souhaitez venir avec votre animal.
          </div>
        </div>
  
        <div class="mb-4 border-b border-[#C7AF94] pb-4"
             @click="activeTab = activeTab === 6 ? null : 6"
             :class="activeTab === 6 ? 'border-[#95714F]' : ''"
             class="cursor-pointer">
          <div class="flex justify-between items-center">
            <h3 class="font-medium text-[#6d4927]">Y a-t-il un accès Wi-Fi?</h3>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform" :class="activeTab === 6 ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </div>
          <div x-show="activeTab === 6" class="mt-2 text-gray-700 text-sm">
            Oui, le Wi-Fi gratuit est disponible dans tout l'établissement pour tous nos clients.
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <!-- Bandeau CTA -->
  <section class="py-12 px-4 bg-[#95714F] text-white text-center mb-16" data-aos="fade-up">
    <div class="max-w-4xl mx-auto">
      <h2 class="text-2xl md:text-3xl font-bold mb-4">Besoin d'aide pour votre réservation?</h2>
      <p class="mb-6">Notre équipe est disponible pour répondre à toutes vos questions et vous accompagner dans votre réservation.</p>
      <div class="flex flex-col md:flex-row items-center justify-center space-y-4 md:space-y-0 md:space-x-6">
        <a href="tel:+212500406789" class="flex items-center px-6 py-3 bg-white text-[#95714F] rounded-lg hover:bg-[#F3ECE3] transition-colors duration-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
          </svg>
          +212 5 00 40 67 89
        </a>
        <a href="mailto:contact@lamicasa.com" class="flex items-center px-6 py-3 bg-white text-[#95714F] rounded-lg hover:bg-[#F3ECE3] transition-colors duration-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
          </svg>
          contact@lamicasa.com
        </a>
      </div>
    </div>
  </section>
  @endsection
  
  @section('scripts')
<!-- Stripe JS -->
<script src="https://js.stripe.com/v3/"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initialiser Stripe
      const stripe = Stripe('{{ env('STRIPE_KEY') }}');
      const elements = stripe.elements();

      // Créer l'élément de carte
      const style = {
        base: {
          color: '#32325d',
          fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
          fontSmoothing: 'antialiased',
          fontSize: '16px',
          '::placeholder': {
            color: '#aab7c4'
          }
        },
        invalid: {
          color: '#fa755a',
          iconColor: '#fa755a'
        }
      };

      const card = elements.create('card', {style: style});
      card.mount('#card-element');

      // Gestion des erreurs
      card.addEventListener('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
          displayError.textContent = event.error.message;
        } else {
          displayError.textContent = '';
        }
      });

      // Soumission du formulaire
      const form = document.getElementById('reservation-form'); // Assurez-vous que c'est l'ID de votre formulaire de réservation
      form.addEventListener('submit', function(event) {
        // On empêche la soumission standard du formulaire
        event.preventDefault();

        // Valider le formulaire avant de traiter le paiement
        if (!validateForm()) {
          return false;
        }
        
        // Désactiver le bouton pendant le traitement
        const submitButton = document.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.innerHTML = '<span>Traitement en cours...</span><svg class="animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

        const cardholderName = document.getElementById('cardholder-name').value;

        // Création du token de carte
        /* stripe.createToken(card, {
          name: cardholderName
        }).then(function(result) {
          if (result.error) {
            // Afficher l'erreur
            const errorElement = document.getElementById('card-errors');
            errorElement.textContent = result.error.message;
            
            // Réactiver le bouton
            submitButton.disabled = false;
            submitButton.textContent = originalButtonText;
          } else {
            // Insérer le token dans le formulaire et soumettre
            const hiddenInput = document.getElementById('stripe-token-field');
            hiddenInput.value = result.token.id;
            form.submit();
          } */
          stripe.createToken(card, {
            name: cardholderName
        }).then(function(result) {
            if (result.error) {
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
                
                submitButton.disabled = false;
                submitButton.textContent = originalButtonText;
            } else {
                const hiddenInput = document.getElementById('stripe-token-field');
                hiddenInput.value = result.token.id;
                
                // Soumettre le formulaire au serveur
                form.submit();
            }
        });
      });

      // Fonction pour valider le formulaire avant de procéder au paiement
      function validateForm() {
        let isValid = true;
        
        // Validation de base - vous pouvez personnaliser cela selon vos besoins
        const requiredFields = document.querySelectorAll('[required]');
        requiredFields.forEach(field => {
          if (!field.value && field.type !== 'checkbox') {
            field.classList.add('border-red-500');
            isValid = false;
          } else if (field.type === 'checkbox' && !field.checked) {
            field.parentElement.classList.add('border-red-500');
            isValid = false;
          } else {
            field.classList.remove('border-red-500');
            if (field.type === 'checkbox') {
              field.parentElement.classList.remove('border-red-500');
            }
          }
        });

        // Ajoutez ici d'autres validations spécifiques si nécessaire
        
        return isValid;
      }
    });
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Définir les dates min pour les champs de date
      const today = new Date().toISOString().split('T')[0];
      if (document.getElementById('arrival-date')) {
        document.getElementById('arrival-date').min = today;
      }
    });
  </script>
  @endsection