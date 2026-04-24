@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Actualités</h1>
    @auth
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('news.create') }}" class="btn btn-primary">+ Ajouter une news</a>
        @endif
    @endauth
</div>

<div class="row">
    @foreach($news as $item)
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" style="height:200px; object-fit:cover;">
            @endif
            <div class="card-body">
                <span class="badge {{ $item->type === 'event' ? 'bg-warning text-dark' : 'bg-primary' }} mb-2">
                    {{ $item->type === 'event' ? '🎭 Événement' : '📰 News' }}
                </span>
                <h5 class="card-title">{{ $item->title }}</h5>
                <p class="card-text text-muted">{{ Str::limit($item->content, 100) }}</p>
                @if($item->type === 'event' && $item->event_id)
                    <a href="{{ route('events.show', $item->event->slug) }}" class="btn btn-sm btn-outline-warning">Voir l'événement</a>
                @endif
            </div>
            <div class="card-footer text-muted small">
                {{ $item->created_at->format('d/m/Y') }}
            </div>
        </div>
    </div>
    @endforeach
</div>

{{ $news->links() }}
@endsection