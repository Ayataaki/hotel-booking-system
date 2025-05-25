@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<!-- Hero Section avec Parallax et Animation -->
  <section class="relative h-screen bg-cover bg-center bg-fixed flex items-center justify-center"
           style="background-image: url('{{ asset('images/hotel_1.jpg') }}')">
    <div class="absolute inset-0 bg-[#EADED0] bg-opacity-60"></div>

    <div id="hero-content" class="relative z-10 text-center px-4 animated-fade">
      <h1 class="text-5xl md:text-7xl font-bold font-['Playfair_Display'] mb-6 text-[#6d4927] tracking-tight">
        Bienvenue à <br><span class="italic">Mi Casa</span>
      </h1>
      <p class="text-xl md:text-2xl text-[#6d4927] mb-10 max-w-2xl mx-auto">
        Où l'élégance rencontre le confort pour un séjour inoubliable
      </p>

      <div class="flex flex-col sm:flex-row justify-center gap-4">
        <a href="{{ route('reservation') }}" class="bg-[#95714F] hover:bg-[#86572a] text-white font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
          Réserver maintenant
        </a>
        <a href="{{ route('chambres') }}" class="bg-transparent hover:bg-[#C7AF94] text-[#95714F] hover:text-white font-bold py-3 px-8 border border-[#95714F] hover:border-transparent rounded-lg transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
          Découvrir nos chambres
        </a>
      </div>
    </div>

    <!-- Indicateur de défilement -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex flex-col items-center text-[#95714F] animated-fade">
      <span class="text-sm mb-2">Découvrir</span>
      <div class="w-6 h-6 border-2 border-[#95714F] rounded-full relative bounce">
        <div class="absolute top-1/2 left-1/2 w-2 h-2 border-r-2 border-b-2 border-[#95714F] transform rotate-45 -translate-x-1/2 -translate-y-1/4"></div>
      </div>
    </div>

    <!-- Séparateur en vague -->
    <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none rotate-180">
      <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none" class="w-full h-24 text-[#F8F7F4] fill-current">
        <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"></path>
      </svg>
    </div>
  </section>

  <!-- Section d'avantages avec animation en décalage -->
  <section class="py-20 bg-[#F8F7F4] relative overflow-hidden">
    <div class="max-w-6xl mx-auto px-4">
      <div class="text-center mb-16">
        <h2 class="text-3xl md:text-4xl font-bold font-['Playfair_Display'] mb-4 text-[#6d4927] animated-fade">Pourquoi choisir Mi Casa</h2>
        <p class="text-[#95714F] max-w-2xl mx-auto animated-fade">Nous combinons luxe, confort et hospitalité pour créer une expérience mémorable.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Avantage 1 -->
        <div class="bg-white rounded-xl shadow-lg p-8 transform transition-all duration-500 hover:-translate-y-2 hover:shadow-xl relative overflow-hidden group animated-fade">
          <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-[#95714F] opacity-10 group-hover:scale-110 transition-transform duration-500"></div>
          <div class="relative">
            <div class="w-16 h-16 rounded-full bg-[#F3ECE3] flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#95714F]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-4 text-[#6d4927] group-hover:text-[#95714F] transition-colors duration-300">Service personnalisé</h3>
            <p class="text-gray-600">Notre personnel attentionné est disponible 24/7 pour répondre à tous vos besoins et rendre votre séjour exceptionnel.</p>
          </div>
        </div>

        <!-- Avantage 2 -->
        <div class="bg-white rounded-xl shadow-lg p-8 transform transition-all duration-500 hover:-translate-y-2 hover:shadow-xl relative overflow-hidden group animated-fade" style="animation-delay: 200ms;">
          <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-[#ACB087] opacity-10 group-hover:scale-110 transition-transform duration-500"></div>
          <div class="relative">
            <div class="w-16 h-16 rounded-full bg-[#F3ECE3] flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#95714F]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-4 text-[#6d4927] group-hover:text-[#95714F] transition-colors duration-300">Emplacement idéal</h3>
            <p class="text-gray-600">Situé au cœur de la ville, notre hôtel vous offre un accès facile aux attractions touristiques et points d'intérêt.</p>
          </div>
        </div>

        <!-- Avantage 3 -->
        <div class="bg-white rounded-xl shadow-lg p-8 transform transition-all duration-500 hover:-translate-y-2 hover:shadow-xl relative overflow-hidden group animated-fade" style="animation-delay: 400ms;">
          <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-[#C7AF94] opacity-10 group-hover:scale-110 transition-transform duration-500"></div>
          <div class="relative">
            <div class="w-16 h-16 rounded-full bg-[#F3ECE3] flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#95714F]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-4 text-[#6d4927] group-hover:text-[#95714F] transition-colors duration-300">Confort premium</h3>
            <p class="text-gray-600">Nos chambres sont équipées de tout le confort moderne pour assurer un séjour relaxant et revitalisant.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Séparateur ondulé -->
    <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none rotate-180">
      <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none" class="w-full h-20 text-[#EADED0] fill-current">
        <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25"></path>
        <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5"></path>
        <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"></path>
      </svg>
    </div>
  </section>

  <!-- Section Témoignages -->
  <section class="py-20 bg-[#EADED0] relative overflow-hidden">
    <!-- Éléments décoratifs -->
    <div class="absolute top-0 left-0 w-32 h-32 bg-[#ACB087] opacity-10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-64 h-64 bg-[#95714F] opacity-10 rounded-full translate-x-1/4 translate-y-1/4"></div>

    <div class="max-w-6xl mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold font-['Playfair_Display'] mb-4 text-[#6d4927] animated-fade">Ce que nos clients disent</h2>
        <p class="text-gray-600 max-w-2xl mx-auto animated-fade">Découvrez les expériences authentiques vécues par nos clients lors de leur séjour dans notre établissement.</p>
      </div>

      <!-- Carrousel de témoignages avec Alpine.js -->
      {{-- <div x-data="{
        testimonials: [
          {
            name: 'Sophie Martin',
            location: 'Paris, France',
            rating: 5,
            comment: 'Une expérience inoubliable ! Le personnel était attentionné, la chambre impeccable et la vue sur la ville à couper le souffle. Je reviendrai sans hésiter lors de mon prochain passage.',
            image: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&auto=format&fit=crop&w=150&q=80'
          },
          {
            name: 'Marc Dubois',
            location: 'Lyon, France',
            rating: 5,
            comment: 'Cet hôtel a dépassé toutes mes attentes. Le petit-déjeuner était exquis, le lit incroyablement confortable, et l\'emplacement idéal pour explorer la ville à pied.',
            image: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-1.2.1&auto=format&fit=crop&w=150&q=80'
          },
          {
            name: 'Isabelle Laurent',
            location: 'Bordeaux, France',
            rating: 4,
            comment: 'Nous avons passé un week-end en amoureux parfait. L\'ambiance de l\'hôtel est chaleureuse et élégante. Seul petit bémol, le bruit de la rue le matin, mais rien qui ne gâche le séjour !',
            image: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-1.2.1&auto=format&fit=crop&w=150&q=80'
          },
          {
            name: 'Thomas Mercier',
            location: 'Marseille, France',
            rating: 5,
            comment: 'Service irréprochable, chambres spacieuses et bien équipées. Le restaurant de l\'hôtel propose une cuisine locale délicieuse. Une adresse à conserver précieusement !',
            image: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=crop&w=150&q=80'
          }
        ],
        activeIndex: 0,
        autoplayEnabled: true,
        autoplayInterval: null,

        init() {
          this.startAutoplay();

          // Arrêter l'autoplay quand la souris survole le carrousel
          this.$el.addEventListener('mouseenter', () => this.stopAutoplay());
          this.$el.addEventListener('mouseleave', () => this.startAutoplay());
        },

        startAutoplay() {
          if (this.autoplayEnabled) {
            this.autoplayInterval = setInterval(() => {
              this.next();
            }, 5000);
          }
        },

        stopAutoplay() {
          clearInterval(this.autoplayInterval);
        },

        next() {
          this.activeIndex = (this.activeIndex + 1) % this.testimonials.length;
        },

        prev() {
          this.activeIndex = (this.activeIndex - 1 + this.testimonials.length) % this.testimonials.length;
        },

        setActive(index) {
          this.activeIndex = index;
        }
      }" class="relative animated-fade">

        <!-- Flèches de navigation -->
        <button @click="prev(); stopAutoplay()"
                class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-0 md:-translate-x-8 z-10 bg-white/80 hover:bg-white text-[#95714F] p-3 rounded-full shadow-md transition-all duration-300 transform hover:scale-110 hover:-translate-x-1 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>

        <button @click="next(); stopAutoplay()"
                class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-0 md:translate-x-8 z-10 bg-white/80 hover:bg-white text-[#95714F] p-3 rounded-full shadow-md transition-all duration-300 transform hover:scale-110 hover:translate-x-1 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
 --}}
        <!-- Conteneur des témoignages -->
        {{-- <div class="overflow-hidden rounded-2xl shadow-xl">
          <div class="flex transition-transform duration-500 ease-in-out" :style="`transform: translateX(-${activeIndex * 100}%)`">
            <!-- Témoignages individuels -->
            <template x-for="(testimonial, index) in testimonials" :key="index">
              <div class="w-full flex-shrink-0 px-4">
                <div class="bg-white/90 backdrop-blur-sm rounded-xl shadow-xl p-8 md:p-10 relative">
                  <!-- Guillemets décoratifs -->
                  <div class="absolute top-6 left-6 text-[#ACB087] opacity-20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                    </svg>
                  </div>

                  <!-- Étoiles -->
                  <div class="flex mb-4">
                    <template x-for="i in testimonial.rating">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 transform transition-all duration-300 hover:scale-125" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    </template>
                    <template x-for="i in (5 - testimonial.rating)">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    </template>
                  </div>

                  <!-- Commentaire -->
                  <p class="text-gray-600 mb-8 text-lg italic" x-text="testimonial.comment"></p>

                  <!-- Informations client -->
                  <div class="flex items-center">
                    <div class="w-16 h-16 rounded-full overflow-hidden mr-4 border-2 border-[#ACB087] shadow-md transform transition-all duration-300 hover:scale-110 hover:border-[#95714F]">
                      <img :src="testimonial.image" alt="Photo client" class="w-full h-full object-cover">
                    </div>
                    <div>
                      <p class="font-bold text-[#6d4927]" x-text="testimonial.name"></p>
                      <p class="text-gray-500 text-sm" x-text="testimonial.location"></p>
                    </div>
                  </div>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </div> --}}

        <!-- Section Témoignages -->
 {{--  <section class="py-20 bg-[#EADED0] relative overflow-hidden">
    <!-- Éléments décoratifs -->
    <div class="absolute top-0 left-0 w-32 h-32 bg-[#ACB087] opacity-10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-64 h-64 bg-[#95714F] opacity-10 rounded-full translate-x-1/4 translate-y-1/4"></div>

    <div class="max-w-6xl mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold font-['Playfair_Display'] mb-4 text-[#6d4927] animated-fade">Ce que nos clients disent</h2>
        <p class="text-gray-600 max-w-2xl mx-auto animated-fade">Découvrez les expériences authentiques vécues par nos clients lors de leur séjour dans notre établissement.</p>
      </div> --}}

      <!-- Carrousel de témoignages avec Alpine.js -->
      <div x-data="{
        // Utilisation des données dynamiques provenant de la base de données
        testimonials: {{ Illuminate\Support\Js::from($temoignages) }},
        activeIndex: 0,
        autoplayEnabled: true,
        autoplayInterval: null,

        init() {
          this.startAutoplay();

          // Arrêter l'autoplay quand la souris survole le carrousel
          this.$el.addEventListener('mouseenter', () => this.stopAutoplay());
          this.$el.addEventListener('mouseleave', () => this.startAutoplay());
        },

        startAutoplay() {
          if (this.autoplayEnabled) {
            this.autoplayInterval = setInterval(() => {
              this.next();
            }, 5000);
          }
        },

        stopAutoplay() {
          clearInterval(this.autoplayInterval);
        },

        next() {
          this.activeIndex = (this.activeIndex + 1) % this.testimonials.length;
        },

        prev() {
          this.activeIndex = (this.activeIndex - 1 + this.testimonials.length) % this.testimonials.length;
        },

        setActive(index) {
          this.activeIndex = index;
        }
      }" class="relative animated-fade" x-cloak>

        <!-- Flèches de navigation -->
        <button @click="prev(); stopAutoplay()"
                class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-0 md:-translate-x-8 z-10 bg-white/80 hover:bg-white text-[#95714F] p-3 rounded-full shadow-md transition-all duration-300 transform hover:scale-110 hover:-translate-x-1 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>

        <button @click="next(); stopAutoplay()"
                class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-0 md:translate-x-8 z-10 bg-white/80 hover:bg-white text-[#95714F] p-3 rounded-full shadow-md transition-all duration-300 transform hover:scale-110 hover:translate-x-1 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>

        <!-- Message si aucun témoignage n'est disponible -->
        <template x-if="testimonials.length === 0">
          <div class="bg-white/90 backdrop-blur-sm rounded-xl shadow-xl p-8 md:p-10 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-[#ACB087] mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <p class="text-[#6d4927] text-lg font-medium mb-4">Nos clients n'ont pas encore laissé d'avis.</p>
            <p class="text-gray-600">Soyez le premier à partager votre expérience!</p>
            <a href="{{ route('commentaires.create') }}" class="mt-6 inline-block px-6 py-3 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors">
              Donner votre avis
            </a>
          </div>
        </template>

        <!-- Conteneur des témoignages -->
        <template x-if="testimonials.length > 0">
          <div class="overflow-hidden rounded-2xl shadow-xl">
            <div class="flex transition-transform duration-500 ease-in-out" :style="`transform: translateX(-${activeIndex * 100}%)`">              <!-- Témoignages individuels -->
              <template x-for="(testimonial, index) in testimonials" :key="index">
                <div class="w-full flex-shrink-0 px-4">
                  <div class="bg-white/90 backdrop-blur-sm rounded-xl shadow-xl p-8 md:p-10 relative">
                    <!-- Guillemets décoratifs -->
                    <div class="absolute top-6 left-6 text-[#ACB087] opacity-20">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                      </svg>
                    </div>

                    <!-- Étoiles -->
                    <div class="flex mb-4">
                      <template x-for="i in testimonial.rating">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 transform transition-all duration-300 hover:scale-125" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                      </template>
                      <template x-for="i in (5 - testimonial.rating)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                      </template>
                    </div>

                    <!-- Commentaire -->
                    <p class="text-gray-600 mb-8 text-lg italic" x-text="testimonial.comment"></p>

                    <!-- Informations client -->
                    <div class="flex items-center">
                      <div>
                        <p class="font-bold text-[#6d4927]" x-text="testimonial.name"></p>
                        <p class="text-gray-500 text-sm" x-text="testimonial.location"></p>
                      </div>
                    </div> 
                  </div>
                </div>
              </template>
            </div>
          </div>
        </template>

        <!-- Indicateurs -->
      <template x-if="testimonials.length > 0">
        <div class="flex justify-center mt-8 space-x-2">
          <template x-for="(testimonial, index) in testimonials" :key="index">
            {{-- <button @click="setActive(index); stopAutoplay()"
              :class="`h-3 rounded-full transition-all duration-500 ${activeIndex === index ? 'bg-[#95714F] w-10' : 'bg-gray-300 hover:bg-gray-400 w-3'}`">
            </button> --}}
            <button @click="setActive(index); stopAutoplay()"
            :class="'h-3 rounded-full transition-all duration-500 ' + (activeIndex === index ? 'bg-[#95714F] w-10' : 'bg-gray-300 hover:bg-gray-400 w-3')">
          </button>
          </template>
        </div>
      </template>
    </div>

    <!-- Appel à l'action -->
    <div class="mt-16 text-center">
      <p class="text-lg font-medium mb-6 text-[#6d4927] animated-fade">Rejoignez nos clients satisfaits et vivez une expérience exceptionnelle</p>
      <a href="{{ route('reservation') }}"
        class="inline-block px-8 py-4 bg-[#95714F] text-white font-semibold rounded-md transition-all duration-500 transform hover:-translate-y-2 hover:shadow-xl hover:bg-[#86572a] relative overflow-hidden group animated-fade">
        <span class="relative z-10">Réserver votre séjour</span>
        <span class="absolute inset-0 w-full h-0 bg-[#6d4927] transition-all duration-300 group-hover:h-full"></span>
      </a>
    </div>
  </div>

  <!-- Séparateur ondulé -->
  <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none rotate-180">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none" class="w-full h-20 text-white fill-current">
      <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25"></path>
      <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5"></path>
      <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"></path>
    </svg>
  </div>
  </section>

  <!-- Chambres Vedettes -->
  <section class="py-20 relative bg-fixed bg-cover bg-center"
  style="background-image: url('{{ asset('images/hotel_2.jpg') }}')">
  <!-- Dégradé de transition (haut) -->
  <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-white via-white/70 to-transparent z-10"></div>

  <!-- Overlay semi-transparent -->
  <div class="absolute inset-0 bg-white/80 backdrop-blur-sm z-0"></div>

  <div class="relative z-10 max-w-6xl mx-auto px-4 py-20">
  <h2 class="text-3xl font-bold text-center font-['Playfair_Display'] text-[#6d4927] mb-4 animated-fade">Nos Chambres Vedettes</h2>
  <p class="text-center text-[#95714F] max-w-2xl mx-auto mb-12 animated-fade">Découvrez nos espaces de vie élégants et confortables, conçus pour rendre votre séjour inoubliable.</p>

  <div class="grid md:grid-cols-3 gap-8">
  @forelse($chambresVedettes as $chambre)
  <div class="group relative bg-white/90 backdrop-blur-sm rounded-2xl overflow-hidden shadow-lg transition-all duration-500 hover:-translate-y-3 hover:shadow-2xl border border-transparent hover:border-[#95714F] animated-fade" style="animation-delay: {{ $loop->index * 200 }}ms;">
    <div class="absolute inset-0 bg-gradient-to-t from-white/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10"></div>
    <div class="overflow-hidden">
      <img src="{{ $chambre->image ? asset('images/' .$chambre->image): asset('images/chambre_standard_1.jpg') }}" alt="{{-- $chambre->titre ?? $chambre->categorie->nom ?? 'Chambre' --}}"
          class="w-full h-60 object-cover transform group-hover:scale-110 group-hover:rotate-1 transition duration-700">
    </div>
    <span class="absolute top-3 left-3 bg-[#95714F] text-white text-xs font-bold px-3 py-1 rounded-full shadow-md z-20">{{ $chambre->categorie->nom ?? 'Chambre' }}</span>
    <div class="p-6 relative z-20">
      <h3 class="text-xl font-bold text-[#6d4927] mb-2 group-hover:text-[#95714F] transition-colors">{{ $chambre->titre ?? '' . $chambre->categorie->nom }}</h3>
      <p class="text-gray-600 text-sm mb-4">{{ Str::limit($chambre->categorie->description, 100) ?? 'Confort et élégance pour un séjour de qualité.' }}</p>
      <div class="flex justify-between items-center">
        <span class="font-bold text-[#95714F]">À partir de {{ $chambre->prixNuit }}€</span>
      </div>
    </div>
  </div>
  @empty
  <!-- Fallback si aucune chambre n'est trouvée dans la base de données -->
  <div class="col-span-3 text-center p-8 bg-white/90 rounded-lg">
    <p class="text-[#6d4927] mb-4">Nos chambres vedettes seront bientôt disponibles.</p>
    <a href="{{ route('chambres') }}" class="inline-block px-6 py-3 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors">
      Voir toutes nos chambres
    </a>
  </div>
  @endforelse
  </div>

  <!-- Bouton voir toutes nos chambres -->
  <div class="text-center mt-12">
  <a href="{{ route('chambres') }}" class="inline-block px-8 py-4 bg-[#95714F] text-white font-semibold rounded-lg transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg hover:bg-[#6d4927] animated-fade">
  Découvrir toutes nos chambres
  </a>
  </div>
  </div>

  <!-- Dégradé de transition (bas) -->
  <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-[#F8F7F4] via-[#F8F7F4]/70 to-transparent z-10"></div>
  </section>

@endsection
