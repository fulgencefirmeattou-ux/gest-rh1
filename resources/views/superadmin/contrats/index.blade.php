@extends('layouts.app')

@section('content')

<div class="container mt-3">

    <h2 class="mb-4 text-start">Liste des contrats</h2>

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('contrats.create') }}" class="btn btn-primary">+ Nouveau contrat</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($contrats->isEmpty())
        <p class="text-center text-muted">Aucun contrat enregistré.</p>
    @else
        <div class="table-responsive">
            <table class="table table-sm table-nowrap table-hover mb-0 text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Employé</th>
                        <th>Type</th>
                        <th>Salaire de base</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Statut</th>
                        <th>PDF</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contrats as $contrat)
                        <tr>
                            <td>{{ $contrats->firstItem() + $loop->index }}</td>
                            <td>{{ $contrat->employe?->nom ?? '—' }} {{ $contrat->employe?->prenom }}</td>
                            <td>{{ $contrat->type_contrat }}</td>
                            <td>{{ number_format($contrat->salaire_base, 0, ',', ' ') }} FCFA</td>
                            <td>{{ \Carbon\Carbon::parse($contrat->date_debut)->format('d/m/Y') }}</td>
                            <td>{{ $contrat->date_fin ? \Carbon\Carbon::parse($contrat->date_fin)->format('d/m/Y') : '—' }}</td>
                            <td>
                                @if($contrat->statut === 'actif')
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($contrat->statut) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($contrat->pdf_path)
                                    <a href="{{ route('contrats.download', $contrat->id) }}"
                                       class="btn btn-sm btn-outline-danger">
                                        <span class="mdi mdi-file-pdf-box"></span> PDF
                                    </a>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('contrats.show', $contrat->id) }}" class="btn btn-sm btn-success">
                                    <span class="mdi mdi-eye"></span>
                                </a>
                                <a href="{{ route('contrats.edit', $contrat->id) }}" class="btn btn-sm btn-primary">
                                    <span class="mdi mdi-pencil"></span>
                                </a>
                                <form action="{{ route('contrats.destroy', $contrat->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Supprimer ce contrat ?')">
                                        <span class="mdi mdi-trash-can"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $contrats->links() }}
        </div>
    @endif

</div>

@endsection
