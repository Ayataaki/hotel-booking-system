<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentaireController extends Controller
{
    /**
     * Afficher la liste des commentaires
     */
    public function index()
    {
        $commentaires = Commentaire::with('utilisateur')
            ->orderBy('created_at', 'desc')
            ->paginate(9);
            
        // Récupérer les commentaires récents pour la sidebar
        $recentCommentaires = Commentaire::with('utilisateur')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('client.commentaires.index', compact('commentaires', 'recentCommentaires'));
    }

    /**
     * Afficher le formulaire de commentaire
     */
    public function create()
    {
        // Récupérer les commentaires récents pour les afficher à côté du formulaire
        $recentCommentaires = Commentaire::with('utilisateur')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('client.commentaires.create', compact('recentCommentaires'));
    }

    /**
     * Enregistrer un nouveau commentaire
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'titre' => 'required|max:255',
            'note' => 'required|integer|min:1|max:5',
            'avis' => 'required|max:1000',
        ]);

        // Création du commentaire
        $commentaire = new Commentaire();
        $commentaire->utilisateur_id = Auth::id();
        $commentaire->titre = $validated['titre'];
        $commentaire->note = $validated['note'];
        $commentaire->avis = $validated['avis'];
        $commentaire->save();

        // Redirection avec message de succès
        return redirect()->route('commentaires.confirmation')
            ->with('success', 'Votre commentaire a été enregistré avec succès!');
    }

    /**
     * Afficher la page de confirmation
     */
    public function confirmation()
    {
        return view('client.commentaires.confirmation');
    }
    
    /**
     * Récupérer les commentaires récents pour l'affichage sur la page d'accueil
     */
    public function getRecentCommentaires()
    {
        return Commentaire::with('utilisateur')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
    }
    
    /**
     * Récupérer les commentaires les mieux notés
     */
    public function getTopCommentaires()
    {
        return Commentaire::with('utilisateur')
            ->orderBy('note', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
    }
}