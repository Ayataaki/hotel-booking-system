<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index(){
        //puisqu'on veut mettre un boucle, il faut envoyer toutes les catégories avec ce return mais avant il faut les avoir
        $categories=Categorie::all();
        return view("categorie.index",["categories"=>$categories]);
    }
    public function store(Request $request){
        return view("categorie.addCat");
    }
    public function create(Request $request){
        $cat=new Categorie();
        $cat->typeChambre=$request->input('nom');
        $cat->description=$request->input('descr');
        $cat->save();
        return redirect('/chambre/categorie/');
    }
    public function edit($id)
    {
        $categorie = Categorie::findOrFail($id);
        return view('categorie.edit', compact('categorie'));
    }
    public function update(Request $request, $id)
    {
        // Validation des données
        $request->validate([
            'typeChambre' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        // Trouver la catégorie existante
        $categorie = Categorie::findOrFail($id);

        // Mise à jour des informations de la catégorie
        $categorie->typeChambre = $request->input('typeChambre');
        $categorie->description = $request->input('description');

        // Sauvegarder les modifications dans la base de données
        $categorie->save();

        // Rediriger vers la liste des catégories avec un message de succès
        return redirect()->route('categorie.index')->with('success', 'Catégorie mise à jour avec succès!');
    }
    public function destroy($id)
    {
        $categorie = Categorie::findOrFail($id);
        $categorie->delete();
        return redirect()->route('categorie.index')->with('success', 'Catégorie supprimée avec succès.');
    }


}
