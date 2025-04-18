<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
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
        'nom' => 'required|string',
        'prenom' => 'required|string',
        'pays' => 'nullable|string',
        'region' => 'nullable|string',
        'numTel' => 'required|string',
        'typeId' => 'required|in:CIN,passeport',
        'CIN' => 'nullable|required_if:typeId,CIN|string',
        'passeport' => 'nullable|required_if:typeId,passeport|string',
    ]);

    // Vérification de l'existence du client selon le type d'identité
    if ($request->typeId == 'CIN') {
        $client = Client::where('CIN', $request->CIN)->first();        
    } else {
        $client = Client::where('passeport', $request->passeport)->first();
    }

    // Si le client n'existe pas, on le crée
    if (!$client) {
        if ($request->typeId == 'CIN') {
            $client = Client::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'pays' => $request->pays,
                'region' => $request->region,
                'numTel' => $request->numTel,
                'typeId' => $request->typeId,
                'CIN' => $request->CIN,  // Ajout du CIN
                'utilisateur_id' => Auth::id(),// parce que le client ne pourra remplir le formulaire que s'il est authentifié
            ]);
        } else {
            $client = Client::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'pays' => $request->pays,
                'region' => $request->region,
                'numTel' => $request->numTel,
                'typeId' => $request->typeId,
                'passeport' => $request->passeport,  
                'utilisateur_id' => Auth::id(),
            ]);
        }
    }else{
        //si jamais le client à reserver sur place et il veut cette fois ci réserver en ligne, la condition pour procéder vers le formulaire des informations était l'authentification et donc on aura à mettre à jour l'id d'utilisateur, comme suit
        $client->update(['utilisateur_id'=>Auth::id()]);
    }


    // Redirection après la création
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


}
