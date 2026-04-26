<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asso Gaming</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">Asso Gaming</a>
            <div class="navbar-nav ms-auto">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('admin.users') }}">Gestion Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('stats.index') }}">Statistiques</a>
                         </li>
                    @endif
                    <a class="nav-link text-white fw-bold" href="/profile">{{ Auth::user()->pseudo }}</a>
                    <a class="nav-link text-white" href="/rechercheAppareil">Appareils</a>
                    <a class="nav-link text-white" href="/logout"> Déconnexion</a>
                @endauth
                <a class="nav-link text-white" href="{{ route('events.index') }}">Nos events</a>
                <a class="nav-link text-white" href="{{ route('news.index') }}">News</a>
                @guest
                    <a class="nav-link text-white" href="/login">Connexion</a>
                    <a class="nav-link text-white" href="/register">Inscription</a>
                @endguest
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>