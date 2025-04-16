<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Chambre;
use Illuminate\Http\Request;

class ChambreController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function test(){
        return 'test';
    }

    public function index(Request $request)
    {
        // Charge la relation avec la catégorie, parce que sinon, on n'arrive pas à lier les chambres avec les catégories
        $query = Chambre::with('categorie');

        // Filtrer par numéro d'étage si fourni
        if ($request->filled('numEtg')) {
            //si true on va extraire l'étage, sinon ça ne sera pas inclu (null) -> par la méthode GET
            $query->where('NumEtg', $request->numEtg);
        }

        // Filtrer par catégorie si sélectionnée
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        // Trier par colonne sélectionnée
        $sortBy = $request->input('sort_by', 'prixNuit'); // Valeur par défaut = prix
        $sortDirection = $request->input('sort_direction', 'asc'); // Valeur par défaut = croissant
        $query->orderBy($sortBy, $sortDirection);

        // Récupérer les chambres filtrées
        $chambres = $query->get();

        // Charger toutes les catégories pour le formulaire
        $categories = Categorie::all();

        //on envoie avec les catégories pour les afficher
        return view('chambre.index', compact('chambres', 'categories'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories=Categorie::all();//on cherche toutes les cats
        return view("chambre.form",["categories"=>$categories]);// on fait passer ces cats pour qu'on puisse les montrer
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $chambre=new Chambre();
        $chambre->NumCh=$request->input('numCh');
        $chambre->NumEtg=$request->input('numEtg');
        $chambre->prixNuit=$request->input('prixNuit');
        $chambre->categorie_id=$request->input('categorie_id');
        $chambre->save();
        return redirect()->route('chambre.index')->with('success', 'Chambre supprimée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('chambre.page', [
            'chambre' => Chambre::findOrFail($id)
        ]);
        //its route : Route::get('/user/{id}', [UserController::class, 'show']);
    }

    /**
     * Affiche la liste des chambres pour les clients
     */
    public function showAll(Request $request)
    {
        $query = Chambre::with('categorie');

        // Filtrer par catégorie si fourni
        if ($request->filled('categorie')) {
            $query->whereHas('categorie', function($q) use ($request) {
                $q->where('libelle', $request->categorie);
            });
        }

        // Récupérer les chambres filtrées
        $chambres = $query->get();

        // Retourner la vue avec les chambres
        return view('client.chambres', compact('chambres'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $chambre = Chambre::findOrFail($id);
        $categories = Categorie::all();
        return view('chambre.edit', compact('chambre','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validation des données du formulaire
        $request->validate([
            'numChambre' => 'required|string|max:255',
            'numEtg' => 'required|integer',
            'prixNuit' => 'required|numeric',
            'status' => 'required|string',
            'categorie_id' => 'required|exists:categories,id',
        ]);

        // Trouver la chambre existante
        $chambre = Chambre::findOrFail($id);

        // Mise à jour des informations de la chambre
        $chambre->numChambre = $request->input('numChambre');
        $chambre->numEtg = $request->input('numEtg');
        $chambre->prixNuit = $request->input('prixNuit');
        $chambre->status = $request->input('status');
        $chambre->categorie_id = $request->input('categorie_id');

        // Sauvegarder les modifications dans la base de données
        $chambre->save();

        // Rediriger vers la liste des chambres avec un message de succès
        return redirect()->route('chambre.index')->with('success', 'Chambre mise à jour avec succès!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $chambre = Chambre::findOrFail($id);
        $chambre->delete();
        return redirect()->route('chambre.index')->with('success', 'Chambre supprimée avec succès.');
    }
}
