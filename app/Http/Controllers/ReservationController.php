<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Chambre;
use App\Models\Paiement;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\Supplementaire;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

/**
 * Affiche la liste des réservations de l'utilisateur connecté
 */
public function mesReservations()
{
    // Vérifier si l'utilisateur est connecté
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    
    // Récupérer les réservations de l'utilisateur avec les relations
    $reservations = Reservation::with(['chambre', 'supplements'])
        ->where('user_id', Auth::id())
        ->orderBy('created_at', 'desc')
        ->get();
        
    return view('reservation.mes-reservations', compact('reservations'));
}

/**
 * Annule une réservation
 */

 

/**
 * Affiche la page de confirmation de réservation
 */
/* public function confirmation($id)
{
    // Récupérer la réservation avec ses suppléments
    $reservation = Reservation::with(['chambre', 'chambre.categorie', 'supplements'])->findOrFail($id);
    
    return view('reservation.confirmation', compact('reservation'));
} */
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
        $dateDebut = \Carbon\Carbon::parse($reservation->dateDeb);
        $today = \Carbon\Carbon::today();
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
/* public function mesReservations()
{
    // Vérifier si l'utilisateur est connecté
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    
    // Récupérer les réservations de l'utilisateur avec les relations
    $reservations = Reservation::with(['chambre', 'supplements'])
        ->where('user_id', Auth::id())
        ->orderBy('created_at', 'desc')
        ->get();
        
    return view('reservation.mes-reservations', compact('reservations'));
} */
    /* public function index()
    {
        $reservations = Reservation::all();
        $clients= Client::all();
        return view("reservation.liste",compact('reservations','clients'));
    } */

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
        //dd($request->all());
        $request->validate([
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
            //'services' => 'nullable|array',
        ]);

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
                    'passeport' => $request->passeport,
                ]);
            }
        }

        $reservation = Reservation::create([
            'dateDeb' => $request->dateDeb,
            'dateFin' => $request->dateFin,
            'totalPayer' => $request->totalPayer,
            'soldePayer' => $request->totalPayer,
            'receptionniste_id' => 0,
            'client_id' => $client->id,
        ]);

        //dd($reservation);

        $paiement = Paiement::create([
            'montant'=>$request->totalPayer,
            'mode'=>'carte',
            'reservation_id'=>$reservation->id,
            'datePa'=>now(),
        ]);

        
        //dd($paiement);

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

        return view('client.reservation', [
            'chambres' => $chambres,
            'supplementaires' => $supplementaires
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
