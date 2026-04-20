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
                <a class="nav-link text-white" href="/profil">Profil</a>
                @auth 
                    <a class="nav-link text-white" href="/rechercheAppareil">Appareils</a>
                    <a class="nav-link text-white" href="/logout"> Déconnexion</a>
                @endauth
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