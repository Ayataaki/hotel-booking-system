<?php

use App\Http\Controllers\Admin\AccountControllerAdmin;
use App\Models\Client;
use App\Models\Reservation;
use App\Models\Supplementaire;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ChambreController;
use App\Http\Controllers\ChambreControllerAdmin;
use App\Http\Controllers\ReceptionnisteControllerAdmin;
use App\Http\Controllers\ReservationControllerAdmin;
use App\Http\Controllers\ReceptionController;
// Le nouveau qu'Anas a crée :
use App\Http\Controllers\FactureController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\FactureControllerReceptionniste;
use App\Http\Controllers\ReservationController;
// use App\Http\Controllers\ReceptionnisteController;
use App\Http\Controllers\SupplementaireController;
use App\Http\Controllers\ReservationControllerReceptionniste;
use App\Http\Controllers\ServiceSupplementaireControllerAdmin;

// Routes publiques pour l'interface client
Route::get("/", [HomeController::class, "index"])->name("home");
Route::get("/chambres/admin", [ChambreController::class, "showAll"])->name("chambres");
//pour réserver le client doit être authentifier
//Route::get("/reservation", [ReservationController::class, "createForm"])->name("reservation");
Route::get("/profile", [ClientController::class,"profile"])->name("client.profil");
// Routes pour les chambres
Route::get('/chambres', [ChambreController::class, 'index'])->name('chambres');
//Route::get('/chambres/{id}', [ChambreController::class, 'show'])->name('chambres.show');
Route::post('/chambres/check-availability', [ChambreController::class, 'checkAvailability'])->name('chambres.check-availability');

Route::get('/commentaires/creer', [CommentaireController::class, 'create'])->name('commentaires.create');
Route::post('/commentaires', [CommentaireController::class, 'store'])->name('commentaires.store');
Route::get('/commentaires/confirmation', [CommentaireController::class, 'confirmation'])->name('commentaires.confirmation');

Route::get('/forgot-password', 'PasswordResetController@showLinkRequestForm')->name('password.request');
Route::post('/forgot-password', 'PasswordResetController@sendResetLinkEmail')->name('password.email');
Route::get('/reset-password/{token}', 'PasswordResetController@showResetForm')->name('password.reset');
Route::post('/reset-password', 'PasswordResetController@reset')->name('password.update');


// Routes pour le paiement avec Stripe
Route::get('/payment', [App\Http\Controllers\StripeController::class, 'showPaymentForm'])->name('stripe.form');
Route::post('/payment/process', [App\Http\Controllers\StripeController::class, 'processPayment'])->name('stripe.process');
Route::get('/payment/success', [App\Http\Controllers\StripeController::class, 'paymentSuccess'])->name('payment.success');

// Routes fictives pour les liens dans la page de succès
Route::get('/account/bookings', function() {
    return redirect('/')->with('info', 'Fonctionnalité en cours de développement');
})->name('account.bookings');


//confirmation de la réservation


//manip reservations
//je ne pense pas que cette route marche

//Route::post('/reservation/store', [ReservationController::class, 'storeOnLine'])->name('reservation.store.online');

// Routes d'authentification
// Route::get("/", function () {   return view("welcome");});
Route::get( "/login", [AuthController::class,"login"])->name("login");
Route::post( "/login", [AuthController::class,"loginPost"])->name("login.post");
Route::get("/register", [AuthController::class,"register"])->name("register");
Route::post( "/register", [AuthController::class,"registerPost"])->name("register.post");





// Admin uniquement
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/rooms', [App\Http\Controllers\AdminController::class, 'rooms'])->name('admin.rooms');
    Route::get('/admin/staff', [App\Http\Controllers\AdminController::class, 'staff'])->name('admin.staff');
    // Route::get('/admin/reservations', [App\Http\Controllers\AdminController::class, 'reservations'])->name('admin.reservations');

    //La gestion des chambres.
    // Route::put('/admin/chambres/{id}', [ChambreControllerAdmin::class, 'update']);
    // Route::post('/admin/chambres', [ChambreControllerAdmin::class, 'update']);
    // Route::put('/admin/chambres/{id}', [ChambreControllerAdmin::class, 'update']);
    // Dans web.php
    // Route::match(['put', 'post'], '/admin/chambres/{id}', [ChambreControllerAdmin::class, 'update'])->name('admin.chambres.update');

