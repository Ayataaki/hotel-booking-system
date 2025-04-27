{{-- resources/views/commentaires/create.blade.php --}}
@extends('layouts.app')

@section('content')
<section class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-4 mb-12">
    <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
        <h2 class="text-3xl font-bold mb-6 text-center text-[#6d4927]">Partagez votre expérience</h2>
        
        @if ($errors->any())
        <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <form action="{{ route('commentaires.store') }}" method="POST" class="space-y-8 max-w-3xl mx-auto">
            @csrf
            
            {{-- Titre du commentaire --}}
            <div>
                <label for="titre" class="block text-base font-medium text-gray-700 mb-2">Titre de votre avis</label>
                <input 
                    type="text" 
                    id="titre" 
                    name="titre" 
                    value="{{ old('titre') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#95714F] text-base"
                    required
                >
            </div>
            
            {{-- Note sur 5 étoiles --}}
            <div x-data="{ rating: {{ old('note', 0) }} }">
                <label class="block text-base font-medium text-gray-700 mb-2">Votre note</label>
                <div class="flex space-x-2">
                    <template x-for="i in 5">
                        <button 
                            type="button"
                            @click="rating = i"
                            class="focus:outline-none transition-transform hover:scale-110"
                        >
                            <svg 
                                class="h-8 w-8" 
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
                <span class="text-sm text-red-600 block mt-1">{{ $message }}</span>
                @enderror
                <div class="text-sm text-gray-500 mt-1" x-show="rating > 0">
                    <span x-text="rating"></span>/5 étoiles
                </div>
            </div>
            
            
            {{-- Commentaire --}}
            <div>
                <label for="avis" class="block text-base font-medium text-gray-700 mb-2">Votre avis</label>
                <textarea 
                    id="avis" 
                    name="avis" 
                    rows="6" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#95714F] text-base"
                    required
                >{{ old('avis') }}</textarea>
            </div>
            
            {{-- Bouton de soumission --}}
            <div class="text-center pt-4">
                <button 
                    type="submit" 
                    class="px-8 py-4 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors text-lg font-medium"
                >
                    Soumettre mon avis
                </button>
            </div>
        </form>
    </div>
</section>

{{-- Commentaires récents --}}
<section class="container mx-auto px-4 sm:px-6 lg:px-8 mb-12">
    <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
        <h2 class="text-2xl font-bold mb-6 text-[#6d4927]">Commentaires récents</h2>
        
        <div class="space-y-6">
            @forelse($recentCommentaires ?? [] as $commentaire)
                <div class="bg-[#F8F7F4] p-5 rounded-lg shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-3">
                        <p class="font-semibold text-lg text-[#6d4927]">{{ $commentaire->titre }}</p>
                        <div class="flex space-x-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="h-5 w-5 {{ $i <= $commentaire->note ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-gray-600 italic mb-3 text-base leading-relaxed">"{{ Str::limit($commentaire->avis, 150) }}"</p>
                    <div class="flex justify-between items-center text-sm text-gray-500 border-t border-gray-200 pt-3">
                        <span>{{ $commentaire->utilisateur->name ?? 'Client' }}</span>
                        <span>{{ $commentaire->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 italic bg-[#F8F7F4] rounded-lg">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                    <p>Aucun commentaire à afficher pour le moment.</p>
                    <p class="mt-2">Vous serez le premier à partager votre expérience!</p>
                </div>
            @endforelse
        </div>
        
        @if(($recentCommentaires ?? collect())->isNotEmpty())
            <div class="mt-6 text-center">
                <a href="{{ route('commentaires.index') }}" class="inline-block px-6 py-2 border border-[#95714F] text-[#95714F] rounded-lg hover:bg-[#F8F7F4] transition-colors">
                    Voir tous les avis →
                </a>
            </div>
        @endif
    </div>
</section>
@endsection