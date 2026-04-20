@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Appareils</title>
</head>
<body>

    <h1>Liste des appareils</h1>

    <!-- 🔍 Barre de recherche -->
    <form method="GET" action="/appareils">
        <input type="text" name="search" placeholder="Rechercher un appareil...">
        <button type="submit">Rechercher</button>
    </form>

    <br>

    @if($appareils->isEmpty())
        <p>Aucun appareil trouvé.</p>
    @else
        <table border="1">
            <tr>
                <th>Nom</th>
                <th>Statut</th>
            </tr>

            @foreach($appareils as $appareil)
                <tr>
                    <td>{{ $appareil->nom }}</td>
                    <td>
                        @if($appareil->statut == 'connecté')
                            <span style="color:green;">● Connecté</span>
                        @else
                            <span style="color:red;">● Déconnecté</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    @endif

</body>
</html>
@endsection