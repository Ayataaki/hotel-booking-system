@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')

  <!-- Section Informations -->
  <section class="w-full px-8 md:px-16 lg:px-24 mt-8">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full">
      <h2 class="text-2xl font-bold mb-4">Mes Informations</h2>
      
      <div class="flex flex-col">
        <div class="mb-4">
          <p class="text-gray-600">Nom: <span class="font-semibold text-lg">{{ $user->name }}</span></p>
        </div>
        
        <div>
          <p class="text-gray-600">Email: <span class="font-semibold text-lg">{{ $user->email }}</span></p>
        </div>
      </div>
    </div>
  </section>

  <!-- Section Historique -->
  <section class="w-full px-8 md:px-16 lg:px-24 mt-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
      <div class="px-6 py-4 border-b">
        <h2 class="text-2xl font-bold">Historique de vos réservations</h2>
      </div>
      
      @if ($historiques->count() > 0)
        <table class="min-w-full bg-white">
          <thead>
            <tr>
              <th class="px-4 py-2 bg-gray-100 text-left">Date de la réservation</th>
              <th class="px-4 py-2 bg-gray-100 text-left">Type de la chambre</th>
              <th class="px-4 py-2 bg-gray-100 text-left">Date début</th>
              <th class="px-4 py-2 bg-gray-100 text-left">Date fin</th>
            </tr>
          </thead>
          
          <tbody>
            @foreach ($historiques as $hist)
              <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-2">{{ $hist->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-2">
                  {{ $hist->chambre->categorie->typeChambre ?? 'Type inconnu' }}
                </td>
                <td class="px-4 py-2">{{ $hist->reservation->dateDeb->format('d/m/Y') ?? 'N/A' }}</td>
                <td class="px-4 py-2">{{ $hist->reservation->dateFin->format('d/m/Y') ?? 'N/A' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @else
        <div class="p-8 text-center text-gray-500">
          Vous n'avez pas encore effectué de réservation.
        </div>
      @endif
    </div>
  </section>

  <!-- Section Commentaires -->
  <section class="w-full px-8 md:px-16 lg:px-24 mt-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
      <h2 class="text-2xl font-bold mb-4">Mes Commentaires</h2>
      <div class="space-y-4">
        @if ($commentaires->count() > 0)
          @foreach ($commentaires as $com)
            <div class="bg-[#F8F7F4] p-4 rounded-lg">
              <div class="flex items-center mb-2">
                <p class="font-semibold mr-4">{{$com->titre}}</p>
                <div class="flex space-x-1">
                  @for($i = 1; $i <= 5; $i++)
                    <svg class="h-4 w-4 {{ $i <= $com->note ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                  @endfor
                </div>
              </div>
              <p class="text-gray-600 italic">{{$com->avis ?? "Séjour parfait, je recommande vivement cet hôtel !"}}</p>
              <p class="text-sm text-gray-500 mt-2">{{ $com->created_at->format('d/m/Y') }}</p>
            </div>
          @endforeach
        @else
          <div class="bg-[#F8F7F4] p-8 text-center text-gray-500 rounded-lg">
            Vous n'avez pas déposé de commentaire.
          </div>
        @endif
      </div>
    </div>
  </section>

  <!-- Bouton Partager votre expérience -->
  <section class="w-full px-8 md:px-16 lg:px-24 mt-8 mb-12">
    <div class="text-center">
      <a href="{{ route('commentaires.create') }}" class="inline-block px-6 py-3 bg-[#95714F] text-white rounded-lg hover:bg-[#6d4927] transition-colors">
        Partagez votre expérience
      </a>
    </div>
  </section>

@endsection