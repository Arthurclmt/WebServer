@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h1 class="mb-4">Panel Admin - Gestion des utilisateurs</h1>
    <p class="text-muted">{{ $users->count() }} utilisateur(s) enregistré(s)</p>

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    {{-- Bloc à ajouter en haut de ta page admin --}}
    <div class="card mb-4 border-primary">
        <div class="card-body">
            <h5 class="text-primary">Autoriser une inscription (White List)</h5>
            
            <form action="{{ route('admin.allowed.store') }}" method="POST" class="row g-2">
                @csrf
                <div class="col-auto">
                    <input type="email" name="email" class="form-control" placeholder="email@exemple.com" required>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Ajouter l'email</button>
                </div>
            </form>

            <hr>

            <h6>Emails en attente d'inscription :</h6>
            <ul class="list-group">
                @foreach($allowedEmails as $allowed)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $allowed->email }}
                        <form action="{{ route('admin.allowed.destroy', $allowed) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

{{-- Tu peux aussi ajouter ici un petit tableau accordéon ou une liste 
     pour voir qui est dans la whitelist mais pas encore inscrit --}}
    {{-- TABLEAU USERS --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#ID</th>
                        <th>Pseudo</th>
                        <th>Email</th>
                        <th>Points</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Inscrit le</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($users as $user)
                        <tr class="{{ $user->is_banned ? 'table-danger' : '' }}">

                            {{-- ID --}}
                            <td>{{ $user->id }}</td>

                            {{-- Pseudo --}}
                            <td>{{ $user->pseudo }}</td>

                            {{-- Email modifiable --}}
                            <td>
                                @if($user->id !== auth()->id())
                                    <form
                                        method="POST"
                                        action="{{ route('admin.users.updateEmail', $user) }}"
                                        class="d-flex gap-2"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <input
                                            type="email"
                                            name="email"
                                            class="form-control form-control-sm"
                                            value="{{ $user->email }}"
                                            required
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-primary text-nowrap"
                                        >
                                            Modifier
                                        </button>
                                    </form>
                                @else
                                    {{ $user->email }}
                                @endif
                            </td>

                            {{-- Points --}}
                            <td>{{ $user->points }}</td>

                            {{-- Role --}}
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-warning text-dark">
                                        Admin
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        Membre
                                    </span>
                                @endif
                            </td>

                            {{-- Statut --}}
                            <td>
                                @if($user->is_banned)
                                    <span class="badge bg-danger">
                                        Banni
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        Actif
                                    </span>
                                @endif
                            </td>

                            {{-- Date inscription --}}
                            <td>
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>

                            {{-- Actions --}}
                            <td class="d-flex gap-2 flex-wrap">

                                @if($user->id !== auth()->id())

                                    {{-- Promote / Demote --}}
                                    @if($user->role !== 'admin')
                                        <form
                                            method="POST"
                                            action="{{ route('admin.promote', $user) }}"
                                        >
                                            @csrf
                                            <button class="btn btn-sm btn-warning">
                                                Promote
                                            </button>
                                        </form>
                                    @else
                                        <form
                                            method="POST"
                                            action="{{ route('admin.demote', $user) }}"
                                        >
                                            @csrf
                                            <button class="btn btn-sm btn-outline-warning">
                                                Demote
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Ban / Unban --}}
                                    @if(!$user->is_banned)
                                        <form
                                            method="POST"
                                            action="{{ route('admin.ban', $user) }}"
                                        >
                                            @csrf
                                            <button class="btn btn-sm btn-danger">
                                                Bannir
                                            </button>
                                        </form>
                                    @else
                                        <form
                                            method="POST"
                                            action="{{ route('admin.unban', $user) }}"
                                        >
                                            @csrf
                                            <button class="btn btn-sm btn-outline-success">
                                                Débannir
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Delete --}}
                                    <form
                                        method="POST"
                                        action="{{ route('admin.users.destroy', $user) }}"
                                        onsubmit="return confirm('Supprimer le compte de {{ $user->pseudo }} ?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-dark"
                                        >
                                            Supprimer
                                        </button>
                                    </form>

                                @else
                                    <span class="text-muted fst-italic">
                                        Vous
                                    </span>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>

</div>
@endsection