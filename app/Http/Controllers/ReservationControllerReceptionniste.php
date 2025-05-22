<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Chambre;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationControllerReceptionniste extends Controller
{
    // public function index()
    // {
    //     // Récupérer les réservations avec pagination
    //     $reservations = Reservation::with(['client', 'historique.chambre'])
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(5);

    //     return view('reception.reservations.index', compact('reservations'));
    // }



    public function index(Request $request)
    {
        $query = Reservation::with(['client', 'historique.chambre']);

        // Recherche globale client
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('client', function($q) use ($search) {
                $q->where('prenom', 'like', '%' . $search . '%')
                ->orWhere('nom', 'like', '%' . $search . '%')
                ->orWhere('CIN', 'like', '%' . $search . '%')
                ->orWhere('passeport', 'like', '%' . $search . '%');
            });
        }

        // Filtre par date d'arrivée
        if ($request->filled('date_debut')) {
            $query->where('dateDeb', '>=', $request->date_debut);
        }

        // Filtre par date de départ
        if ($request->filled('date_fin')) {
            $query->where('dateFin', '<=', $request->date_fin);
        }

        // Tri par date de création
        $query->orderBy('created_at', 'desc');

        // Pagination
        $reservations = $query->paginate(5);

        $page = $request->get('page', 1);
        $total = $reservations->total();
        $totalPages = $reservations->lastPage();

        return view('reception.reservations.index', compact('reservations', 'page', 'total', 'totalPages'));
    }






    // public function edit($id)
    // {
    //     $reservation = Reservation::with(['client', 'historique.chambre'])->findOrFail($id);
    //     return view('reception.reservations.create', compact('reservation'));
    // }


    // public function show($id)
    // {
    //     $reservation = Reservation::with(['client', 'historique.chambre'])->findOrFail($id);
    //     return view('reception.reservations.show', compact('reservation'));
    // }

    // public function edit($id)
    // {
    //     $reservation = Reservation::with(['client', 'historique.chambre'])->findOrFail($id);
    //     return view('reception.reservations.edit', compact('reservation'));
    // }

    // public function destroy($id)
    // {
    //     $reservation = Reservation::findOrFail($id);
    //     $reservation->delete();

    //     return redirect()->route('reception.reservations')
    //         ->with('success', 'Réservation supprimée avec succès');
    // }

    // public function update(Request $request)
    // {
    //     $request->validate([
    //         'id' => 'required|exists:reservations,id',
    //         'dateDeb' => 'required|date',
    //         'dateFin' => 'required|date|after:dateDeb',
    //     ]);

    //     $reservation = Reservation::findOrFail($request->id);

    //     $reservation->update([
    //         'dateDeb' => $request->dateDeb,
    //         'dateFin' => $request->dateFin,
    //     ]);

    //     return redirect()->route('reception.reservations')
    //         ->with('success', 'Réservation modifiée avec succès');
    // }

    // public function update(Request $request)
    // {
    //     $request->validate([
    //         'id' => 'required|exists:reservations,id',
    //         'dateFin' => 'required|date',
    //     ]);

    //     $reservation = Reservation::findOrFail($request->id);

    //     try {
    //         DB::beginTransaction();

    //         // Vérifier que la nouvelle date de fin est après la date de début
    //         if (strtotime($request->dateFin) <= strtotime($reservation->dateDeb)) {
    //             throw new \Exception('La date de fin doit être après la date de début');
    //         }

    //         // Calculer le nouveau prix total
    //         $dateDeb = $reservation->dateDeb;
    //         $nouvelleDateFin = $request->dateFin;
    //         $nombreNuits = (strtotime($nouvelleDateFin) - strtotime($dateDeb)) / (60 * 60 * 24);

    //         // Récupérer le prix par nuit depuis la relation avec historique
    //         $historique = $reservation->historique;
    //         if (!$historique || !$historique->chambre) {
    //             throw new \Exception('Impossible de trouver les détails de la chambre');
    //         }

    //         $prixNuit = $historique->chambre->prixNuit;
    //         $nouveauPrixTotal = $nombreNuits * $prixNuit;

    //         // Mettre à jour UNIQUEMENT la table reservations
    //         $reservation->update([
    //             'dateFin' => $nouvelleDateFin,
    //             'totalPayer' => $nouveauPrixTotal
    //         ]);

    //         DB::commit();

    //         return redirect()->route('reception.reservations')
    //             ->with('success', 'Date de fin modifiée avec succès. Nouveau total : ' . $nouveauPrixTotal . '€');

    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return redirect()->route('reception.reservations')
    //             ->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
    //     }
    // }

    // public function update(Request $request)
    // {
    //     $request->validate([
    //         'id' => 'required|exists:reservations,id',
    //         'dateFin' => 'required|date',
    //     ]);

    //     $reservation = Reservation::findOrFail($request->id);

    //     try {
    //         DB::beginTransaction();

    //         // Vérifier que la nouvelle date de fin est après la date de début
    //         if (strtotime($request->dateFin) <= strtotime($reservation->dateDeb)) {
    //             throw new \Exception('La date de fin doit être après la date de début');
    //         }

    //         // Calculer les anciennes et nouvelles nuits
    //         $dateDeb = $reservation->dateDeb;
    //         $ancienneDateFin = $reservation->dateFin;
    //         $nouvelleDateFin = $request->dateFin;

    //         $ancienNombreNuits = (strtotime($ancienneDateFin) - strtotime($dateDeb)) / (60 * 60 * 24);
    //         $nouveauNombreNuits = (strtotime($nouvelleDateFin) - strtotime($dateDeb)) / (60 * 60 * 24);

    //         // Calculer la différence de nuits
    //         $differenceNuits = $nouveauNombreNuits - $ancienNombreNuits;

    //         // Récupérer le prix par nuit
    //         $historique = $reservation->historique;
    //         if (!$historique || !$historique->chambre) {
    //             throw new \Exception('Impossible de trouver les détails de la chambre');
    //         }

    //         $prixNuit = $historique->chambre->prixNuit;

    //         // Calculer le montant supplémentaire/remboursement
    //         $montantSupplementaire = $differenceNuits * $prixNuit;

    //         // Nouveau total = ancien total + montant supplémentaire
    //         $nouveauTotal = $reservation->totalPayer + $montantSupplementaire;

    //         // Mettre à jour la table reservations
    //         $reservation->update([
    //             'dateFin' => $nouvelleDateFin,
    //             'totalPayer' => $nouveauTotal
    //         ]);

    //         DB::commit();

    //         $message = 'Date de fin modifiée avec succès.';
    //         if ($differenceNuits > 0) {
    //             $message .= ' Ajout de ' . $differenceNuits . ' nuit(s) : +' . $montantSupplementaire . '€';
    //         } elseif ($differenceNuits < 0) {
    //             $message .= ' Réduction de ' . abs($differenceNuits) . ' nuit(s) : -' . abs($montantSupplementaire) . '€';
    //         }
    //         $message .= ' Nouveau total : ' . $nouveauTotal . '€';

    //         return redirect()->route('reception.reservations')
    //             ->with('success', $message);

    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return redirect()->route('reception.reservations')
    //             ->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
    //     }
    // }


    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:reservations,id',
            'dateFin' => 'required|date',
        ]);

        $reservation = Reservation::findOrFail($request->id);


        try {
            DB::beginTransaction();

            // Vérifier que la nouvelle date de fin est après la date de début
            if (strtotime($request->dateFin) <= strtotime($reservation->dateDeb)) {
                throw new \Exception('La date de fin doit être après la date de début');
            }

            // Calculer les anciennes et nouvelles nuits
            $dateDeb = $reservation->dateDeb;
            $ancienneDateFin = $reservation->dateFin;
            $nouvelleDateFin = $request->dateFin;

            $ancienNombreNuits = (strtotime($ancienneDateFin) - strtotime($dateDeb)) / (60 * 60 * 24);
            $nouveauNombreNuits = (strtotime($nouvelleDateFin) - strtotime($dateDeb)) / (60 * 60 * 24);

            // Calculer la différence de nuits
            $differenceNuits = $nouveauNombreNuits - $ancienNombreNuits;

            // Récupérer le prix par nuit
            $historique = $reservation->historique;
            if (!$historique || !$historique->chambre) {
                throw new \Exception('Impossible de trouver les détails de la chambre');
            }

            $prixNuit = $historique->chambre->prixNuit;

            // Calculer le montant supplémentaire/remboursement
            $montantSupplementaire = $differenceNuits * $prixNuit;

            // Nouveau total = ancien total + montant supplémentaire
            $nouveauTotal = $reservation->totalPayer + $montantSupplementaire;

            // Calculer nouveau soldePayer
            // $nouveauSoldePayer = $reservation->soldePayer + $montantSupplementaire;
            // $soldeRecu = $request->input('soldePayer');
            // $soldeRecu = floatval($request->input('soldePayer'));
            // $nouveauSoldePayer = $soldeRecu;
            $montantAAjouter = $differenceNuits * $prixNuit;
            $nouveauSoldePayer = $reservation->soldePayer + $montantAAjouter;



            // Mettre à jour la table reservations
            $reservation->update([
                'dateFin' => $nouvelleDateFin,
                'totalPayer' => $nouveauTotal,
                'soldePayer' => $nouveauSoldePayer
            ]);
            // à ajouter
            $paiement = Paiement::create([
                'montant'=>$nouveauSoldePayer,
                'mode'=>'carte',
                'reservation_id'=>$reservation->id,
                'datePa'=>now(),
            ]);
            DB::commit();

            $message = 'Date de fin modifiée avec succès.';
            if ($differenceNuits > 0) {
                $message .= ' Ajout de ' . $differenceNuits . ' nuit(s) : +' . $montantSupplementaire . '€';
            } elseif ($differenceNuits < 0) {
                $message .= ' Réduction de ' . abs($differenceNuits) . ' nuit(s) : -' . abs($montantSupplementaire) . '€';
            }
            $message .= ' Nouveau total : ' . $nouveauTotal . '€ (soldePayer: ' . $nouveauSoldePayer . '€)';

            return redirect()->route('reception.reservations')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('reception.reservations')
                ->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }


    // public function destroy(Request $request)
    // {
    //     $request->validate([
    //         'id' => 'required|exists:reservations,id',
    //     ]);

    //     $reservation = Reservation::findOrFail($request->id);
    //     $reservation->delete();

    //     return redirect()->route('reception.reservations')
    //         ->with('success', 'Réservation supprimée avec succès');
    // }
    // public function destroy(Request $request)
    // {
    //     $request->validate([
    //         'id' => 'required|exists:reservations,id',
    //     ]);

    //     $reservation = Reservation::findOrFail($request->id);

    //     try {
    //         // Commencer une transaction pour garantir l'intégrité des données
    //         DB::beginTransaction();

    //         // 1. Supprimer les factures associées
    //         $reservation->factures()->delete();

    //         // 2. Supprimer l'historique associé
    //         $reservation->historique()->delete();

    //         // 3. Supprimer la réservation
    //         $reservation->delete();

    //         // Valider la transaction
    //         DB::commit();

    //         return redirect()->route('reception.reservations')
    //             ->with('success', 'Réservation supprimée avec succès');

    //     } catch (\Exception $e) {
    //         // En cas d'erreur, annuler la transaction
    //         DB::rollBack();

    //         return redirect()->route('reception.reservations')
    //             ->with('error', 'Erreur lors de la suppression de la réservation');
    //     }
    // }


    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:reservations,id',
        ]);



        $reservation = Reservation::findOrFail($request->id);

        try {
            // Commencer une transaction pour garantir l'intégrité des données
            DB::beginTransaction();

            // 1. Supprimer toutes les factures associées
            DB::table('factures')->where('reservation_id', $reservation->id)->delete();

            // 2. Supprimer les entrées historiques
            DB::table('historiques')->where('reservation_id', $reservation->id)->delete();

            // 3. Supprimer la réservation
            $reservation->delete();

            // Valider la transaction
            DB::commit();

            return redirect()->route('reception.reservations')
                ->with('success', 'Réservation supprimée avec succès');

        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            DB::rollBack();

            // return redirect()->route('reception.reservations')
            //     ->with('error', 'Erreur lors de la suppression de la réservation : ' . $e->getMessage());
            return redirect()->back()->with('success', 'Réservation supprimée avec succès');

        }
    }

}
