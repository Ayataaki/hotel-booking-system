<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Facture;
use App\Models\Reservation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FactureControllerReceptionniste extends Controller
{

    public function showConfirmReception(Request $request)
    {
        try {
            $reservationId = $request->reservation_id;
            $factureId = $request->facture_id;

            \Log::info('showConfirmReception appelé', [
                'reservation_id' => $reservationId,
                'facture_id' => $factureId
            ]);

            if (!$reservationId || !$factureId) {
                return redirect()->route('reception.dashboard')
                    ->with('error', 'Paramètres de confirmation manquants');
            }

            $reservation = Reservation::with('client')->findOrFail($reservationId);
            $facture = Facture::findOrFail($factureId);

            \Log::info('Données récupérées avec succès', [
                'reservation' => $reservation->id,
                'client' => $reservation->client->id,
                'facture' => $facture->id
            ]);

            return view('reception.reservations.confirmation', compact('reservation', 'facture'));
        } catch (\Exception $e) {
            \Log::error('Erreur dans showConfirmReception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('reception.dashboard')
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }
    // public function showConfirmReception(Request $request)
    // {
    //     $reservationId = $request->reservation_id;
    //     $factureId = $request->facture_id;

    //     if (!$reservationId || !$factureId) {
    //         return redirect()->route('reception.dashboard')
    //             ->with('error', 'Paramètres de confirmation manquants');
    //     }

    //     // Récupérer la réservation et la facture
    //     $reservation = Reservation::findOrFail($reservationId);
    //     $facture = Facture::findOrFail($factureId);

    //     // Récupérer les infos de la chambre
    //     $chambres = DB::table('historiques')
    //         ->join('chambres', 'historiques.chambre_id', '=', 'chambres.id')
    //         ->where('historiques.reservation_id', $reservationId)
    //         ->select('chambres.*')
    //         ->get();

    //     // Récupérer les services supplémentaires si disponibles
    //     $services = DB::table('posseders')
    //         ->join('supplementaires', 'posseders.supplementaire_id', '=', 'supplementaires.id')
    //         ->where('posseders.reservation_id', $reservationId)
    //         ->select('supplementaires.*')
    //         ->get();

    //     return view('reception.reservations.confirmation', compact(
    //         'reservation',
    //         'facture',
    //         'chambres',
    //         'services'
    //     ));
    // }



    // public function genererFactureMontant($reservation, $montant)
    // {
    //     $facture = Facture::create([
    //         'reservation_id' => $reservation->id,
    //         'montant' => $montant,
    //         'dateFacture' => now()
    //     ]);

    //     return ['facture' => $facture];
    // }



    public function generateFactureHTML($data)
    {
        // Extraire les variables
        $facture = $data['facture'];
        $reservation = $data['reservation'];
        $client = $data['client'];
        $chambres = $data['chambres'];
        $services = $data['services'];

        // Calculer les dates et la durée du séjour
        $dateDebut = Carbon::parse($reservation->dateDeb);
        $dateFin = Carbon::parse($reservation->dateFin);
        $nombreJours = $dateDebut->diffInDays($dateFin);

        // Formater les dates
        $dateFacture = date('d/m/Y', strtotime($facture->date_emission));
        $dateDebFormatted = date('d/m/Y', strtotime($reservation->dateDeb));
        $dateFinFormatted = date('d/m/Y', strtotime($reservation->dateFin));

        // Calculer les sous-totaux
        $totalChambres = 0;
        foreach ($chambres as $chambre) {
            $totalChambres += $chambre->prixNuit * $nombreJours;
        }

        $totalServices = 0;
        foreach ($services as $service) {
            $totalServices += $service->tarif;
        }

        // Utiliser directement le montant total de la facture comme TTC
        // $montantTTC = $facture->montant_total;
        // $montantTTC = $data['totalPayer'];
        $montantTTC = $reservation->soldePayer;

        // $montantTTC = $facture->montant_soldePayer;
        // $montantTTC = $reservation->soldePayer;
        // $montantTTC = $data['totalPayer'];=

        // TVA et total
        $tauxTVA = 20; // À ajuster selon votre pays
        $montantHT = $montantTTC / (1 + ($tauxTVA / 100));
        $montantTVA = $montantTTC - $montantHT;

        // Votre code HTML de la facture
        $html = <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Facture {$facture->numero_facture}</title>
            <style>
                body {
                    font-family: 'Helvetica', 'Arial', sans-serif;
                    margin: 0;
                    padding: 20px;
                    color: #2d3748;
                    font-size: 14px;
                    line-height: 1.5;
                    background-color: #ffffff;
                }
                /* Autres styles CSS... */
                .container {
                    max-width: 800px;
                    margin: 0 auto;
                    border: 1px solid #e2e8f0;
                    border-radius: 8px;
                    padding: 25px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                }
                .header {
                    text-align: center;
                    margin-bottom: 30px;
                    border-bottom: 2px solid #95714F;
                    padding-bottom: 15px;
                    position: relative;
                }
                .logo-container {
                    position: absolute;
                    top: 0;
                    left: 0;
                }
                .header h1 {
                    color: #95714F;
                    margin: 10px 0;
                    font-size: 28px;
                    font-weight: 700;
                    letter-spacing: 0.5px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 25px 0;
                }
                th, td {
                    padding: 12px 15px;
                    text-align: left;
                    border-bottom: 1px solid #e2e8f0;
                }
                th {
                    background-color: #95714F;
                    color: white;
                    font-weight: 500;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>FACTURE D'HÉBERGEMENT</h1>
                    <div>
                        <p>Numéro: <strong>{$facture->numero_facture}</strong> | Date: <strong>{$dateFacture}</strong></p>
                    </div>
                </div>

                <div class="info-blocks">
                    <div class="info-block">
                        <h4>HÔTEL</h4>
                        <p>
                            <strong>Mi Casa</strong><br>
                            Avenue Abderrahim Bouabid, Agdal<br>
                            75000 Rabat, Maroc<br>
                            Email: contact@lamicasa.com<br>
                            Tél: +212 5 00 40 67 89<br>
                        </p>
                    </div>

                    <div class="info-block">
                        <h4>CLIENT</h4>
                        <p>
                            <strong>{$client->nom} {$client->prenom}</strong><br>
                            Pays: {$client->pays}<br>
                            {$client->region}
                            Tél: {$client->numTel}
                        </p>
                    </div>
                </div>

                <div>
                    <h3>Détails de la réservation</h3>
                    <p><strong>Numéro de réservation:</strong> #{$reservation->id}</p>
                    <p><strong>Période de séjour:</strong> Du {$dateDebFormatted} au {$dateFinFormatted} ({$nombreJours} nuits)</p>
                </div>

                <h3>Chambres</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Numéro de chambre</th>
                            <th>Numéro d'étage</th>
                            <th>Prix par nuit</th>
                            <th>Nuits</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
        HTML;

        // $prixTotal = $reservation->soldePayer;
        // Ajouter les chambres
        foreach ($chambres as $chambre) {
            $prixTotal = $chambre->prixNuit * $nombreJours;
            $html .= "
                <tr>
                    <td>{$chambre->NumCh}</td>
                    <td>{$chambre->NumEtg}</td>
                    <td>{$chambre->prixNuit} €</td>
                    <td>{$nombreJours}</td>
                    <td>{$prixTotal} €</td>
                </tr>";
        }

        $html .= "
                    </tbody>
                </table>";

        // Ajouter les services si présents
        if (count($services) > 0) {
            $html .= "
                <h3>Services additionnels</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Prix</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($services as $service) {
                $html .= "
                        <tr>
                            <td>{$service->libelle}</td>
                            <td>{$service->tarif} €</td>
                        </tr>";
            }

            $html .= "
                    </tbody>
                </table>";
        }

        $html .= "
                <div>
                    <table>
                        <tr>
                            <th>Total HT:</th>
                            <td>{$montantHT} €</td>
                        </tr>
                        <tr>
                            <th>TVA ({$tauxTVA}%):</th>
                            <td>{$montantTVA} €</td>
                        </tr>
                        <tr>
                            <th>Total TTC:</th>
                            <td>{{ $reservation->soldePayer }} €</td>
                        </tr>
                    </table>
                </div>

                <div>
                    <h4>Informations de paiement</h4>
                    <p>Le montant total a été réglé le {$dateFacture}.</p>
                    <p>Nous vous remercions pour votre paiement.</p>
                </div>

                <div>
                    Merci d'avoir choisi Mi Casa pour votre séjour!
                </div>

                <div>
                    <p>Mi Casa - Avenue Abderrahim Bouabid, Agdal - 75000 Rabat, Maroc</p>
                    <p>SIRET: 123 456 789 00012 - TVA: MA 12 345 678 90</p>
                </div>
            </div>
        </body>
        </html>";

        return $html;
    }

    public function download($id)
    {
        try {
            // 1. Récupérer la facture avec toutes les relations nécessaires
            $facture = Facture::with('reservation.client')->findOrFail($id);

            // 2. Décoder les détails JSON
            $details = json_decode($facture->details, true);

            // 3. Prépare les données pour la facture
            $data = [
                'facture' => $facture,


                'reservation' => $facture->reservation,
                'totalPayer' => $facture->reservation->soldePayer,
                'client' => $facture->reservation->client,
                'chambres' => DB::table('historiques')
                    ->join('chambres', 'historiques.chambre_id', '=', 'chambres.id')
                    ->where('historiques.reservation_id', $facture->reservation_id)
                    ->select('chambres.*')
                    ->get(),
                'services' => DB::table('posseders')
                    ->join('supplementaires', 'posseders.supplementaire_id', '=', 'supplementaires.id')
                    ->where('posseders.reservation_id', $facture->reservation_id)
                    ->select('supplementaires.*')
                    ->get(),
                'adultsCount' => $details['adultsCount'] ?? 0,
                'childrenCount' => $details['childrenCount'] ?? 0,
            ];

            // 4. Générer le HTML de la facture
            $html = $this->generateFactureHTML($data);

            // 5. Créer le PDF
            $pdf = Pdf::loadHTML($html);
            $pdf->setPaper('a4');

            // 6. Définir le chemin de sauvegarde et créer le dossier s'il n'existe pas
            $directory = storage_path("app/public/factures");

            // Créer le dossier factures s'il n'existe pas
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            $path = $directory . "/facture-{$facture->numero_facture}.pdf";

            // 7. Sauvegarder le PDF
            $pdf->save($path);

            // 8. Télécharger le PDF
            if (file_exists($path)) {
                return response()->download(
                    $path,
                    "Facture_{$facture->numero_facture}.pdf",
                    [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => "attachment; filename=\"Facture_{$facture->numero_facture}.pdf\"",
                    ]
                );
            } else {
                return response()->json([
                    'error' => "Échec de création du PDF",
                    'path' => $path
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
}
