@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Gestion des utilisateurs</h1>
    <p class="text-muted">{{ $users->count() }} utilisateur(s) enregistré(s)</p>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#ID</th>
                        <th>Pseudo</th>
                        <th>Email</th>
                        <th>score</th>
                        <th>Rôle</th>
                        <th>Inscrit le</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->pseudo }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span>{{ $user->points }}</span></td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">Admin</span>
                                @else
                                    <span class="badge bg-secondary">User</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection