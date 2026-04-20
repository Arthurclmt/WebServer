@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">Liste des appareils</h2>
                        {{-- Lien vers l'ajout si besoin --}}
                        <a href="#" class="btn btn-primary btn-sm">+ Ajouter</a>
                    </div>

                    <form method="GET" action="/rechercheAppareil" class="mb-4">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Rechercher un appareil (nom, type, marque...)" value="{{ request('search') }}">
                            <button class="btn btn-outline-primary" type="submit">Rechercher</button>
                        </div>
                    </form>

                    @if($appareils->isEmpty())
                        <div class="alert alert-info text-center">
                            Aucun appareil trouvé.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appareils as $appareil)
                                        <tr>
                                            <td>
                                                <a href="/appareil/{{ $appareil->id }}" class="text-decoration-none fw-bold text-primary">
                                                    {{ $appareil->name }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">{{ $appareil->type }}</span>
                                            </td>
                                            <td>
                                                @if($appareil->status == 'actif')
                                                    <span class="text-success small fw-bold">● Connecté</span>
                                                @else
                                                    <span class="text-danger small fw-bold">● Déconnecté</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="/appareil/{{ $appareil->id }}" class="btn btn-sm btn-light border">Voir</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection