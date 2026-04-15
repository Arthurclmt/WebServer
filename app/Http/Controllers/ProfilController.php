<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AllowedMember;


class ProfilController extends Controller
{
        public function showProfil()
    {
        return view('profil');
    }
    
    //
}