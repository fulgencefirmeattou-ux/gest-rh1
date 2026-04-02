@extends('layouts.app')

@section('content')

<div class="container mt-3">
    <h2 class="mb-4 text-start">Détail du département</h2>
    <a href="{{ route('departements.index') }}" class="btn btn-secondary mb-3">Retour à la liste</a>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="mb-3">
            <label class="form-label fw-bold">Nom</label>
            <p>{{ $departement->nom }}</p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Description</label>
            <p>{{ $departement->description ?? '—' }}</p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Responsable</label>
            <p>{{ $departement->responsable->name ?? '—' }}</p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Créé le</label>
            <p>{{ $departement->created_at ? $departement->created_at->format('d/m/Y H:i') : '—' }}</p>
        </div>
        <a href="{{ route('departements.edit', $departement->id) }}" class="btn btn-primary">Modifier</a>
    </div>
</div>

@endsection
