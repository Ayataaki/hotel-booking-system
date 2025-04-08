<?php

use App\Models\Client;
use App\Models\Reservation;
use App\Models\Supplementaire;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
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
Route::middleware(['auth'])->group(function () {


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

    Route::post('/reservation/store', [ReservationController::class, 'store'])->name('reservation.store');

    Route::get('/check-client', [ReservationController::class, 'checkClient'])->name('client.search');

    //manip client
    Route::get('/clients/search', [ClientController::class, 'search'])->name('client.search');


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