// Route::put('/admin/chambres/{id}', [ChambreControllerAdmin::class, 'update']);
// Route::post('/admin/chambres/{id}', [ChambreControllerAdmin::class, 'update']); // Pour la compatibilité avec fetch
    Route::post('/admin/chambres/update', [ChambreControllerAdmin::class, 'update'])->name('admin.chambres.update');
    Route::delete('/admin/chambres/{id}', [ChambreControllerAdmin::class, 'destroy'])->name('destroy');
    Route::post('/admin/chambres', [ChambreControllerAdmin::class, 'store']);
    Route::get('/admin/chambres', [ChambreControllerAdmin::class, 'index'])->name('admin.rooms');


    //La gestion des personnels.
    Route::get('/admin/staff', [ReceptionnisteControllerAdmin::class, 'index'])->name('admin.staff');
    Route::post('/admin/staff/update', [ReceptionnisteControllerAdmin::class, 'update'])->name('admin.staff.update');
    Route::post('/admin/staff/add', [ReceptionnisteControllerAdmin::class, 'store'])->name('admin.staff.add');
    // Route::delete('/admin/staff/{id}', [ReceptionnisteControllerAdmin::class, 'destroy'])->name('admin.staff.delete');
    // Par celle-ci (qui utilise POST avec un paramètre _method)
    Route::post('/admin/staff/delete', [ReceptionnisteControllerAdmin::class, 'destroy'])->name('admin.staff.delete');
    // Route de test :
    // Route::post('/admin/test-delete', function (\Illuminate\Http\Request $request) {
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Test suppression réussie',
    //         'id_recu' => $request->id,
    //     ]);
    // });

    Route::get('/admin/reservations', [ReservationControllerAdmin::class, 'index'])->name('admin.reservations');
    Route::get('/admin/reservations/{id}', [ReservationControllerAdmin::class, 'viewChambre'])->name('getChambreDetails');
    // Route::post('/admin/reservations/store', [ReservationControllerAdmin::class, 'store'])->name('admin.reservations.store');

    // Routes pour les services supplémentaires
    Route::get('/admin/services', [ServiceSupplementaireControllerAdmin::class, 'index'])->name('admin.services');
    Route::post('/admin/services', [ServiceSupplementaireControllerAdmin::class, 'store'])->name('admin.services.store');
    Route::put('/admin/services/{id}', [ServiceSupplementaireControllerAdmin::class, 'update'])->name('admin.services.update');
    Route::delete('/admin/services/{id}', [ServiceSupplementaireControllerAdmin::class, 'destroy'])->name('admin.services.destroy');

    // Pour la gestion du compte de l'admin
    Route::get('/admin/account', [AccountControllerAdmin::class, 'show'])->name('admin.account');
    Route::put('/admin/account', [AccountControllerAdmin::class, 'update'])->name('admin.account.update');
    // Route::get('/admin/account', [AccountControllerAdmin::class, 'show'])->name('admin.account');
    // Route::get('/admin/account', [App\Http\Controllers\Admin\AccountControllerAdmin::class, 'show'])->name('admin.account');
    // Route::put('/admin/account', [App\Http\Controllers\Admin\AccountControllerAdmin::class, 'update'])->name('admin.account.update');
    // Route::get('/admin/account', [AccountControllerAdmin::class, 'show'])->name('admin.account');
    // Route::put('/admin/account', [AccountControllerAdmin::class, 'update'])->name('admin.account.update');






    // Route::get('/admin/chambres/{id}/edit', [ChambreController::class, 'edit'])->name('chambres.edit');

    // Route::delete('/admin/chambres/{id}', [ChambreController::class, 'destroy'])->name('chambres.destroy');
    // Route::put('/admin/chambres/{id}', [ChambreController::class, 'update'])->name('chambres.update');

});
// Route::middleware(['auth', 'role:admin'])->group(function () {

// });






