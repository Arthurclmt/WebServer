<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appareil;


class AppareilController extends Controller{
    public function index(Request $request){
        $query = Appareil::query();

        // Recherche
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%'); 
        }

        $appareils = $query->get();

        return view('appareil.rechercheAppareil', compact('appareils'));
    }

    public function show($id)
{
    // On récupère l'appareil ou on renvoie une erreur 404 s'il n'existe pas
    $appareil = Appareil::findOrFail($id);

    return view('appareil.show', compact('appareil'));
}
}
