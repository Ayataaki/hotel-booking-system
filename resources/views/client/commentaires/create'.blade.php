{{-- resources/views/commentaires/create.blade.php --}}
@extends('layouts.app')

@section('content')
<section class="max-w-3xl mx-auto p-6 mt-8">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-6 text-center text-[#6d4927]">Partagez votre expérience</h2>
        
        @if ($errors->any())
        <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <form action="{{ route('commentaires.store') }}" method="POST" class="space-y-6">
            @csrf
            
            {{-- Titre du commentaire --}}
            <div>
                <label for="titre" class="block text-sm font-medium text-gray-600 mb-1">Titre de votre avis</label>
                <input 
                    type="text" 
                    id="titre" 
                    name="titre" 
                    value="{{ old('titre') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                    required
                >
            </div>
            
            {{-- Note sur 5 étoiles --}}
            <div x-data="{ rating: {{ old('note', 0) }} }">
                <label class="block text-sm font-medium text-gray-600 mb-1">Votre note</label>
                <div class="flex space-x-1">
                    <template x-for="i in 5">
                        <button 
                            type="button"
                            @click="rating = i"
                            class="focus:outline-none"
                        >
                            <svg 
                                class="h-6 w-6" 
                                :class="i <= rating ? 'text-yellow-400' : 'text-gray-300'"
                                fill="currentColor" 
                                viewBox="0 0 20 20"
                            >
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </button>
                    </template>
                </div>
                <input type="hidden" name="note" x-bind:value="rating">
                @error('note')
                <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>
            
            
            {{-- Commentaire --}}
            <div>
                <label for="avis" class="block text-sm font-medium text-gray-600 mb-1">Votre avis</label>
                <textarea 
                    id="avis" 
                    name="avis" 
                    rows="5" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#95714F]"
                    required
                >{{ old('avis') }}</textarea>
            </div>
            
            {{-- Bouton de soumission --}}
            <div class="text-center">
                <button 
                    type="submit" 
                    class="px-6 py-3 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors"
                >
                    Soumettre mon avis
                </button>
            </div>
        </form>
    </div>
</section>

{{-- Commentaires récents --}}
<section class="max-w-3xl mx-auto p-6 mb-12">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-xl font-bold mb-6 text-[#6d4927]">Commentaires récents</h2>
        
        <div class="space-y-6">
            @forelse($recentCommentaires ?? [] as $commentaire)
                <div class="bg-[#F8F7F4] p-4 rounded-lg">
                    <div class="flex justify-between items-center mb-2">
                        <p class="font-semibold">{{ $commentaire->titre }}</p>
                        <div class="flex space-x-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="h-4 w-4 {{ $i <= $commentaire->note ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-gray-600 italic mb-2">"{{ Str::limit($commentaire->avis, 150) }}"</p>
                    <p class="text-sm text-gray-500">{{ $commentaire->utilisateur->name }} - {{ $commentaire->created_at->format('d/m/Y') }}</p>
                </div>
            @empty
                <p class="text-gray-500 text-center italic">Aucun commentaire à afficher pour le moment.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection