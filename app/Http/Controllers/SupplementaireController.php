<?php

namespace App\Http\Controllers;

use App\Models\Supplementaire;
use Illuminate\Http\Request;

class SupplementaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supplementaires=Supplementaire::all();
        return view("supp.index",["supplementaires"=>$supplementaires]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("supp.form");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $supp=new Supplementaire();
        $supp->libelle=$request->input('libelle');
        $supp->tarif=$request->input('tarif');
        $supp->save();
        return redirect('/supp');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('supp.page', [
            'supplementaire' => Supplementaire::findOrFail($id)
        ]);
        //its route : Route::get('/user/{id}', [UserController::class, 'show']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $supp = Supplementaire::findOrFail($id);
        return view('supp.edit', compact('supp'));
    }

    /**
     * Update the specified resource in storage.
     */
    
    
     public function update(Request $request, $id)
    {
        $request->validate([
            'libelle' => 'required|string|max:255',
            'tarif' => 'required|numeric|min:0',
        ]);

        $supp = Supplementaire::findOrFail($id);
        $supp->libelle = $request->input('libelle');
        $supp->tarif = $request->input('tarif');
        $supp->save();

        return redirect('/supp')->with('success', 'Service mis à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $supp = Supplementaire::findOrFail($id);
        $supp->delete();

        return redirect('/supp')->with('success', 'Service supprimé avec succès!');
    }
}
