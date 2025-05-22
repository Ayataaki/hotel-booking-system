<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chambre;
use App\Models\Reservation;
use Illuminate\Routing\Controller;
use App\Models\Client;
use App\Models\Historique;
use App\Models\Paiement;
use Carbon\Carbon;
// use App\Http\Controllers\Facture;
use App\Models\Facture;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// use App\Http\Controllers\Log;


class ReceptionController extends Controller
{
    /**
     * Constructeur - vérifie que l'utilisateur est authentifié
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Si vous avez un middleware 'role', décommentez la ligne suivante
        // $this->middleware('role:recep');
    }

    /**
     * Affiche le tableau de bord du réceptionniste
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Chambres disponibles aujourd'hui (status = 1)
        $chambresDisponibles = Chambre::where('status', 1)->count();

        // Arrivées du jour
        $today = Carbon::today();
        $arriveesDuJour = Reservation::whereDate('dateDeb', $today)
            ->count();

        // Départs du jour
        $departsDuJour = Reservation::whereDate('dateFin', $today)
            ->count();

        // Réservations récentes
        $reservationsRecentes = Reservation::with('client')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Pour chaque réservation, récupérer la chambre associée
        foreach ($reservationsRecentes as $reservation) {
            if ($reservation->historique) {
                $chambre = $reservation->historique->chambre;
                $reservation->chambre = $chambre;
            } else {
                // Si pas d'historique, essayons de voir si la réservation a une relation directe avec chambre
                $chambre = $reservation->chambres;
                $reservation->chambre = $chambre;
            }
        }

        return view('reception.dashboard', compact(
            'chambresDisponibles',
            'arriveesDuJour',
            'departsDuJour',
            'reservationsRecentes'
        ));
    }

    /**
     * Affiche les chambres disponibles
     *
     * @return \Illuminate\View\View
     */
    public function chambresDisponibles()
    {
        // Récupère toutes les chambres disponibles (status = 1)
        // $chambresDisponibles = Chambre::where('status', 1)->get();
        // Ici je veux afficher toutes les chambres.
        $chambresDisponibles = Chambre::get();

        // Retourne la vue avec les données
        return view('reception.chambres.disponibles', compact('chambresDisponibles'));
    }

