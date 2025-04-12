<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Models\Receptionniste;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.index");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        
        $user = User::create([
            'name' => $request->input('nomRec') . ' ' . $request->input('prenomRec'),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'userType'=>'recep',
        ]);

        $recep=new Receptionniste();
        $recep->nomRec=$request->input('nomRec');
        $recep->prenomRec=$request->input('prenomRec');
        $recep->CIN=$request->input('CIN');
        $recep->numTel=$request->input('numTel');
        $recep->user_id=$user->id;
        $recep->save();



        return redirect()->route('admin.form')->with('success','');



    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        //
    }
}
