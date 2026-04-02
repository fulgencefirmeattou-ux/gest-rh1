@extends('layouts.app')

@section('content')

<div class="container mt-3">
    <h2 class="mb-4 text-start">Détail du type de contrat</h2>
    <a href="{{ route('type_contrats.index') }}" class="btn btn-secondary mb-3">Retour à la liste</a>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="mb-3">
            <label class="form-label fw-bold">Nom</label>
            <p>{{ $type_contrat->name }}</p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Description</label>
            <p>{{ $type_contrat->description ?? '—' }}</p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Créé le</label>
            <p>{{ $type_contrat->created_at ? $type_contrat->created_at->format('d/m/Y H:i') : '—' }}</p>
        </div>
        <a href="{{ route('type_contrats.edit', $type_contrat->id) }}" class="btn btn-primary">Modifier</a>
    </div>
</div>

@endsection