// Réceptionniste uniquement
// Routes pour le réceptionniste
Route::middleware(['auth', 'role:recep'])->group(function () {
    // Dashboard
        // Route::get('/reception/dashboard', [ReceptionController::class, 'dashboard'])->name('reception.dashboard');
    // Dashboard
    // Route::get('/reception/dashboard', [ReceptionController::class, 'dashboard'])->name('reception.dashboard');

    // Route pour le dashboard qui redirige vers les chambres
    Route::get('/reception/dashboard', function () {
        return redirect()->route('reception.chambres.disponibles');
    })->name('reception.dashboard');

    // Chambres
    Route::get('/reception/chambres/disponibles', [ReceptionController::class, 'chambresDisponibles'])->name('reception.chambres.disponibles');
    Route::get('/reception/chambres/disponibles', [ReceptionController::class, 'filtrerChambres'])->name('reception.chambres.disponibles');
    // Route::get('/reception/chambres/disponibles', [ReceptionController::class, 'getChambresDisponibles']);
    // Route::get('/reception/chambres/{id}', [ReceptionController::class, 'getChambreDetails']);

    Route::get('/reception/reservations/create', [ReceptionController::class, 'createReservation'])->name('reception.reservations.create');
    // Route::post('/reception/reservations', [ReservationController::class, 'store'])->name('reception.reservations.store');



    // Réservations
    Route::get('/reception/reservations', [ReceptionController::class, 'indexReservations'])->name('reception.reservations');
    Route::get('/reception/reservations/create', [ReceptionController::class, 'createReservation'])->name('reception.reservations.create');
    // Route::post('/reception/reservations', [ReceptionController::class, 'storeReservation'])->name('reception.reservations.store');

    //Pour la facture:
    // Route::get('/reception/reservations/confirmation', [FactureControllerReceptionniste::class, 'showConfirmReception'])->name('reception.confirmation');
    // Route pour le FactureControllerReceptionniste
    // Route::get('/reception/reservations/confirmation', [FactureControllerReceptionniste::class, 'showConfirmReception'])
    //     ->name('reception.confirmation');
    Route::get('/reception/reservations/confirmation', [FactureControllerReceptionniste::class, 'showConfirmReception'])
    ->name('reception.confirmation');

    // Route::get('/reception/reservations/confirmation', function(Request $request) {
    //     return view('reception.reservations.confirmation');
    // })->name('reception.confirmation');

    // Route pour télécharger la facture
    Route::get('/facture/telecharger/{id}', [FactureControllerReceptionniste::class, 'download'])
        ->name('facture.download');


    // Route::get('/reception/reservations/confirmation', function () {
    //     return view('reception.reservations.confirmation');
    // })->name('reception.confirmation');
    // Route::get('/reception/reservations/confirmation', [ReceptionController::class, 'confirmation'])
    // ->name('reception.confirmation');


    Route::post('/reception/reservations', [ReceptionController::class, 'storeReservation'])->name('reception.reservations.store');
    // Route::get('/reception/reservations/{id}', [ReceptionContro ller::class, 'showReservation'])->name('reception.reservations.show');
    // Route::put('/reception/reservations/{id}/status', [ReceptionController::class, 'updateStatus'])->name('reception.reservations.update-status');
    // Route::put('/reception/reservations/{id}/cancel', [ReceptionController::class, 'cancelReservation'])->name('reception.reservations.cancel');
    // Route::post('/reception/reservations/{id}/notes', [ReceptionController::class, 'addNote'])->name('reception.reservations.add-note');
    Route::get('/reception/reservations', [ReservationControllerReceptionniste::class, 'index'])->name('reception.reservations');
    Route::put('/reception/reservations/update', [ReservationControllerReceptionniste::class, 'update'])->name('reception.reservations.update');
    Route::delete('/reception/reservations/destroy', [ReservationControllerReceptionniste::class, 'destroy'])->name('reception.reservations.destroy');

    // Route::post('/reception/reservations', [ReceptionController::class, 'storeReservation'])->name('reception.reservations.store');

    // API pour la recherche de clients
    Route::get('/reception/api/clients', [ReceptionController::class, 'searchClients']);
    Route::get('/reception/api/chambres/{id}', [ReceptionController::class, 'getChambreDetails']);

});
// Route::middleware(['auth', 'role:recep'])->group(function () {
//     Route::get('/reception/dashboard', [ReceptionController::class, 'dashboard'])->name('reception.dashboard');
// });




/* Route::get('/test-pdf', function() {
    // Utilisez la façade (recommandé)
    $pdf = Pdf::loadHTML('<h1>Test PDF</h1>');

    $path = storage_path('app/public/test.pdf');
    $pdf->save($path);

    if (file_exists($path)) {
        return response()->download($path, 'test.pdf');
    } else {
        return "Échec de création du PDF";
    }
}); */



