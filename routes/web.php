<?php

use App\Models\Client;
use App\Models\Reservation;
use App\Models\Supplementaire;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\ChambreController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SupplementaireController;

Route::get("/", function () {   return view("welcome");});

Route::get( "/login", [AuthController::class,"login"])->name("login");

Route::post( "/login", [AuthController::class,"loginPost"])->name("login.post");

Route::get("/register", [AuthController::class,"register"])->name("register");

Route::post( "/register", [AuthController::class,"registerPost"])->name("register.post");



//whenever an unauth. person tries to access those views, will be directly redirected to the '/login' by default 
Route::middleware(['auth'/*, 'RoleMiddleware:client' !!ça ne marche pas*/])->group(function () {
//j'ai mis client pour pouvoir accéder à tous les pages avant de bien assigner la tâche de chaque utilisateur de l'application 

    //la liste des réservation faites avec un filtre pour savoir les réservations qui ont été effectuer en ligne
    Route::get('/reservation/display',[ReservationController::class,'index'])
    ->name('reservation.liste');

    Route::post('/reservation/destroy/{id}', [ReservationController::class, 'destroy'])
    ->name('reservation.destroy');

    Route::get('/reservation/{id}/edit',[ReservationController::class,'update'])
    ->name('reservation.edit');
    
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

    Route::post('/client/form/create',[ClientController::class,'store'])
    ->name('client.form.create');

    //manip reservations
    Route::post('/reserver',[ReservationController::class,'create'])->name('reserver.post');

    Route::post('/reservation/store', [ReservationController::class, 'store'])->name('reservation.store');


    //strip's route
    Route::get('/checkout', [StripeController::class, 'checkout'])
    ->name('payment.checkout');
    Route::post('/session', [StripeController::class, 'session'])
    ->name('payment.session');
    Route::get('/success', [StripeController::class, 'success'])
    ->name('payment.success');
    Route::get('/cancel', [StripeController::class, 'cancel'])
    ->name('payment.cancel');

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