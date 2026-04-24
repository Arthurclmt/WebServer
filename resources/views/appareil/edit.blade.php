
@extends('layouts.app')
 
@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-body p-4">
 
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Modifier — {{ $appareil->name }}</h2>
                    <a href="{{ route('appareil.show', $appareil->id) }}" class="btn btn-outline-secondary btn-sm">← Retour</a>
                </div>
 
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
 
                <form action="{{ route('appareil.update', $appareil->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
 
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $appareil->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Salle</label>
                        <select name="room_id" class="form-control">
                            <option value=""> Aucune salle </option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}"
                                    {{ old('room_id', $appareil->room_id ?? '') == $room->id ? 'selected' : '' }}>
                                    {{ $room->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Type</label>
                            <input type="text" name="type" class="form-control"
                                   value="{{ old('type', $appareil->type) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Marque</label>
                            <input type="text" name="brand" class="form-control"
                                   value="{{ old('brand', $appareil->brand) }}">
                        </div>
                    </div>
 
                    <div class="mb-3">
                        <label class="form-label fw-bold">Statut <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="inactif"     {{ old('status', $appareil->status) == "inactif"     ? 'selected' : '' }}>Inactif</option>
                            <option value="actif"       {{ old('status', $appareil->status) == "actif"       ? 'selected' : '' }}>Actif</option>
                            <option value="maintenance" {{ old('status', $appareil->status) == 'maintenance' ? 'selected' : '' }}>En maintenance</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
 
                    <div class="mb-4">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $appareil->description) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        @if($appareil->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $appareil->image) }}" 
                                    alt="Image actuelle" 
                                    class="img-thumbnail" 
                                    style="max-height: 150px;">
                                <p class="text-muted small">Image actuelle (laisser vide pour la conserver)</p>
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        <a href="{{ route('appareil.show', $appareil->id) }}" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
 
            </div>
        </div>
    </div>
</div>
@endsection