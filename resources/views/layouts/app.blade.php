<!DOCTYPE html>
<html lang="fr" x-data="{ openMenu: false, scrolled: false, searchActive: false }">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ config('app.name', 'Mi Casa') }} - @yield('title', 'Accueil')</title>
  <link rel="stylesheet" href="{{ asset('css/output.css') }}">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
  <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Configuration Tailwind personnalisée -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brown: {
                            light: '#EADED0',
                            DEFAULT: '#95714F',
                            dark: '#6d4927'
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js via CDN -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <style>
    /* Styles minimums pour les animations essentielles */
    .animated-fade {
      opacity: 0;
      transform: translateY(20px);
      transition: opacity 0.8s, transform 0.8s;
    }

    .animated-fade.show {
      opacity: 1;
      transform: translateY(0);
    }

    /* Animation du loader */
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Animation pour l'indicateur de défilement */
    @keyframes bounce {
      0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
      40% { transform: translateY(-10px); }
      60% { transform: translateY(-5px); }
    }

    .bounce {
      animation: bounce 2s infinite;
    }

    [x-cloak] { display: none !important; }
  </style>

  @yield('styles')
</head>

<body class="bg-[#EADED0] text-[#95714F] font-['Times_New_Roman'] min-h-screen flex flex-col"
      x-data="{ scrolled: false, searchActive: false }">

  <!-- Écran de chargement -->
  <div id="loading" class="fixed inset-0 bg-[#EADED0] flex items-center justify-center z-[9999]" style="transition: opacity 0.6s ease;">
    <p class="text-[#95714F] text-xl">Chargement...</p>
  </div>

  <!-- Header -->
  @include('partials.header')

  <!-- Contenu principal -->
  @yield('content')


  <!-- Footer -->
  @include('partials.footer')

  <!-- Bouton de retour en haut de page -->
  <button id="back-to-top" class="fixed bottom-8 right-8 bg-[#95714F] text-white p-3 rounded-full shadow-lg opacity-0 translate-y-5 transition-all duration-300 z-50 hover:bg-[#6d4927]">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
    </svg>
  </button>

  <!-- Scripts -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <script>
    // Force le masquage du loader après un délai même si le reste ne charge pas
    document.addEventListener('DOMContentLoaded', function() {
      // Force la suppression du loader après 1 seconde maximum
      setTimeout(function() {
        var loader = document.getElementById('loading');
        if (loader) {
          loader.style.opacity = '0';
          setTimeout(function() {
            loader.style.display = 'none';
          }, 600);
        }
      }, 1000);
    });

    // Fonction pour animer les éléments avec la classe .animated-fade
    function animateElements() {
      const animatedElements = document.querySelectorAll('.animated-fade');
      animatedElements.forEach(element => {
        if (isElementInViewport(element)) {
          element.classList.add('show');
        }
      });
    }

    // Vérifier si un élément est visible dans la fenêtre
    function isElementInViewport(el) {
      const rect = el.getBoundingClientRect();
      return (
        rect.top <= (window.innerHeight || document.documentElement.clientHeight) * 0.9 &&
        rect.bottom >= 0
      );
    }

    // Gérer le chargement complet de la page
    window.addEventListener('load', function() {
      try {
        // Animer les éléments visibles immédiatement
        animateElements();

        // Animer lors du défilement
        window.addEventListener('scroll', animateElements);

        // Initialiser AOS de manière sécurisée
        try {
          AOS.init({
            once: false,
            duration: 800,
            offset: 100,
            easing: 'ease-in-out',
            disable: window.innerWidth < 768
          });
        } catch (e) {
          console.error("Erreur lors de l'initialisation d'AOS:", e);
        }

        // Bouton retour en haut
        var backToTopButton = document.getElementById('back-to-top');
        if (backToTopButton) {
          window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
              backToTopButton.classList.remove('opacity-0', 'translate-y-5');
            } else {
              backToTopButton.classList.add('opacity-0', 'translate-y-5');
            }
          });

          backToTopButton.addEventListener('click', function() {
            window.scrollTo({
              top: 0,
              behavior: 'smooth'
            });
          });
        }
      } catch (e) {
        console.error("Erreur lors du chargement:", e);
        // S'assurer que le loader est masqué même en cas d'erreur
        var loader = document.getElementById('loading');
        if (loader) {
          loader.style.display = 'none';
        }
      }
    });
  </script>

  @yield('scripts')
</body>
</html>
