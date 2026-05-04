@extends('layouts.app')
@section('title', 'Tableau de bord — RH')

@section('content')
<div class="container-fluid">

    {{-- En-tête --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-1">Tableau de bord <span class="badge bg-primary ms-2 fs-12">RH</span></h4>
                    <p class="text-muted mb-0"><i class="ri-calendar-line me-1"></i>{{ \Carbon\Carbon::now()->translatedFormat('l d F Y') }}</p>
                </div>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">RH</a></li>
                    <li class="breadcrumb-item active">Tableau de bord</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- KPI --}}
    <div class="row g-3 mb-4">
        <div class="col-xxl-3 col-sm-6">
            <div class="card widget-flat h-100" style="background:linear-gradient(135deg,#3bc0c3,#23909c);">
                <div class="card-body text-white">
                    <div class="float-end"><i class="ri-group-line" style="font-size:2rem;opacity:.6;"></i></div>
                    <h6 class="text-uppercase mt-0 fw-semibold opacity-75">Employés</h6>
                    <h2 class="my-2 fw-bold">{{ $totalEmployes }}</h2>
                    <p class="mb-0 small"><span class="badge bg-white bg-opacity-25 me-1">+{{ $nouveauxEmployesMois }}</span>ce mois-ci</p>
                </div>
                <a href="{{ route('employes.index') }}" class="card-footer bg-white bg-opacity-10 text-white text-decoration-none d-flex align-items-center justify-content-between px-3 py-2 small">
                    <span>Voir les employés</span><i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card widget-flat h-100" style="background:linear-gradient(135deg,#5b73e8,#3d5bd8);">
                <div class="card-body text-white">
                    <div class="float-end"><i class="ri-file-text-line" style="font-size:2rem;opacity:.6;"></i></div>
                    <h6 class="text-uppercase mt-0 fw-semibold opacity-75">Contrats actifs</h6>
                    <h2 class="my-2 fw-bold">{{ $contratsActifs }}</h2>
                    <p class="mb-0 small"><span class="badge bg-white bg-opacity-25 me-1">{{ $contratsExpirantBientot->count() }}</span>expirent bientôt</p>
                </div>
                <a href="{{ route('contrats.index') }}" class="card-footer bg-white bg-opacity-10 text-white text-decoration-none d-flex align-items-center justify-content-between px-3 py-2 small">
                    <span>Voir les contrats</span><i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card widget-flat h-100" style="background:linear-gradient(135deg,#f77e53,#e05b2d);">
                <div class="card-body text-white">
                    <div class="float-end"><i class="ri-calendar-check-line" style="font-size:2rem;opacity:.6;"></i></div>
                    <h6 class="text-uppercase mt-0 fw-semibold opacity-75">Congés à valider</h6>
                    <h2 class="my-2 fw-bold">{{ $congesEnAttente }}</h2>
                    <p class="mb-0 small">En attente de validation DG/RH</p>
                </div>
                <a href="{{ route('conges.approbation.dgRh') }}" class="card-footer bg-white bg-opacity-10 text-white text-decoration-none d-flex align-items-center justify-content-between px-3 py-2 small">
                    <span>Valider les congés</span><i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card widget-flat h-100" style="background:linear-gradient(135deg,#9c5de8,#7340d8);">
                <div class="card-body text-white">
                    <div class="float-end"><i class="ri-error-warning-line" style="font-size:2rem;opacity:.6;"></i></div>
                    <h6 class="text-uppercase mt-0 fw-semibold opacity-75">Absences à traiter</h6>
                    <h2 class="my-2 fw-bold">{{ $absencesEnAttente }}</h2>
                    <p class="mb-0 small">Déclarations en attente</p>
                </div>
                <a href="{{ route('justisificatifs.absence.rh') }}" class="card-footer bg-white bg-opacity-10 text-white text-decoration-none d-flex align-items-center justify-content-between px-3 py-2 small">
                    <span>Traiter les absences</span><i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Congés à valider + Absences à traiter --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-transparent d-flex align-items-center justify-content-between pt-3">
                    <h5 class="card-title mb-0"><i class="ri-calendar-check-line me-2 text-warning"></i>Congés à valider</h5>
                    <a href="{{ route('conges.approbation.dgRh') }}" class="btn btn-sm btn-outline-secondary">Tous</a>
                </div>
                <div class="card-body p-0">
                    @if($congesAValider->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="ri-checkbox-circle-line fs-1 d-block mb-2 text-success"></i>
                            Aucun congé en attente.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr><th class="ps-3">Employé</th><th>Type</th><th>Période</th><th>Durée</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($congesAValider as $c)
                                    <tr>
                                        <td class="ps-3 fw-semibold">{{ $c->employe->prenom ?? '—' }} {{ $c->employe->nom ?? '' }}</td>
                                        <td><span class="badge bg-info-subtle text-info">{{ $c->typeLabel() }}</span></td>
                                        <td class="small text-muted">
                                            {{ $c->date_debut_conge?->format('d/m/Y') }} → {{ $c->date_fin_conge?->format('d/m/Y') }}
                                        </td>
                                        <td><span class="badge bg-secondary-subtle text-secondary">{{ $c->jours_ouvres }}j</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-transparent d-flex align-items-center justify-content-between pt-3">
                    <h5 class="card-title mb-0"><i class="ri-error-warning-line me-2 text-danger"></i>Absences à traiter</h5>
                    <a href="{{ route('justisificatifs.absence.rh') }}" class="btn btn-sm btn-outline-secondary">Toutes</a>
                </div>
                <div class="card-body p-0">
                    @if($absencesATraiter->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="ri-checkbox-circle-line fs-1 d-block mb-2 text-success"></i>
                            Aucune absence en attente.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr><th class="ps-3">Employé</th><th>Type</th><th>Date absence</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($absencesATraiter as $a)
                                    <tr>
                                        <td class="ps-3 fw-semibold">{{ $a->employe->prenom ?? '—' }} {{ $a->employe->nom ?? '' }}</td>
                                        <td><span class="badge bg-warning-subtle text-warning">{{ $a->typeLabel() }}</span></td>
                                        <td class="small text-muted">{{ $a->date_absence?->format('d/m/Y') ?? '—' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Contrats expirant --}}
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent d-flex align-items-center justify-content-between pt-3">
                    <h5 class="card-title mb-0"><i class="ri-alarm-warning-line me-2 text-warning"></i>Contrats expirant dans 30 jours</h5>
                    <a href="{{ route('contrats.index') }}" class="btn btn-sm btn-outline-secondary">Tous</a>
                </div>
                <div class="card-body p-0">
                    @if($contratsExpirantBientot->isEmpty())
                        <div class="text-center text-muted py-4">
                            <i class="ri-checkbox-circle-line fs-1 d-block mb-2 text-success"></i>
                            Aucun contrat n'expire dans les 30 prochains jours.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr><th class="ps-3">Employé</th><th>Type contrat</th><th>Date expiration</th><th>Jours restants</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($contratsExpirantBientot as $contrat)
                                    @php $jours = now()->diffInDays($contrat->date_fin, false); $cls = $jours <= 7 ? 'danger' : ($jours <= 15 ? 'warning' : 'info'); @endphp
                                    <tr>
                                        <td class="ps-3 fw-semibold">{{ $contrat->employe->nom ?? '—' }} {{ $contrat->employe->prenom ?? '' }}</td>
                                        <td><span class="badge bg-secondary-subtle text-secondary">{{ $contrat->type_contrat }}</span></td>
                                        <td><span class="badge bg-{{ $cls }}-subtle text-{{ $cls }}">{{ $contrat->date_fin->format('d/m/Y') }}</span></td>
                                        <td class="fw-semibold text-{{ $cls }}">J-{{ $jours }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
