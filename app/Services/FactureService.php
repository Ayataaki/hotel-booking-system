<?php
// app/Services/FactureService.php
namespace App\Services;

use App\Models\Facture;
use App\Models\Reservation;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class FactureService
{
    public function genererFacture(Reservation $reservation)
    {
        // Générer un numéro de facture unique
        $numeroFacture = 'FAC-' . date('Ymd') . '-' . $reservation->id;
        
        // Créer l'enregistrement de facture dans la base de données
        $facture = Facture::create([
            'reservation_id' => $reservation->id,
            'numero_facture' => $numeroFacture,
            'montant_total' => $reservation->totalPayer,
            'date_emission' => Carbon::now(),
            'statut' => 'payée',
            'details' => json_encode([
                'chambres' => $reservation->chambres,
                'services' => $reservation->services,
                'dates' => [$reservation->dateDeb, $reservation->dateFin],
                'adultsCount' => $reservation->adultsCount,
                'childrenCount' => $reservation->childrenCount
            ])
        ]);
        
        // Générer le PDF
        $pdf = PDF::loadView('factures.template', [
            'facture' => $facture,
            'reservation' => $reservation,
            'client' => $reservation->client
        ]);
        
        // Sauvegarder dans storage
        $pdfPath = 'factures/facture-' . $numeroFacture . '.pdf';
        $pdf->save(storage_path('app/public/' . $pdfPath));
        
        return [
            'facture' => $facture,
            'pdf_path' => $pdfPath
        ];
    }
}