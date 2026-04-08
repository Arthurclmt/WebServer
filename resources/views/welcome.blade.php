@extends('layouts.app')

@section('content')
    <div class="p-5 bg-white rounded shadow-sm">
        <h1 class="display-4 fw-bold">Bienvenue sur Asso Gaming</h1>
        <p class="lead text-muted">La plateforme de gestion de notre association.</p>
        <a href="/register" class="btn btn-primary btn-lg me-2">Rejoindre l'asso</a>
        <a href="/login" class="btn btn-outline-secondary btn-lg">Se connecter</a>
    </div>
@endsection