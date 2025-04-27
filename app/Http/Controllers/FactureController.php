<?php

namespace App\Http\Controllers;

//use Carbon\Carbon;
use Illuminate\Support\Carbon;
use App\Models\Facture;
use App\Mail\FactureEmail;
use App\Models\Reservation;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\FactureService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreFactureRequest;
use App\Http\Requests\UpdateFactureRequest;


class FactureController extends Controller
{    
    public function showConfirm()
    {
        try {
            // Récupérer les IDs depuis la session
            $reservationId = session('reservation_id');
            $factureId = session('facture_id');
            $adultsCount = session('adultsCount', 0);
            $childrenCount = session('childrenCount', 0);
            
            Log::info('Variables extraites de la session:', [
                'reservationId' => $reservationId,
                'factureId' => $factureId,
                'adultsCount' => $adultsCount,
                'childrenCount' => $childrenCount
            ]);
            
            if (!$reservationId) {
                return redirect()->route('home')
                    ->with('error', 'Données de réservation non trouvées');
            }
            
            // Récupérer les données de la réservation
            $reservation = Reservation::findOrFail($reservationId);
            
            // Récupérer la facture si disponible, sinon générer une nouvelle
            $facture = null;
            if ($factureId) {
                $facture = Facture::find($factureId);
            }
            
            // Si aucune facture n'existe, essayez d'en générer une nouvelle
            if ($facture === null) {
                try {
                    $factureController = new FactureController();
                    $factureData = $factureController->genererFactureApresReservation($reservation);
                    if ($factureData && isset($factureData['facture'])) {
                        $facture = $factureData['facture'];
                        // Mettre à jour la session avec le nouvel ID de facture
                        session(['facture_id' => $facture->id]);
                        Log::info('Nouvelle facture générée avec succès', ['facture_id' => $facture->id]);
                    }
                } catch (\Exception $e) {
                    Log::error('Erreur lors de la génération de la facture: ' . $e->getMessage());
                }
            }
            
            // Récupérer les données associées
            $chambres = DB::table('historiques')
                ->join('chambres', 'historiques.chambre_id', '=', 'chambres.id')
                ->where('historiques.reservation_id', $reservationId)
                ->select('chambres.*')
                ->get();
                
            $services = DB::table('posseders')
                ->join('supplementaires', 'posseders.supplementaire_id', '=', 'supplementaires.id')
                ->where('posseders.reservation_id', $reservationId)
                ->select('supplementaires.*')
                ->get();
            
            // Afficher la vue avec toutes les données nécessaires
            return view("client.confirmationReservation", compact(
                'reservation', 
                'facture', 
                'chambres', 
                'services',
                'adultsCount',
                'childrenCount'
            ));
            
        } catch (\Exception $e) {
            Log::error('Erreur dans showConfirm: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('home')
                ->with('error', 'Une erreur s\'est produite lors du chargement de la confirmation');
        }
    }


    public function genererFactureApresReservation(Reservation $reservation)
    {
        try {
            // Récupérer les chambres
            $chambres = DB::table('historiques')
                ->join('chambres', 'historiques.chambre_id', '=', 'chambres.id')
                ->where('historiques.reservation_id', $reservation->id)
                ->select('chambres.*')
                ->get();
            
            // S'assurer que nous avons des données
            if ($chambres->isEmpty()) {
                Log::warning('Aucune chambre trouvée pour la réservation ' . $reservation->id);
            }
            
            // Convertir la collection en tableau
            $chambresArray = $chambres->toArray();
            
            // Récupérer les services
            $services = DB::table('posseders')
                ->join('supplementaires', 'posseders.supplementaire_id', '=', 'supplementaires.id')
                ->where('posseders.reservation_id', $reservation->id)
                ->select('supplementaires.*')
                ->get();
            
            // Convertir la collection en tableau
            $servicesArray = $services->toArray();
            
            // Créer un numéro de facture unique
            $numeroFacture = 'FAC-' . date('Ymd') . '-' . $reservation->id;
            
            // Préparer les données adultes et enfants
            $adultsCount = $reservation->adultsCount ?? 0;
            $childrenCount = $reservation->childrenCount ?? 0;
            
            // Log pour le débogage
            Log::info('Création de facture pour réservation ' . $reservation->id, [
                'adultsCount' => $adultsCount,
                'childrenCount' => $childrenCount,
                'chambres' => count($chambresArray),
                'services' => count($servicesArray)
            ]);
            
            // Créer la facture dans la base de données
            $facture = Facture::create([
                'reservation_id' => $reservation->id,
                'numero_facture' => $numeroFacture,
                'montant_total' => $reservation->totalPayer,
                'date_emission' => now(),
                'details' => json_encode([
                    'dates' => [$reservation->dateDeb, $reservation->dateFin],
                    'adultsCount' => $adultsCount,
                    'childrenCount' => $childrenCount,
                    'chambres' => $chambresArray,
                    'services' => $servicesArray
                ]),
            ]);
            
            // Vérifions que la facture a bien été créée
            if (!$facture || !$facture->exists) {
                Log::error('Échec de création de la facture pour la réservation ' . $reservation->id);
                throw new \Exception('Échec de création de la facture');
            }
            
            Log::info('Facture créée avec succès', ['facture_id' => $facture->id]);
            
            return [
                'facture' => $facture,
                'pdf_path' => null, // À compléter selon votre logique
            ];
        } catch (\Exception $e) {
            Log::error('Erreur dans genererFactureApresReservation: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            throw $e;
        }
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
                'totalPayer'=>$facture->montant_total,
                'reservation' => $facture->reservation,
                'client' => $facture->reservation->client,
                'chambres' => $details['chambres'] ?? [],
                'services' => $details['services'] ?? [],
                'dates' => $details['dates'] ?? [],
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
                mkdir($directory, 0777, true); // Créer récursivement le chemin complet
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
    

        private function generateFactureHTML($data)
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
            $totalChambres += $chambre['prixNuit'] * $nombreJours;
        }
        
        $totalServices = 0;
        foreach ($services as $service) {
            $totalServices += $service['tarif'];
        }
        
        // Utiliser directement le montant total de la facture comme TTC
        $montantTTC = $facture->montant_total;
        
        // TVA et total
        $tauxTVA = 20; // À ajuster selon votre pays
        $montantHT = $montantTTC / (1 + ($tauxTVA / 100));
        $montantTVA = $montantTTC - $montantHT;
        
   

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
                .logo {
                    max-height: 80px;
                }
                .header h1 {
                    color: #95714F;
                    margin: 10px 0;
                    font-size: 28px;
                    font-weight: 700;
                    letter-spacing: 0.5px;
                }
                .invoice-meta {
                    background-color: #f7fafc;
                    border-radius: 6px;
                    padding: 10px 15px;
                    display: inline-block;
                    margin: 10px 0;
                }
                .info-blocks {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 30px;
                }
                .info-block {
                    width: 48%;
                    padding: 20px;
                    background-color: #f8f9fa;
                    border-radius: 8px;
                    border-left: 4px solid #95714F;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                }
                .info-block h4 {
                    margin-top: 0;
                    color: #95714F;
                    border-bottom: 1px solid #e2e8f0;
                    padding-bottom: 8px;
                    font-size: 16px;
                    font-weight: 600;
                }
                .info-block {
                        width: 100%; /* Modifié de 48% à 100% pour occuper toute la largeur */
                        padding: 20px;
                        background-color: #f8f9fa;
                        border-radius: 8px;
                        border-left: 4px solid #95714F;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                        margin-bottom: 15px; /* Ajout d'une marge en bas pour séparer les blocs */
                    }

                    /* Si vous voulez conserver l'arrangement côte à côte mais avec des largeurs différentes */
                .info-blocks {
                        display: flex;
                        flex-wrap: wrap; /* Permet de passer à la ligne sur les petits écrans */
                        justify-content: space-between;
                        margin-bottom: 30px;
                    }

                    /* Pour le bloc spécifique de l'hôtel, si vous voulez le cibler individuellement */
                .info-block:first-child {
                        width: 60%; /* Bloc hôtel plus large */
                    }

                .info-block:last-child {
                        width: 35%; /* Bloc client plus étroit */
                    }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 25px 0;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                }
                th, td {
                    padding: 12px 15px;
                    text-align: left;
                }
                th {
                    background-color: #95714F;
                    color: white;
                    font-weight: 500;
                    text-transform: uppercase;
                    font-size: 12px;
                    letter-spacing: 0.5px;
                }
                tr {
                    border-bottom: 1px solid #e2e8f0;
                }
                tr:last-child {
                    border-bottom: none;
                }
                tr:nth-child(even) {
                    background-color: #f8fafc;
                }
                .footer {
                    margin-top: 40px;
                    text-align: center;
                    font-size: 12px;
                    color: #718096;
                    border-top: 1px solid #e2e8f0;
                    padding-top: 15px;
                }
                .totals {
                    margin-left: auto;
                    width: 350px;
                    background-color: #f7fafc;
                    padding: 15px 20px;
                    border-radius: 8px;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                }
                .totals table {
                    margin: 0;
                    box-shadow: none;
                }
                .totals table tr {
                    border-bottom: 1px dashed #e2e8f0;
                }
                .totals table tr:last-child {
                    border-bottom: none;
                }
                .totals th {
                    text-align: right;
                    background-color: transparent;
                    color: #4a5568;
                    font-weight: normal;
                    text-transform: none;
                    padding: 8px 0;
                }
                .totals td {
                    text-align: right;
                    font-weight: 600;
                    padding: 8px 0 8px 20px;
                }
                .total-ttc {
                    font-size: 18px;
                    color: #95714F;
                }
                .total-ttc th, .total-ttc td {
                    padding-top: 12px;
                }
                .section-title {
                    color: #95714F;
                    border-bottom: 2px solid #e2e8f0;
                    padding-bottom: 8px;
                    margin-top: 30px;
                    font-size: 18px;
                    font-weight: 600;
                }
                .reservation-details {
                    background-color: #f8f9fa;
                    padding: 20px;
                    border-radius: 8px;
                    margin-bottom: 25px;
                    border-left: 4px solid #95714F;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                }
                .reservation-details p {
                    margin: 8px 0;
                    line-height: 1.6;
                }
                .reservation-details strong {
                    color: #4a5568;
                }
                .payment-info {
                    margin-top: 30px;
                    padding: 15px;
                    background-color: #ebf8ff;
                    border-radius: 8px;
                    border-left: 4px solid #3182ce;
                }
                .payment-info h4 {
                    color: #2b6cb0;
                    margin-top: 0;
                    margin-bottom: 10px;
                }
                .thankyou {
                    text-align: center;
                    margin-top: 40px;
                    color: #95714F;
                    font-size: 16px;
                    font-weight: 600;
                }
                .divider {
                    height: 1px;
                    background-color: #e2e8f0;
                    margin: 30px 0;
                }
                .section-wrapper {
                    page-break-inside: avoid;
                    break-inside: avoid;
                    -webkit-column-break-inside: avoid;
                    display: block;
                }

                h3.section-title {
                    margin-bottom: 10px; /* Réduire l'espace entre le titre et le tableau */
                    page-break-after: avoid;
                    break-after: avoid;
                    -webkit-column-break-after: avoid;
                }

                table {
                    page-break-inside: avoid;
                    break-inside: avoid;
                    -webkit-column-break-inside: avoid;
                }

                /* Si la table est encore trop grande et se divise entre les pages */
                .small-table {
                    font-size: 12px; /* Réduire légèrement la taille du texte */
                    line-height: 1.2; /* Réduire l'interligne */
                }

                .small-table th, 
                .small-table td {
                    padding: 8px 10px; /* Réduire le rembourrage */
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <div class="logo-container">
                        <!-- Si vous avez un logo, décommentez cette ligne -->
                        <!-- <img src="logo.png" alt="Logo" class="logo"> -->
                    </div>
                    <h1>FACTURE D'HÉBERGEMENT</h1>
                    <div class="invoice-meta">
                        <p>Numéro: <strong>{$facture->numero_facture}</strong> | Date: <strong>{$dateFacture}</strong></p>
                    </div>
                </div>
                
                <div class="info-blocks">
                    <div class="info-block">
                        <h4>HÔTEL</h4>
                        <p>
                            <strong>LA MI CASA</strong><br>
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
                
                <div class="reservation-details">
                    <h3 class="section-title">Détails de la réservation</h3>
                    <p><strong>Numéro de réservation:</strong> #{$reservation->id}</p>
                    <p><strong>Période de séjour:</strong> Du {$dateDebFormatted} au {$dateFinFormatted} ({$nombreJours} nuits)</p>
                </div>
        
                <div class="section-wrapper">    
                <h3 class="section-title">Chambres</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Numéro de la Chambre</th>
                            <th>Numéro de l'étage</th>
                            <th>Prix par nuit</th>
                            <th>Nuits</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
        HTML;
        
        // Ajouter les chambres
        foreach ($chambres as $chambre) {
            $prixTotal = $chambre['prixNuit'] * $nombreJours;
            $html .= <<<HTML
                        <tr>
                            <td>{$chambre['NumCh']}</td>
                            <td>{$chambre['NumEtg']}</td>
                            <td>{$chambre['prixNuit']} €</td>
                            <td>{$nombreJours}</td>
                            <td>{$prixTotal} €</td>
                        </tr>
        HTML;
        }
        
        $html .= <<<HTML
                    </tbody>
                </table>
                </div>
        HTML;
        
        // Ajouter les services si présents
        if (!empty($services)) {
            $html .= <<<HTML
                <h3 class="section-title">Services additionnels</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Prix</th>
                        </tr>
                    </thead>
                    <tbody>
        HTML;
        
            foreach ($services as $service) {
                $html .= <<<HTML
                        <tr>
                            <td>{$service['libelle']}</td>
                            <td>{$service['tarif']} €</td>
                        </tr>
        HTML;
            }
        
            $html .= <<<HTML
                    </tbody>
                </table>
        HTML;
        }
        
        $html .= <<<HTML
                <div class="divider"></div>
        
                <div class="totals">
                    <table>
                        <tr>
                            <th>Total HT:</th>
                            <td>{$montantHT} €</td>
                        </tr>
                        <tr>
                            <th>TVA ({$tauxTVA}%):</th>
                            <td>{$montantTVA} €</td>
                        </tr>
                        <tr class="total-ttc">
                            <th>Total TTC:</th>
                            <td>{$montantTTC} €</td>
                        </tr>
                    </table>
                </div>
                
                <div class="payment-info">
                    <h4>Informations de paiement</h4>
                    <p>Le montant total a été réglé le {$dateFacture}.</p>
                    <p>Nous vous remercions pour votre paiement.</p>
                </div>
                
                <div class="thankyou">
                    Merci d'avoir choisi La Mi Casa pour votre séjour!
                </div>
                
                <div class="footer">
                    <p>LA MI CASA - Avenue Abderrahim Bouabid, Agdal - 75000 Rabat, Maroc</p>
                    <p>SIRET: 123 456 789 00012 - TVA: MA 12 345 678 90</p>
                </div>
            </div>
        </body>
        </html>
        HTML;


        return $html;
    }


}
