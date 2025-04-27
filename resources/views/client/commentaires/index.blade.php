{{-- resources/views/commentaires/index.blade.php --}}
@extends('layouts.app')

@section('content')
<section class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-4 mb-12">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6 md:p-8 bg-[#F3ECE3]">
            <h1 class="text-3xl font-bold text-[#6d4927]">Avis de nos clients</h1>
            <p class="text-gray-600 mt-2 text-lg">Découvrez ce que nos clients pensent de leur séjour à LA MI CASA</p>
        </div>
        
        <div class="p-6 md:p-8">
            <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($commentaires as $commentaire)
                    <div class="bg-[#F8F7F4] p-4 md:p-6 rounded-lg shadow-sm hover:shadow-lg transition-shadow border border-gray-100">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="font-semibold text-[#6d4927] text-lg">{{ $commentaire->titre }}</h3>
                            <div class="flex space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-5 w-5 {{ $i <= $commentaire->note ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        
                        <p class="text-gray-600 italic mb-4 text-base leading-relaxed">"{{ $commentaire->avis }}"</p>
                        
                        <div class="flex justify-between items-center text-sm text-gray-500 border-t border-gray-200 pt-3 mt-2">
                            <span class="font-medium">{{ $commentaire->utilisateur->name ?? 'Client' }}</span>
                            <span>{{ $commentaire->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full p-12 text-center bg-[#F8F7F4] rounded-lg">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                        <p class="text-gray-500 italic mb-6 text-lg">Aucun avis client pour le moment.</p>
                        @auth
                            <a href="{{ route('commentaires.create') }}" class="inline-block px-6 py-3 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors text-lg font-medium">
                                Soyez le premier à donner votre avis
                            </a>
                        @else
                            <a href="{{ route('login') }}?redirect={{ route('commentaires.create') }}" class="inline-block px-6 py-3 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors text-lg font-medium">
                                Connectez-vous pour donner votre avis
                            </a>
                        @endauth
                    </div>
                @endforelse
            </div>
            
            {{-- Pagination --}}
            @if($commentaires->hasPages())
                <div class="mt-10 flex justify-center">
                    {{ $commentaires->links() }}
                </div>
            @endif
            
            {{-- Call-to-action pour laisser un avis --}}
            @if(count($commentaires) > 0)
                <div class="mt-16 text-center bg-[#F3ECE3] py-10 px-4 rounded-lg">
                    <h3 class="text-xl font-bold text-[#6d4927] mb-4">Vous avez séjourné chez nous ?</h3>
                    @auth
                        <a href="{{ route('commentaires.create') }}" class="inline-block px-8 py-4 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors text-lg font-medium">
                            Partagez votre expérience
                        </a>
                    @else
                        <a href="{{ route('login') }}?redirect={{ route('commentaires.create') }}" class="inline-block px-8 py-4 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors text-lg font-medium">
                            Connectez-vous pour donner votre avis
                        </a>
                    @endauth
                </div>
            @endif
        </div>
    </div>
</section>
@endsection