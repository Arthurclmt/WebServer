@extends('layouts.app')

@section('content')
    <h1>Bienvenue sur le dashboard !</h1>
    <section class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Dernières actualités</h2>
        <a href="{{ route('news.index') }}" class="btn btn-outline-primary btn-sm">Voir tout</a>
    </div>
    <div class="row">
        @foreach($latestNews as $item)
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" style="height:180px; object-fit:cover;">
                @endif
                <div class="card-body">
                    <span class="badge {{ $item->type === 'event' ? 'bg-warning text-dark' : 'bg-primary' }} mb-1">
                        {{ $item->type === 'event' ? '🎭 Événement' : '📰 News' }}
                    </span>
                    <h6 class="card-title">{{ $item->title }}</h6>
                    <p class="card-text small text-muted">{{ Str::limit($item->content, 80) }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

@endsection