    /**
     * Récupère les chambres disponibles via API
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChambresDisponibles(Request $request)
    {
        $dateDebut = $request->input('dateDebut');
        $dateFin = $request->input('dateFin');
        $categorie = $request->input('categorie');
        $capacite = $request->input('capacite');

        // Query de base
        $query = Chambre::where('status', 1);

        // Filtrer par catégorie si spécifiée
        if ($categorie) {
            $query->where('categorie_id', $categorie);
        }

        // Filtrer par capacité si spécifiée
        if ($capacite) {
            $query->where('capacite', $capacite);
        }

        // Filtrer par disponibilité dans la période demandée
        if ($dateDebut && $dateFin) {
            // Exclure les chambres déjà réservées pour cette période
            $chambresReservees = Historique::whereHas('reservation', function($q) use ($dateDebut, $dateFin) {
                $q->where(function($query) use ($dateDebut, $dateFin) {
                    // Réservations qui chevauchent la période demandée
                    $query->where(function($q) use ($dateDebut, $dateFin) {
                        $q->where('dateDeb', '<=', $dateDebut)
                          ->where('dateFin', '>=', $dateDebut);
                    })
                    ->orWhere(function($q) use ($dateDebut, $dateFin) {
                        $q->where('dateDeb', '<=', $dateFin)
                          ->where('dateFin', '>=', $dateFin);
                    })
                    ->orWhere(function($q) use ($dateDebut, $dateFin) {
                        $q->where('dateDeb', '>=', $dateDebut)
                          ->where('dateFin', '<=', $dateFin);
                    });
                });
            })
            ->pluck('chambre_id')
            ->toArray();

            $query->whereNotIn('id', $chambresReservees);
        }

        $chambres = $query->get();

        return response()->json($chambres);
    }

    /**
     * Récupère les détails d'une chambre
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChambreDetails($id)
    {
        $chambre = Chambre::findOrFail($id);
        return response()->json($chambre);
    }

    //  ************************  Code qui marche très bien  ***********************
    // public function filtrerChambres(Request $request)
    // {
    //     $query = Chambre::query(); // Aucune condition par défaut

    //     if ($request->filled('categorie')) {
    //         $query->where('categorie_id', $request->categorie);
    //     }

    //     if ($request->filled('capacite')) {
    //         $query->where('capacite', $request->capacite);
    //     }

    //     $chambresDisponibles = $query->get();

    //     return view('reception.chambres.disponibles', compact('chambresDisponibles'));
    // }
    public function filtrerChambres(Request $request)
    {
        $dateDebut = $request->input('dateDebut');
        $dateFin = $request->input('dateFin');
        $categorie = $request->input('categorie');
        $capacite = $request->input('capacite');

        $query = Chambre::query();

        if ($categorie) {
            $query->where('categorie_id', $categorie);
        }

        if ($capacite) {
            $query->where('capacite', $capacite);
        }

        if ($dateDebut && $dateFin) {
            $chambresReservees = DB::table('historiques')
                ->join('reservations', 'historiques.reservation_id', '=', 'reservations.id')
                ->where(function ($q) use ($dateDebut, $dateFin) {
                    $q->whereBetween('reservations.dateDeb', [$dateDebut, $dateFin])
                    ->orWhereBetween('reservations.dateFin', [$dateDebut, $dateFin])
                    ->orWhere(function ($query) use ($dateDebut, $dateFin) {
                        $query->where('reservations.dateDeb', '<=', $dateDebut)
                                ->where('reservations.dateFin', '>=', $dateFin);
                    });
                })
                ->pluck('historiques.chambre_id');

            $query->whereNotIn('id', $chambresReservees);
        }

        $chambresDisponibles = $query->get();

        return view('reception.chambres.disponibles', compact('chambresDisponibles'));
    }


    // public function createReservation(Request $request)
    // {
    //     $chambre = Chambre::findOrFail($request->chambre);
    //     $chambresDisponibles = Chambre::where('status', 1)->get(); // ou juste Chambre::all();
    //     return view('reception.reservations.create', compact('chambre', 'chambresDisponibles'));
    // }

    public function createReservation(Request $request)
    {
        $chambresDisponibles = Chambre::all();

        $reservationData = null;
        $clientData = null;

        if ($request->has('reservation')) {
            $reservation = Reservation::with('client', 'historique.chambre')->find($request->reservation);

            if ($reservation) {
                $reservationData = $reservation;
                $clientData = $reservation->client;
            }
        }

        return view('reception.reservations.create', compact('chambresDisponibles', 'reservationData', 'clientData'));
    }

    // Pour rechercher le client existants dans la page /reception/reservations/create.
    // public function searchClients(Request $request)
    // {
    //     $q = $request->query('q');

    //     $clients = Client::where('cin', 'like', "%$q%")
    //         ->orWhere('passeport', 'like', "%$q%")
    //         ->get();

    //     return response()->json($clients);
    // }
    public function searchClients(Request $request)
    {
        $query = $request->input('q');

        $clients = Client::where('CIN', 'LIKE', "%$query%")
            ->orWhere('passeport', 'LIKE', "%$query%")
            ->get()
            ->map(function ($client) {
                return [
                    'id' => $client->id,
                    'nom' => $client->nom,
                    'prenom' => $client->prenom,
                    'telephone' => $client->numTel,
                    'pays' => $client->pays,
                    'region' => $client->region,
                ];
            });

        return response()->json($clients);
    }



    // public function storeReservation(Request $request)
    // {
    //     // 1. Vérifier si le client est existant ou nouveau
    //     if ($request->client_type === 'existant') {
    //         $clientId = $request->input('client_id');
    //     } else {
    //         // 2. Créer un nouveau client
    //         $request->validate([
    //             'nom' => 'required|string|max:255',
    //             'prenom' => 'required|string|max:255',
    //             'email' => 'nullable|email',
    //             'telephone' => 'nullable|string|max:20',
    //             'dateNaissance' => 'nullable|date',
    //             'cin' => 'nullable|string|max:20',
    //             'passeport' => 'nullable|string|max:20',
    //         ]);

    //         $client = Client::create([
    //             'nom' => $request->nom,
    //             'prenom' => $request->prenom,
    //             'email' => $request->email,
    //             'telephone' => $request->telephone,
    //             'dateNaissance' => $request->dateNaissance,
    //             'cin' => $request->cin,
    //             'passeport' => $request->passeport,
    //         ]);

    //         $clientId = $client->id;
    //     }

    //     // 🟢 Maintenant tu peux utiliser $clientId pour créer la réservation
    // }


    // **************************  Méthode qui ne marche pas ***************************
    // public function storeReservation(Request $request)
    // {
    //     $request->validate([
    //         'dateDeb' => 'required|date',
    //         'dateFin' => 'required|date|after:dateDeb',
    //         'chambre_id' => 'required|exists:chambres,id',
    //         'methodePaiement' => 'required',
    //     ]);

    //     // 1. Gestion du client
    //     if ($request->client_type === 'existant') {
    //         $clientId = $request->client_id;
    //     } else {
    //         // Création du nouveau client
    //         // $client = Client::create([
    //         //     'nom' => $request->nom,
    //         //     'prenom' => $request->prenom,
    //         //     'pays' => 'Maroc', // ou un champ à rajouter si variable
    //         //     'region' => 'Casablanca-settat', // idem
    //         //     'numTel' => $request->telephone,
    //         //     'typeId' => $request->passeport ? 'passeport' : 'CIN',
    //         //     'CIN' => $request->cin,
    //         //     'passeport' => $request->passeport,
    //         //     'utilisateur_id' => 0, // pas de création de compte
    //         // ]);

    //         // $clientId = $client->id;

    //         // Vérifier si un client avec le même CIN ou passeport existe déjà
    //         $client = Client::where(function ($q) use ($request) {
    //             $q->where('CIN', $request->cin)
    //             ->orWhere('passeport', $request->passeport);
    //         })->first();

    //         if (!$client) {
    //             // S’il n’existe pas, on l’ajoute
    //             $client = Client::create([
    //                 'nom' => $request->nom,
    //                 'prenom' => $request->prenom,
    //                 'pays' => $request->pays,
    //                 'region' => $request->region,
    //                 'numTel' => $request->telephone,
    //                 'typeId' => $request->passeport ? 'passeport' : 'CIN',
    //                 'CIN' => $request->cin,
    //                 'passeport' => $request->passeport,
    //                 'utilisateur_id' => 0,
    //             ]);
    //         }

    //         $clientId = $client->id;

    //     }

    //     // 2. Calcul du prix total
    //     $chambre = Chambre::findOrFail($request->chambre_id);
    //     $dateDeb = Carbon::parse($request->dateDeb);
    //     $dateFin = Carbon::parse($request->dateFin);
    //     $duree = $dateFin->diffInDays($dateDeb);
    //     $prixTotal = $duree * $chambre->prixNuit;

    //     // 3. Création de la réservation
    //     $reservation = Reservation::create([
    //         'client_id' => $clientId,
    //         'dateDeb' => $request->dateDeb,
    //         'dateFin' => $request->dateFin,
    //         'totalPayer' => $prixTotal,
    //         'soldePayer' => $prixTotal,
    //         'methodePaiement' => $request->methodePaiement,
    //         'notes' => $request->notes,
    //     ]);

    //     // 4. Enregistrement dans l’historique
    //     Historique::create([
    //         'reservation_id' => $reservation->id,
    //         'chambre_id' => $chambre->id,
    //     ]);

    //     // return redirect()->route('reception.reservations')->with('success', 'Réservation enregistrée avec succès.');
    //     // return view('reception.reservations.confirmation', compact('reservation'));
    //     // Créer une facture pour cette réservation
    //     $factureController = new \App\Http\Controllers\FactureController();
    //     $factureData = $factureController->genererFactureApresReservation($reservation);
    //     $facture = $factureData['facture'];

    //     // Rediriger vers la page de confirmation
    //     // return redirect()->route('reception.confirmation', [
    //     //     'reservation_id' => $reservation->id,
    //     //     'facture_id' => $facture->id
    //     // ]);
    //     // Rediriger vers la page de confirmation
    //     return redirect()->route('reception.confirmation', [
    //         'reservation_id' => $reservation->id,
    //         'facture_id' => $facture->id
    //     ]);
    // }

    /**
 * Affiche la liste des réservations
 *
 * @return \Illuminate\View\View
 */
    public function indexReservations()
    {
        // Récupérer toutes les réservations avec les relations
        $reservations = Reservation::with(['client', 'chambres'])
            ->orderBy('dateDeb', 'desc')
            ->paginate(10);

        // Pour chaque réservation, récupérer la chambre associée via l'historique
        foreach ($reservations as $reservation) {
            if ($reservation->historique) {
                $chambre = $reservation->historique->chambre;
                $reservation->chambre = $chambre;
            }
        }

        return view('reception.reservations.index', compact('reservations'));
    }

