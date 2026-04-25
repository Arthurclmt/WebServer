@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Gestion des utilisateurs</h1>
    <p class="text-muted">{{ $users->count() }} utilisateur(s) enregistré(s)</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#ID</th>
                        <th>Pseudo</th>
                        <th>Email</th>
                        <th>Score</th>
                        <th>Rôle</th>
                        <th>Inscrit le</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->pseudo }}</td>
                            <td>
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.updateEmail', $user) }}" class="d-flex gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="email" name="email" class="form-control form-control-sm" value="{{ $user->email }}" required>
                                        <button type="submit" class="btn btn-sm btn-outline-primary text-nowrap">Modifier</button>
                                    </form>
                                @else
                                    {{ $user->email }}
                                @endif
                            </td>
                            <td>{{ $user->points }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">Admin</span>
                                @else
                                    <span class="badge bg-secondary">User</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Supprimer le compte de {{ $user->pseudo }} ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                    </form>
                                @else
                                    <span class="text-muted fst-italic">Vous</span>
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
