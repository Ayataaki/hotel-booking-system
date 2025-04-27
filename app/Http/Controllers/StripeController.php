<?php

namespace App\Http\Controllers;

use Exception;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Customer;
use Illuminate\Http\Request;
use App\Services\FactureService;
use Stripe\Exception\CardException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class StripeController extends Controller
{
    /**
     * Affiche le formulaire de paiement
     */
    public function showPaymentForm()
    {
        return view('client.payment.form');
    }

    /**
     * Traite le paiement
     */
    public function processPayment(Request $request)
    {
        // Validation des données
        $request->validate([
            'stripeToken' => 'required',
            'amount' => 'required|numeric|min:1',
        ]);

        // Configuration de la clé secrète Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Création d'un paiement avec le token sécurisé
            $charge = Charge::create([
                'amount' => $request->amount * 100, // Stripe utilise les centimes
                'currency' => 'eur',
                'description' => 'Paiement pour LA MI CASA',
                'source' => $request->stripeToken,
                'metadata' => [
                    'user_id' => Auth::id() ?? 'guest',
                    'order_id' => 'ORDER-' . uniqid()
                ]
            ]);

            // Enregistrement du paiement dans votre base de données
            // Vous pourriez créer un modèle Payment et l'enregistrer ici
            // Par exemple:
            // $payment = Payment::create([
            //     'user_id' => auth()->id(),
            //     'amount' => $request->amount,
            //     'payment_id' => $charge->id,
            //     'status' => 'completed'
            // ]);


            // Générer et envoyer la facture
        $factureService = new FactureService();
        //$factureData = $factureService->genererFacture($reservation);
        
        // Envoyer la facture par email
        //Mail::to(Auth::user()->email)->send(new FactureEmail($factureData['facture'], $factureData['pdf_path']));
        
        // Rediriger vers une page de confirmation avec un lien pour télécharger la facture
        /* return redirect()->route('reservation.confirmation', [
            'id' => $reservation->id,
            'facture_id' => $factureData['facture']->id
        ])->with('success', 'Votre réservation a été confirmée et la facture vous a été envoyée par email.');
         */

            return redirect()->route('payment.success')->with('success', 'Paiement effectué avec succès');
        } catch (CardException $e) {
            // Erreur liée à la carte
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (Exception $e) {
            // Autres erreurs
            return back()->withErrors(['error' => 'Une erreur est survenue lors du traitement du paiement.']);
        }
    }

/*     public function traitementPaiement(Request $request)
{
    // Code existant pour le traitement du paiement Stripe
    
    // Si le paiement est réussi, on crée d'abord la réservation
    $reservation = Reservation::create([
        'user_id' => auth()->id(),
        'dateDeb' => $request->dateDeb,
        'dateFin' => $request->dateFin,
        'adultsCount' => $request->adultsCount,
        'childrenCount' => $request->childrenCount,
        'totalPayer' => $request->totalPayer,
        'statut' => 'confirmée'
        // autres champs nécessaires
    ]);
    
    // Associer les chambres à la réservation
    if ($request->has('chambresIds')) {
        foreach ($request->chambresIds as $chambreId) {
            $reservation->chambres()->attach($chambreId);
        }
    }
    
    // Associer les services à la réservation
    if ($request->has('services')) {
        foreach ($request->services as $serviceId) {
            $reservation->services()->attach($serviceId);
        }
    }
    
    // Générer et envoyer la facture
    $factureService = new FactureService();
    $factureData = $factureService->genererFacture($reservation);
    
    // Envoyer la facture par email
    Mail::to(auth()->user()->email)->send(new FactureEmail($factureData['facture'], $factureData['pdf_path']));
    
    // Rediriger vers une page de confirmation avec un lien pour télécharger la facture
    return redirect()->route('reservation.confirmation', [
        'id' => $reservation->id,
        'facture_id' => $factureData['facture']->id
    ])->with('success', 'Votre réservation a été confirmée et la facture vous a été envoyée par email.');
} */
    /**
     * Page de succès du paiement
     */
    public function paymentSuccess()
    {
        return view('client.payment.success');
    }
}