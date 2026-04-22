@extends('layouts.app')

@section('title', 'Événements')

@section('content')
<div class="container">
    <h1 class="mb-4">Nos Événements</h1>
    @auth
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('events.create') }}" class="btn btn-primary mb-3">
                + Ajouter un événement
            </a>
        @endif
    @endauth   
    @if($events->isEmpty())
        <div class="alert alert-info">Aucun événement à venir.</div>
    @else
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($events as $event)
                <div class="col">
                    <div class="card h-100 shadow-sm">

                        {{-- Affiche --}}
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}"
                                 class="card-img-top"
                                 alt="{{ $event->title }}"
                                 style="height: 220px; object-fit: cover;">
                        @else
                            <div class="bg-secondary text-white d-flex align-items-center 
                                        justify-content-center" style="height: 220px;">
                                <span>Pas d'affiche</span>
                            </div>
                        @endif

                        <div class="card-body">
                            <h5 class="card-title">{{ $event->title }}</h5>
                            <p class="card-text text-muted small">
                                📅 {{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') }}
                                @if($event->location)
                                    &nbsp;|&nbsp; 📍 {{ $event->location }}
                                @endif
                            </p>
                            <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                        </div>

                        <div class="card-footer bg-transparent">
                            <a href="{{ route('events.show', $event->slug) }}"
                               class="btn btn-primary w-100">
                                Voir l'événement
                            </a>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection