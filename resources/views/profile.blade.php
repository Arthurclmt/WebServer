@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Réglages du profil</h2>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.index') }}" class="btn btn-warning btn-sm">Panel Admin</a>
                    @endif
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="/profile">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Pseudo</label>
                        <input type="text" name="pseudo" class="form-control" value="{{ $user->pseudo }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date de naissance</label>
                        <input type="date" name="date_naissance" class="form-control" value="{{ $user->date_naissance }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Genre</label>
                        <select name="genre" class="form-select">
                            <option value="homme" {{ $user->genre === 'homme' ? 'selected' : '' }}>Homme</option>
                            <option value="femme" {{ $user->genre === 'femme' ? 'selected' : '' }}>Femme</option>
                            <option value="autre" {{ $user->genre === 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nouveau mot de passe <span class="text-muted">(laisser vide pour ne pas changer)</span></label>
                        <input type="password" name="password" class="form-control" minlength="8">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Sauvegarder</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