    // public function storeReservation(Request $request)
    // {
    //     // Log pour vérifier si la méthode est appelée
    //     Log::info('=== DEBUT storeReservation ===');
    //     Log::info('Toutes les données du formulaire', ['data' => $request->all()]);
    //     Log::info('client_type', ['value' => $request->input('client_type')]);
    //     Log::info('dateDeb', ['value' => $request->input('dateDeb')]);
    //     Log::info('dateFin', ['value' => $request->input('dateFin')]);
    //     Log::info('chambre_id', ['value' => $request->input('chambre_id')]);

    //     $request->validate([
    //         'dateDeb' => 'required|date',
    //         'dateFin' => 'required|date|after:dateDeb',
    //         'chambre_id' => 'required|exists:chambres,id',
    //     ]);

    //     // Ajouter un log pour debug
    //     Log::info('Début storeReservation', $request->all());

    //     // 1. Gestion du client
    //     if ($request->client_type === 'existant') {
    //         $clientId = $request->client_id;
    //         Log::info('Client existant', ['id' => $clientId]);
    //     } else {
    //         // Vérifier si un client avec le même CIN ou passeport existe déjà
    //         $client = null;
    //         if ($request->cin) {
    //             $client = Client::where('CIN', $request->cin)->first();
    //         } elseif ($request->passeport) {
    //             $client = Client::where('passeport', $request->passeport)->first();
    //         }

