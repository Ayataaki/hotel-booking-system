<?php
namespace App\Http\Controllers;
use App\Models\Receptionniste;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;





class ReceptionnisteControllerAdmin extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // public function index()
    // {
    //     $receptionnistes = Receptionniste::all();
    //     return view('admin.staff', compact('receptionnistes'));
    // }
    public function index()
    {
        // Récupérer les réceptionnistes avec pagination (10 par page)
        $receptionnistes = Receptionniste::orderBy('updated_at', 'asc')->paginate(4);

        return view('admin.staff', compact('receptionnistes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */


    // public function store(Request $request)
    // {
    //     // 1. Créer l'utilisateur dans 'users'
    //     $user = User::create([
    //         'name' => $request->prenomRec . ' ' . $request->nomRec,
    //         'email' => $request->email,
    //         'password' => Hash::make('LmC@1234!'), // mot de passe commun
    //         'role' => 'recep', // ou 'receptionniste' selon ton système
    //     ]);

    //     // 2. Créer le réceptionniste
    //     Receptionniste::create([
    //         'prenomRec' => $request->prenomRec,
    //         'nomRec' => $request->nomRec,
    //         'email' => $request->email,
    //         'numTel' => $request->numTel,
    //         'CIN' => $request->CIN,
    //         'adresse' => $request->adresse,
    //         'statut' => $request->statut,
    //         'user_id' => $user->id, // lier le receptionniste au user
    //     ]);

    //     return redirect()->back()->with('success', 'Employé ajouté avec succès.');
    // }
public function store(Request $request)
{
    try {
        // Validation des données
        $request->validate([
            'prenomRec' => 'required|string|max:255',
            'nomRec' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:receptionnistes,email',
            'numTel' => 'required|string|max:20',
            'CIN' => 'required|string|max:20',
            'adresse' => 'nullable|string',
            // 'statut' => 'required|in:active,inactive',
            // 'statut' => 'required|in:active',
        ]);

        // 1. Créer l'utilisateur dans la table 'users'
        $user = User::create([
            'name' => $request->prenomRec . ' ' . $request->nomRec,
            'email' => $request->email,
            'password' => Hash::make('LaMiCasa@2023'), // Mot de passe par défaut
            'userType' => 'recep', // Type d'utilisateur 'recep'
        ]);

        // 2. Créer le réceptionniste avec référence à l'utilisateur créé
        $receptionniste = Receptionniste::create([
            'prenomRec' => $request->prenomRec,
            'nomRec' => $request->nomRec,
            'email' => $request->email,
            'numTel' => $request->numTel,
            'CIN' => $request->CIN,
            'adresse' => $request->adresse ?? '',
            // 'statut' => $request->statut,
            'statut' => "active",
            'user_id' => $user->id, // Lier au compte utilisateur créé
            'created_at' => $request->created_at ?? now(),
        ]);

        // Retourner une réponse JSON pour AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Employé ajouté avec succès',
                'receptionniste' => $receptionniste
            ]);
        }

        // Redirection standard avec message flash
        return redirect()->back()->with('success', 'Employé ajouté avec succès.');
    } catch (\Exception $e) {
        // Log l'erreur pour débogage
        \Log::error('Erreur lors de l\'ajout d\'un réceptionniste: ' . $e->getMessage());

        // Retourner une réponse JSON en cas d'erreur pour AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 422);
        }

        // Redirection standard avec message flash d'erreur
        return redirect()->back()->with('error', 'Erreur lors de l\'ajout: ' . $e->getMessage())->withInput();
    }
}

    //************************* Code qui marche ********************************
    // public function store(Request $request)
    // {
    //     try {
    //         // Validation des données
    //         $request->validate([
    //             'prenomRec' => 'required|string|max:255',
    //             'nomRec' => 'required|string|max:255',
    //             'email' => 'required|email|unique:receptionnistes,email',
    //             'numTel' => 'required|string|max:20',
    //             'CIN' => 'required|string|max:20',
    //             'adresse' => 'nullable|string',
    //             'statut' => 'required|in:active,inactive',
    //         ]);

    //         // Créer un nouvel enregistrement Receptionniste
    //         $receptionniste = Receptionniste::create([
    //             'prenomRec' => $request->prenomRec,
    //             'nomRec' => $request->nomRec,
    //             'email' => $request->email,
    //             'numTel' => $request->numTel,
    //             'CIN' => $request->CIN,
    //             'adresse' => $request->adresse ?? '',
    //             'statut' => $request->statut,
    //             'user_id' => 1, // Temporaire, à remplacer par la création d'un User
    //             'created_at' => $request->created_at ?? now(),
    //         ]);

    //         // Retourner une réponse JSON pour AJAX
    //         if ($request->ajax()) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Employé ajouté avec succès',
    //                 'receptionniste' => $receptionniste
    //             ]);
    //         }

    //         // Redirection standard avec message flash
    //         return redirect()->back()->with('success', 'Employé ajouté avec succès.');
    //     } catch (\Exception $e) {
    //         // Retourner une réponse JSON en cas d'erreur pour AJAX
    //         if ($request->ajax()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Erreur: ' . $e->getMessage()
    //             ], 422);
    //         }

    //         // Redirection standard avec message flash d'erreur
    //         return redirect()->back()->with('error', 'Erreur lors de l\'ajout: ' . $e->getMessage());
    //     }
    // }
