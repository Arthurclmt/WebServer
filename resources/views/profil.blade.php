@extends('layouts.app')

@section('content')
<!-- Les informations de l'user (Auth::user() sont déterminé à la connexion dans
 AuthController.php)-->
    <h1>Mon Profil</h1>

    @auth
        <p><strong>Pseudo :</strong> {{ Auth::user()->pseudo }}</p>
        <p><strong>Email :</strong> {{ Auth::user()->email }}</p>
        <p><strong>Genre :</strong> {{ Auth::user()->genre }}</p>

        <br>

        <a href="/dashboard">Retour au dashboard</a>

        <br><br>

        <form method="POST" action="/logout">
            @csrf
            <button type="submit">Se déconnecter</button>
        </form>
    @endauth
<!--Si l'utilisateur est déconnecté on affiche une vue différente-->
    @guest
        <p>Vous n'êtes pas connecté.</p>
        <a href="/login">Se connecter</a>
    @endguest
@endsection