    //         if (!$client) {
    //             // S'il n'existe pas, on l'ajoute
    //             $client = Client::create([
    //                 'nom' => $request->nom,
    //                 'prenom' => $request->prenom,
    //                 'pays' => $request->pays,
    //                 'region' => $request->region,
    //                 'numTel' => $request->telephone,
    //                 'typeId' => $request->passeport ? 'passeport' : 'CIN',
    //                 'CIN' => $request->cin,
    //                 'passeport' => $request->passeport,
    //                 'utilisateur_id' => 0,
    //             ]);
    //             Log::info('Nouveau client créé', ['id' => $client->id]);
    //         } else {
    //             Log::info('Client existant trouvé par CIN/passeport', ['id' => $client->id]);
    //         }

    //         $clientId = $client->id;
    //     }

    //     // 2. Calcul du prix total
    //     $chambre = Chambre::findOrFail($request->chambre_id);
    //     $dateDeb = Carbon::parse($request->dateDeb);
    //     $dateFin = Carbon::parse($request->dateFin);
    //     $duree = $dateFin->diffInDays($dateDeb);
    //     $prixTotal = $duree * $chambre->prixNuit;

    //     Log::info('Infos réservation', [
    //         'chambre_id' => $chambre->id,
    //         'dateDeb' => $dateDeb,
    //         'dateFin' => $dateFin,
    //         'duree' => $duree,
    //         'prixTotal' => $prixTotal
    //     ]);

