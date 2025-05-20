<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Chambre;
use App\Models\Categorie;
use App\Models\Reservation;
use App\Models\Supplementaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationControllerAdmin extends Controller
{
    /**
     * Affiche la liste des réservations.
     *
     * @return \Illuminate\Http\Response
     */
   public function index(Request $request)
    {
        // Nombre d'éléments par page
        $perPage = 5;

        // Page actuelle (par défaut 1)
        $page = $request->input('page', 1);

        // Compter depuis la table historiques
        $total = DB::table('historiques')->count();

        // Nombre total de pages
        $totalPages = ceil($total / $perPage);

        // S'assurer que la page demandée est valide
        if ($page < 1) $page = 1;
        if ($total > 0 && $page > $totalPages) $page = $totalPages;

        // Calculer l'offset
        $offset = ($page - 1) * $perPage;

        // Récupérer les IDs des réservations pour la page actuelle
        $reservationIds = DB::table('historiques')
            ->orderBy('created_at', 'desc')
            ->offset($offset)
            ->limit($perPage)
            ->pluck('reservation_id');

        // S'assurer que nous avons exactement perPage éléments (sauf pour la dernière page)
        $reservations = Reservation::with(['client.user', 'historique.chambre'])
            ->whereIn('id', $reservationIds)
            ->orderBy('created_at', 'desc')
            ->get();
            // ->paginate(5);

        // Si nous sommes sur la dernière page et qu'il manque des enregistrements, ajuster l'affichage
        $itemsOnLastPage = $total % $perPage;
        $isLastPage = ($page == $totalPages);
        $expectedItems = $isLastPage && $itemsOnLastPage > 0 ? $itemsOnLastPage : $perPage;

        // Données supplémentaires
        $clients = Client::all();
        $chambres = Chambre::where('status', 1)->get();
        $receptionnistes = \App\Models\Receptionniste::all();

        return view('admin.reservations', compact(
            'reservations',
            'clients',
            'chambres',
            'receptionnistes',
            'page',
            'totalPages',
            'total',
            'perPage',
            'expectedItems'
        ));
    }

    /**
 * Affiche les détails d'une chambre spécifique.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function viewChambre($id)
    {
        // Récupérer la chambre avec ses équipements et autres détails
        $chambre = Chambre::with(['equipements', 'categorie', 'etage'])
            ->findOrFail($id);

        // Récupérer les réservations actuelles pour cette chambre
        $reservationsActuelles = DB::table('historiques')
            ->join('reservations', 'historiques.reservation_id', '=', 'reservations.id')
            ->join('clients', 'reservations.client_id', '=', 'clients.id')
            ->where('historiques.chambre_id', $id)
            ->where('reservations.dateFin', '>=', now())
            ->select('reservations.*', 'clients.prenom', 'clients.nom')
            ->get();

        return view('admin.chambres.show', compact('chambre', 'reservationsActuelles'));
    }


    /**
     * Récupère les détails d'une chambre au format JSON.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    // public function getChambreDetails($id)
    // {
    //     try {
    //         // Récupérer la chambre avec ses relations
    //         $chambre = Chambre::findOrFail($id);

    //         // Récupérer les équipements associés
    //         $equipements = $chambre->equipements()->pluck('libEquip')->toArray() ?? [];

    //         // Récupérer les réservations actuelles
    //         $reservationsActuelles = DB::table('historiques')
    //             ->join('reservations', 'historiques.reservation_id', '=', 'reservations.id')
    //             ->join('clients', 'reservations.client_id', '=', 'clients.id')
    //             ->where('historiques.chambre_id', $id)
    //             ->where('reservations.dateFin', '>=', now())
    //             ->select(
    //                 'reservations.id',
    //                 'clients.prenom',
    //                 'clients.nom',
    //                 'reservations.dateDeb',
    //                 'reservations.dateFin'
    //             )
    //             ->get()
    //             ->map(function($res) {
    //                 return [
    //                     'id' => $res->id,
    //                     'client' => $res->prenom . ' ' . $res->nom,
    //                     'debut' => \Carbon\Carbon::parse($res->dateDeb)->format('d/m/Y'),
    //                     'fin' => \Carbon\Carbon::parse($res->dateFin)->format('d/m/Y')
    //                 ];
    //             })
    //             ->toArray();

    //         return response()->json([
    //             'id' => $chambre->id,
    //             'numero' => $chambre->NumCh,
    //             'etage' => $chambre->NumEtg,
    //             'categorie' => $chambre->categorie->libCat ?? 'Non définie',
    //             'prix' => $chambre->prixNuit,
    //             'statut' => $chambre->status == 1 ? 'disponible' : 'occupée',
    //             'equipements' => $equipements,
    //             'reservations' => $reservationsActuelles
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }


    /**
     * Récupère les détails d'une chambre au format JSON.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChambreDetails($id)
    {
        try {
            // 1. Vérifier si la chambre existe
            $chambre = Chambre::find($id);

            if (!$chambre) {
                return response()->json(['error' => 'Chambre non trouvée'], 404);
            }

            // 2. Construire une réponse simple pour commencer
            $response = [
                'id' => $chambre->id,
                'numero' => $chambre->NumCh,
                'etage' => $chambre->NumEtg,
                'prix' => $chambre->prixNuit,
                'statut' => $chambre->status == 1 ? 'disponible' : 'occupée'
            ];

            // 3. Ajouter la catégorie si disponible
            try {
                $response['categorie'] = $chambre->categorie ? $chambre->categorie->libCat : 'Non définie';
            } catch (\Exception $e) {
                $response['categorie'] = 'Non définie';
            }

            // 4. Ajouter l'image si disponible
            $response['image'] = $chambre->image ?? '';

            // 5. Ajouter la capacité si disponible
            $response['capacite'] = $chambre->capacite ?? '';

            // 6. Ajouter les équipements si la table existe et la relation est correcte
            $response['equipements'] = [];
            try {
                if (Schema::hasTable('chambre_equipement') && Schema::hasTable('equipements')) {
                    $equipements = DB::table('equipements')
                        ->join('chambre_equipement', 'equipements.id', '=', 'chambre_equipement.equipement_id')
                        ->where('chambre_equipement.chambre_id', $id)
                        ->pluck('equipements.libEquip')
                        ->toArray();
                    $response['equipements'] = $equipements;
                }
            } catch (\Exception $e) {
                // Ignorer les erreurs liées aux équipements
            }

            // 7. Ajouter les réservations si la relation existe
            $response['reservations'] = [];
            try {
                $reservationsActuelles = DB::table('historiques')
                    ->join('reservations', 'historiques.reservation_id', '=', 'reservations.id')
                    ->join('clients', 'reservations.client_id', '=', 'clients.id')
                    ->where('historiques.chambre_id', $id)
                    ->where('reservations.dateFin', '>=', now())
                    ->select(
                        'reservations.id',
                        'clients.prenom',
                        'clients.nom',
                        'reservations.dateDeb',
                        'reservations.dateFin',
                        'reservations.statut'
                    )
                    ->get();

                if ($reservationsActuelles) {
                    $response['reservations'] = $reservationsActuelles->map(function($res) {
                        return [
                            'id' => $res->id,
                            'client' => $res->prenom . ' ' . $res->nom,
                            'debut' => date('d/m/Y', strtotime($res->dateDeb)),
                            'fin' => date('d/m/Y', strtotime($res->dateFin)),
                            'statut' => $res->statut
                        ];
                    })->toArray();
                }
            } catch (\Exception $e) {
                // Ignorer les erreurs liées aux réservations
            }

            return response()->json($response);

        } catch (\Exception $e) {
            // Log l'erreur pour le débogage
            Log::error('Erreur lors de la récupération des détails de chambre: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la récupération des détails de la chambre: ' . $e->getMessage()], 500);
        }
    }




//     public function index()
// {
//     // Utiliser with() pour charger les relations et éviter les requêtes N+1
//     $reservations = Reservation::with(['client', 'historique.chambre'])
//         ->orderBy('created_at', 'desc')
//         ->paginate(5);

//     // Ajouter les paramètres de requête à la pagination
//     $reservations->appends(request()->query());

//     // Vérifier si la page est vide et rediriger vers la dernière page
//     if ($reservations->isEmpty() && $reservations->currentPage() > 1) {
//         return redirect()->route('admin.reservations', ['page' => $reservations->lastPage()]);
//     }

//     // Récupérer les clients pour le formulaire d'ajout
//     $clients = Client::all();

//     // Récupérer les chambres disponibles
//     $chambres = Chambre::where('status', 1)->get();

//     // Récupérer les réceptionnistes
//     $receptionnistes = \App\Models\Receptionniste::all();

//     return view('admin.reservations', compact('reservations', 'clients', 'chambres', 'receptionnistes'));
// }
    // public function index()
    // {
    //     $reservations = Reservation::with(['client', 'chambres'])->orderBy('created_at', 'desc')->paginate(5);
    //     // $reservations = Reservation::with(['client', 'chambre'])
    //     // ->orderBy('created_at', 'desc')
    //     // ->paginate(5)
    //     // ->appends(request()->query()); // <-- Important pour garder la page
    //     // ✅ Ajoute ici la vérification
    //     if ($reservations->isEmpty() && $reservations->currentPage() > 1) {
    //         return redirect()->route('admin.reservations', ['page' => $reservations->lastPage()]);
    //     }


    //     // Récupérer les clients pour le formulaire d'ajout
    //     $clients = Client::all();

    //     // Récupérer les chambres disponibles
    //     $chambres = Chambre::where('status', 1)->get(); // ou tout autre filtre pour les chambres disponibles

    //     // Récupérer les réceptionnistes si nécessaire
    //     $receptionnistes = \App\Models\Receptionniste::all(); // Assurez-vous que ce modèle existe

    //     return view('admin.reservations', compact('reservations', 'clients', 'chambres', 'receptionnistes'));
    // }
    // public function index()
    // {
    //     $reservations = Reservation::with(['client', 'chambres'])
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(10);

    //     return view('admin.reservations', compact('reservations'));
    // }

    /**
     * Affiche le formulaire de création pour une nouvelle réservation.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();
        $chambres = Chambre::where('statut', 'disponible')->get();
        $categories = Categorie::all();
        $services = Supplementaire::all();

        return view('admin.reservations.create', compact('clients', 'chambres', 'categories', 'services'));
    }

    /**
     * Stocke une nouvelle réservation créée par l'administrateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    /**
     * Affiche les détails d'une réservation spécifique.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reservation = Reservation::with(['client', 'chambres', 'services'])->findOrFail($id);
        return view('admin.reservations.show', compact('reservation'));
    }
    // public function show($id)
    // {
    //     $reservation = Reservation::with(['client', 'chambres', 'services'])->findOrFail($id);
    //     return view('admin.reservations.show', compact('reservation'));
    // }

    /**
     * Affiche le formulaire de modification pour une réservation existante.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $reservation = Reservation::with(['client', 'chambres'])->findOrFail($id);
        $clients = Client::all();
        $chambres = Chambre::all();
        $categories = Categorie::all();
        $services = Supplementaire::all();

        return view('admin.reservations.edit', compact('reservation', 'clients', 'chambres', 'categories', 'services'));
    }

    /**
     * Met à jour la réservation spécifiée.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            // Valider les données du formulaire
            $validatedData = $request->validate([
                'roomNumber' => 'required|exists:chambres,id',
                'checkin' => 'required|date',
                'checkout' => 'required|date|after:checkin',
                'adults' => 'required|integer|min:1',
                'children' => 'required|integer|min:0',
                'status' => 'required|string|in:confirmed,pending,cancelled',
                'pricePerNight' => 'required|numeric|min:0',
                'totalPrice' => 'required|numeric|min:0',
                'paymentMethod' => 'required|string|in:card,cash,transfer',
                'depositPaid' => 'required|numeric|min:0',
                'notes' => 'nullable|string',
            ]);

            // Début de la transaction
            DB::beginTransaction();

            $reservation = Reservation::findOrFail($id);

            // Vérifier si la chambre a changé
            $oldChambreId = DB::table('historiques')
                ->where('reservation_id', $id)
                ->value('chambre_id');

            if ($oldChambreId != $validatedData['roomNumber']) {
                // Libérer l'ancienne chambre
                $oldChambre = Chambre::find($oldChambreId);
                if ($oldChambre) {
                    $oldChambre->statut = 'disponible';
                    $oldChambre->save();
                }

                // Mettre à jour l'historique
                DB::table('historiques')
                    ->where('reservation_id', $id)
                    ->update([
                        'chambre_id' => $validatedData['roomNumber'],
                        'updated_at' => now()
                    ]);

                // Réserver la nouvelle chambre
                $newChambre = Chambre::find($validatedData['roomNumber']);
                $newChambre->statut = 'occupée';
                $newChambre->save();
            }

            // Mettre à jour la réservation
            $reservation->dateDeb = $validatedData['checkin'];
            $reservation->dateFin = $validatedData['checkout'];
            $reservation->statutReserv = $validatedData['status'];
            $reservation->modePaiement = $validatedData['paymentMethod'];
            $reservation->totalPayer = $validatedData['totalPrice'];
            $reservation->acompte = $validatedData['depositPaid'];
            $reservation->notes = $validatedData['notes'];
            $reservation->adultes = $validatedData['adults'];
            $reservation->enfants = $validatedData['children'];
            $reservation->prixParNuit = $validatedData['pricePerNight'];
            $reservation->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Réservation mise à jour avec succès',
                'reservation' => $reservation
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la réservation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprime la réservation spécifiée.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $reservation = Reservation::findOrFail($id);

            // Récupérer l'ID de la chambre associée
            $chambreId = DB::table('historiques')
                ->where('reservation_id', $id)
                ->value('chambre_id');

            // Libérer la chambre
            if ($chambreId) {
                $chambre = Chambre::find($chambreId);
                if ($chambre) {
                    $chambre->statut = 'disponible';
                    $chambre->save();
                }
            }

            // Supprimer les entrées dans les tables de liaison
            DB::table('historiques')->where('reservation_id', $id)->delete();
            DB::table('posseders')->where('reservation_id', $id)->delete();

            // Supprimer la réservation
            $reservation->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Réservation supprimée avec succès'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la réservation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Recherche et filtre les réservations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = Reservation::with(['client', 'chambres']);

        // Filtre par référence
        if ($request->has('reference') && !empty($request->reference)) {
            $query->where('reference', 'like', '%' . $request->reference . '%');
        }

        // Filtre par nom de client
        if ($request->has('client_name') && !empty($request->client_name)) {
            $query->whereHas('client', function ($q) use ($request) {
                $q->where(DB::raw("CONCAT(prenomCli, ' ', nomCli)"), 'like', '%' . $request->client_name . '%');
            });
        }

        // Filtre par email de client
        if ($request->has('client_email') && !empty($request->client_email)) {
            $query->whereHas('client', function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->client_email . '%');
            });
        }

        // Filtre par date d'arrivée
        if ($request->has('checkin') && !empty($request->checkin)) {
            $query->whereDate('dateDeb', '>=', $request->checkin);
        }

        // Filtre par date de départ
        if ($request->has('checkout') && !empty($request->checkout)) {
            $query->whereDate('dateFin', '<=', $request->checkout);
        }

        // Filtre par statut
        if ($request->has('status') && !empty($request->status)) {
            $query->where('statutReserv', $request->status);
        }

        // Tri
        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction ?? 'desc';
        $query->orderBy($sort, $direction);

        $reservations = $query->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'reservations' => $reservations,
                'pagination' => (string) $reservations->links('pagination::tailwind')
            ]);
        }

        return view('admin.reservations', compact('reservations'));
    }
}
