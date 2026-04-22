@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">Liste des appareils</h2>
                        @if(auth()->check() && auth()->user()->role === "admin")
                            <a href="{{ route('appareil.create') }}" class="btn btn-primary btn-sm">+ Ajouter</a>
                        @endif
                    </div>

                    {{-- Formulaire Recherche + Filtres --}}
                    <form method="GET" action="{{ route('appareils.index') }}" class="mb-4">
                        <div class="row g-2">
                            {{-- Barre de recherche --}}
                            <div class="col-md-4">
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
 
                            {{-- Boutons --}}
                            <div class="col-md-2 d-flex gap-2">
                                <button class="btn btn-outline-primary flex-grow-1" type="submit">Filtrer</button>
                                <a href="{{ route('appareils.index') }}" class="btn btn-outline-secondary">✕</a>
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
                                <thead class="table-light">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Type</th>
                                        <th>Marque</th>
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
 
                                                        <form action="{{ route('appareil.destroy', $appareil->id) }}"
                                                              method="POST"
                                                              onsubmit="return confirm('Supprimer « {{ $appareil->name }} » ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">🗑</button>
                                                        </form>
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
</div>
@endsection