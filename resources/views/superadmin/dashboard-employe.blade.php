@extends('layouts.app')
@section('title', 'Mon tableau de bord')

@section('content')
<div class="container-fluid">

    {{-- En-tête --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-1">
                        Bonjour, {{ $employe ? $employe->prenom : auth()->user()->prenom }} 👋
                    </h4>
                    <p class="text-muted mb-0"><i class="ri-calendar-line me-1"></i>{{ \Carbon\Carbon::now()->translatedFormat('l d F Y') }}</p>
                </div>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">Mon espace</a></li>
                    <li class="breadcrumb-item active">Tableau de bord</li>
                </ol>
            </div>
        </div>
    </div>

    @if(!$employe)
        <div class="alert alert-info">
            <i class="ri-information-line me-2"></i>Votre profil employé n'est pas encore configuré. Contactez votre RH.
        </div>
    @else

    {{-- KPI --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xxl-3">
            <div class="card widget-flat h-100" style="background:linear-gradient(135deg,#47ad77,#2e8b57);">
                <div class="card-body text-white">
                    <div class="float-end"><i class="ri-plane-line" style="font-size:2rem;opacity:.6;"></i></div>
                    <h6 class="text-uppercase mt-0 fw-semibold opacity-75">Solde de congés</h6>
                    <h2 class="my-2 fw-bold">{{ $soldeConges }} <small class="fs-14 fw-normal">jours</small></h2>
                    <p class="mb-0 small"><span class="badge bg-white bg-opacity-25 me-1">{{ $congesPris }}</span>jours pris</p>
                </div>
                <a href="{{ route('conges.voir') }}" class="card-footer bg-white bg-opacity-10 text-white text-decoration-none d-flex align-items-center justify-content-between px-3 py-2 small">
                    <span>Mes congés</span><i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>
        <div class="col-sm-6 col-xxl-3">
            <div class="card widget-flat h-100" style="background:linear-gradient(135deg,#5b73e8,#3d5bd8);">
                <div class="card-body text-white">
                    <div class="float-end"><i class="ri-time-line" style="font-size:2rem;opacity:.6;"></i></div>
                    <h6 class="text-uppercase mt-0 fw-semibold opacity-75">Permissions restantes</h6>
                    <h2 class="my-2 fw-bold">{{ $soldePermissions }} <small class="fs-14 fw-normal">jours</small></h2>
                    <p class="mb-0 small">Disponibles</p>
                </div>
                <a href="{{ route('conges.create-conge') }}" class="card-footer bg-white bg-opacity-10 text-white text-decoration-none d-flex align-items-center justify-content-between px-3 py-2 small">
                    <span>Faire une demande</span><i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>
        <div class="col-sm-6 col-xxl-3">
            <div class="card widget-flat h-100" style="background:linear-gradient(135deg,#f77e53,#e05b2d);">
                <div class="card-body text-white">
                    <div class="float-end"><i class="ri-error-warning-line" style="font-size:2rem;opacity:.6;"></i></div>
                    <h6 class="text-uppercase mt-0 fw-semibold opacity-75">Absences ce mois</h6>
                    <h2 class="my-2 fw-bold">{{ $absencesMois }}</h2>
                    <p class="mb-0 small">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
                </div>
                <a href="{{ route('justificatifs.absence.liste') }}" class="card-footer bg-white bg-opacity-10 text-white text-decoration-none d-flex align-items-center justify-content-between px-3 py-2 small">
                    <span>Mes absences</span><i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>
        <div class="col-sm-6 col-xxl-3">
            <div class="card widget-flat h-100" style="background:linear-gradient(135deg,#3bc0c3,#23909c);">
                <div class="card-body text-white">
                    <div class="float-end"><i class="ri-file-text-line" style="font-size:2rem;opacity:.6;"></i></div>
                    <h6 class="text-uppercase mt-0 fw-semibold opacity-75">Bulletins de paie</h6>
                    <h2 class="my-2 fw-bold">{{ $totalBulletins }}</h2>
                    <p class="mb-0 small">Disponibles</p>
                </div>
                <a href="{{ route('employe.bulletins') }}" class="card-footer bg-white bg-opacity-10 text-white text-decoration-none d-flex align-items-center justify-content-between px-3 py-2 small">
                    <span>Voir mes bulletins</span><i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Actions rapides --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent pt-3">
                    <h5 class="card-title mb-0"><i class="ri-flashlight-line me-2 text-warning"></i>Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('conges.create-conge') }}" class="btn btn-primary">
                            <i class="ri-plane-line me-1"></i>Demander un congé
                        </a>
                        <a href="{{ route('justificatifs.absence') }}" class="btn btn-outline-warning">
                            <i class="ri-error-warning-line me-1"></i>Déclarer une absence
                        </a>
                        <a href="{{ route('employe.bulletins') }}" class="btn btn-outline-teal">
                            <i class="ri-file-text-line me-1"></i>Mes bulletins
                        </a>
                        <a href="{{ route('employe.contrat') }}" class="btn btn-outline-secondary">
                            <i class="ri-file-list-3-line me-1"></i>Mes contrats
                        </a>
                        <a href="{{ route('employe.profil') }}" class="btn btn-outline-info">
                            <i class="ri-user-line me-1"></i>Mon profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Dernières demandes + Dernières absences --}}
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-transparent d-flex align-items-center justify-content-between pt-3">
                    <h5 class="card-title mb-0"><i class="ri-plane-line me-2 text-success"></i>Mes dernières demandes de congé</h5>
                    <a href="{{ route('conges.voir') }}" class="btn btn-sm btn-outline-secondary">Toutes</a>
                </div>
                <div class="card-body p-0">
                    @if($dernieresDemandes->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="ri-plane-line fs-1 d-block mb-2"></i>Aucune demande.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr><th class="ps-3">Type</th><th>Du</th><th>Au</th><th>Statut</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($dernieresDemandes as $c)
                                    @php
                                        $statutClass = match($c->statut) {
                                            'approuvee'              => 'success',
                                            'rejetee'                => 'danger',
                                            'attente_service'        => 'warning',
                                            'attente_departement'    => 'warning',
                                            'attente_dg'             => 'info',
                                            'modification_demandee'  => 'secondary',
                                            default                  => 'secondary',
                                        };
                                    @endphp
                                    <tr>
                                        <td class="ps-3"><span class="badge bg-primary-subtle text-primary">{{ $c->typeLabel() }}</span></td>
                                        <td class="small">{{ $c->date_debut_conge?->format('d/m/Y') }}</td>
                                        <td class="small">{{ $c->date_fin_conge?->format('d/m/Y') }}</td>
                                        <td><span class="badge bg-{{ $statutClass }}-subtle text-{{ $statutClass }}">{{ $c->statutLabel() }}</span></td>
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
                    <h5 class="card-title mb-0"><i class="ri-error-warning-line me-2 text-warning"></i>Mes dernières absences</h5>
                    <a href="{{ route('justificatifs.absence.liste') }}" class="btn btn-sm btn-outline-secondary">Toutes</a>
                </div>
                <div class="card-body p-0">
                    @if($dernieresAbsences->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="ri-checkbox-circle-line fs-1 d-block mb-2 text-success"></i>
                            Aucune absence enregistrée.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr><th class="ps-3">Type</th><th>Date</th><th>Statut</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($dernieresAbsences as $a)
                                    @php
                                        $cls = match($a->statut) {
                                            'validee'    => 'success',
                                            'rejetee'    => 'danger',
                                            default      => 'warning',
                                        };
                                    @endphp
                                    <tr>
                                        <td class="ps-3"><span class="badge bg-warning-subtle text-warning">{{ $a->typeLabel() }}</span></td>
                                        <td class="small">{{ $a->date_absence?->format('d/m/Y') ?? '—' }}</td>
                                        <td><span class="badge bg-{{ $cls }}-subtle text-{{ $cls }}">{{ $a->statutLabel() }}</span></td>
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

    @endif {{-- fin if employe --}}

</div>
@endsection