Route::get('/preview-template', function() {
    return view('factures.template', [
        'reservation' => App\Models\Reservation::first(),
        'facture' => [
            'numero_facture' => 'PREVIEW',
            'date_emission' => now(),
            'montant_total' => 100
        ]
    ]);
});

// Créez une route de test pour visualiser le template
/* Route::get('/test-template', function() {
    // Récupérer une réservation existante
    $reservation = App\Models\Reservation::first();

    // Récupérer manuellement les données
    $chambres = DB::table('historiques')
        ->join('chambres', 'historiques.chambre_id', '=', 'chambres.id')
        ->where('historiques.reservation_id', $reservation->id)
        ->select('chambres.*')
        ->get();

    $services = DB::table('posseders')
        ->join('supplementaires', 'posseders.supplementaire_id', '=', 'supplementaires.id')
        ->where('posseders.reservation_id', $reservation->id)
        ->select('supplementaires.*')
        ->get();

    $client = DB::table('clients')
        ->where('id', $reservation->client_id)
        ->first();

    // Afficher le template dans le navigateur
    return view('factures.template', [
        'facture' => [
            'numero_facture' => 'TEST-123',
            'date_emission' => now(),
            'montant_total' => $reservation->totalPayer
        ],
        'reservation' => $reservation,
        'client' => $client,
        'chambres' => $chambres,
        'services' => $services,
        'adultsCount' => 2,
        'childrenCount' => 1,
        'dateDebut' => $reservation->dateDeb,
        'dateFin' => $reservation->dateFin,
    ]);
});

Route::get('/test-pdf-generation/{id}', function($id) {
    try {
        // Récupérer la facture et la réservation
        $facture = App\Models\Facture::findOrFail($id);
        $reservation = App\Models\Reservation::findOrFail($facture->reservation_id);

        // Récupérer les données manuellement
        $chambres = DB::table('historiques')
            ->join('chambres', 'historiques.chambre_id', '=', 'chambres.id')
            ->where('historiques.reservation_id', $reservation->id)
            ->select('chambres.*')
            ->get();

        $services = DB::table('posseders')
            ->join('supplementaires', 'posseders.supplementaire_id', '=', 'supplementaires.id')
            ->where('posseders.reservation_id', $reservation->id)
            ->select('supplementaires.*')
            ->get();

        $client = DB::table('clients')
            ->where('id', $reservation->client_id)
            ->first();

        // Décodez les détails
        $details = json_decode($facture->details, true) ?? [];

        // Créer le PDF
        $pdf = Pdf::loadView('factures.template', [
            'facture' => $facture,
            'reservation' => $reservation,
            'client' => $client,
            'chambres' => $chambres,
            'services' => $services,
            'adultsCount' => $details['adultsCount'] ?? 0,
            'childrenCount' => $details['childrenCount'] ?? 0,
            'dateDebut' => $reservation->dateDeb,
            'dateFin' => $reservation->dateFin,
        ]);

        // Définir le chemin de sauvegarde
        $path = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'factures'.DIRECTORY_SEPARATOR.'facture-'.$facture->numero_facture.'.pdf');

        // Créer le répertoire
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Sauvegarder le PDF
        $pdf->save($path);

        return response()->json([
            'success' => true,
            'path' => $path,
            'file_exists' => file_exists($path),
            'file_size' => file_exists($path) ? filesize($path) : 0
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
}); */




// Dans routes/web.php
//Route::get('/facture/telecharger/{id}', FactureController::class,"download")->name('facture.download');
Route::get('/facture/telecharger/{id}', [FactureController::class,"download"])->name('facture.download');



//Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile')->middleware('auth');

