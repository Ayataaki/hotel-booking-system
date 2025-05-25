<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte - Mi Casa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body x-data="{ sidebarOpen: false }" class="bg-[#EADED0] text-[#6d4927] flex flex-col md:flex-row">
    @include('admin.partials.sidebar_account')

    <div class="flex-1 overflow-hidden">
        <div class="max-w-5xl mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold mb-8">Mon compte</h1>

            <div class="bg-white rounded-lg shadow-sm p-6">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.account.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-medium mb-1">Nom</label>
                        <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="current_password" class="block text-sm font-medium mb-1">Mot de passe actuel</label>
                        <input type="password" id="current_password" name="current_password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium mb-1">Nouveau mot de passe</label>
                        <input type="password" id="password" name="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium mb-1">Confirmer le nouveau mot de passe</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <button type="submit" class="px-6 py-2.5 bg-[#95714F] text-white rounded-lg hover:bg-[#805F3E] transition-colors">
                        Enregistrer les modifications
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
