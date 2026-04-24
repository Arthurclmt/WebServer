@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">⚙️ Configuration — {{ $appareil->name }}</h2>
                    <a href="{{ route('appareil.show', $appareil->id) }}" class="btn btn-outline-secondary btn-sm">← Retour</a>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form action="{{ route('appareil.updateConfig', $appareil->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Heure de début</label>
                            <input type="time" name="start_hour" class="form-control"
                                   value="{{ old('start_hour', $appareil->start_hour ? \Carbon\Carbon::parse($appareil->start_hour)->format('H:i') : '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Heure de fin</label>
                            <input type="time" name="end_hour" class="form-control"
                                   value="{{ old('end_hour', $appareil->end_hour ? \Carbon\Carbon::parse($appareil->end_hour)->format('H:i') : '') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Temps d'utilisation max <small class="text-muted">(minutes)</small></label>
                            <div class="input-group">
                                <input type="number" name="usage_time" class="form-control"
                                       min="1" max="1440" value="{{ old('usage_time', $appareil->usage_time) }}" placeholder="ex : 120">
                                <span class="input-group-text">min</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Consommation <small class="text-muted">(watts)</small></label>
                            <div class="input-group">
                                <input type="number" name="consumption" class="form-control"
                                       min="0" max="99999" value="{{ old('consumption', $appareil->consumption) }}" placeholder="ex : 150">
                                <span class="input-group-text">W</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
                        <a href="{{ route('appareil.show', $appareil->id) }}" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection