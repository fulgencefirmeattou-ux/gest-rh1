@extends('layouts.app')

@section('content')

<div class="container mt-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Détail du contrat</h2>
        <a href="{{ route('contrats.index') }}" class="btn btn-secondary">Retour à la liste</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                Contrat de {{ $contrat->employe->nom }} {{ $contrat->employe->prenom }}
            </h5>
            <div class="d-flex gap-2">
                @if($contrat->pdf_path)
                    <a href="{{ route('contrats.download', $contrat->id) }}"
                       class="btn btn-sm btn-outline-danger">
                        <span class="mdi mdi-file-pdf-box"></span> Télécharger PDF
                    </a>
                @endif
                <a href="{{ route('contrats.edit', $contrat->id) }}" class="btn btn-sm btn-primary">
                    <span class="mdi mdi-pencil"></span> Modifier
                </a>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th style="width:35%">Type de contrat</th>
                    <td>{{ $contrat->type_contrat }}</td>
                </tr>
                <tr>
                    <th>Date de début</th>
                    <td>{{ \Carbon\Carbon::parse($contrat->date_debut)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>Date de fin</th>
                    <td>{{ $contrat->date_fin ? \Carbon\Carbon::parse($contrat->date_fin)->format('d/m/Y') : '—' }}</td>
                </tr>
                <tr>
                    <th>Salaire de base</th>
                    <td>{{ number_format($contrat->salaire_base, 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr>
                    <th>Heures / semaine</th>
                    <td>{{ $contrat->heures_par_semaine ?? 40 }} h</td>
                </tr>
                <tr>
                    <th>Mode de calcul</th>
                    <td>{{ ucfirst($contrat->mode_calcul) }}</td>
                </tr>
                <tr>
                    <th>Statut</th>
                    <td>
                        <span class="badge bg-{{ $contrat->statut === 'actif' ? 'success' : 'secondary' }}">
                            {{ strtoupper($contrat->statut) }}
                        </span>
                    </td>
                </tr>
            </table>

            <h5 class="mt-4">Primes associées</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Libellé</th>
                        <th>Montant</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contrat->primes as $prime)
                        <tr>
                            <td>{{ $prime->libelle }}</td>
                            <td>{{ number_format($prime->montant, 0, ',', ' ') }} FCFA</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">Aucune prime</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