//whenever an unauth. person tries to access those views, will be directly redirected to the '/login' by default
Route::middleware(['auth'/*, 'RoleMiddleware:client' !!ça ne marche pas*/])->group(function () {
//j'ai mis client pour pouvoir accéder à tous les pages avant de bien assigner la tâche de chaque utilisateur de l'application



Route::get('/chambres/disponibles', [ChambreController::class, 'getChambresDisponibles']);
Route::get('/chambres/{id}', [ChambreController::class, 'getChambre']);

    Route::get("/reservation", [ReservationController::class, "createForm"])->name("reservation");

    Route::post('/reservation/store', [ReservationController::class, 'storeOnLine'])->name('reservation.store.online');

    //cofirmation de la réservation
    Route::get('/reservation/confirmation',[FactureController::class,"showConfirm"])->name("reservation.confirm");

    Route::get('/commentaires', [CommentaireController::class, 'index'])->name('commentaires.index');

    //la liste des réservation faites avec un filtre pour savoir les réservations qui ont été effectuer en ligne
    Route::get('/reservation/display',[ReservationController::class,'index'])
    ->name('reservation.liste');

    Route::post('/reservation/destroy/{id}', [ReservationController::class, 'destroy'])
    ->name('reservation.destroy');

    Route::get('/reservation/{id}/edit',[ReservationController::class,'update'])
    ->name('reservation.edit');

    //Route::get("/reservation", [ReservationController::class, "createForm"])
    //->name("reservation");


    Route::put('/reservation/edit/client/info/{id}',[ReservationController::class,'edit'])
    ->name('edit.client');

    Route::get('/reservation/client/detail/{id}',[ReservationController::class,'displayClient'])
    ->name('reservation.client');

    Route::get('/reservation/{id}/edit/date', [ReservationController::class, 'editDate'])
    ->name('reservation.editDate');

    Route::put('/reservation/{id}/update/date', [ReservationController::class, 'updateDate'])
    ->name('reservation.updateDate');

    Route::get('/reservation/{id}/paiement', [ReservationController::class, 'afficherPaiement'])
    ->name('reservation.paiement');

    Route::put('/reservation/{id}/paiement', [ReservationController::class, 'validerPaiement'])
    ->name('reservation.paiement.valider');





    //manip chambres
    Route::get('/chambre',[ChambreController::class,'index'])->name('chambre.index');

    Route::get('/chambre/form',[ChambreController::class,'create'])->name('chambre.form');

    Route::post('/chambre/add', [ChambreController::class, 'store'])->name('chambre.store');

    Route::get('/chambre/{id}/edit',[ChambreController::class,'edit'])->name('chambre.edit');

    Route::put('/chambre/{id}', [ChambreController::class, 'update'])->name('chambre.update');

    Route::post('/chambre/{id}', [ChambreController::class, 'destroy'])->name('chambre.destroy');

    //manip service supp
    Route::get('/supp',[SupplementaireController::class,'index'])->name('supp.index');

    Route::get('/supp/form',[SupplementaireController::class,'create'])->name('supp.form');

    Route::post('/supp/add',[SupplementaireController::class,'store'])->name("supp.store");

    Route::get('/supp/{id}/edit',[SupplementaireController::class,'edit'])->name('supp.edit');

    Route::put('/supp/{id}', [SupplementaireController::class, 'update'])->name('supp.update');

    Route::post('/supp/{id}', [SupplementaireController::class, 'destroy'])->name('supp.destroy');

    //manip client
    Route::get('/client/form',[ClientController::class,'show'])->name('client.form.reservation');

    Route::post('/client/form/create',[ClientController::class,'store'])->name('client.form.create');

    //manip reservations
    Route::post('/reserver',[ReservationController::class,'create'])->name('reserver.post');

    //Route::post('/reservation/store', [ReservationController::class, 'store'])->name('reservation.store');

    //manip categories
    Route::get('/chambre/categorie/', [CategorieController::class,'index'])->name('categorie.index');

    Route::get('/chambre/categorie/add', [CategorieController::class,'store'])->name("categorie.add");

    Route::post('/chambre/categorie/form', [CategorieController::class,'create'])->name('categorie.form');

    Route::get('/categorie/{id}/edit',[CategorieController::class,'edit'])->name('categorie.edit');

    Route::delete('/categorie/{id}', [CategorieController::class, 'destroy'])->name('categorie.destroy');

    Route::put('/categorie/{id}', [CategorieController::class, 'update'])->name('categorie.update');

    //create employees by the admin
    Route::get('/admin/recep/form', [AdminController::class,'create'])->name('admin.form');

    Route::post('/admin/recep/add', [AdminController::class,'store'])->name('admin.store');



    //log out
    Route::post( "/logout", [AuthController::class,"logoutPost"])->name("logout.post");

});
