@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">📊 Rapports et Statistiques IdO</h1>

    <div class="row mb-5">
        <div class="col-md-6">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Consommation Totale Plateforme</h5>
                    <h2 class="display-4">{{ number_format($totalConsommation, 2) }} <small>kWh</small></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Temps d'Usage Moyen</h5>
                    <h2 class="display-4">{{ number_format($avgUsage, 1) }} <small>heures</small></h2>
                </div>
            </div>
        </div>
    </div>

    @if($inefficientDevices->count() > 0)
    <div class="card border-warning mb-5 shadow-sm">
        <div class="card-header bg-warning text-dark">
            <strong>⚠️ Analyse d'optimisation : Objets inefficaces</strong>
        </div>
        <div class="card-body">
            <p class="text-muted">Ces objets consomment beaucoup de ressources pour un temps d'usage très faible. Une maintenance est peut-être nécessaire.</p>
            <ul class="list-group list-group-flush">
                @foreach($inefficientDevices as $device)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $device->name }} ({{ $device->type }})
                    <span class="badge bg-danger rounded-pill">{{ $device->consumption }} kWh</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            Historique et Détails par Objet
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nom de l'objet</th>
                            <th>Localisation</th>
                            <th>Consommation</th>
                            <th>Usage</th>
                            <th>Ratio (kWh/h)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allDevices as $device)
                        <tr>
                            <td><strong>{{ $device->name }}</strong><br><small class="text-muted">{{ $device->brand }}</small></td>
                            <td>{{ $device->room ? $device->room->name : 'N/A' }}</td>
                            <td>{{ $device->consumption }} kWh</td>
                            <td>{{ $device->usage_time }} h</td>
                            <td>
                                @if($device->usage_time > 0)
                                    {{ number_format($device->consumption / $device->usage_time, 2) }}
                                @else
                                    0
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('appareil.export', $device->id) }}" class="btn btn-sm btn-outline-secondary">
                                    📄 Export CSV
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection