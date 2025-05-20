<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chambre;
use App\Models\Reservation;
use App\Models\Client;
use Carbon\Carbon;

class ReceptionController extends Controller
{
    /**
     * Affiche le tableau de bord du réceptionniste
     */
    public function dashboard()
    {
        // Chambres disponibles aujourd'hui

        // $chambresDisponibles = Chambre::count();
        $chambresTotal = Chambre::count();
        dd($chambresTotal);
        // $chambresDisponibles = Chambre::where('status', 1)->count();


        // Arrivées du jour
        $today = Carbon::today();
        $arriveesDuJour = Reservation::whereDate('dateDeb', $today)
            ->where('statut', '!=', 'annulée')
            ->count();

        // Départs du jour
        $departsDuJour = Reservation::whereDate('dateFin', $today)
            ->where('statut', '!=', 'annulée')
            ->count();

        // Réservations récentes
        $reservationsRecentes = Reservation::with('client', 'chambre')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // return view('reception.dashboard', compact(
        //     'chambresDisponibles',
        //     'chambresTotal',
        //         'arriveesDuJour',
        //         'departsDuJour',
        //         'reservationsRecentes'
        // ));
        // return view('reception.dashboard', [
        //     'chambresTotal' => $chambresTotal,
        //     'arriveesDuJour' => $arriveesDuJour,
        //     'departsDuJour' => $departsDuJour,
        //     'reservationsRecentes' => $reservationsRecentes
        // ]);
        return view('reception.dashboard', compact(
            'chambresTotal',
            'arriveesDuJour',
            'departsDuJour',
            'reservationsRecentes'
        ));
    }

    /**
     * Affiche la page de disponibilité des chambres
     */
    public function chambresDisponibles(Request $request)
    {
        // $chambresDisponibles = Chambre::where('status', 1)->get();
        $chambresDisponibles = Chambre::get();

        return view('reception.chambres.disponibles', compact('chambresDisponibles'));
    }

    /**
     * Obtient la liste des chambres disponibles pour les dates données (API)
     */
    public function getChambresDisponibles(Request $request)
    {
        $dateDebut = $request->dateDebut;
        $dateFin = $request->dateFin;
        $categorie = $request->categorie;
        $capacite = $request->capacite;

        $query = Chambre::where('status', 1);

        if ($categorie) {
            $query->where('categorie_id', $categorie);
        }

        if ($capacite) {
            $query->where('capacite', '>=', $capacite);
        }

        // Exclure les chambres déjà réservées pour cette période
        if ($dateDebut && $dateFin) {
            $query->whereNotIn('id', function($q) use ($dateDebut, $dateFin) {
                $q->select('chambre_id')
                  ->from('reservations')
                  ->where('statut', '!=', 'annulée')
                  ->where(function($sq) use ($dateDebut, $dateFin) {
                      $sq->whereBetween('dateDeb', [$dateDebut, $dateFin])
                        ->orWhereBetween('dateFin', [$dateDebut, $dateFin])
                        ->orWhere(function($ssq) use ($dateDebut, $dateFin) {
                            $ssq->where('dateDeb', '<=', $dateDebut)
                                ->where('dateFin', '>=', $dateFin);
                        });
                  });
            });
        }

        $chambres = $query->get();

        if ($request->expectsJson()) {
            return response()->json($chambres);
        }

        return view('reception.chambres.disponibles', compact('chambres'));
    }

    /**
     * Obtient les détails d'une chambre (API)
     */
    public function getChambreDetails($id)
    {
        $chambre = Chambre::findOrFail($id);

        return response()->json($chambre);
    }

    /**
     * Affiche le formulaire de création de réservation
     */
    public function createReservation(Request $request)
    {
        // $chambresDisponibles = Chambre::where('status', 1)->get();
        $chambresDisponibles = Chambre::get();

        return view('reception.reservations.create', compact('chambresDisponibles'));
    }

