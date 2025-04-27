<?php

namespace App\Http\Controllers;

use App\Models\Chambre;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ChambreController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function test(){
        return 'test';
    }

    /* public function index(Request $request)
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
    } */


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
    // public function store(Request $request)
    // {
        //dd($request->all());
        // $request->validate([
        //     'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // ]);

        // $chambre=new Chambre();
        // $chambre->NumCh=$request->input('numCh');
        // $chambre->NumEtg=$request->input('numEtg');
        // $chambre->prixNuit=$request->input('prixNuit');
        // $chambre->categorie_id=$request->input('categorie_id');
        // $chambre->capacite=$request->input('capacite');

        // if ($request->hasFile('image')) {
        //     $image = $request->file('image');
        //     $imageName = time().'.'.$image->getClientOriginalExtension();
        //     $path = $image->storeAs('images/chambres', $imageName, 'public');

            //if (Storage::disk('public')->exists('images/chambres/' . $imageName)) {
            //    dd('Image enregistrée avec succès dans le stockage public.');
            //} else {
            //    dd('Échec de l’enregistrement de l’image.');
            //}

        //     if ($path) {
        //         Log::info("Image stockée avec succès : " . $path);
        //     } else {
        //         Log::error("Échec du stockage de l'image.");
        //     }
        //     $chambre->image = $imageName;  // Enregistre le nom de l'image dans la base de données
        // }

        //pour l'affichage de l'image :
        //<img src="{{ asset('storage/images/chambres/'.$chambre->image) }}" alt="Image de la chambre">

    //     $chambre->save();
    //     return redirect()->route('chambre.index')->with('success', 'Chambre ajoutée avec succès.');
    // }



    //La nouvelle fonction d'ajout des chambres.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'NumCh' => 'required',
            'NumEtg' => 'required|integer',
            'status' => 'required|integer',
            'prixNuit' => 'required|numeric',
            'categorie_id' => 'required|integer',
            'capacite' => 'required|integer',
            'image' => 'nullable|string' // juste un nom d'image
        ]);

        $validated['reservation_id'] = 0;

        Chambre::create($validated);

        // return response()->json(['message' => 'Chambre ajoutée avec succès']);
        return redirect()->back()->with('success', 'Chambre ajoutée avec succès');
    }



    /**
     * Display the specified resource.
     */
    /* public function show(string $id)
    {
        return view('chambre.page', [
            'chambre' => Chambre::findOrFail($id)
        ]);
        //its route : Route::get('/user/{id}', [UserController::class, 'show']);
    } */
    public function index(Request $request)
     {
        // Récupération de toutes les catégories pour le filtre
         $categories = Categorie::all();

        // Initialisation de la requête de base
         $query = Chambre::with('categorie');

        // Filtrage par catégorie si demandé
         if ($request->has('categorie')) {
             $query->where('categorie_id', $request->categorie);
         }

        // Récupération des chambres
         $chambres = $query->get();




         $query = Chambre::query();

         if ($request->filled('type')) {
             $query->where('categorie_id', $request->type);
         }

         if ($request->filled('status')) {
             $query->where('status', $request->status);
         }

         $chambres = $query->get();




        //dd($chambres->count(), $chambres->toArray());

        // Passage des données à la vue
         return view('client.chambres', compact('chambres', 'categories'));
    }


    // public function index(Request $request)
    // {
    //     $query = Chambre::query();

    //     if ($request->filled('type')) {
    //         $query->where('categorie_id', $request->input('type'));
    //     }

    //     if ($request->filled('status')) {
    //         $query->where('status', $request->input('status'));
    //     }

    //     $chambres = $query->get();

    //     return view('admin.rooms', [
    //         'chambres' => $chambres,
    //         'selectedType' => $request->input('type'),
    //         'selectedStatus' => $request->input('status')
    //     ]);
    // }
    public function indexAdmin(Request $request)
    {
        $query = Chambre::query();

        if ($request->filled('type')) {
            $query->where('categorie_id', $request->input('type'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $chambres = $query->get();

        return view('admin.rooms', [
            'chambres' => $chambres,
            'selectedType' => $request->input('type'),
            'selectedStatus' => $request->input('status')
        ]);
    }


    /**
     * Affiche les détails d'une chambre spécifique
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $chambre = Chambre::with('categorie')->findOrFail($id);
        return view('chambre.details', compact('chambre'));
    }


    /**
     * Affiche la liste des chambres pour les clients
     */


    /* public function showAll(Request $request)
    {
        $query = Chambre::with('categorie');

        // Filtrer par catégorie si fourni
        if ($request->filled('categorie')) {
            $query->whereHas('categorie', function($q) use ($request) {
                $q->where('libelle', $request->categorie);
            });
        }

        // Récupérer les chambres filtrées
        $chambre = $query->get();

        $categorie = Categorie::all();


        // Retourner la vue avec les chambres
        return view('client.chambres', compact('chambre','categorie'));
    } */

    public function showAll(Request $request)
    {
        $query = Chambre::with('categorie');

        // Filtrer par catégorie si fourni
        if ($request->filled('categorie')) {
            $query->whereHas('categorie', function($q) use ($request) {
                $q->where('typeChambre', $request->categorie); // Utilisez le nom réel de la colonne
            });
        }

        // Récupérer les chambres filtrées
        $chambres = $query->get();

        // Ajouter manuellement l'ID si nécessaire pour les chambres qui n'en ont pas
        foreach ($chambres as $chambre) {
            if (!isset($chambre->id)) {
                // Utilisez NumCh comme ID alternatif
                $chambre->id = $chambre->NumCh;
            }
        }

        $categories = Categorie::all();

        // Retourner la vue avec les chambres
        return view('client.chambres', compact('chambres', 'categories'));
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
    // public function update(Request $request, $id)
    // {
    //     // Validation des données du formulaire
    //     $request->validate([
    //         'numChambre' => 'required|string|max:255',
    //         'numEtg' => 'required|integer',
    //         'prixNuit' => 'required|numeric',
    //         'status' => 'required|string',
    //         'categorie_id' => 'required|exists:categories,id',
    //     ]);

    //     // Trouver la chambre existante
    //     $chambre = Chambre::findOrFail($id);

    //     // Mise à jour des informations de la chambre
    //     $chambre->numChambre = $request->input('numChambre');
    //     $chambre->numEtg = $request->input('numEtg');
    //     $chambre->prixNuit = $request->input('prixNuit');
    //     $chambre->status = $request->input('status');
    //     $chambre->categorie_id = $request->input('categorie_id');

    //     // Sauvegarder les modifications dans la base de données
    //     $chambre->save();

    //     // Rediriger vers la liste des chambres avec un message de succès
    //     return redirect()->route('chambre.index')->with('success', 'Chambre mise à jour avec succès!');
    // }

    // La nouvelle fonction ajoutée dans la page d'admin.
    public function update(Request $request, $id)
    {
        $chambre = Chambre::findOrFail($id);

        $chambre->update([
            'titre' => $request->input('titre'),
            'prixNuit' => $request->input('prixNuit'),
            'NumCh' => $request->input('NumCh'),
            'NumEtg' => $request->input('NumEtg'),
            'capacite' => $request->input('capacite'),
            'status' => $request->input('status'),
            'categorie_id' => $request->input('categorie_id'),
            // Ajoute ici les autres champs si nécessaire
        ]);

        return redirect()->back()->with('success', 'Chambre modifiée avec succès');
    }



    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     $chambre = Chambre::findOrFail($id);
    //     $chambre->delete();
    //     return redirect()->route('chambre.index')->with('success', 'Chambre supprimée avec succès.');
    // }

    // La nouvelle fonction ajouté dans la page d'admin.
    // public function destroy($id)
    // {
    //     $chambre = Chambre::findOrFail($id);
    //     $chambre->delete();

    //     return redirect()->back()->with('success', 'Chambre supprimée avec succès');
    // }

    public function destroy($id)
    {
        $room = Chambre::findOrFail($id);
        $room->delete();


        return redirect()->back()->with('success', 'Chambre supprimée avec succès');
        // return response()->json(['message' => 'Chambre supprimée avec succès']);
    }



    /**
     * Vérifie la disponibilité d'une chambre pour les dates spécifiées
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'chambre_id' => 'required|exists:chambres,id',
            'date_arrivee' => 'required|date|after_or_equal:today',
            'date_depart' => 'required|date|after:date_arrivee',
        ]);

        $chambre = Chambre::findOrFail($request->chambre_id);

        // Vérifier si la chambre est déjà réservée pour les dates demandées
        $isAvailable = $chambre->isAvailableForDates(
            $request->date_arrivee,
            $request->date_depart
        );

        return response()->json([
            'available' => $isAvailable
        ]);
    }

    /**
     * Récupérer les chambres disponibles pour une période donnée
     */
    public function getChambresDisponibles(Request $request)
    {
        // Valider les données d'entrée
        $request->validate([
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after:dateDebut',
            'personnes' => 'required|integer|min:1'
        ]);

        $dateDebut = $request->dateDebut;
        $dateFin = $request->dateFin;
        $personnes = $request->personnes;

        // Récupérer les chambres disponibles
        // Une chambre est disponible si elle n'a pas de réservation qui chevauche la période demandée
        // et si sa capacité est suffisante pour le nombre de personnes
        $chambresDisponibles = Chambre::with('categorie_id')
            ->where('status', 0) // Chambre actuellement disponible
            ->where('capacite', '>=', $personnes)
            ->whereNotIn('id', function($query) use ($dateDebut, $dateFin) {
                $query->select('chambre_id')
                      ->from('reservations')
                      ->where(function($q) use ($dateDebut, $dateFin) {
                          // Recherche des chevauchements de dates
                          $q->whereBetween('dateDeb', [$dateDebut, $dateFin])
                            ->orWhereBetween('dateFin', [$dateDebut, $dateFin])
                            ->orWhere(function($subq) use ($dateDebut, $dateFin) {
                                $subq->where('dateDeb', '<=', $dateDebut)
                                     ->where('dateFin', '>=', $dateFin);
                            });
                      });
            })
            ->get();

        return response()->json($chambresDisponibles);
    }

    /**
     * Récupérer les détails d'une chambre spécifique
     */
    public function getChambre($id)
    {
        $chambre = Chambre::with('categorie_id')->findOrFail($id);
        return response()->json($chambre);
    }

}