    //     try {
    //         // 3. Création de la réservation
    //         $reservation = Reservation::create([
    //             'client_id' => $clientId,
    //             'dateDeb' => $request->dateDeb,
    //             'dateFin' => $request->dateFin,
    //             'totalPayer' => $prixTotal,
    //             'soldePayer' => $prixTotal,
    //         ]);

    //         // Log::info('Réservation créée', ['id' => $reservation->id]);
    //         Log::info('Réservation créée', ['id' => $reservation->id]);

    //         // 4. Enregistrement dans l'historique
    //         $historique = Historique::create([
    //             'reservation_id' => $reservation->id,
    //             'chambre_id' => $chambre->id,
    //         ]);

    //         // Log::info('Historique créé', ['id' => $historique->id]);
    //         Log::info('Historique créé', ['id' => $historique->id]);

    //         // Créer une facture pour cette réservation
    //         $factureController = new \App\Http\Controllers\FactureController();
    //         $factureData = $factureController->genererFactureApresReservation($reservation);

    //         if (isset($factureData['facture'])) {
    //             $facture = $factureData['facture'];
    //             // Log::info('Facture créée', ['id' => $facture->id]);
    //             Log::info('Facture créée', ['id' => $facture->id]);
    //         } else {
    //             Log::error('Erreur lors de la création de la facture');
    //             return redirect()->back()->with('error', 'Erreur lors de la création de la facture');
    //         }

    //         // Rediriger vers la page de confirmation
    //         return redirect()->route('reception.confirmation')->with([
    //             'reservation_id' => $reservation->id,
    //             'success' => 'Réservation créée avec succès'
    //         ]);
    //     }catch (\Exception $e) {
    //         Log::error('ERREUR CRITIQUE:', [
    //             'message' => $e->getMessage(),
    //             'file' => $e->getFile(),
    //             'line' => $e->getLine(),
    //             'trace' => $e->getTraceAsString()
    //         ]);
    //         throw $e; // Re-lancer l'erreur pour voir le message complet
    //     }




    // *******************************  Fonction qui marche très bien  *********************************
    // public function storeReservation(Request $request)
    // {
    //     // Log pour vérifier si la méthode est appelée
    //     Log::info('=== DEBUT storeReservation ===');
    //     Log::info('Toutes les données du formulaire', ['data' => $request->all()]);

    //     try {
    //         $request->validate([
    //             'dateDeb' => 'required|date',
    //             'dateFin' => 'required|date|after:dateDeb',
    //             'chambre_id' => 'required|exists:chambres,id',
    //         ]);

    //         // 1. Gestion du client
    //         if ($request->client_type === 'existant') {
    //             $clientId = $request->client_id;
    //             Log::info('Client existant', ['id' => $clientId]);
    //         } else {
    //             // Vérifier si un client avec le même CIN ou passeport existe déjà
    //             $client = null;
    //             if ($request->cin) {
    //                 $client = Client::where('CIN', $request->cin)->first();
    //             } elseif ($request->passeport) {
    //                 $client = Client::where('passeport', $request->passeport)->first();
    //             }

