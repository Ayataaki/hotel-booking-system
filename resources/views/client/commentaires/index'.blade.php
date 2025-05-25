{{-- resources/views/commentaires/index.blade.php --}}
@extends('layouts.app')

@section('content')
<section class="max-w-6xl mx-auto p-6 mt-8 mb-12">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6 bg-[#F3ECE3]">
            <h1 class="text-2xl font-bold text-[#6d4927]">Avis de nos clients</h1>
            <p class="text-gray-600 mt-2">Découvrez ce que nos clients pensent de leur séjour à Mi Casa</p>
        </div>
        
        <div class="p-6">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($commentaires as $commentaire)
                    <div class="bg-[#F8F7F4] p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-semibold text-[#6d4927]">{{ $commentaire->titre }}</h3>
                            <div class="flex space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-4 w-4 {{ $i <= $commentaire->note ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        
                        <p class="text-gray-600 italic mb-4">"{{ $commentaire->avis }}"</p>
                        
                        <div class="flex justify-between items-center text-sm text-gray-500">
                            <span>{{ $commentaire->utilisateur->name }}</span>
                            <span>{{ $commentaire->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 p-12 text-center">
                        <p class="text-gray-500 italic mb-6">Aucun avis client pour le moment.</p>
                        @auth
                            <a href="{{ route('commentaires.create') }}" class="inline-block px-6 py-3 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors">
                                Soyez le premier à donner votre avis
                            </a>
                        @else
                            <a href="{{ route('login') }}?redirect={{ route('commentaires.create') }}" class="inline-block px-6 py-3 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors">
                                Connectez-vous pour donner votre avis
                            </a>
                        @endauth
                    </div>
                @endforelse
            </div>
            
            {{-- Pagination --}}
            @if($commentaires->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $commentaires->links() }}
                </div>
            @endif
            
            {{-- Call-to-action pour laisser un avis --}}
            @if(count($commentaires) > 0)
                <div class="mt-12 text-center">
                    @auth
                        <a href="{{ route('commentaires.create') }}" class="inline-block px-6 py-3 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors">
                            Partagez votre expérience
                        </a>
                    @else
                        <a href="{{ route('login') }}?redirect={{ route('commentaires.create') }}" class="inline-block px-6 py-3 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors">
                            Connectez-vous pour donner votre avis
                        </a>
                    @endauth
                </div>
            @endif
        </div>
    </div>
</section>
@endsection