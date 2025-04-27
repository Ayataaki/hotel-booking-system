@extends('layouts.app')

@section('title', 'Nos Chambres')

@section('content')
<!-- Hero Section -->
<section class="relative h-[60vh] bg-cover bg-center flex items-center justify-center"
    style="background-image: url('{{ asset('images/chambre_7.jpg') }}');">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 text-center text-white px-4">
        <h1 class="text-5xl font-bold" data-aos="fade-up">Nos Chambres</h1>
        <p class="text-lg mt-2" data-aos="fade-up" data-aos-delay="100">
            Confort élégant, design raffiné et vue exceptionnelle
        </p>
    </div>
</section>

<!-- Section des chambres -->
<section class="py-20 px-4 max-w-[100%] mx-auto mt-24 overflow-hidden card">
    <h2 class="text-3xl font-bold text-center text-[#6d4927] mb-6">Choisissez votre expérience</h2>
    
    <!-- Grille des chambres -->
    <div class="grid md:grid-cols-3 gap-12 max-w-[100%] card">
            @foreach($chambres as $chambre)
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl h-[520px]" x-data="{ showDetails: false }">
                <!-- Image de la chambre -->
                <div class="relative overflow-hidden group cursor-pointer h-64">
                    <img src="{{ $chambre->image ? asset('images/' .$chambre->image): asset('images/chambre_standard_1.jpg') }}" 
                        alt="Chambre {{ ucfirst($chambre->categorie->typeChambre ?? 'Standard') }}"
                        class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" />
                    
                    <div class="absolute inset-0 bg-black bg-opacity-30 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <span class="text-white font-semibold text-lg">Voir en détail</span>
                    </div>
                    
                    <div class="absolute top-4 right-4 bg-[#95714F] text-white text-xs font-semibold px-3 py-1 rounded-full shadow">
                        {{ $chambre->categorie->typeChambre ?? 'Standard' }}
                    </div>
                </div>
                
                <div class="p-6 flex flex-col h-[256px]">
                    <!-- Numéro et étage -->
                    <div class="text-sm text-gray-500 mb-1">
                        <span>N° {{ $chambre->NumCh }} - Étage {{ $chambre->NumEtg }}</span>
                    </div>
                    
                    <!-- Zone de défilement contenant la description et la capacité -->
                    <div class="flex-grow overflow-y-auto my-2" style="scrollbar-width: thin">
                        <!-- Description de la catégorie -->
                        <p class="text-sm text-gray-600">
                            {{ $chambre->categorie->description ?? 'Chambre confortable et accueillante' }}
                        </p>
                        
                        <!-- Capacité de la chambre -->
                        <p class="text-sm text-gray-600 mt-1">
                            <span class="font-medium">Capacité:</span> {{ $chambre->capacite }} personne(s)
                        </p>
                        
                        <!-- Caractéristiques supplémentaires qui s'affichent uniquement lorsqu'on clique sur le bouton détails -->
                        <div x-show="showDetails" 
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 transform translate-y-0"
                            x-transition:leave-end="opacity-0 transform -translate-y-2"
                            class="pt-3 mt-3 border-t border-gray-100">
                            <h4 class="font-medium text-[#6d4927] mb-2">Caractéristiques:</h4>
                            <ul class="space-y-1">
                                <li class="text-sm text-gray-600 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#95714F] mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Wi-Fi premium</span>
                                </li>
                                <li class="text-sm text-gray-600 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#95714F] mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Climatisation</span>
                                </li>
                                <li class="text-sm text-gray-600 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#95714F] mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ $chambre->capacite > 1 ? 'Lits confortables' : 'Lit confortable' }}</span>
                                </li>
                                <li class="text-sm text-gray-600 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#95714F] mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Salle de bain privative</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                        
                    <!-- Prix de la chambre -->
                    <div class="flex items-center justify-between mt-auto pt-2 border-t border-gray-100">
                        <div class="flex text-yellow-500">
                            @for($i = 0; $i < 3; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                            @for($i = 0; $i < 2; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <span class="text-sm font-medium text-[#6d4927]">{{ $chambre->prixNuit }}€/nuit</span>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="flex gap-2 mt-4">
                        <button @click="showDetails = !showDetails" 
                            class="flex-1 text-center text-[#95714F] border border-[#95714F] hover:bg-[#95714F] hover:text-white font-medium py-2 px-2 rounded-lg transition-colors duration-300">
                            <span x-text="showDetails ? 'Masquer' : 'Détails'"></span>
                        </button>
                        {{-- <a href="{{ route('reservation') }}?chambre={{ $chambre->id }}"
                            class="flex-1 text-center {{ $chambre->status == 0 ? 'bg-[#95714F] hover:bg-[#6d4927] text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }} font-medium py-2 px-2 rounded-lg transition-colors duration-300">
                            Réserver
                        </a> --}}
                    </div>
                </div>
            </div>
        @endforeach

    </div>
</section>

@endsection