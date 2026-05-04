@extends('layouts.app')

@section('title', 'Mes bulletins de paie')

@section('content')
<div class="container-fluid mt-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="ri-money-dollar-circle-line me-2"></i>Mes bulletins de paie
        </h2>
        <a href="{{ route('employe.profil') }}" class="btn btn-outline-secondary btn-sm">
            <i class="ri-arrow-left-line me-1"></i>Retour
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($bulletins->isEmpty())
        <div class="text-center text-muted py-5">
            <i class="ri-file-list-3-line fs-1"></i>
            <p class="mt-2">Aucun bulletin de paie disponible.</p>
        </div>
    @else
        {{-- Résumé --}}
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
                        <div class="small text-muted mb-1">Total net perçu</div>
                        <div class="fs-4 fw-bold text-success">
                            {{ number_format($bulletins->where('statut','paye')->sum('net_a_payer'), 0, ',', ' ') }} F
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="small text-muted mb-1">Dernier bulletin</div>
                        <div class="fs-5 fw-bold">
                            @if($bulletins->first())
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $bulletins->first()->mois)->locale('fr')->isoFormat('MMMM YYYY') }}
                            @else
                                —
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tableau --}}
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white fw-semibold py-2">
                <i class="ri-file-list-3-line me-1"></i>Historique
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
                                <th class="text-center">Statut</th>
                                <th class="text-center">PDF</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bulletins as $b)
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
                                <td class="text-center">
                                    <span class="badge bg-{{ $b->statut_color }}">{{ $b->statut_label }}</span>
                                </td>
                                <td class="text-center">
                                    @if($b->statut === 'paye')
                                        <a href="{{ route('employe.bulletins.download', $b) }}"
                                           class="btn btn-outline-danger btn-sm" title="Télécharger">
                                            <i class="ri-file-pdf-line"></i>
                                        </a>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
