<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Chambre;
use App\Models\Historique;
use App\Models\Commentaire;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients=Client::all();
        return view("client.show",["clients"=>$clients]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users=User::all();//on cherche toutes les cats 
        return view("client.form",["users"=>$users]);// on fait passer ces cats pour qu'on puisse les montrer   
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'pays'=> 'required|string|max:255',
            'region'=> 'required|string|max:255',
            'numTel'=> 'required|string|max:255',
            'typeId'=> 'required|in:CIN,Passeport', // Le type d'identité doit être CIN ou Passeport
            'CIN' => 'nullable|required_if:typeId,CIN|string|max:255', // Si le type est CIN, le CIN est requis
            'numero_passeport' => 'nullable|required_if:typeId,Passeport|string|max:255', // Si le type est Passeport, le numéro de passeport est requis
        ]);

        $nom=$request->input('nom');
        $prenom=$request->input('prenom');        
        $pays=$request->input('pays');        
        $region=$request->input('region');        
        $numTel=$request->input('numTel');        
        $typeId=$request->input('typeId');        
        $CIN = $request->input('CIN');
        $passeport = $request->input('passeport');
        //avant d'enregistrer ça , il faut s'assurer que ça n'existe pas dans la bd
        $clientTest = Client::where('numTel', $numTel)
        ->where('CIN', $CIN)
        ->where('passeport', $passeport)
        ->first();
        if(!$clientTest){
            $newClient = new Client();
            $newClient->nom =$nom;
            $newClient->prenom = $prenom;
            $newClient->numTel = $numTel;
            $newClient->pays = $pays;
            $newClient->region = $region;
            $newClient->typeId = $typeId;
            $newClient->CIN = $CIN;
            $newClient->passeport = $passeport;          
            $newClient->save();
        }
        //en tout cas on doit rediriger vers le paiement 
        return redirect('/chambre');

    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $client = Client::all();
        return view("client.index",["client"=>$client]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }

    public function profile(){

        $user = Auth::user();
    
        // Récupérer les clients associés à cet utilisateur
        $clients = Client::where('utilisateur_id', $user->id)->get();

        // Extraire uniquement les IDs des clients
        $clientIds = $clients->pluck('id')->toArray();

        // Récupérer les réservations associées à ces clients
        $reservations = Reservation::whereIn('client_id', $clientIds)->get();

        $reservationIds=$reservations->pluck('id')->toArray();

        $chambres=Chambre::whereIn('reservation_id',$reservationIds)->get();
        
        $historiques=Historique::whereIn('reservation_id',$reservationIds)->get();

        $historiques = Historique::whereIn('reservation_id', $reservationIds)
            ->with(['chambre.categorie', 'reservation'])
            ->orderBy('created_at', 'desc')
            ->get();

        $commentaires=Commentaire::where('utilisateur_id', $user->id)->get();

        return view("client.profile",compact('reservations','user','chambres','historiques','commentaires'));
    }


}
