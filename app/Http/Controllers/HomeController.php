<?php

namespace App\Http\Controllers;

use App\Models\Chambre;
use App\Models\Commentaire;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $chambresVedettes = Chambre::with('categorie')
            ->take(3) // Limiter à 3 chambres pour l'affichage en vedette
            ->get();
        
        
        $temoignages = Commentaire::with('utilisateur')
        ->where('note', '>=', 4) // Seulement les avis avec 4 étoiles ou plus
        ->orderBy('note', 'desc')
        ->orderBy('created_at', 'desc')
        ->take(4) // Limité à 4 témoignages
        ->get()
        ->map(function ($commentaire) {
            // Formater les données pour correspondre à la structure attendue dans la vue
            return [
                'name' => $commentaire->utilisateur->name ?? 'Client',
                'rating' => $commentaire->note,
                'comment' => $commentaire->avis,
            ];
        });

        return view('client.index',compact('chambresVedettes', 'temoignages'));
    }
}
