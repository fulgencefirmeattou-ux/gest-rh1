@extends('layouts.app')

@section('content')

<section class="container mt-3">
    <h2 class="mb-4 text-start">Détail de l'utilisateur</h2>
    <a href="{{ route('utilisateurs.index') }}" class="btn btn-secondary mb-3">Retour à la liste</a>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="mb-3">
            <label class="form-label fw-bold">Nom d'utilisateur</label>
            <p>{{ $user->name }}</p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Email</label>
            <p>{{ $user->email }}</p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Créé le</label>
            <p>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '—' }}</p>
        </div>
        <a href="{{ route('utilisateurs.edit', $user->id) }}" class="btn btn-primary">Modifier</a>
    </div>
</section>

@endsection
