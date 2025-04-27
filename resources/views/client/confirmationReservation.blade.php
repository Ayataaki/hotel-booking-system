<!-- resources/views/reservation/confirmation.blade.php -->
@extends('layouts.app')

@section('title', 'Confirmation de réservation')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-8">
            <div class="text-center mb-8">
                <div class="bg-green-100 rounded-full h-20 w-20 flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-[#6d4927]">Réservation confirmée!</h1>
                <p class="text-gray-600 mt-2">Merci pour votre réservation chez LA MI CASA</p>
            </div>

            <div class="bg-[#F3ECE3] rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-[#6d4927] mb-4">Détails de votre réservation</h2>
                
                <div class="grid md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="font-medium text-gray-700">Numéro de réservation:</p>
                        <p>#{{ $reservation->id }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-700">Statut:</p>
                        <p class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded">Confirmée</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-700">Date d'arrivée:</p>
                        <p>{{ \Carbon\Carbon::parse($reservation->dateDeb)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-700">Date de départ:</p>
                        <p>{{ \Carbon\Carbon::parse($reservation->dateFin)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-700">Durée:</p>
                        <p>{{ \Carbon\Carbon::parse($reservation->dateDeb)->diffInDays(\Carbon\Carbon::parse($reservation->dateFin)) }} nuit(s)</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-700">Nombre de personnes:</p>
                        <p>{{ $adultsCount ?? 0 }} adulte(s), {{ $childrenCount ?? 0 }} enfant(s)</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="font-medium text-gray-700">Montant total:</p>
                        <p class="text-xl font-bold text-[#6d4927]">{{ number_format($reservation->totalPayer, 2) }} €</p>
                    </div>
                </div>
            </div>

           {{--  <div class="text-center">
                <a href="{{ route('facture.download', $facture->id) }}" class="inline-flex items-center px-6 py-3 bg-[#95714F] text-white font-medium rounded-lg hover:bg-[#6d4927] transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Télécharger ma facture
                </a>                
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('home') }}" class="text-[#95714F] hover:underline">Retour à l'accueil</a>
                </div>
            </div> --}}
            <div class="text-center">
                @if(isset($facture) && $facture)
                    <a href="{{ route('facture.download', $facture->id) }}" class="inline-flex items-center px-6 py-3 bg-[#95714F] text-white font-medium rounded-lg hover:bg-[#6d4927] transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Télécharger ma facture
                    </a>
                @else
                    <div class="p-4 mb-4 bg-yellow-100 text-yellow-700 rounded-lg">
                        <p>La facture n'est pas encore disponible. Veuillez réessayer ultérieurement ou contacter le service client.</p>
                    </div>
                @endif
                
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('home') }}" class="text-[#95714F] hover:underline">Retour à l'accueil</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection