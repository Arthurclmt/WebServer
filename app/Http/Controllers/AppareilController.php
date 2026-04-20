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

        return view('rechercheAppareil', compact('appareils'));
    }
}
