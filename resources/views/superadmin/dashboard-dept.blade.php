@extends('layouts.app')
@section('title', 'Tableau de bord — Responsable département')

@section('content')
<div class="container-fluid">

    {{-- En-tête --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-1">
                        Tableau de bord
                        @if($departement)
                            <span class="badge ms-2 fs-12" style="background:#5b73e8;">{{ $departement->nom }}</span>
                        @endif
                    </h4>
                    <p class="text-muted mb-0"><i class="ri-calendar-line me-1"></i>{{ \Carbon\Carbon::now()->translatedFormat('l d F Y') }}</p>
                </div>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">Département</a></li>
                    <li class="breadcrumb-item active">Tableau de bord</li>
                </ol>
            </div>
        </div>
    </div>

    @if(!$departement)
        <div class="alert alert-warning">
            <i class="ri-error-warning-line me-2"></i>Aucun département ne vous est assigné comme responsable.
        </div>
    @else

    {{-- KPI --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="card widget-flat h-100" style="background:linear-gradient(135deg,#5b73e8,#3d5bd8);">
                <div class="card-body text-white">
                    <div class="float-end"><i class="ri-group-line" style="font-size:2rem;opacity:.6;"></i></div>
                    <h6 class="text-uppercase mt-0 fw-semibold opacity-75">Employés du département</h6>
                    <h2 class="my-2 fw-bold">{{ $totalEmployesDept }}</h2>
                    <p class="mb-0 small">{{ $departement->nom }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card widget-flat h-100" style="background:linear-gradient(135deg,#f77e53,#e05b2d);">
                <div class="card-body text-white">
                    <div class="float-end"><i class="ri-calendar-check-line" style="font-size:2rem;opacity:.6;"></i></div>
                    <h6 class="text-uppercase mt-0 fw-semibold opacity-75">Congés à valider</h6>
                    <h2 class="my-2 fw-bold">{{ $congesEnAttente }}</h2>
                    <p class="mb-0 small">En attente dans votre département</p>
                </div>
                <a href="{{ route('conges.approbation.departement') }}" class="card-footer bg-white bg-opacity-10 text-white text-decoration-none d-flex align-items-center justify-content-between px-3 py-2 small">
                    <span>Valider</span><i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card widget-flat h-100" style="background:linear-gradient(135deg,#3bc0c3,#23909c);">
                <div class="card-body text-white">
                    <div class="float-end"><i class="ri-error-warning-line" style="font-size:2rem;opacity:.6;"></i></div>
                    <h6 class="text-uppercase mt-0 fw-semibold opacity-75">Absences ce mois</h6>
                    <h2 class="my-2 fw-bold">{{ $absencesMois }}</h2>
                    <p class="mb-0 small">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Congés à valider + Employés --}}
    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header bg-transparent d-flex align-items-center justify-content-between pt-3">
                    <h5 class="card-title mb-0"><i class="ri-calendar-check-line me-2 text-warning"></i>Congés à valider</h5>
                    <a href="{{ route('conges.approbation.departement') }}" class="btn btn-sm btn-outline-primary">Traiter</a>
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
                                    <tr><th class="ps-3">Employé</th><th>Type</th><th>Période</th><th>Jours</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($congesAValider as $c)
                                    <tr>
                                        <td class="ps-3 fw-semibold">{{ $c->employe->prenom ?? '—' }} {{ $c->employe->nom ?? '' }}</td>
                                        <td><span class="badge bg-info-subtle text-info">{{ $c->typeLabel() }}</span></td>
                                        <td class="small text-muted">{{ $c->date_debut_conge?->format('d/m') }} → {{ $c->date_fin_conge?->format('d/m/Y') }}</td>
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

        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header bg-transparent pt-3">
                    <h5 class="card-title mb-0"><i class="ri-group-line me-2 text-primary"></i>Employés du département</h5>
                </div>
                <div class="card-body p-0">
                    @if($employesDept->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="ri-user-line fs-1 d-block mb-2"></i>Aucun employé trouvé.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr><th class="ps-3">Nom</th><th>Poste</th><th>Service</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($employesDept as $emp)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center" style="width:32px;height:32px;min-width:32px;">
                                                    <span class="text-primary fw-bold small">{{ strtoupper(substr($emp->prenom,0,1)) }}{{ strtoupper(substr($emp->nom,0,1)) }}</span>
                                                </div>
                                                <span class="fw-semibold">{{ $emp->prenom }} {{ $emp->nom }}</span>
                                            </div>
                                        </td>
                                        <td class="small text-muted">{{ $emp->poste->name ?? '—' }}</td>
                                        <td class="small text-muted">{{ $emp->service->nom ?? '—' }}</td>
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

    @endif {{-- fin if departement --}}

</div>
@endsection
