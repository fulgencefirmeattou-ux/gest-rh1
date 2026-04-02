@extends('layouts.app')

@section('content')

<div class="container mt-3">
    <h2 class="mb-4 text-start">Détail du poste</h2>
    <a href="{{ route('postes.index') }}" class="btn btn-secondary mb-3">Retour à la liste</a>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="mb-3">
            <label class="form-label fw-bold">Nom</label>
            <p>{{ $poste->name }}</p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Description</label>
            <p>{{ $poste->description ?? '—' }}</p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Créé le</label>
            <p>{{ $poste->created_at ? $poste->created_at->format('d/m/Y H:i') : '—' }}</p>
        </div>
        <a href="{{ route('postes.edit', $poste->id) }}" class="btn btn-primary">Modifier</a>
    </div>
</div>

@endsection
