@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">Liste des appareils</h2>
                        @if(auth()->check() && auth()->user()->role === "admin")
                            <a href="{{ route('appareil.create') }}" class="btn btn-primary btn-sm">+ Ajouter</a>
                        @endif
                    </div>

                    {{-- Formulaire Recherche + Filtres --}}
                    <form method="GET" action="{{ route('appareil.index') }}" class="mb-4">
                        <div class="row g-2">
                            {{-- Barre de recherche --}}
                            <div class="col-md-2">
                                <input type="text" name="search" class="form-control"
                                       placeholder="Nom, type, marque…"
                                       value="{{ request('search') }}">
                            </div>
                            
                            {{-- Filtre statut --}}
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">Tous les statuts</option>
                                    <option value="actif"       {{ request('status') == "actif"       ? 'selected' : '' }}>● Actif</option>
                                    <option value="inactif"     {{ request('status') == "inactif"     ? 'selected' : '' }}>● Inactif</option>
                                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>● Maintenance</option>
                                </select>
                            </div>
 
                            {{-- Filtre type --}}
                            <div class="col-md-2">
                                <select name="type" class="form-select">
                                    <option value="">Tous les types</option>
                                    @foreach($types as $t)
                                        <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>
 
                            {{-- Filtre marque --}}
                            <div class="col-md-2">
                                <select name="brand" class="form-select">
                                    <option value="">Toutes les marques</option>
                                    @foreach($brands as $b)
                                        <option value="{{ $b }}" {{ request('brand') == $b ? 'selected' : '' }}>{{ $b }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filtre salle --}}
                            <div class="col-md-2">
                                <select name="room" class="form-select">
                                    <option value="">Toutes les salles</option>
                                    @foreach($rooms as $r)
                                        <option value="{{ $r->id }}" 
                                            {{ request('room') == $r->id ? 'selected' : '' }}>
                                            {{ $r->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            {{-- Boutons --}}
                            <div class="col-md-2 d-flex gap-2">
                                <button class="btn btn-outline-primary flex-grow-1" type="submit">Filtrer</button>
                                <a href="{{ route('appareil.index') }}" class="btn btn-outline-secondary">✕</a>
                            </div>
                        </div>
                    </form>

                    @if($appareils->isEmpty())
                        <div class="alert alert-info text-center">
                            Aucun appareil trouvé.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Type</th>
                                        <th>Marque</th>
                                        <th>Salle</th>
                                        <th>Statut</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appareils as $appareil)
                                        <tr>
                                            <td>
                                                <a href="{{route('appareil.show', $appareil->id) }}" class="text-decoration-none fw-bold text-primary">
                                                    {{ $appareil->name }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">{{ $appareil->type }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">{{ $appareil->brand }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">{{ optional($appareil->room)->name ?? 'Aucune salle' }}</span>
                                            </td>
                                            <td>
                                                @if($appareil->status == "actif")
                                                    <span class="text-success small fw-bold">● Connecté</span>
                                                @elseif($appareil->status == "maintenance")
                                                    <span class="text-warning small fw-bold">● Maintenance</span>
                                                @else
                                                    <span class="text-danger small fw-bold">● Déconnecté</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-1">
                                                    <a href="{{ route('appareil.show', $appareil->id) }}"
                                                       class="btn btn-sm btn-light border">Voir</a>
 
                                                    @if(auth()->check() && auth()->user()->role === "admin")
                                                        {{-- Toggle actif/inactif --}}
                                                        <form action="{{ route('appareil.toggleStatus', $appareil->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="btn btn-sm {{ $appareil->status === "actif" ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                                    title="{{ $appareil->status === "actif" ? 'Désactiver' : 'Activer' }}">
                                                                {{ $appareil->status === "actif" ? '⏸' : '▶' }}
                                                            </button>
                                                        </form>
 
                                                        <a href="{{ route('appareil.edit', $appareil->id) }}"
                                                           class="btn btn-sm btn-outline-primary">✏</a>

                                                        <a href="{{ route('appareil.editConfig', $appareil->id) }}"
                                                                class="btn btn-sm btn-outline-primary">⚙️</a>

                                                        <form action="{{ route('appareil.destroy', $appareil->id) }}"
                                                              method="POST"
                                                              onsubmit="return confirm('Supprimer « {{ $appareil->name }} » ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">🗑</button>
                                                        </form>
                                                        {{-- Cellule indicateur demandes de suppression --}}
                                                        <td class="text-center">
                                                            @if($appareil->delete_request_number > 0)
                                                                <span class="badge bg-danger" title="{{ $appareil->delete_request_number }} demande(s) de suppression">
                                                                    {{ $appareil->delete_request_number }} 🗑📨
                                                                </span>
                                                            @else
                                                                <span class="text-muted">—</span>
                                                            @endif
                                                        </td>

                                                    @elseif(auth()->check() && auth()->user()->role === "simple")
                                                        @if(in_array(auth()->id(), $appareil->delete_requested_by ?? []))
                                                            <span class="text-muted small">⏳ Demandé</span>
                                                        @else
                                                            <form action="{{ route('appareil.requestDelete', $appareil->id) }}" method="POST"
                                                                onsubmit="return confirm('Demander la suppression de « {{ $appareil->name }} » ?')">
                                                                @csrf
                                                                <button type="submit" class="btn btn-outline-danger btn-sm">🗑️</button>
                                                            </form>
                                                        @endif

                                                    @endif
                                                </div>
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

    {{-- Surveillance et optimisation --}}
    <div class="row justify-content-center mt-4">
        <div class="col-md-11">

            {{-- Consommation --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header fw-bold">Rapport de consommation énergétique</div>
                <div class="card-body p-0">
                    @if($parConsommation->isEmpty())
                        <p class="text-muted p-3 mb-0">Aucun appareil avec des données de consommation.</p>
                    @else
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Appareil</th>
                                    <th>Puissance (W)</th>
                                    <th>Utilisation/jour (min)</th>
                                    <th>Conso/jour (Wh)</th>
                                    <th>Conso/semaine (Wh)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($parConsommation as $a)
                                <tr>
                                    <td><a href="{{ route('appareil.show', $a->id) }}" class="text-decoration-none fw-bold">{{ $a->name }}</a></td>
                                    <td>{{ $a->consumption }} W</td>
                                    <td>{{ $a->usage_time }} min</td>
                                    <td>{{ $a->wh_jour }} Wh</td>
                                    <td>{{ $a->wh_semaine }} Wh</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            {{-- Inefficaces --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header fw-bold">Appareils nécessitant attention</div>
                <div class="card-body p-0">
                    @if($inefficaces->isEmpty())
                        <p class="text-muted p-3 mb-0">Aucun appareil en maintenance ou signalé.</p>
                    @else
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Appareil</th>
                                    <th>Statut</th>
                                    <th>Raison</th>
                                    <th>Demandes de suppression</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inefficaces as $a)
                                <tr>
                                    <td><a href="{{ route('appareil.show', $a->id) }}" class="text-decoration-none fw-bold">{{ $a->name }}</a></td>
                                    <td>
                                        @if($a->status === 'maintenance')
                                            <span class="badge bg-warning text-dark">Maintenance</span>
                                        @elseif($a->status === 'inactif')
                                            <span class="badge bg-secondary">Inactif</span>
                                        @else
                                            <span class="badge bg-success">Actif</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($a->status === 'maintenance')
                                            <span class="text-warning">⚠ En maintenance</span>
                                        @elseif($a->status === 'inactif')
                                            <span class="text-secondary">⏸ Appareil inactif</span>
                                        @elseif($a->wh_jour !== null && $a->wh_jour > 500)
                                            <span class="text-danger">⚡ Consommation élevée ({{ $a->wh_jour }} Wh/jour)</span>
                                        @elseif($a->delete_request_number >= 2)
                                            <span class="text-danger">🗑 Signalé par plusieurs utilisateurs</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($a->delete_request_number > 0)
                                            <span class="badge bg-danger">{{ $a->delete_request_number }} demande(s)</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection