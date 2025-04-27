@extends('layouts.app')

@section('title', 'Paiement réussi')

@section('styles')
<style>
  [x-cloak] { display: none !important; }

  .fade-in {
    animation: fadeIn 0.8s ease forwards;
    opacity: 0;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .checkmark {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: block;
    stroke-width: 2;
    stroke: #fff;
    stroke-miterlimit: 10;
    box-shadow: inset 0px 0px 0px #95714F;
    animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
    position: relative;
    margin: 0 auto;
  }

  .checkmark__circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 2;
    stroke-miterlimit: 10;
    stroke: #95714F;
    fill: none;
    animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
  }

  .checkmark__check {
    transform-origin: 50% 50%;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
  }

  @keyframes stroke {
    100% {
      stroke-dashoffset: 0;
    }
  }

  @keyframes scale {
    0%, 100% {
      transform: none;
    }
    50% {
      transform: scale3d(1.1, 1.1, 1);
    }
  }

  @keyframes fill {
    100% {
      box-shadow: inset 0px 0px 0px 30px #95714F;
    }
  }
</style>
@endsection

@section('content')
<!-- Main Content -->
<main class="flex-grow flex items-center justify-center py-12 px-4 bg-cover bg-center bg-fixed relative"
      style="background-image: url('{{ asset('images/hotel_5.jpg') }}')">
  <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

  <!-- Success Card -->
  <div class="bg-white/90 backdrop-blur-md max-w-md w-full rounded-2xl shadow-2xl p-8 md:p-10 relative z-10 fade-in">
    <div class="text-center">
      <!-- Animation de confirmation -->
      <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
        <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
        <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
      </svg>

      <h2 class="text-3xl font-bold text-[#6d4927] mt-6 mb-2">Paiement réussi !</h2>
      <p class="text-gray-600 mb-6">Votre transaction a été traitée avec succès.</p>
      
      <!-- Détails de la transaction -->
      <div class="bg-[#F3ECE3] rounded-lg p-6 mb-6 text-left">
        <h3 class="font-semibold text-[#6d4927] mb-3">Détails de la réservation</h3>
        <div class="space-y-2">
          <div class="flex justify-between">
            <span class="text-gray-600">Numéro de réservation</span>
            <span class="font-medium">{{ 'RES-' . rand(10000, 99999) }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Date</span>
            <span class="font-medium">{{ date('d/m/Y') }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Montant</span>
            <span class="font-medium">140 €</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Méthode de paiement</span>
            <span class="font-medium">Carte de crédit</span>
          </div>
        </div>
      </div>

      <p class="text-gray-600 mb-6">
        Un email de confirmation a été envoyé à votre adresse email. Vous trouverez tous les détails de votre réservation dans votre compte.
      </p>
      
      <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
        <a href="{{ route('home') }}" class="px-6 py-3 bg-[#95714F] text-white rounded-lg font-medium focus:outline-none hover:bg-[#6d4927] transition-colors duration-300">
          Retour à l'accueil
        </a>
        <a href="{{ route('account.bookings') }}" class="px-6 py-3 border border-[#95714F] text-[#95714F] rounded-lg font-medium focus:outline-none hover:bg-[#F3ECE3] transition-colors duration-300">
          Mes réservations
        </a>
      </div>
    </div>
  </div>
</main>
@endsection