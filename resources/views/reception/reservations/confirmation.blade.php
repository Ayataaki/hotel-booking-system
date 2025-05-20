<!-- confirmation.blade.php -->
@extends('reception.layout')

@section('content')
<div class="main-content min-h-screen">
    @include('reception.partials.header', ['pageTitle' => 'Confirmation de réservation'])

    <div class="p-6 md:p-8 pt-20 md:pt-8">
        <div class="bg-white rounded-xl shadow-md p-8 max-w-2xl mx-auto">
            <!-- Message de succès -->
            <div class="text-center mb-8">
                <div class="mx-auto h-16 w-16 rounded-full bg-green-100 flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-[#6d4927] mb-2">Réservation confirmée !</h2>
                <p class="text-[#95714F]">La réservation a été enregistrée avec succès</p>
            </div>

            <!-- Détails de la réservation -->
            <div class="bg-[#F8F7F4] rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-[#6d4927] mb-4">Détails de la réservation</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-[#95714F] mb-1">Client:</p>
                        <p class="font-semibold text-[#6d4927]">{{ $reservation->client->nom }} {{ $reservation->client->prenom }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-[#95714F] mb-1">Numéro de réservation:</p>
                        <p class="font-semibold text-[#6d4927]">#{{ $reservation->id }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-[#95714F] mb-1">Date d'arrivée:</p>
                        <p class="font-semibold text-[#6d4927]">{{ \Carbon\Carbon::parse($reservation->dateDeb)->format('d/m/Y') }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-[#95714F] mb-1">Date de départ:</p>
                        <p class="font-semibold text-[#6d4927]">{{ \Carbon\Carbon::parse($reservation->dateFin)->format('d/m/Y') }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-[#95714F] mb-1">Durée du séjour:</p>
                        <p class="font-semibold text-[#6d4927]">{{ \Carbon\Carbon::parse($reservation->dateDeb)->diffInDays(\Carbon\Carbon::parse($reservation->dateFin)) }} nuit(s)</p>
                    </div>

                    <div>
                        <p class="text-sm text-[#95714F] mb-1">Montant total:</p>
                        <!-- Affichage corrigé du montant total -->
                        <p class="font-semibold text-[#6d4927]">{{ number_format(abs($reservation->totalPayer), 2, ',', ' ') }}€</p>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                @if(isset($facture))
                    <a href="{{ route('facture.download', ['id' => $facture->id]) }}"
                       class="inline-flex items-center justify-center px-8 py-3 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Imprimer la facture
                    </a>
                @endif

                <a href="{{ route('reception.chambres.disponibles') }}"
                   class="inline-flex items-center justify-center px-8 py-3 border border-[#95714F] text-[#95714F] rounded-lg hover:bg-[#F8F7F4] transition-colors">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nouvelle réservation
                </a>

                <!-- <a href="{{ route('reception.dashboard') }}"
                   class="inline-flex items-center justify-center px-8 py-3 border border-[#95714F] text-[#95714F] rounded-lg hover:bg-[#F8F7F4] transition-colors">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Retour au tableau de bord
                </a> -->
            </div>
        </div>
    </div>
</div>
@endsection
