<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\RedirectResponse;


class AuthController extends Controller{
    public function login(){
        return view("auth.login");
    }

    public function loginPost(Request $request){
        //validation des données et on exige que l'email doit avoir une forme valide 
        $request->validate([
            "email"=> ["required","email"],
            "password"=> ["required"],
        ]);

        //on récupère les identifiants
        $credentials=$request->only("email","password");

        //on compare les données entrées par celle qui se trouve dans la BD
        if(Auth::attempt($credentials)){
            //with(): permet de passer des paramètres par le biais d'une variable de session , ça sera session(success)
            return redirect("/chambre")->with("success","Successfuly logged in");
        }
        return redirect("/login")->with("error","Try again");
    }
    public function register(){
        return view("auth.register");
    }
    public function registerPost(Request $request){
        $request->validate([
            "name"=>["required"],
            "email"=> ["required","email"],
            "password"=> ["required"],
        ]);
        //on créé un utilisateur
        $user = new User();
        //on remplit les champs dans la BD
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);//crypter le mot de passe
        if( $user->save() ){	
            return redirect("/chambre")->with("success","User created successfuly");
        }
        return redirect("/register")->with("error","Failed to create an account");
    }
    public function logoutPost(Request $request): RedirectResponse{
        // on supprime l'auth, mais la session est tjrs là
        Auth::logout();    

        //suppression des données stockées dans la session
        $request->session()->invalidate();
        //regeneration d'un jeton CSRF
        $request->session()->regenerateToken();
        return redirect('/login');
}
}
