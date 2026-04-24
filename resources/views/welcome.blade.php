@extends('layouts.app')

@section('content')
    <div class="p-5 rounded" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
        <h1 class="display-4 fw-bold text-white">Bienvenue sur Asso Gaming</h1>
        <p class="lead" style="color: rgba(255,255,255,0.8);">La plateforme de gestion de notre association.</p>
        <a href="/register" class="btn btn-primary btn-lg me-2">Rejoindre l'asso</a>
        <a href="/login" class="btn btn-outline-light btn-lg">Se connecter</a>
    </div>
@endsection