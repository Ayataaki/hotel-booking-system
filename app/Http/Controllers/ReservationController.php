<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Chambre;
use App\Models\Paiement;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\Supplementaire;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::all();
        $clients= Client::all();
        return view("reservation.liste",compact('reservations','clients'));
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


    //stockage par le biais d'un formulaire de la part du réceptionniste
    public function store(Request $request)
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
                    'Passeport' => $request->numPasseport,
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
    }

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