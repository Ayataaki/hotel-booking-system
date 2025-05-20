<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountControllerAdmin extends Controller
{
    public function show()
    {
        return view('admin.account');
    }

    public function update(Request $request)
    {
        // Pour tester
        return redirect()->route('admin.account')->with('success', 'Test');
    }
}
