@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="container">

    <a href="{{ route('events.index') }}" class="btn btn-outline-secondary mb-4">
        ← Retour aux événements
    </a>

    <div class="row">
        {{-- Affiche --}}
        <div class="col-md-4 mb-4">
            @if($event->image)
                <img src="{{ asset('storage/' . $event->image) }}"
                     class="img-fluid rounded shadow"
                     alt="{{ $event->title }}">
            @else
                <div class="bg-secondary text-white rounded d-flex align-items-center 
                            justify-content-center" style="height: 300px;">
                    <span>Pas d'affiche</span>
                </div>
            @endif
        </div>

        {{-- Infos --}}
        <div class="col-md-8">
            <h1>{{ $event->title }}</h1>
            <hr>

            <ul class="list-unstyled text-muted mb-3">
                <li>📅 <strong>Date :</strong>
                    {{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') }}
                </li>
                @if($event->location)
                    <li>📍 <strong>Lieu :</strong> {{ $event->location }}</li>
                @endif
            </ul>

            <p class="lead">{{ $event->description }}</p>

            @if($event->content)
                <div class="mt-3">
                    {!! nl2br(e($event->content)) !!}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection