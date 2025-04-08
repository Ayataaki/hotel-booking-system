<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;

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

    public function search(Request $request)
    {
        $query = $request->input('query');

        $clients = Client::where('nom', 'LIKE', "%$query%")
            ->orWhere('prenom', 'LIKE', "%$query%")
            ->orWhere('numTel', 'LIKE', "%$query%")
            ->get();

        $html = '';
        foreach ($clients as $client) {
            $html .= "<button type='button' class='list-group-item list-group-item-action client-item' 
                        data-nom='{$client->nom}'
                        data-prenom='{$client->prenom}'
                        data-pays='{$client->pays}'
                        data-region='{$client->region}'
                        data-numtel='{$client->numTel}'
                        data-typeid='{$client->typeId}'
                        data-cin='{$client->numCIN}'
                        data-passeport='{$client->numPasseport}'
                    '>{$client->nom} {$client->prenom} - {$client->numTel}</button>";
        }

        return response()->json($html);
    }


}