    /**
     * Enregistre une nouvelle réservation
     */
    public function storeReservation(Request $request)
    {
        // Validation des données...
        $request->validate([
            'dateDeb' => 'required|date',
            'dateFin' => 'required|date|after:dateDeb',
            'chambre_id' => 'required|exists:chambres,id',
            // ... autres validations selon vos besoins
        ]);

        // Créer ou récupérer le client
        if ($request->client_type === 'existant' && $request->client_id) {
            $client = Client::findOrFail($request->client_id);
        } else {
            // Créer un nouveau client
            $client = Client::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'dateNaissance' => $request->dateNaissance,
                'cin' => $request->cin,
                'passeport' => $request->passeport,
            ]);
        }

        // Calculer le montant total
        $chambre = Chambre::findOrFail($request->chambre_id);
        $dateDeb = Carbon::parse($request->dateDeb);
        $dateFin = Carbon::parse($request->dateFin);
        $duree = $dateDeb->diffInDays($dateFin);
        $montantTotal = $duree * $chambre->prixNuit;

        // Créer la réservation
        $reservation = Reservation::create([
            'client_id' => $client->id,
            'chambre_id' => $request->chambre_id,
            'dateDeb' => $request->dateDeb,
            'dateFin' => $request->dateFin,
            'statut' => 'confirmée', // Les réservations au comptoir sont confirmées directement
            'soldePayer' => $montantTotal,
            'methodePaiement' => $request->methodePaiement,
            'notes' => $request->notes,
            'online' => false, // Cette réservation n'est pas faite en ligne
        ]);

        // Mettre à jour le statut de la chambre si nécessaire
        if ($dateDeb->isToday()) {
            $chambre->update(['status' => 2]); // Occupée
        }

        return redirect()->route('reception.reservations.show', $reservation->id)
            ->with('success', 'Réservation créée avec succès');
    }

    /**
     * Affiche la liste des réservations
     */
    public function indexReservations(Request $request)
    {
        $query = Reservation::with('client', 'chambre');

        // Filtres
        if ($request->dateDebut) {
            $query->whereDate('dateDeb', '>=', $request->dateDebut);
        }

        if ($request->dateFin) {
            $query->whereDate('dateFin', '<=', $request->dateFin);
        }

        if ($request->statut) {
            $query->where('statut', $request->statut);
        }

        if ($request->nom) {
            $query->whereHas('client', function($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->nom . '%')
                  ->orWhere('prenom', 'like', '%' . $request->nom . '%');
            });
        }

        // Tri
        $query->orderBy('created_at', 'desc');

        // Pagination
        $reservations = $query->paginate(15);

        return view('reception.reservations.index', compact('reservations'));
    }

    /**
     * Affiche les détails d'une réservation
     */
    public function showReservation($id)
    {
        $reservation = Reservation::with('client', 'chambre')->findOrFail($id);

        return view('reception.reservations.show', compact('reservation'));
    }

    /**
     * Met à jour le statut d'une réservation
     */
    public function updateStatus(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $reservation->update([
            'statut' => $request->statut
        ]);

        return redirect()->back()->with('success', 'Statut mis à jour avec succès');
    }

    /**
     * Annule une réservation
     */
    public function cancelReservation($id)
    {
        $reservation = Reservation::findOrFail($id);

        $reservation->update([
            'statut' => 'annulée'
        ]);

        // Libérer la chambre si elle était occupée
        if ($reservation->chambre && $reservation->chambre->status == 2) {
            $reservation->chambre->update(['status' => 1]); // Disponible
        }

        return redirect()->back()->with('success', 'Réservation annulée avec succès');
    }

    /**
     * Ajoute une note à une réservation
     */
    public function addNote(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        // Ajouter la nouvelle note aux notes existantes
        $notes = $reservation->notes ? $reservation->notes . "\n\n" . $request->note : $request->note;

        $reservation->update([
            'notes' => $notes
        ]);

        return redirect()->back()->with('success', 'Note ajoutée avec succès');
    }

    /**
     * Recherche des clients (API)
     */
    public function searchClients(Request $request)
    {
        $query = $request->q;

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $clients = Client::where('nom', 'like', '%' . $query . '%')
            ->orWhere('prenom', 'like', '%' . $query . '%')
            ->orWhere('email', 'like', '%' . $query . '%')
            ->orWhere('telephone', 'like', '%' . $query . '%')
            ->take(10)
            ->get();

        return response()->json($clients);
    }
}
