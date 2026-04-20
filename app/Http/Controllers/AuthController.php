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
        $role = $allowed ? 'admin' : 'simple';

        User::create([
            'pseudo'         => $request->pseudo,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'date_naissance' => $request->date_naissance,
            'genre'          => $request->genre,
            'role'           => $role,
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
    
    public function showProfile()
    {
        return view('profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'pseudo'         => 'required|unique:users,pseudo,' . $user->id . '|max:50',
            'date_naissance' => 'nullable|date',
            'genre'          => 'nullable',
            'password'       => 'nullable|min:8',
        ]);

        $user->pseudo         = $request->pseudo;
        $user->date_naissance = $request->date_naissance;
        $user->genre          = $request->genre;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil mis à jour.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/');
    }
}
