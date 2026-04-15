<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AllowedMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
        public function showRegister()
    {
        return view('auth.register');
    }
        
        public function register(Request $request)
    {
        $request->validate([
            'pseudo'         => 'required|unique:users,pseudo|max:50',
            'email'          => 'required|email|unique:users',
            'password'       => 'required|min:8',
            'date_naissance' => 'required|date',
            'genre'          => 'required',
        ]);

        $allowed = AllowedMember::where('email', $request->email)->first();

        if (!$allowed) {
            return back()->withErrors(['email' => 'Cet email n\'est pas autorisé à s\'inscrire.']);
        }

        User::create([
            'pseudo'         => $request->pseudo,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'date_naissance' => $request->date_naissance,
            'genre'          => $request->genre,
        ]);

        return redirect('/login')->with('success', 'Inscription réussie ! Connecte-toi.');
    }

        public function showLogin()
    {
        return view('auth.login');
    }

        public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required',
            'password' => 'required',
        ]);
        
        
        //Création des variables de sessions lors de la connexion de l'utilisateur.
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            return redirect('/dashboard');
        } else {
            return back()->withErrors(['email' => 'Email ou mot de passe incorrect.']);
        }
    }
    
    //
}