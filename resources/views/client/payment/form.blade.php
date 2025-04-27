@extends('layouts.app')

@section('title', 'Paiement')

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

  /* Styles pour Stripe Elements */
  .StripeElement {
    box-sizing: border-box;
    width: 100%;
    height: 50px;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: white;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
    transition: box-shadow 150ms ease;
  }

  .StripeElement--focus {
    box-shadow: 0 0 0 2px rgba(149, 113, 79, 0.4);
    border-color: #95714F;
  }

  .StripeElement--invalid {
    border-color: #fa755a;
  }
</style>
@endsection

@section('content')
<!-- Main Content -->
<main class="flex-grow flex items-center justify-center py-12 px-4 bg-cover bg-center bg-fixed relative"
      style="background-image: url('{{ asset('images/hotel_4.jpg') }}')">
  <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

  <!-- Decorative elements -->
  <div class="absolute top-20 left-20 w-40 h-40 rounded-full bg-[#95714F] opacity-5 animate-pulse"></div>
  <div class="absolute bottom-20 right-20 w-32 h-32 rounded-full bg-[#95714F] opacity-5 animate-pulse" style="animation-delay: 1s;"></div>

  <!-- Payment Form -->
  <div class="bg-white/90 backdrop-blur-md max-w-lg w-full rounded-2xl shadow-2xl p-8 md:p-10 relative z-10 fade-in">
    <div class="text-center mb-8">
      <h2 class="text-3xl font-bold text-[#6d4927] mb-2">Paiement</h2>
      <p class="text-gray-600">Finalisez votre réservation en toute sécurité</p>
    </div>

    @if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative mb-6">
      <ul class="list-disc pl-5">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    <form id="payment-form" method="POST" action="{{ route('stripe.process') }}" class="space-y-6">
      @csrf

      <!-- Détails de la réservation (à ajuster selon vos besoins) -->
      <div class="bg-[#F3ECE3] rounded-lg p-6 mb-6">
        <h3 class="font-semibold text-lg text-[#6d4927] mb-3">Détails de la réservation</h3>
        <div class="space-y-2">
          <div class="flex justify-between">
            <span class="text-gray-600">Chambre Deluxe</span>
            <span class="font-medium">120 €</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Taxes</span>
            <span class="font-medium">20 €</span>
          </div>
          <div class="border-t border-gray-300 my-2 pt-2 flex justify-between">
            <span class="font-semibold">Total</span>
            <span class="font-semibold">140 €</span>
          </div>
        </div>
      </div>

      <!-- Montant caché pour le traitement -->
      <input type="hidden" name="amount" value="140">

      <!-- Informations de paiement -->
      <div>
        <h3 class="font-semibold text-lg text-[#6d4927] mb-3">Informations de paiement</h3>
        
        <div class="space-y-4">
          <!-- Nom sur la carte -->
          <div>
            <label for="cardholder-name" class="block text-sm font-medium text-gray-700 mb-1">Nom sur la carte <span class="text-red-500">*</span></label>
            <input type="text" id="cardholder-name" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F]" required>
          </div>
          
          <!-- Élément Stripe pour la carte -->
          <div>
            <label for="card-element" class="block text-sm font-medium text-gray-700 mb-1">Informations de la carte <span class="text-red-500">*</span></label>
            <div id="card-element" class="mt-1"></div>
            <div id="card-errors" class="text-red-500 text-sm mt-2" role="alert"></div>
          </div>
        </div>
      </div>

      <!-- Termes et conditions -->
      <div>
        <div class="flex items-start">
          <input type="checkbox" id="terms" name="terms" required class="mt-1 h-4 w-4 text-[#95714F] rounded border-gray-300 focus:ring-[#95714F]">
          <label for="terms" class="ml-2 block text-sm text-gray-700">
            J'accepte les <a href="#" class="text-[#95714F] hover:underline">conditions générales</a> et la <a href="#" class="text-[#95714F] hover:underline">politique de confidentialité</a> <span class="text-red-500">*</span>
          </label>
        </div>
      </div>

      <div class="flex justify-center">
        <button type="submit" id="submit-button" class="px-6 py-3 bg-[#95714F] text-white rounded-lg font-medium focus:outline-none hover:bg-[#6d4927] transition-colors duration-300 w-full md:w-auto flex items-center justify-center">
          <span id="button-text">Payer 140 €</span>
          <svg id="spinner" class="animate-spin ml-2 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </button>
      </div>
    </form>
    
    <div class="mt-8 text-center">
      <div class="flex items-center justify-center space-x-4">
        <span class="text-gray-500 text-xs">Paiement sécurisé par</span>
        <img src="{{ asset('images/stripe-logo.svg') }}" alt="Stripe" class="h-6">
      </div>
      <div class="flex items-center justify-center mt-4 space-x-4">
        <img src="{{ asset('images/visa.svg') }}" alt="Visa" class="h-6">
        <img src="{{ asset('images/mastercard.svg') }}" alt="Mastercard" class="h-6">
        <img src="{{ asset('images/amex.svg') }}" alt="American Express" class="h-6">
      </div>
    </div>
  </div>
</main>
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
    const form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
      event.preventDefault();

      // Désactiver le bouton pendant le traitement
      document.getElementById('submit-button').disabled = true;
      document.getElementById('button-text').textContent = 'Traitement en cours...';
      document.getElementById('spinner').classList.remove('hidden');

      const cardholderName = document.getElementById('cardholder-name').value;

      stripe.createToken(card, {
        name: cardholderName
      }).then(function(result) {
        if (result.error) {
          // Afficher l'erreur
          const errorElement = document.getElementById('card-errors');
          errorElement.textContent = result.error.message;
          
          // Réactiver le bouton
          document.getElementById('submit-button').disabled = false;
          document.getElementById('button-text').textContent = 'Payer 140 €';
          document.getElementById('spinner').classList.add('hidden');
        } else {
          // Envoyer le token au serveur
          stripeTokenHandler(result.token);
        }
      });
    });

    // Soumettre le token au serveur
    function stripeTokenHandler(token) {
      const form = document.getElementById('payment-form');
      const hiddenInput = document.createElement('input');
      hiddenInput.setAttribute('type', 'hidden');
      hiddenInput.setAttribute('name', 'stripeToken');
      hiddenInput.setAttribute('value', token.id);
      form.appendChild(hiddenInput);

      form.submit();
    }
  });
</script>
@endsection