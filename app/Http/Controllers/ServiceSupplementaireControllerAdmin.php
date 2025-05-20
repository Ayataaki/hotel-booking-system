<?php

namespace App\Http\Controllers;

use App\Models\Supplementaire;
use Illuminate\Http\Request;

class ServiceSupplementaireControllerAdmin extends Controller
{
    public function index(Request $request)
    {
        $query = Supplementaire::query();

        // Recherche
        if ($request->has('search')) {
            $query->where('libelle', 'like', '%' . $request->search . '%');
        }

        // Filtre par prix maximum
        if ($request->has('max_price')) {
            $query->where('tarif', '<=', $request->max_price);
        }

        // Tri
        $sortBy = $request->get('sort', 'libelle');
        $order = $request->get('order', 'asc');
        $query->orderBy($sortBy, $order);

        // Pagination
        $perPage = 10;
        $services = $query->paginate($perPage);

        return view('admin.services', [
            'services' => $services,
            'page' => $services->currentPage(),
            'total' => $services->total(),
            'totalPages' => $services->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string|max:255',
            'tarif' => 'required|numeric|min:0',
        ]);

        Supplementaire::create([
            'libelle' => $request->libelle,
            'tarif' => $request->tarif,
        ]);

        return response()->json(['message' => 'Service ajouté avec succès']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'libelle' => 'required|string|max:255',
            'tarif' => 'required|numeric|min:0',
        ]);

        $service = Supplementaire::findOrFail($id);
        $service->update([
            'libelle' => $request->libelle,
            'tarif' => $request->tarif,
        ]);

        return response()->json(['message' => 'Service modifié avec succès']);
    }

    public function destroy($id)
    {
        $service = Supplementaire::findOrFail($id);
        $service->delete();

        return response()->json(['message' => 'Service supprimé avec succès']);
    }
}
