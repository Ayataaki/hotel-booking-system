<!-- reception/layout.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Casa - Réception</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>

    <style>
        .sidebar {
            width: 256px;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 40;
        }
        .main-content {
            margin-left: 256px;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#F8F7F4] text-[#95714F] font-['Times_New_Roman']" x-data="{ sidebarOpen: false }">

    <!-- Sidebar -->
    @include('reception.partials.sidebar')

    <!-- Content -->
    @yield('content')

    <!-- Overlay mobile -->
    <div class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
         x-show="sidebarOpen"
         @click="sidebarOpen = false"></div>

</body>
</html>
