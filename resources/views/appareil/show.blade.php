@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                {{-- En-tête avec bouton retour --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">{{ $appareil->name }}</h2>
                    <a href="{{ route('appareils.index') }}" class="btn btn-outline-secondary btn-sm">
                        ← Retour à la liste
                    </a>
                </div>

                <div class="row">
                    {{-- Informations principales --}}
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Informations techniques</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Type :</strong> <span>{{ $appareil->type ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Marque :</strong> <span>{{ $appareil->brand ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Statut :</strong> 
                                @if($appareil->status == "actif")
                                    <span class="badge bg-success text-white">Actif / Connecté</span>
                                @elseif($appareil->status == 'maintenance')
                                    <span class="badge bg-warning text-dark">En maintenance</span>
                                @else
                                    <span class="badge bg-danger text-white">Inactif</span>
                                @endif
                            </li>
                        </ul>
                    </div>

                    {{-- Localisation et gestion --}}
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Localisation & Gestion</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Salle :</strong> 
                                <span>{{ $appareil->room->name ?? 'Aucune salle' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Ajouté par :</strong> 
                                <span>{{ $appareil->user->pseudo ?? 'Inconnu' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Date d'ajout :</strong> 
                                <span>{{ $appareil->created_at->format('d/m/Y') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Description --}}
                <div class="mt-4">
                    <h5>Description</h5>
                    <div class="p-3 bg-light rounded border">
                        {{ $appareil->description ?: 'Aucune description disponible pour cet appareil.' }}
                    </div>
                </div>

                {{-- Actions (Optionnel) --}}
                {{-- Actions admin --}}
                @if(auth()->check() && auth()->user()->role === "admin")
                    <div class="mt-4 d-flex gap-2 flex-wrap">
                        <a href="{{ route('appareil.edit', $appareil->id) }}" class="btn btn-primary">
                            ✏ Modifier
                        </a>
 
                        {{-- Toggle statut --}}
                        <form action="{{ route('appareil.toggleStatus', $appareil->id) }}" method="POST">
                            @csrf
                            @if($appareil->status === "actif")
                                <button type="submit" class="btn btn-warning">⏸ Désactiver</button>
                            @else
                                <button type="submit" class="btn btn-success">▶ Activer</button>
                            @endif
                        </form>
 
                        <form action="{{ route('appareil.destroy', $appareil->id) }}" method="POST"
                              onsubmit="return confirm('Supprimer définitivement cet appareil ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">🗑 Supprimer</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Fenêtre de Félicitations --}}
@if(session('level_up'))
    <div class="modal fade show" id="levelModal" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <h2 class="text-primary">✨ Bravo ! ✨</h2>
                    <p>Tu passes au niveau :</p>
                    <h3 class="fw-bold">{{ ucfirst(session('level_up')) }}</h3>
                    
                    <hr>
                    
                    <a href="{{ url()->current() }}" class="btn btn-success w-100">Continuer</a>
                </div>
            </div>
        </div>
    </div>
@endif