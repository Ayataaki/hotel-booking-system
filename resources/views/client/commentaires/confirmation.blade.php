{{-- resources/views/commentaires/confirmation.blade.php --}}
@extends('layouts.app')

@section('content')
<section class="max-w-3xl mx-auto p-6 mt-12 mb-24">
    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="flex justify-center mb-6">
            <svg class="h-16 w-16 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold mb-4 text-[#6d4927]">Merci pour votre commentaire !</h2>
        <p class="text-gray-600 mb-8">Votre avis a été enregistré avec succès. Nous apprécions votre retour qui nous aide à améliorer constamment nos services.</p>
        <div class="flex justify-center space-x-4">
            <a href="{{ route('home') }}" class="px-6 py-3 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors">
                Retour à l'accueil
            </a>
            @auth
                <a href="{{ route('client.profil') }}" class="px-6 py-3 border border-[#95714F] text-[#95714F] rounded-lg hover:bg-[#f5f0eb] transition-colors">
                    Mon profil
                </a>
            @endauth
        </div>
    </div>
</section>
@endsection