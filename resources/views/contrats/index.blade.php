@extends('layouts.base')

@section('title', 'Contrats')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <h1 class="mb-0">Liste des contrats</h1>
        <a href="{{ route('contrats.create') }}" class="btn btn-primary">
            + Nouveau contrat
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div id="yearly-sales-collapse" class="collapse show">
            <div class="table-responsive">
                <table class="table table-nowrap table-hover mb-0 text-center" id="btn-editable">
                    
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Employé</th>
                            <th>Type</th>
                            <th>Salaire de base</th>
                            <th>Date début</th>
                            <th>Date fin</th>
                            <th>Statut</th>
                            <th>Contrat PDF</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contrats as $contrat)
                            <tr>
                                <td>{{ $contrat->id }}</td>
                                <td>
                                    {{ $contrat->employe->nom }} {{ $contrat->employe->prenom }}
                                </td>
                                <td>{{ $contrat->type_contrat }}</td>
                                <td>{{ number_format($contrat->salaire_base, 0, ',', ' ') }} FCFA</td>
                                <td>{{ \Carbon\Carbon::parse($contrat->date_debut)->format('d/m/Y') }}</td>
                                <td>
                                    {{ $contrat->date_fin ? \Carbon\Carbon::parse($contrat->date_fin)->format('d/m/Y') : '—' }}
                                </td>
                                <td>
                                    @if($contrat->statut === 'actif')
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($contrat->pdf_path)
                                        {{-- <a href="{{ asset('storage/'.$contrat->pdf_path) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                            PDF
                                        </a> --}}
                                        @if($contrat->pdf_path)
                                            <a href="{{ asset($contrat->pdf_path) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-outline-danger">
                                                PDF
                                            </a>
                                        @endif
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('contrats.show', $contrat->id) }}" class="btn btn-sm btn-info">Voir</a>
                                    <a href="{{ route('contrats.edit', $contrat->id) }}" class="btn btn-sm btn-primary">Modifier</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">
                                    Aucun contrat enregistré
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection



