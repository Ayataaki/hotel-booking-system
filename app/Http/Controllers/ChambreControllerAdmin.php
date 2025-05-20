<?php

namespace App\Http\Controllers;

use App\Models\Chambre;
use App\Models\Categorie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class ChambreControllerAdmin extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
    /**
 * Mettre à jour une chambre dans la base de données
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
public function update(Request $request)
{
    \Log::info('Requête update chambre reçue', [
        'request_all' => $request->all(),
        'id' => $request->id
    ]);
    try {
        // Valider les données
        $validated = $request->validate([
            'id' => 'required|exists:chambres,id',
            'NumCh' => 'required|string|max:255',
            'NumEtg' => 'required|integer',
            'status' => 'required|in:0,1,2', // 0: Maintenance, 1: Disponible, 2: Occupée
            'prixNuit' => 'required|numeric|min:0',
            'categorie_id' => 'required|integer|exists:categories,id',
            'capacite' => 'required|integer|min:1',
            'image' => 'nullable|string|max:255',
        ]);

        // Récupérer la chambre à mettre à jour
        $chambre = \App\Models\Chambre::findOrFail($request->id);

        // Mettre à jour les attributs
        $chambre->NumCh = $request->NumCh;
        $chambre->NumEtg = $request->NumEtg;
        $chambre->status = $request->status;
        $chambre->prixNuit = $request->prixNuit;
        $chambre->categorie_id = $request->categorie_id;
        $chambre->capacite = $request->capacite;

        // Mettre à jour l'image si elle est fournie
        if ($request->filled('image')) {
            $chambre->image = $request->image;
        }

        // Sauvegarder les modifications
        $chambre->save();

        // Si la requête est AJAX, renvoyer JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Chambre mise à jour avec succès',
                'chambre' => $chambre
            ]);
        }

        // Sinon, rediriger vers la page des chambres
        // return redirect()->route('admin.rooms')->with('success', 'Chambre mise à jour avec succès');
        return redirect()->back()->with('success', 'Chambre modifiée avec succès');

        // Retourner une réponse de succès
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Chambre mise à jour avec succès',
        //     'chambre' => $chambre
        // ]);
    }
    // catch (\Illuminate\Validation\ValidationException $e) {
    //     // Erreur de validation
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Erreur de validation',
    //         'errors' => $e->errors()
    //     ], 422);
    // }
    catch (\Exception $e) {
        // Autres erreurs
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour de la chambre',
            'error' => $e->getMessage()
        ], 500);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
    }
}
    // public function update(Request $request, $id)
    // {
    //     $chambre = Chambre::findOrFail($id);

    //     $chambre->NumCh = $request->NumCh;
    // $chambre->NumEtg = $request->NumEtg;
    // $chambre->status = $request->status;
    // $chambre->prixNuit = $request->prixNuit;
    // $chambre->categorie_id = $request->categorie_id;
    // $chambre->capacite = $request->capacite;
    // // IMPORTANT : vérifier si l'image a été remplie
    // if (!empty($request->image)) {
    //     $chambre->image = $request->image;
    // }
    // // $chambre->image = $request->image; // juste un texte
    // $chambre->save();
    // return redirect()->back()->with('success', 'Chambre modifiée avec succès');
//         $chambre->update([
//             'titre' => $request->input('titre'),
//     'prixNuit' => $request->input('prixNuit'),
//     'NumCh' => $request->input('NumCh'),
//     'NumEtg' => $request->input('NumEtg'),
//     'capacite' => $request->input('capacite'),
//     'status' => $request->input('status'),
//     'categorie_id' => $request->input('categorie_id'),
//     'image' => $request->input('image'),
// ]);

        // return response()->json(['success' => true]);

    // }

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
     * Remove the specified resource from storage.
     */
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

    public function getChambre($id)
    {
        $chambre = Chambre::with('categorie_id')->findOrFail($id);
        return response()->json($chambre);
    }
}
