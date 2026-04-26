@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="mb-1">{{ $user->pseudo }}</h2>
                <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-secondary' }} mb-3">
                    {{ $user->role === 'admin' ? 'Admin' : 'Membre' }}
                </span>

                <ul class="list-group list-group-flush mt-2">
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Points</span>
                        <strong>{{ $user->points }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Membre depuis</span>
                        <strong>{{ $user->created_at->format('d/m/Y') }}</strong>
                    </li>
                </ul>

                <a href="{{ route('membres.index') }}" class="btn btn-outline-secondary mt-4 w-100">Retour aux membres</a>
            </div>
        </div>
    </div>
</div>
@endsection
