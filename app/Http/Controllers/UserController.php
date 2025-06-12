<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Country;

class UserController extends Controller
{
   
    public function profile()
    {
        $user = Auth::user();
        $countries = Country::all(); 

        return view('profile', compact('user', 'countries'));
    }
}

