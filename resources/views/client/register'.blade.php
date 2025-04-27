@extends('layouts.app')

@section('title', 'Inscription')

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

  .delay-1 { animation-delay: 0.1s; }
  .delay-2 { animation-delay: 0.2s; }
  .delay-3 { animation-delay: 0.3s; }

  /* Style pour l'indicateur d'étape */
  .step-indicator {
    height: 2px;
    transition: width 0.3s ease;
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

  <!-- Register Form -->
  <div class="bg-white/90 backdrop-blur-md max-w-xl w-full rounded-2xl shadow-2xl p-8 md:p-10 relative z-10 fade-in">
    <div class="text-center mb-8">
      <h2 class="text-3xl font-bold text-[#6d4927] mb-2">Créer un compte</h2>
      <p class="text-gray-600">Rejoignez LA MI CASA pour bénéficier d'offres exclusives</p>
    </div>

    <!--<form id="register-form" method="POST" action="{{-- route('register') --}}" class="space-y-6">-->
    <form id="register-form" method="POST" action="{{ route('register.post') }}" class="space-y-6">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
          <label for="firstname" class="block text-sm font-medium text-gray-700 mb-1">Nom d'utilisateur <span class="text-red-500">*</span></label>
          <input type="text" id="firstname" name="name" placeholder="Jean" required
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F] @error('firstname') border-red-500 @enderror">
          @error('firstname')
            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
          @enderror
        </div>

        <!--j'ai enlevé le champs nom & prénom et j'ai mis nom d'utilisateur-->

      <div class="mb-6">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
        <input type="email" id="email" name="email" placeholder="votre@email.com" required
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F] @error('email') border-red-500 @enderror">
        @error('email')
          <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
        @enderror
      </div>

      
      <!--pour que le type d'utilisateur soit client-->
      <input type="hidden" name="userType" value="client">

      <div class="mb-6">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe <span class="text-red-500">*</span></label>
        <div class="relative">
          <input type="password" id="password" name="password" placeholder="••••••••" required
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F] @error('password') border-red-500 @enderror">
          <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-[#95714F] focus:outline-none">
            <svg id="showPasswordIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
              <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
            </svg>
            <svg id="hidePasswordIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M3.28 2.22a.75.75 0 00-1.06 1.06l14.5 14.5a.75.75 0 101.06-1.06l-1.745-1.745a10.029 10.029 0 003.3-4.38 1.651 1.651 0 000-1.185A10.004 10.004 0 009.999 3a9.956 9.956 0 00-4.744 1.194L3.28 2.22zM7.752 6.69l1.092 1.092a2.5 2.5 0 013.374 3.373l1.091 1.092a4 4 0 00-5.557-5.557z" clip-rule="evenodd" />
              <path d="M10.748 13.93l2.523 2.523a9.987 9.987 0 01-3.27.547c-4.258 0-7.894-2.66-9.337-6.41a1.651 1.651 0 010-1.186A10.007 10.007 0 012.839 6.02L6.07 9.252a4 4 0 004.678 4.678z" />
            </svg>
          </button>
        </div>
        @error('password')
          <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
        @enderror

        <!-- Password strength indicator -->
        <div class="h-1 w-full bg-gray-200 rounded-full mt-2 overflow-hidden">
          <div id="password-strength" class="h-full transition-all duration-300 bg-red-500 w-0"></div>
        </div>

        <div class="text-xs mt-1" id="password-strength-text">
          <span id="strength-weak" class="text-red-500 hidden">Faible - Ajoutez au moins 6 caractères</span>
          <span id="strength-medium" class="text-yellow-500 hidden">Moyen - Ajoutez des majuscules et des chiffres</span>
          <span id="strength-strong" class="text-green-500 hidden">Fort - Parfait !</span>
        </div>
      </div>

      <div class="mb-6">
        <label for="newsletter" class="flex items-start">
          <input type="checkbox" id="newsletter" name="newsletter" class="mt-1 h-4 w-4 text-[#95714F] rounded border-gray-300 focus:ring-[#95714F]">
          <span class="ml-2 block text-sm text-gray-700">
            Je souhaite recevoir des offres spéciales et des promotions par email
          </span>
        </label>
      </div>

      <div class="bg-[#F3ECE3] rounded-lg p-6 mb-6">
        <div class="space-y-4">
          <div class="flex items-start">
            <input type="checkbox" id="terms" name="terms" required class="mt-1 h-4 w-4 text-[#95714F] rounded border-gray-300 focus:ring-[#95714F]">
            <label for="terms" class="ml-2 block text-sm text-gray-700">
              J'accepte les <a href="#" class="text-[#95714F] hover:underline">conditions générales</a> et la <a href="#" class="text-[#95714F] hover:underline">politique de confidentialité</a> <span class="text-red-500">*</span>
            </label>
          </div>

          <div class="flex items-start">
            <input type="checkbox" id="age-confirm" name="age_confirm" required class="mt-1 h-4 w-4 text-[#95714F] rounded border-gray-300 focus:ring-[#95714F]">
            <label for="age-confirm" class="ml-2 block text-sm text-gray-700">
              Je confirme avoir au moins 18 ans <span class="text-red-500">*</span>
            </label>
          </div>
        </div>
      </div>

      <p class="text-sm text-gray-600 mb-8">
        En créant un compte, vous acceptez que vos données personnelles soient traitées conformément à notre politique de confidentialité. Vous pouvez exercer vos droits d'accès, de rectification et de suppression à tout moment.
      </p>

      <div class="flex justify-center">
        <button type="submit" class="px-6 py-3 bg-[#95714F] text-white rounded-lg font-medium focus:outline-none hover:bg-[#6d4927] transition-colors duration-300 w-full md:w-auto">
          Créer mon compte
        </button>
      </div>
    </form>

    <div class="mt-8 text-center">
      <p class="text-gray-600 text-sm">
        Vous avez déjà un compte ?
        <a href="{{ route('login') }}" class="text-[#95714F] font-semibold hover:text-[#6d4927] hover:underline no-underline transition-colors duration-300">Connectez-vous</a>
      </p>
    </div>
  </div>
</main>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');
    const showPasswordIcon = document.getElementById('showPasswordIcon');
    const hidePasswordIcon = document.getElementById('hidePasswordIcon');

    if (togglePassword) {
      togglePassword.addEventListener('click', () => {
        if (passwordField.type === 'password') {
          passwordField.type = 'text';
          showPasswordIcon.classList.add('hidden');
          hidePasswordIcon.classList.remove('hidden');
        } else {
          passwordField.type = 'password';
          showPasswordIcon.classList.remove('hidden');
          hidePasswordIcon.classList.add('hidden');
        }
      });
    }

    // Password strength checker
    const passwordStrength = document.getElementById('password-strength');
    const strengthWeak = document.getElementById('strength-weak');
    const strengthMedium = document.getElementById('strength-medium');
    const strengthStrong = document.getElementById('strength-strong');

    if (passwordField) {
      passwordField.addEventListener('input', () => {
        const value = passwordField.value;

        // Hide all strength indicators
        strengthWeak.classList.add('hidden');
        strengthMedium.classList.add('hidden');
        strengthStrong.classList.add('hidden');

        // Check password strength
        if (value.length === 0) {
          passwordStrength.style.width = '0';
          passwordStrength.classList.remove('bg-red-500', 'bg-yellow-500', 'bg-green-500');
        } else if (value.length < 6) {
          // Weak password
          passwordStrength.style.width = '33.3%';
          passwordStrength.classList.add('bg-red-500');
          passwordStrength.classList.remove('bg-yellow-500', 'bg-green-500');
          strengthWeak.classList.remove('hidden');
        } else if (!/[A-Z]/.test(value) || !/[0-9]/.test(value)) {
          // Medium password
          passwordStrength.style.width = '66.6%';
          passwordStrength.classList.add('bg-yellow-500');
          passwordStrength.classList.remove('bg-red-500', 'bg-green-500');
          strengthMedium.classList.remove('hidden');
        } else {
          // Strong password
          passwordStrength.style.width = '100%';
          passwordStrength.classList.add('bg-green-500');
          passwordStrength.classList.remove('bg-red-500', 'bg-yellow-500');
          strengthStrong.classList.remove('hidden');
        }
      });
    }

    // Form submission animation
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
      registerForm.addEventListener('submit', (e) => {
        const submitButton = e.submitter;
        submitButton.textContent = 'Création en cours...';
        submitButton.disabled = true;
      });
    }
  });
</script>
@endsection
