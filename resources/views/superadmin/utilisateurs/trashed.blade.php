@extends('layouts.app')

@section('content')

<section class="container mt-3">
    <h2 class="mb-4 text-start">Corbeille — Utilisateurs archivés</h2>
    <a href="{{ route('utilisateurs.index') }}" class="btn btn-secondary mb-3">Retour à la liste</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($users->isEmpty())
        <p class="text-center">Aucun utilisateur archivé.</p>
    @else
        <div class="table-responsive">
            <table class="table table-nowrap table-hover mb-0 text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nom et Prénoms</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Archivé le</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email ?? '—' }}</td>
                            <td>{{ $user->role ?? '—' }}</td>
                            <td>{{ $user->deleted_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <form action="{{ route('utilisateurs.restore', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Restaurer cet utilisateur ?')">
                                        <span class="mdi mdi-restore"></span>
                                    </button>
                                </form>
                                <form action="{{ route('utilisateurs.forceDelete', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer définitivement ? Cette action est irréversible.')">
                                        <span class="mdi mdi-trash-can"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</section>

@endsection
