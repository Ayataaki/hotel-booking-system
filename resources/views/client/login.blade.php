@extends('layouts.app')

@section('title', 'Connexion')

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
</style>
@endsection

@section('content')
<!-- Main Content -->
<main class="flex-grow flex items-center justify-center py-16 px-4 bg-cover bg-center bg-fixed relative"
      style="background-image: url('{{ asset('images/hotel_5.jpg') }}')">
  <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

  <!-- Decorative elements -->
  <div class="absolute top-20 left-20 w-40 h-40 rounded-full bg-[#95714F] opacity-5 animate-pulse"></div>
  <div class="absolute bottom-20 right-20 w-32 h-32 rounded-full bg-[#95714F] opacity-5 animate-pulse" style="animation-delay: 1s;"></div>

  <!-- Login Form -->
  <div class="bg-white/90 backdrop-blur-md max-w-md w-full rounded-2xl shadow-2xl p-8 md:p-10 relative z-10 fade-in">
    <div class="text-center mb-8">
      <h2 class="text-3xl font-bold text-[#6d4927] mb-2">Bienvenue</h2>
      <p class="text-gray-600">Connectez-vous à votre compte</p>
    </div>

    <form id="login-form" action="{{ route('login.post') }}" method="POST" class="space-y-6">
      @csrf

      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" id="email" name="email" placeholder="votre@email.com" required
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#95714F] @error('email') border-red-500 @enderror" value="{{ old('email') }}">
        @error('email')
          <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
        @enderror
      </div>

      <div>
        <div class="flex items-center justify-between mb-1">
          <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
          <a href="#" class="text-xs text-[#95714F] hover:text-[#6d4927] no-underline hover:underline transition-colors duration-300">Mot de passe oublié?</a>
        </div>
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
      </div>

      <div class="flex items-center">
        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-[#95714F] rounded border-gray-300 focus:ring-[#95714F]">
        <label for="remember" class="ml-2 block text-sm text-gray-700">Se souvenir de moi</label>
      </div>

      <button type="submit" class="w-full py-3 px-4 bg-[#95714F] text-white rounded-lg font-medium hover:bg-[#6d4927] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#95714F] transform hover:-translate-y-0.5 transition-all duration-300">
        Se connecter
      </button>
    </form>
  
    <div class="mt-8 text-center">
      <p class="text-gray-600 text-sm">Ou connectez-vous avec</p>
      <div class="flex justify-center space-x-4 mt-4">
        <a href="#" class="flex items-center justify-center w-12 h-12 rounded-full bg-white shadow-md hover:shadow-lg transition-shadow duration-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h2.773l-.443 2.89h-2.33v8.385C19.612 23.027 24 18.062 24 12.073z"/>
          </svg>
        </a>
        <a href="#" class="flex items-center justify-center w-12 h-12 rounded-full bg-white shadow-md hover:shadow-lg transition-shadow duration-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24">
            <path d="M12 0C5.372 0 0 5.373 0 12s5.372 12 12 12c6.627 0 12-5.373 12-12S18.627 0 12 0zm.14 19.018c-3.868 0-7-3.14-7-7.018 0-3.878 3.132-7.018 7-7.018s7 3.14 7 7.018c0 3.878-3.132 7.018-7 7.018zm8.515-11.295l-.707-.707-7.778 7.778-.707.707-4.6-4.6-.707.707 5.314 5.314 9.185-9.192z" fill="#34A853"/>
          </svg>
        </a>
        <a href="#" class="flex items-center justify-center w-12 h-12 rounded-full bg-white shadow-md hover:shadow-lg transition-shadow duration-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24">
            <path d="M12 0C5.373 0 0 5.373 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z" fill="#E60023"/>
          </svg>
        </a>
      </div>
    </div>

    <div class="mt-8 text-center">
      <p class="text-gray-600 text-sm">
        Vous n'avez pas de compte ?
        <a href="{{ route('register') }}" class="text-[#95714F] font-semibold hover:text-[#6d4927] hover:underline no-underline transition-colors duration-300">Inscrivez-vous</a>
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

    if (togglePassword && passwordField) {
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

    // Form submission animation
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
      loginForm.addEventListener('submit', (e) => {
        const submitButton = e.submitter;
        submitButton.textContent = 'Connexion en cours...';
        submitButton.disabled = true;
      });
    }
  });
</script>
@endsection