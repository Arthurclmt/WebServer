@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Panel Admin</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Pseudo</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Points</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="{{ $user->is_banned ? 'table-danger' : '' }}">
                <td>{{ $user->id }}</td>
                <td>{{ $user->pseudo }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->role === 'admin')
                        <span class="badge bg-warning text-dark">Admin</span>
                    @else
                        <span class="badge bg-secondary">Membre</span>
                    @endif
                </td>
                <td>{{ $user->points }}</td>
                <td>
                    @if($user->is_banned)
                        <span class="badge bg-danger">Banni</span>
                    @else
                        <span class="badge bg-success">Actif</span>
                    @endif
                </td>
                <td class="d-flex gap-2 flex-wrap">
                    @if($user->id !== auth()->id())
                        {{-- Promouvoir / Rétrograder --}}
                        @if($user->role !== 'admin')
                            <form method="POST" action="{{ route('admin.promote', $user) }}">
                                @csrf
                                <button class="btn btn-sm btn-warning">Promouvoir admin</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.demote', $user) }}">
                                @csrf
                                <button class="btn btn-sm btn-outline-warning">Rétrograder</button>
                            </form>
                        @endif

                        {{-- Bannir / Débannir --}}
                        @if(!$user->is_banned)
                            <form method="POST" action="{{ route('admin.ban', $user) }}">
                                @csrf
                                <button class="btn btn-sm btn-danger">Bannir</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.unban', $user) }}">
                                @csrf
                                <button class="btn btn-sm btn-outline-success">Débannir</button>
                            </form>
                        @endif
                    @else
                        <span class="text-muted fst-italic">Vous</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
