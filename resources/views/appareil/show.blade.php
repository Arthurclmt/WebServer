@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                {{-- En-tête avec bouton retour --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">{{ $appareil->name }}</h2>
                    <a href="{{ route('appareil.index') }}" class="btn btn-outline-secondary btn-sm">
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

                {{-- Paramètres de configuration --}}
                @if($appareil->start_hour || $appareil->end_hour || $appareil->usage_time || $appareil->consumption)
                <div class="mt-4">
                    <h5>⚙️ Paramètres d'utilisation</h5>
                    <div class="row g-2">
                        @if($appareil->start_hour || $appareil->end_hour)
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                                <span>🕐 <strong>Plage horaire</strong></span>
                                <span class="text-primary fw-semibold">
                                    {{ $appareil->start_hour ? \Carbon\Carbon::parse($appareil->start_hour)->format('H:i') : '—' }}
                                    →
                                    {{ $appareil->end_hour ? \Carbon\Carbon::parse($appareil->end_hour)->format('H:i') : '—' }}
                                </span>
                            </div>
                        </div>
                        @endif
                        @if($appareil->usage_time)
                        <div class="col-md-3">
                            <div class="p-3 bg-light rounded border text-center">
                                <div class="text-muted small">Temps max</div>
                                <div class="fw-bold">{{ intdiv($appareil->usage_time, 60) > 0 ? intdiv($appareil->usage_time, 60).'h' : '' }}{{ $appareil->usage_time % 60 > 0 ? $appareil->usage_time % 60 .'min' : '' }}</div>
                            </div>
                        </div>
                        @endif
                        @if($appareil->consumption)
                        <div class="col-md-3">
                            <div class="p-3 bg-light rounded border text-center">
                                <div class="text-muted small">Consommation</div>
                                <div class="fw-bold">{{ $appareil->consumption }} W</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Paramètres de configuration --}}
                @if($appareil->start_hour || $appareil->end_hour || $appareil->usage_time || $appareil->consumption)
                <div class="mt-4">
                    <h5>⚙️ Paramètres d'utilisation</h5>
                    <ul class="list-group list-group-flush">
                        @if($appareil->start_hour || $appareil->end_hour)
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Plage horaire :</strong>
                            <span>{{ $appareil->start_hour ? \Carbon\Carbon::parse($appareil->start_hour)->format('H:i') : '—' }}
                            → {{ $appareil->end_hour ? \Carbon\Carbon::parse($appareil->end_hour)->format('H:i') : '—' }}</span>
                        </li>
                        @endif
                        @if($appareil->usage_time)
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Temps max :</strong>
                            <span>{{ intdiv($appareil->usage_time, 60) > 0 ? intdiv($appareil->usage_time, 60).'h ' : '' }}{{ $appareil->usage_time % 60 > 0 ? $appareil->usage_time % 60 .'min' : '' }}</span>
                        </li>
                        @endif
                        @if($appareil->consumption)
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Consommation :</strong>
                            <span>{{ $appareil->consumption }} W</span>
                        </li>
                        @endif
                    </ul>
                </div>
                @endif

                {{-- Actions admin --}}
                @if(auth()->check() && auth()->user()->role === "admin")
                    <div class="mt-4 d-flex gap-2 flex-wrap align-items-center">
                        <a href="{{ route('appareil.edit', $appareil->id) }}" class="btn btn-primary">✏️ Modifier</a>
                        <a href="{{ route('appareil.editConfig', $appareil->id) }}" class="btn btn-outline-info">⚙️ Configuration</a>

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
                            <button type="submit" class="btn btn-outline-danger">🗑️ Supprimer</button>
                        </form>

                        {{-- Indicateur demandes de suppression --}}
                        @if($appareil->delete_request_number > 0)
                            <span class="badge bg-danger fs-6 ms-2">
                                🗑️ {{ $appareil->delete_request_number }} demande(s) de suppression
                            </span>
                        @endif
                    </div>
                @endif

                {{-- Actions utilisateurs simples --}}
                @if(auth()->check() && auth()->user()->role !== "admin")
                    <div class="mt-4 border-top pt-3">
                        @if(in_array(auth()->id(), $appareil->delete_requested_by ?? []))
                            <p class="text-muted small mb-0">⏳ Vous avez déjà demandé la suppression de cet appareil.</p>
                        @else
                            <form action="{{ route('appareil.requestDelete', $appareil->id) }}" method="POST"
                                onsubmit="return confirm('Signaler cet appareil pour suppression ?')">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    🗑️ Demander la suppression
                                </button>
                            </form>
                        @endif
                        @if(session('info'))
                            <div class="alert alert-info mt-2">{{ session('info') }}</div>
                        @endif
                        @if(session('success'))
                            <div class="alert alert-success mt-2">{{ session('success') }}</div>
                        @endif
                    </div>
                @endif
                @endsection
            </div>
        </div>
    </div>
</div>

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