    //             if (!$client) {
    //                 // S'il n'existe pas, on l'ajoute
    //                 $client = Client::create([
    //                     'nom' => $request->nom,
    //                     'prenom' => $request->prenom,
    //                     'pays' => $request->pays,
    //                     'region' => $request->region,
    //                     'numTel' => $request->telephone,
    //                     'typeId' => $request->passeport ? 'passeport' : 'CIN',
    //                     'CIN' => $request->cin,
    //                     'passeport' => $request->passeport,
    //                     'utilisateur_id' => 0,
    //                 ]);
    //                 Log::info('Nouveau client créé', ['id' => $client->id]);
    //             } else {
    //                 Log::info('Client existant trouvé par CIN/passeport', ['id' => $client->id]);
    //             }

    //             $clientId = $client->id;
    //         }

    //         // 2. Calcul du prix total
    //         $chambre = Chambre::findOrFail($request->chambre_id);
    //         $dateDeb = Carbon::parse($request->dateDeb);
    //         $dateFin = Carbon::parse($request->dateFin);
    //         // $duree = $dateFin->diffInDays($dateDeb);
    //         $duree = $dateDeb->diffInDays($dateFin);
    //         // $prixTotal = $duree * $chambre->prixNuit;
    //         // Assurez-vous que la durée est positive
    //         if ($duree < 0) {
    //             throw new \Exception('La date de fin doit être après la date de début');
    //         }

    //         $prixTotal = $duree * $chambre->prixNuit;

    //         Log::info('Infos réservation', [
    //             'chambre_id' => $chambre->id,
    //             'dateDeb' => $dateDeb,
    //             'dateFin' => $dateFin,
    //             'duree' => $duree,
    //             'prixTotal' => $prixTotal
    //         ]);

    //         // 3. Création de la réservation
    //         $reservation = Reservation::create([
    //             'client_id' => $clientId,
    //             'dateDeb' => $request->dateDeb,
    //             'dateFin' => $request->dateFin,
    //             'totalPayer' => $prixTotal,
    //             'soldePayer' => $prixTotal,
    //             'receptionniste_id' => Auth::user()->id
    //         ]);

    //         Log::info('Réservation créée', ['id' => $reservation->id]);

    //         // 4. Enregistrement dans l'historique
    //         $historique = Historique::create([
    //             'reservation_id' => $reservation->id,
    //             'chambre_id' => $chambre->id,
    //         ]);

    //         Log::info('Historique créé', ['id' => $historique->id]);

    //         // Vérifier si les données ont été insérées
    //         $reservationVerifiee = Reservation::find($reservation->id);
    //         $historiqueVerifiee = Historique::find($historique->id);

    //         if ($reservationVerifiee && $historiqueVerifiee) {
    //             // Succès : retour avec un message de succès
    //             Log::info('SUCCÈS: Données insérées correctement', [
    //                 'reservation_id' => $reservationVerifiee->id,
    //                 'historique_id' => $historiqueVerifiee->id
    //             ]);

    //             // Retourne une réponse JSON avec succès
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Réservation créée avec succès !',
    //                 'reservation_id' => $reservationVerifiee->id,
    //                 'historique_id' => $historiqueVerifiee->id,
    //                 'redirect_url' => route('reception.dashboard')
    //             ]);

    //         } else {
    //             // Échec : retour avec un message d'erreur
    //             Log::error('ÉCHEC: Données non insérées correctement', [
    //                 'reservation_found' => $reservationVerifiee ? true : false,
    //                 'historique_found' => $historiqueVerifiee ? true : false
    //             ]);

    //             // return response()->json([
    //             //     'success' => false,
    //             //     'message' => 'Erreur: Les données n\'ont pas été insérées correctement dans la base de données',
    //             //     'error_details' => [
    //             //         'reservation_found' => $reservationVerifiee ? true : false,
    //             //         'historique_found' => $historiqueVerifiee ? true : false
    //             //     ]
    //             // ], 500);
    //             return redirect()->route('reception.confirmation', [
    //                 'reservation_id' => $reservationVerifiee->id,
    //                 'facture_id' => $facture->id
    //             ])->with('success', 'Réservation créée avec succès !');
    //         }