//    public function store(Request $request)
// {
//     Receptionniste::create([
//         'prenomRec' => $request->prenomRec,
//         'nomRec' => $request->nomRec,
//         'email' => $request->email,
//         'numTel' => $request->numTel,
//         'CIN' => $request->CIN,
//         'adresse' => $request->adresse,
//         'statut' => $request->statut,
//         'user_id' => 1, // temporaire
//         'created_at' => $request->created_at, // ✅ ajouté ici
//     ]);

//     return redirect()->back()->with('success', 'Employé ajouté avec succès.');
// }




    // public function store(Request $request)
    // {
    //     // 1. Validation rapide
    //     $request->validate([
    //         'prenomRec' => 'required|string',
    //         'nomRec' => 'required|string',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|min:8|confirmed',
    //     ]);

    //     // 2. Créer l'utilisateur dans la table users
    //     $user = User::create([
    //         'name' => $request->prenomRec . ' ' . $request->nomRec,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'role' => 'recep',
    //     ]);

    //     // 3. Créer le receptionniste dans la table receptionnistes
    //     Receptionniste::create([
    //         'prenomRec' => $request->prenomRec,
    //         'nomRec' => $request->nomRec,
    //         'email' => $request->email,
    //         'numTel' => $request->numTel,
    //         'CIN' => $request->CIN,
    //         'adresse' => $request->adresse,
    //         'statut' => $request->statut,
    //         'user_id' => $user->id,
    //     ]);

    //     return redirect()->back()->with('success', 'Employé ajouté avec succès.');
    // }


    /**
     * Display the specified resource.
     */
    public function show(Receptionniste $receptionniste)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Receptionniste $receptionniste)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
     // Ajoute cette ligne en haut du controller


     public function update(Request $request)
{
    try {
        // Validation des données
        $request->validate([
            'id' => 'required|exists:receptionnistes,id',
            'prenomRec' => 'required|string|max:255',
            'nomRec' => 'required|string|max:255',
            'email' => 'required|email',
            'numTel' => 'required|string|max:20',
            'CIN' => 'required|string|max:20',
            'adresse' => 'nullable|string',
            // 'statut' => 'required|in:active,inactive',
            'password' => 'nullable|min:8',
            'password_confirmation' => 'nullable|same:password',
        ]);

        $receptionniste = Receptionniste::findOrFail($request->id);

        // Vérifier si l'email a changé et s'il est unique
        if ($receptionniste->email !== $request->email) {
            // Vérifier si le nouvel email existe déjà (excluant l'utilisateur actuel)
            $emailExists = User::where('email', $request->email)
                ->where('id', '!=', $receptionniste->user_id)
                ->exists();

            if ($emailExists) {
                throw new \Exception('Cet email est déjà utilisé par un autre compte.');
            }
        }

        // Mettre à jour le réceptionniste
        $receptionniste->update([
            'prenomRec' => $request->prenomRec,
            'nomRec' => $request->nomRec,
            'email' => $request->email,
            'numTel' => $request->numTel,
            'CIN' => $request->CIN,
            'adresse' => $request->adresse ?? '',
            // 'statut' => $request->statut,
        ]);

        // Mettre à jour l'utilisateur associé
        if ($receptionniste->user_id) {
            $user = User::find($receptionniste->user_id);
            if ($user) {
                $user->name = $request->prenomRec . ' ' . $request->nomRec;
                $user->email = $request->email;

                // Mise à jour du mot de passe si fourni
                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                }

                $user->save();
            }
        }

        return redirect()->back()->with('success', 'Employé modifié avec succès.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Erreur lors de la modification: ' . $e->getMessage())->withInput();
    }
}
    //  ******************* Code qui marche *************************
    // public function update(Request $request)
    // {
    //     $receptionniste = Receptionniste::findOrFail($request->id);

    //     $receptionniste->update([
    //         'prenomRec' => $request->prenomRec,
    //         'nomRec' => $request->nomRec,
    //         'email' => $request->email,
    //         'numTel' => $request->numTel,
    //         'CIN' => $request->CIN,
    //         'adresse' => $request->adresse,
    //         'statut' => $request->statut,
    //     ]);

    //     // Mise à jour du mot de passe dans la table users
    //     if ($request->filled('password')) {
    //         $user = User::find($receptionniste->user_id);
    //         if ($user) {
    //             $user->password = Hash::make($request->password); // 🔥 TRÈS IMPORTANT
    //             $user->save();
    //         }
    //     }

    //     return redirect()->back()->with('success', 'Employé modifié avec succès.');
    // }


    /**
     * Remove the specified resource from storage.
     */
    // **************************  La fonction qui marche très bien *******************************
    // public function destroy(Request $request, $id)
    // {
    //     try {
    //         $receptionniste = Receptionniste::findOrFail($id);

    //         // Supprimer l'utilisateur associé
    //         if ($receptionniste->user_id) {
    //             $user = User::find($receptionniste->user_id);
    //             if ($user) {
    //                 $user->delete();
    //             }
    //         }

    //         // Supprimer le réceptionniste
    //         $receptionniste->delete();

    //         if ($request->ajax()) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Employé supprimé avec succès'
    //             ]);
    //         }

    //         return redirect()->route('admin.staff')->with('success', 'Employé supprimé avec succès');
    //     } catch (\Exception $e) {
    //         if ($request->ajax()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
    //             ], 500);
    //         }

    //         return redirect()->route('admin.staff')->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
    //     }
    // }


    // ************************  Presque !!!  ****************************
    // public function destroy(Request $request)
    // {
    //     // Ajouter ces logs pour le débogage
    //     \Log::info('Tentative de suppression avec ID: ' . $request->id);
    //     \Log::info('Données de la requête: ', $request->all());

    //     try {
    //         // Vérifier si l'ID est présent
    //         if (!$request->has('id')) {
    //             throw new \Exception("L'ID de l'employé n'a pas été fourni");
    //         }

    //         $receptionniste = Receptionniste::findOrFail($request->id);
    //         \Log::info('Réceptionniste trouvé: ' . $receptionniste->id);

    //         if ($receptionniste->user_id) {
    //             $user = User::find($receptionniste->user_id);
    //             if ($user) {
    //                 \Log::info('Suppression de l\'utilisateur: ' . $user->id);
    //                 $user->delete();
    //             }
    //         }

    //         \Log::info('Suppression du réceptionniste: ' . $receptionniste->id);
    //         $receptionniste->delete();
    //         \Log::info('Suppression réussie');

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Employé supprimé avec succès'
    //         ]);
    //     } catch (\Exception $e) {
    //         \Log::error('Erreur de suppression: ' . $e->getMessage());

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }




    // ********************************** Code qui marche ***************************
    // public function destroy(Request $request)
    // {
    //     \DB::beginTransaction();
    //     try {
    //         $receptionniste = Receptionniste::findOrFail($request->id);

    //         // Stocker l'ID utilisateur avant de supprimer le réceptionniste
    //         $userId = $receptionniste->user_id;

    //         // D'abord supprimer le réceptionniste
    //         $receptionniste->delete();

    //         // Ensuite supprimer l'utilisateur si nécessaire
    //         if ($userId) {
    //             $user = User::find($userId);
    //             if ($user) {
    //                 $user->delete();
    //             }
    //         }

    //         \DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Employé supprimé avec succès'
    //         ]);
    //     } catch (\Exception $e) {
    //         \DB::rollback();

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }


    public function destroy(Request $request)
{
    \DB::beginTransaction();
    try {
        $receptionniste = Receptionniste::findOrFail($request->id);

        // Stocker l'ID utilisateur avant de supprimer le réceptionniste
        $userId = $receptionniste->user_id;

        // D'abord supprimer le réceptionniste
        $receptionniste->delete();

        // Ensuite supprimer l'utilisateur si nécessaire
        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $user->delete();
            }
        }

        \DB::commit();

        // Si c'est une requête AJAX, renvoyer JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Employé supprimé avec succès'
            ]);
        }

        // Sinon, rediriger avec un message flash
        return redirect()->back()->with('success', 'Employé supprimé avec succès');
    } catch (\Exception $e) {
        \DB::rollback();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
    }
}











    // public function destroy(Request $request)
    // {
    //     try {
    //         $receptionniste = Receptionniste::findOrFail($request->id);

    //         if ($receptionniste->user_id) {
    //             $user = User::find($receptionniste->user_id);
    //             if ($user) {
    //                 $user->delete();
    //             }
    //         }

    //         $receptionniste->delete();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Employé supprimé avec succès'
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }


}
