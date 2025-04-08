<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Chambre;
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
        //
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
        $client = Client::where('numTel', $request->numTel)
                ->orWhere('CIN', $request->CIN)
                ->where('passeport', $request->passeport)
                ->first();

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

        $reservation = Reservation::create([
            'dateDeb' => $request->dateDeb,
            'dateFin' => $request->dateFin,
            'totalPayer' => $request->totalPayer,
            'soldePayer' => $request->soldePayer,
            'receptionniste_id' => Auth::id(),//extraire l'id de l'utilisateur
            'client_id' => $client->id,
        ]);

        //changer l'id de reservation dans la table chambre & mise à jour du champs statut vers 1
        //$ch=Chambre::where('id', $request->id_chambre)->first();
        //$ch->update(['reservation_id' => $reservation->id,'status'=>'1']); 
        //Remarque : ce code a été valable pour une seul chambre reservée

        //convertir la chaine de caractère en array
        $chambres = json_decode($request->input('chambres'), true);
        $chambresIds=array_column($chambres, 'id');

        foreach($chambresIds as $chambreId){
            $ch=Chambre::where('id', $chambreId)->first();
            $ch->update(['reservation_id' => $reservation->id,'status'=>'1']); 
        }
        


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
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }
}
