<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asso Gaming</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">Asso Gaming</a>
            <div class="navbar-nav ms-auto">
                @auth
                    <a class="nav-link text-white fw-bold" href="/profile">{{ Auth::user()->pseudo }}</a>
                    <form method="POST" action="/logout" class="d-flex align-items-center">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link text-white p-0">Déconnexion</button>
                    </form>
                @else
                    <a class="nav-link text-white" href="/login">Connexion</a>
                    <a class="nav-link text-white" href="/register">Inscription</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>