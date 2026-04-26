@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-1">Membres</h1>
    <p class="text-muted mb-4">{{ $membres->count() }} membre(s) inscrit(s)</p>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($membres as $membre)
        <div class="col">
            <a href="{{ route('membres.show', $membre) }}" class="text-decoration-none text-dark">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-1">{{ $membre->pseudo }}</h5>
                        <span class="badge {{ $membre->role === 'admin' ? 'bg-danger' : 'bg-secondary' }} mb-2">
                            {{ $membre->role === 'admin' ? 'Admin' : 'Membre' }}
                        </span>
                        <p class="card-text text-muted mb-0">Points : <strong>{{ $membre->points }}</strong></p>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection
