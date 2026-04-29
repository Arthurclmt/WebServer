<?php

namespace App\Http\Controllers;

use App\Models\User;

class MembresController extends Controller
{
    //Fonction pour trouver tous les membres
    public function index()
    {
        $membres = User::orderBy('pseudo')->get();
        return view('membres.index', compact('membres'));
    }

    //Fonction pour afficher tous les membres
    public function show(User $user)
    {
        return view('membres.show', compact('user'));
    }
}
