<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Chambre;
use App\Models\Paiement;
use App\Models\Posseder;
use App\Models\Categorie;
use App\Models\Historique;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\Supplementaire;
use Illuminate\Support\Carbon;
use PhpParser\Node\Stmt\Else_;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FactureController;
use App\Models\Facture;
use Illuminate\Support\Facades\Log;


class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index(Request $request)
    {
        // Récupérer les chambres disponibles
        $chambres = Chambre::where('status', 0)->with('categorie')->get();
        
        // Récupérer les services supplémentaires
        $supplements = Supplementaire::all();
        
        // Si un ID de chambre est passé dans l'URL, le pré-sélectionner
        $chambreId = $request->query('chambre');
        
        return view('reservation', [
            'chambres' => $chambres,
            'supplements' => $supplements,
            'chambreId' => $chambreId
        ]);
    }
    
    /**
     * Enregistre une nouvelle réservation
     */
    
     /**
 * Enregistre une nouvelle réservation
 */
public function store(Request $request)
{
    // Valider les données du formulaire
    $validated = $request->validate([
        'dateDeb' => 'required|date',
        'dateFin' => 'required|date|after:dateDeb',
        'nom' => 'required|string',
        'prenom' => 'required|string',
        'numTel' => 'required|string',
        'pays' => 'required|string',
        'region' => 'required|string',
        'totalPayer' => 'required|numeric',
        // Ajoutez les validations pour d'autres champs si nécessaire
    ]);
    
    try {
        DB::beginTransaction();
        
        // Créer la réservation
        $reservation = new Reservation();
        $reservation->dateDeb = $request->dateDeb;
        $reservation->dateFin = $request->dateFin;
        $reservation->nom = $request->nom;
        $reservation->prenom = $request->prenom;
        $reservation->numTel = $request->numTel;
        $reservation->pays = $request->pays;
        $reservation->region = $request->region;
        $reservation->totalPayer = $request->totalPayer;
        
        // Si l'utilisateur est connecté, associer la réservation à son compte
        if (Auth::check()) {
            $reservation->user_id = Auth::id();
        }
        
        // Si un ID de chambre est spécifié
        if ($request->has('chambre_id')) {
            $chambre = Chambre::findOrFail($request->chambre_id);
            $reservation->chambre_id = $chambre->id;
            
            // Mettre à jour le statut de la chambre comme occupée
            $chambre->status = 1; // 1 = occupée
            $chambre->save();
        }
        
        // Enregistrer la réservation
        $reservation->save();
        
        // Traiter les suppléments sélectionnés
        if ($request->has('supplements')) {
            foreach ($request->supplements as $id => $value) {
                if ($value == 1) {
                    // Trouver le supplément pour obtenir son tarif
                    $supplement = Supplementaire::findOrFail($id);
                    
                    // Créer une entrée dans la table posséder
                    DB::table('posseders')->insert([
                        'reservation_id' => $reservation->id,
                        'supplementaire_id' => $id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }
        
        DB::commit();
        
        // Redirection vers une page de confirmation
        return redirect()->route('reservation.confirmation', ['id' => $reservation->id])
            ->with('success', 'Votre réservation a été enregistrée avec succès!');
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        // En cas d'erreur, rediriger avec un message d'erreur
        return redirect()->back()
            ->withInput()
            ->with('error', 'Une erreur est survenue lors de l\'enregistrement de votre réservation: ' . $e->getMessage());
    }
}

/**
 * Affiche la page de confirmation de réservation
 */
public function confirmation($id)
{
    // Récupérer la réservation avec ses suppléments
    $reservation = Reservation::with(['chambre', 'chambre.categorie', 'supplements'])->findOrFail($id);
    
    return view('reservation.confirmation', compact('reservation'));
}

    public function annuler($id)
{
    try {
        DB::beginTransaction();
        
        // Récupérer la réservation
        $reservation = Reservation::findOrFail($id);
        
        // Vérifier si l'utilisateur est autorisé à annuler cette réservation
        if (Auth::id() != $reservation->user_id) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à annuler cette réservation.');
        }
        
        // Vérifier si la réservation peut être annulée (règles d'annulation)
        $dateDebut = Carbon::parse($reservation->dateDeb);
        $today = Carbon::today();
        $delaiAnnulation = 2; // 48 heures
        
        if ($dateDebut->diffInDays($today) < $delaiAnnulation) {
            return redirect()->back()->with('error', 'Les réservations ne peuvent être annulées que 48 heures avant la date d\'arrivée.');
        }
        
        // Libérer la chambre
        if ($reservation->chambre) {
            $chambre = $reservation->chambre;
            $chambre->status = 0; // 0 = disponible
            $chambre->save();
        }
        
        // Supprimer les relations avec les suppléments
        DB::table('posseders')->where('reservation_id', $reservation->id)->delete();
        
        // Mettre à jour le statut de la réservation ou la supprimer
        // Option 1: Mise à jour du statut
        $reservation->status = 'annulée';
        $reservation->save();
        
        // Option 2: Suppression (décommentez si vous préférez cette option)
        // $reservation->delete();
        
        DB::commit();
        
        return redirect()->route('reservation.mes-reservations')
            ->with('success', 'Votre réservation a été annulée avec succès.');
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        return redirect()->back()
            ->with('error', 'Une erreur est survenue lors de l\'annulation de votre réservation: ' . $e->getMessage());
    }
}


    /**
     * Show the form for creating a new resource.
     */
    public function create( Request $request)
    {
        $validated = $request->validate([
            'chambres' => 'required|array|min:1', // On s'assure qu'il y a au moins une chambre sélectionnée
            'chambres.*' => 'exists:chambres,id' // On vérifie que chaque ID existe dans la table chambres
        ]);

        // Récupérer les chambres sélectionnées depuis la base de données
        $chambres = Chambre::whereIn('id', $validated['chambres'])->get();
        $supplementaires = Supplementaire :: all();
        return view("reservation.index",["supplementaires"=> $supplementaires,"chambres"=> $chambres]);
    }

    /**
     * Store a newly created resource in storage.
     */

     //stockage suite à une réservation en ligne 
     public function storeOnLine(Request $request)
    {
        // 1. Récupérez les données brutes sans validation
        $data = $request->all();
        
        // 2. Vérifiez si le problème vient de la validation
        try {
            $validated = $request->validate([
                'nom' => 'required|string',
                'prenom' => 'required|string',
                'pays' => 'nullable|string',
                'region' => 'nullable|string',
                'numTel' => 'required|string',
                'typeId' => 'required|in:CIN,passeport',
                'CIN' => 'nullable|required_if:typeId,CIN|string',
                'passeport' => 'nullable|required_if:typeId,passeport|string',
                'dateDeb' => 'required|date',
                'dateFin' => 'required|date|after:dateDeb',
                'totalPayer' => 'required|numeric',
                // Essayez sans ces validations pour voir si le problème est ici
                // 'services' => 'nullable|array',
                // 'chambresIds'=>'required|array',
            ]);
            
            // Si ça passe, le problème est probablement dans les validations commentées
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
        
        // 3. Créez le client et la réservation
        if( $request->typeId == 'CIN'){
            $client = Client::where('CIN', $request->CIN)
                ->first();
        }
        else{
            $client = Client::where('passeport', $request->passeport)
                ->first();
        }
        if (!$client) {
            if( $request->typeId == 'CIN'){
                $client = Client::create([
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                    'pays' => $request->pays,
                    'region' => $request->region,
                    'numTel' => $request->numTel,
                    'typeId' => $request->typeId,
                    'CIN' => $request->CIN,
                    'utilisateur_id'=>Auth::user()->id,
                ]);
            }else{
                $client = Client::create([
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                    'pays' => $request->pays,
                    'region' => $request->region,
                    'numTel' => $request->numTel,
                    'typeId' => $request->typeId,
                    'passeport' => $request->passeport,
                    'utilisateur_id'=>Auth::user()->id,
                ]);
            }
        }else{
            $client->update($request->only(['utilisateur_id']));
        }

        $reservation = Reservation::create([
            'dateDeb' => $request->dateDeb,
            'dateFin' => $request->dateFin,
            'totalPayer' => $request->totalPayer,
            'soldePayer' => $request->totalPayer,
            'receptionniste_id' => 0,
            'client_id' => $client->id,
        ]);
        
        // 4. Essayez d'abord seulement avec les chambres
        try {
            // Utilisez array_filter pour éliminer les valeurs vides
            $chambresIds = array_filter($request->input('chambresIds', []));
            
            foreach ($chambresIds as $chambreId) {
                $chambre = Chambre::find($chambreId);
                if ($chambre) {
                    /* $chambre->update([
                        'reservation_id' => $reservation->id,
                        'status' => '1'
                    ]); */
                    $historique=Historique::create([
                        'reservation_id'=>$reservation->id,
                        'chambre_id'=>$chambre->id,
                    ]);
                }
            }
            
            // Si ça passe, le problème n'est pas ici
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur avec chambresIds: ' . $e->getMessage()], 422);
        }
        
        // 5. Puis essayez seulement avec les services
        if ($request->has('services')) {
            // Récupérer le tableau services et filtrer les valeurs vides/null
            $services = array_filter($request->input('services', []), function($value) {
                return $value !== null && $value !== '';
            });
            
            // Reconstruire le tableau avec des indices numériques consécutifs
            $services = array_values($services);
            
            // Utiliser le tableau reconstruit
            foreach ($services as $serviceId) {
                Posseder::create([
                    'supplementaire_id' => $serviceId,
                    'reservation_id' => $reservation->id
                ]);
            }
        }
        
        $reservation->adultsCount = $request->adultsCount;
        $reservation->childrenCount = $request->childrenCount;
        //$reservation->save();

        // Dans le contrôleur
        session([
            'adultsCount' => $reservation->adultsCount,
            'childrenCount' => $reservation->childrenCount
        ]);
        
        // 6. Créez le paiement
        $paiement = Paiement::create([
            'montant' => $request->totalPayer,
            'mode' => 'carte',
            'reservation_id' => $reservation->id,
            'datePa' => now(),
        ]);
        /* if ($reservation && $reservation->exists ) {
            try {
                $factureController = new FactureController();
                $factureData = $factureController->genererFactureApresReservation($reservation);
                $facture=Facture::create([
                    'reservation_id'=>$reservation->id,
                    'numero_facture'=>0,
                    'montant_total'=>$request->totalPayer,
                    'date_emission'=>now(),
                    'details' => json_encode([
                        'chambres' => $reservation->chambres,
                        'services' => $reservation->services,
                        'dates' => [$reservation->dateDeb, $reservation->dateFin],
                        'adultsCount' => $reservation->adultsCount, // Récupéré de la réservation
                        'childrenCount' => $reservation->childrenCount // Récupéré de la réservation
                    ]),
                ]);
                $facture->update([
                    'numero_facture' => $facture->id,
                ]);
                // Si la facture a été générée avec succès, ajouter l'info à la session
                if ($factureData) {
                    session()->flash('facture_id', $factureData['facture']->id);
                }
            } catch (\Exception $e) {
                // Log l'erreur mais ne pas interrompre le processus
                \Log::error('Erreur lors de la génération de la facture: ' . $e->getMessage());
            }
        }
        
        
        //return response()->json(['success' => true]);
        //return redirect()->route('home');
        return redirect()->route('reservation.confirm')
        ->with('reservation', $reservation)
        ->with('facture', $facture);
        //return view("client.confirmationReservation",compact("reservation","facture")); */
        
    /*         // Créer la facture
            $reservation->load(['chambres', 'services']);
            $facture = Facture::create([
                'reservation_id' => $reservation->id,
                'numero_facture' => 0, // Temporaire
                'montant_total' => $request->totalPayer,
                'date_emission' => now(),
                'details' => json_encode([
                    //'chambres' => $reservation->chambres,
                    //'services' => $reservation->services,
                    'chambres' => [], // Tableau vide pour déboguer
                    'services' => [],
                    'dates' => [$reservation->dateDeb, $reservation->dateFin],
                    'adultsCount' => $request->adultsCount,
                    'childrenCount' => $request->childrenCount
                ]),
            ]);
            
            // Mettre à jour avec le numéro de facture
            $facture->update([
                'numero_facture' => $facture->id,
            ]);
            
        
        // IMPORTANT: Stocker les objets complets dans la session
        session(['reservation' => $reservation, 'facture' => $facture]);
        
        // Rediriger vers la page de confirmation
        return redirect()->route('reservation.confirm'); */

        /* try {
            // Créer la facture avec absolument aucune mention de relations
            $factureController = new FactureController();
            $factureData = $factureController->genererFactureApresReservation($reservation);
            $facture = Facture::create([
                'reservation_id' => $reservation->id,
                'numero_facture' => 0,
                'montant_total' => $request->totalPayer,
                'date_emission' => now(),
                'details' => json_encode([
                    'chambres' => $reservation->chambres,
                    'services' => $reservation->services,
                    //'chambres' => [], // Tableau vide pour déboguer
                    //'services' => [],
                    'dates' => [$reservation->dateDeb, $reservation->dateFin],
                    'adultsCount' => $request->adultsCount,
                    'childrenCount' => $request->childrenCount
                ]),
            ]);
            
            // Mettre à jour le numéro de facture
            $facture->update([
                'numero_facture' => $facture->id,
            ]);
            
            // Stocker uniquement les IDs dans la session
            session(['reservation_id' => $reservation->id, 'facture_id' => $facture->id]);
            
            // Rediriger
            return redirect()->route('reservation.confirm');
            
        } catch (\Exception $e) {
            // Logger l'erreur avec toutes les informations
            \Log::error('Erreur précise : ' . $e->getMessage());
            \Log::error('Ligne : ' . $e->getLine());
            \Log::error('Fichier : ' . $e->getFile());
            
            // Retourner une erreur
            return back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        } */

        try {
            $factureController = new FactureController();
            $factureData = $factureController->genererFactureApresReservation($reservation);
            
            // S'assurer que la facture a été créée
            if ($factureData && isset($factureData['facture'])) {
                $facture = $factureData['facture'];
                
                // Stocker les IDs dans la session pour la page de confirmation
                session(['reservation_id' => $reservation->id, 'facture_id' => $facture->id,'adultsCount' => $reservation->adultsCount,
            'childrenCount' => $reservation->childrenCount]);
                
                return redirect()->route('reservation.confirm');
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération de la facture: ' . $e->getMessage());
        }
        
        // Redirection en cas d'échec de la facture (on continue quand même)
        session(['reservation_id' => $reservation->id]);
        return redirect()->route('reservation.confirm');

    
    
    }




    //stockage par le biais d'un formulaire de la part du réceptionniste
    /* public function store(Request $request)
    {
        //valide les données et verifient si les conditions sont respectées
        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'pays' => 'nullable|string',
            'region' => 'nullable|string',
            'numTel' => 'required|string',
            'typeId' => 'required|in:CIN,passeport',
            'CIN' => 'nullable|required_if:typeId,CIN|string',
            'Passeport' => 'nullable|required_if:typeId,passeport|string',
            'dateDeb' => 'required|date',
            'dateFin' => 'required|date|after:dateDeb',
            'totalPayer' => 'required|numeric',
            'soldePayer' => 'required|numeric',
            'modePaiement' => 'required|string',
            'services' => 'nullable|array',
        ]);

        //réservation sur place, la ligne suivante va nous permettre de vérifier si le client se trouve dans la base avant d'insérer un nv
        if( $request->typeId == 'CIN'){
            $client = Client::where('CIN', $request->CIN)
                ->first();
        }
        else{
            $client = Client::where('passeport', $request->passeport)
                ->first();
        }
        if (!$client) {
            if( $request->typeId == 'CIN'){
                $client = Client::create([
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                    'pays' => $request->pays,
                    'region' => $request->region,
                    'numTel' => $request->numTel,
                    'typeId' => $request->typeId,
                    'CIN' => $request->CIN,
                ]);
            }else{
                $client = Client::create([
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                    'pays' => $request->pays,
                    'region' => $request->region,
                    'numTel' => $request->numTel,
                    'typeId' => $request->typeId,
                    'Passeport' => $request->Passeport,
                ]);
            }

        }

        $receptionniste = \App\Models\Receptionniste::where('user_id', Auth::id())->first();

        if (!$receptionniste) {
            return back()->withErrors("Aucun réceptionniste associé à cet utilisateur.");
        }


        $reservation = Reservation::create([
            'dateDeb' => $request->dateDeb,
            'dateFin' => $request->dateFin,
            'totalPayer' => $request->totalPayer,
            'soldePayer' => $request->soldePayer,
            'receptionniste_id' => $receptionniste->id,//extraire l'id de l'utilisateur
            'client_id' => $client->id,
        ]);

        //convertir la chaine de caractère en array
        $chambres = json_decode($request->input('chambres'), true);
        $chambresIds=array_column($chambres, 'id');

        foreach($chambresIds as $chambreId){
            $ch=Chambre::where('id', $chambreId)->first();
            $ch->update(['reservation_id' => $reservation->id,'status'=>'1']); //update the status
        }

        $paiement = Paiement::create([
            'montant'=>$request->totalPayer,
            'mode'=>$request->modePaiement,
            'reservation_id'=>$reservation->id,
            'datePa'=>now(),
        ]);

        if ($request->has('supplementaires')) {
            $reservation->supplementaires()->attach($request->supplementaires);
        }

        return redirect()->route('chambre.index')->with('success', 'Réservation ajoutée avec succès.');
    } */

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    // Dans ClientController
    public function edit(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'pays' => 'nullable|string',
            'region' => 'nullable|string',
            'numTel' => 'required|string',
            'CIN' => 'nullable|required_if:typeId,CIN|string',
            'Passeport' => 'nullable|required_if:typeId,passeport|string',
        ]);

        $client = Client::findOrFail($id);

        //distinction entre ceux qui ont déclaré une CIN et num Passeport, une fois le type identifié , on ne peut pas le changer
        if($request->typeId == 'CIN'){
            $client->update($request->only(['nom', 'prenom','pays','region', 'numTel','CIN']));
        }
        else{
            $client->update($request->only(['nom', 'prenom','pays','region', 'numTel','passeport']));
        }

        $client->save();

        return redirect('/reservation/display')->with('success', 'Client mis à jour avec succès!');
        }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'dateDeb' => 'required|date',
            'dateFin' => 'required|date|after:dateDeb',
            'totalPayer' => 'required|numeric',
            'soldePayer' => 'required|numeric',
        ]);

        $reservation=Reservation::findOrFail($id);
        $supp = Supplementaire::findOrFail($id);
        $reservation->dateDeb = $request->input('dateDeb');
        $reservation->dateFin = $request->input('dateFin');
        $reservation->totalPayer = $request->input('totalPayer');
        $reservation->save();

        return redirect('/reservation/display')->with('success', 'Service mis à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        return redirect()->route('reservation.liste')->with('success', 'Reservation supprimée avec succès.');
    }

    public function displayClient(string $id){
        //à retourner une page qui détaille les champs du client
        $client = Client::findOrFail($id);
        return view("reservation.displayClient",compact("client"));
    }

    /**
     * Affiche le formulaire de réservation pour les clients
     */
    public function createForm()
    {
        // Récupérer toutes les chambres
        $chambres = Chambre::all();
        //$chambres = Chambre::with('categorie')->get();

        // Récupérer les services supplémentaires
        $supplementaires = Supplementaire::all();

        $categories=Categorie::all();
        $categoriesMap = $categories->keyBy('id')->toArray();

        return view('client.reservation', [
            'rooms' => $chambres,
            'services' => $supplementaires,
            'categoriesMap'=>$categoriesMap,
        ]);
    }


    public function editDate($id)
    {
        $reservation = Reservation::findOrFail($id);
        return view('reservation.modifierDate', compact('reservation'));
    }

    public function additionalPriceToPay($id,$ancienneDate,$nouvelleDate){
        $chambres = Chambre::where('reservation_id', $id)
                ->get();
        $ancienneDate = Carbon::parse($ancienneDate); // "2025-04-10"
        $nouvelleDate = Carbon::parse($nouvelleDate);     // "2025-04-15"
        $duree = $ancienneDate->diffInDays($nouvelleDate, false);
        $countAmount=0;
        foreach($chambres as $chambre){
            $countAmount+=$chambre->prixNuit;
        }
        return $duree*$countAmount;

    }
    public function updateDate(Request $request, $id)
    {
        //si la date a réellement changer, on doit faire une mise à jour sur le prix de la réservation
        $request->validate([
            'dateFin' => 'required|date|after:dateDeb',
        ]);

        $ancienneDate=$request->ancienneDate;
        $nouvelleDate=$request->dateFin;
        $reservation = Reservation::findOrFail($id);
        $reservation->dateFin =  $nouvelleDate;

        if($nouvelleDate==$ancienneDate){
            return redirect()->route('reservation.liste');
        }
        else{
            $id=$request->id;
            $additional=$this->additionalPriceToPay($id,$ancienneDate,$nouvelleDate);

            //return view('paiement.payerDiffDate',compact('reservation','ancienneDate','nouvelleDate','additional'));cette ligne cause énormement de problème

            return redirect()->route('reservation.paiement', ['id' => $id,                'ancienneDate' => $ancienneDate,
            'nouvelleDate' => $nouvelleDate,
            'additional'=>$additional,
            ]);
        }

    }

    public function afficherPaiement($id, Request $request)
    {
        $reservation = Reservation::findOrFail($id);
        $ancienneDate = $request->query('ancienneDate');
        $nouvelleDate = $request->query('nouvelleDate');

        $montant = $this->additionalPriceToPay($id, $ancienneDate, $nouvelleDate);

        return view('paiement.payerDiffDate', compact('reservation', 'ancienneDate', 'nouvelleDate', 'montant'));
    }

    public function validerPaiement(Request $request, $id)
    {

        $request->validate([
            'modePaiement' => 'required|string',
            'montant' => 'required|numeric',
            'nouvelleDate' => 'required|date|after:dateDeb',  // Ajout de la validation pour 'nouvelleDate'
        ]);


        // Trouver la réservation
        $reservation = Reservation::findOrFail($id);

        $additionalPrice=$request->montant;
        // Ajouter le montant payé au solde de la réservation
        $reservation->soldePayer += $additionalPrice;

        // Si le paiement est complet, mettre à jour les dates de réservation
        $reservation->dateFin = $request->nouvelleDate;
        $reservation->totalPayer += $request->montant;
        $reservation->save();


        $paiement = Paiement::create([
            'montant'=>$additionalPrice,
            'mode'=>$request->modePaiement,
            'reservation_id'=>$reservation->id,
            'datePa'=>now(),
        ]);

        return redirect()->route('reservation.liste')->with('success', 'Paiement effectué avec succès.');
    }





}