    //     } catch (\Exception $e) {
    //         Log::error('ERREUR CRITIQUE', [
    //             'message' => $e->getMessage(),
    //             'file' => $e->getFile(),
    //             'line' => $e->getLine(),
    //             'trace' => $e->getTraceAsString()
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Erreur serveur: ' . $e->getMessage(),
    //             'error_details' => [
    //                 'file' => $e->getFile(),
    //                 'line' => $e->getLine()
    //             ]
    //         ], 500);
    //     }
    // }

    public function storeReservation(Request $request)
    {
        // Log pour vérifier si la méthode est appelée
        Log::info('=== DEBUT storeReservation ===');
        Log::info('Toutes les données du formulaire', ['data' => $request->all()]);

        try {
            $request->validate([
                'dateDeb' => 'required|date',
                'dateFin' => 'required|date|after:dateDeb',
                'chambre_id' => 'required|exists:chambres,id',
            ]);

            // 1. Gestion du client
            if ($request->client_type === 'existant') {
                $clientId = $request->client_id;
                Log::info('Client existant', ['id' => $clientId]);
            } else {
                // Vérifier si un client avec le même CIN ou passeport existe déjà
                $client = null;
                if ($request->cin) {
                    $client = Client::where('CIN', $request->cin)->first();
                } elseif ($request->passeport) {
                    $client = Client::where('passeport', $request->passeport)->first();
                }

                if (!$client) {
                    // S'il n'existe pas, on l'ajoute
                    $client = Client::create([
                        'nom' => $request->nom,
                        'prenom' => $request->prenom,
                        'pays' => $request->pays,
                        'region' => $request->region,
                        'numTel' => $request->telephone,
                        'typeId' => $request->passeport ? 'passeport' : 'CIN',
                        'CIN' => $request->cin,
                        'passeport' => $request->passeport,
                        'utilisateur_id' => 0,
                    ]);
                    Log::info('Nouveau client créé', ['id' => $client->id]);
                } else {
                    Log::info('Client existant trouvé par CIN/passeport', ['id' => $client->id]);
                }

                $clientId = $client->id;
            }

            // 2. Calcul du prix total
            $chambre = Chambre::findOrFail($request->chambre_id);
            $dateDeb = Carbon::parse($request->dateDeb);
            $dateFin = Carbon::parse($request->dateFin);
            $duree = $dateDeb->diffInDays($dateFin);

            // Assurez-vous que la durée est positive
            if ($duree < 0) {
                throw new \Exception('La date de fin doit être après la date de début');
            }

            $prixTotal = $duree * $chambre->prixNuit;

            Log::info('Infos réservation', [
                'chambre_id' => $chambre->id,
                'dateDeb' => $dateDeb,
                'dateFin' => $dateFin,
                'duree' => $duree,
                'prixTotal' => $prixTotal
            ]);




            // Si l'ID de réservation est présent, on fait un update
            if ($request->filled('reservation_id')) {
                $reservation = Reservation::find($request->reservation_id);
                if ($reservation) {
                    $reservation->dateFin = $request->dateFin;
                    $reservation->totalPayer = $prixTotal;
                    // $reservation->soldePayer = $prixTotal;
                    // $reservation->soldePayer += floatval($request->input('soldePayer'));
                    $reservation->soldePayer = floatval($request->input('soldePayer'));

                    $reservation->save();

                    // Mettre à jour l'historique si nécessaire
                    $historique = $reservation->historique;
                    if ($historique) {
                        $historique->chambre_id = $chambre->id;
                        $historique->save();
                    }

                    Log::info('Réservation mise à jour', ['id' => $reservation->id]);

                    // Mettre à jour la facture
                    $factureController = new \App\Http\Controllers\FactureController();
                    $facture = $reservation->facture ?? null;


                    if ($facture) {
                        $facture->montant_soldePayer = $reservation->soldePayer;
                        $factureController->mettreAJourFacture($reservation, $facture);
                    } else {
                        $facture = $factureController->genererFactureApresReservation($reservation)['facture'];
                    }

                    return redirect()->route('reception.confirmation', [
                        'reservation_id' => $reservation->id,
                        'facture_id' => $facture->id
                    ])->with('success', 'Réservation mise à jour avec succès !');
                }
            }









            // 3. Création de la réservation
            $reservation = Reservation::create([
                'client_id' => $clientId,
                'dateDeb' => $request->dateDeb,
                'dateFin' => $request->dateFin,
                'totalPayer' => $prixTotal,
                // 'soldePayer' => $prixTotal,
                'soldePayer' => floatval($request->input('soldePayer')),
                'receptionniste_id' => Auth::user()->id
            ]);

            $paiement = Paiement::create([
                'montant'=>$reservation->soldePayer,
                'mode'=>"carte",
                'reservation_id'=>$reservation->id,
                'datePa'=>now(),
            ]);

            Log::info('Réservation créée', ['id' => $reservation->id]);

            // 4. Enregistrement dans l'historique
            $historique = Historique::create([
                'reservation_id' => $reservation->id,
                'chambre_id' => $chambre->id,
            ]);

            Log::info('Historique créé', ['id' => $historique->id]);

            // 5. Créer une facture pour cette réservation
            $factureController = new \App\Http\Controllers\FactureController();
            $factureData = $factureController->genererFactureApresReservation($reservation);
            $facture = $factureData['facture'];

            // 6. Redirection vers la page de confirmation
            return redirect()->route('reception.confirmation', [
                'reservation_id' => $reservation->id,
                'facture_id' => $facture->id
            ])->with('success', 'Réservation créée avec succès !');

        } catch (\Exception $e) {
            Log::error('ERREUR CRITIQUE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // En cas d'erreur, retour avec un message d'erreur
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

        // catch (\Exception $e) {
        //     \Log::error('Erreur lors de la création de la réservation', [
        //         'message' => $e->getMessage(),
        //         'trace' => $e->getTraceAsString()
        //     ]);

        //     // return redirect()->back()->with('error', 'Erreur lors de la création de la réservation');
        //     return redirect()->back()->with('error', 'Réservation non enregistrée'); // return redirect()->back()->with('error', 'Erreur lors de la création de la réservation: ' . $e->getMessage());
        // }







    // public function confirmation(Request $request)
    // {
    //     $reservation = Reservation::findOrFail($request->reservation_id);
    //     $facture_id = $request->facture_id;

    //     return view('reception.reservations.confirmation', compact('reservation', 'facture_id'));
    // }
    //


    // public function confirmation(Request $request)
    // {
    //     $reservation_id = $request->reservation_id;
    //     $facture_id = $request->facture_id;

    //     // Assurez-vous que ces deux variables sont bien définies
    //     if (!$reservation_id || !$facture_id) {
    //         return redirect()->route('reception.dashboard')
    //             ->with('error', 'Paramètres de confirmation manquants');
    //     }

    //     // Récupérer la réservation et la facture
    //     $reservation = Reservation::findOrFail($reservation_id);
    //     $facture = \App\Models\Facture::findOrFail($facture_id);

    //     // Passer les deux variables à la vue
    //     return view('reception.reservations.confirmation', compact('reservation', 'facture'));
    // }

    public function confirmation(Request $request)
    {
        $reservation_id = $request->reservation_id;
        $facture_id = $request->facture_id;

        // Vérification de l'existence des IDs
        if (!$reservation_id || !$facture_id) {
            return redirect()->route('reception.dashboard')
                ->with('error', 'Paramètres de confirmation manquants');
        }

        // Récupérer la réservation
        $reservation = Reservation::findOrFail($reservation_id);

        // Récupérer la facture (avec le namespace complet)
        $facture = Facture::findOrFail($facture_id);

        // Passer les variables à la vue
        return view('reception.reservations.confirmation', compact('reservation', 'facture'));
    }




}
