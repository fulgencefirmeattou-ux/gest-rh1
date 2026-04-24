@extends('layouts.app')

@section('content')
<div class="container-fluid mt-3">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">
                <i class="ri-history-line me-2"></i>Historique de paiements
            </h2>
            <p class="text-muted mb-0 small mt-1">
                {{ $employe->nom }} {{ $employe->prenom }}
                @if($employe->matricule) &bull; {{ $employe->matricule }} @endif
                @if($employe->poste) &bull; {{ $employe->poste->name }} @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('bulletins.create') }}?employe_id={{ $employe->id }}" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i>Nouveau bulletin
            </a>
            <a href="{{ route('employes.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ri-arrow-left-line me-1"></i>Retour
            </a>
        </div>
    </div>

    {{-- Cartes résumé --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="small text-muted mb-1">Total bulletins</div>
                    <div class="fs-4 fw-bold">{{ $bulletins->count() }}</div>
                    <div class="small text-muted">dont {{ $bulletins->where('statut','paye')->count() }} payé(s)</div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="small text-muted mb-1">Total brut versé</div>
                    <div class="fs-4 fw-bold">{{ number_format($totalBrut, 0, ',', ' ') }} F</div>
                    <div class="small text-muted">sur bulletins payés</div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="small text-muted mb-1">Total net versé</div>
                    <div class="fs-4 fw-bold text-success">{{ number_format($totalPaye, 0, ',', ' ') }} F</div>
                    <div class="small text-danger">Retenues : {{ number_format($totalRetenues, 0, ',', ' ') }} F</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white fw-semibold py-2">
            <i class="ri-file-list-3-line me-1"></i>Bulletins de paie
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mois</th>
                            <th>Période</th>
                            <th class="text-end">Salaire brut</th>
                            <th class="text-end">Retenues</th>
                            <th class="text-end">Net à payer</th>
                            <th class="text-center">Mode règlement</th>
                            <th class="text-center">Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bulletins as $b)
                        <tr>
                            <td class="fw-semibold">
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $b->mois)->locale('fr')->isoFormat('MMMM YYYY') }}
                            </td>
                            <td class="small text-muted">
                                @if($b->periode_debut && $b->periode_fin)
                                {{ \Carbon\Carbon::parse($b->periode_debut)->format('d/m') }}
                                – {{ \Carbon\Carbon::parse($b->periode_fin)->format('d/m/Y') }}
                                @else
                                —
                                @endif
                            </td>
                            <td class="text-end">{{ number_format($b->salaire_brut, 0, ',', ' ') }} F</td>
                            <td class="text-end text-danger">{{ number_format($b->total_retenues, 0, ',', ' ') }} F</td>
                            <td class="text-end fw-bold text-success">{{ number_format($b->net_a_payer, 0, ',', ' ') }} F</td>
                            <td class="text-center small">{{ $b->mode_reglement ?? '—' }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $b->statut_color }}">{{ $b->statut_label }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('bulletins.show', $b) }}" class="btn btn-outline-primary btn-sm" title="Voir">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('bulletins.download', $b) }}" class="btn btn-outline-danger btn-sm" title="PDF">
                                        <i class="ri-file-pdf-line"></i>
                                    </a>
                                    @if($b->statut === 'brouillon')
                                    <form method="POST" action="{{ route('bulletins.valider', $b) }}">
                                        @csrf
                                        <button class="btn btn-outline-success btn-sm" title="Valider">
                                            <i class="ri-check-line"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @if($b->statut === 'valide')
                                    <form method="POST" action="{{ route('bulletins.payer', $b) }}">
                                        @csrf
                                        <button class="btn btn-success btn-sm" title="Marquer payé">
                                            <i class="ri-money-dollar-circle-line"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @if($b->statut !== 'paye')
                                    <form method="POST" action="{{ route('bulletins.destroy', $b) }}" onsubmit="return confirm('Supprimer ce bulletin ?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" title="Supprimer">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Aucun bulletin pour cet employé.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($bulletins->isNotEmpty())
                    <tfoot class="table-light fw-semibold">
                        <tr>
                            <td colspan="2" class="text-end small">TOTAL (payés) :</td>
                            <td class="text-end">{{ number_format($totalBrut, 0, ',', ' ') }} F</td>
                            <td class="text-end text-danger">{{ number_format($totalRetenues, 0, ',', ' ') }} F</td>
                            <td class="text-end text-success">{{ number_format($totalPaye, 0, ',', ' ') }} F</td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
