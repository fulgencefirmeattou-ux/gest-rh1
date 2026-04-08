@extends('layouts.app')

@section('content')
<div class="container-fluid mt-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="ri-dashboard-line me-2"></i>Dashboard Pointage RH</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('pointages.statistiques') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ri-bar-chart-line me-1"></i>Statistiques
            </a>
            <a href="{{ route('pointages.calendrier') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ri-calendar-line me-1"></i>Calendrier
            </a>
            <a href="{{ route('pointages.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="ri-time-line me-1"></i>Mon pointage
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    {{-- Filtres --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('pointages.dashboard') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label mb-1 small">Date</label>
                    <input type="date" name="date" class="form-control form-control-sm" value="{{ $date }}">
                </div>
                <div class="col-md-5">
                    <label class="form-label mb-1 small">Employé</label>
                    <select name="employe_id" class="form-select form-select-sm">
                        <option value="">— Tous les employés —</option>
                        @foreach($employes as $emp)
                            <option value="{{ $emp->id }}" {{ $employeId == $emp->id ? 'selected' : '' }}>
                                {{ $emp->nom }} {{ $emp->prenom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="ri-filter-line me-1"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Export PDF --}}
    <div class="mb-3 text-end">
        <a href="{{ route('pointages.export.pointages', ['mois' => \Carbon\Carbon::parse($date)->month, 'annee' => \Carbon\Carbon::parse($date)->year, 'employe_id' => $employeId]) }}"
           class="btn btn-outline-danger btn-sm">
            <i class="ri-file-pdf-line me-1"></i>Export PDF du mois
        </a>
    </div>

    {{-- Tableau --}}
    @if($pointages->isEmpty())
        <div class="text-center text-muted py-5">
            <i class="ri-inbox-line fs-1 d-block mb-2"></i>
            Aucun pointage pour cette date.
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0 text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-start ps-3">Employé</th>
                                <th>Date</th>
                                <th>Arrivée</th>
                                <th>Début pause</th>
                                <th>Fin pause</th>
                                <th>Départ</th>
                                <th>Travaillées</th>
                                <th>Sup</th>
                                <th>Manquantes</th>
                                <th>Type</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pointages as $p)
                                <tr>
                                    <td class="text-start ps-3">
                                        <span class="fw-semibold">{{ $p->employe->nom ?? '—' }} {{ $p->employe->prenom ?? '' }}</span>
                                    </td>
                                    <td>{{ $p->date->format('d/m/Y') }}</td>
                                    <td class="text-success fw-semibold">
                                        {{ $p->heure_arrivee ? \Carbon\Carbon::createFromTimeString($p->heure_arrivee)->format('H:i') : '—' }}
                                    </td>
                                    <td class="text-warning">
                                        {{ $p->heure_debut_pause ? \Carbon\Carbon::createFromTimeString($p->heure_debut_pause)->format('H:i') : '—' }}
                                    </td>
                                    <td class="text-warning">
                                        {{ $p->heure_fin_pause ? \Carbon\Carbon::createFromTimeString($p->heure_fin_pause)->format('H:i') : '—' }}
                                    </td>
                                    <td class="text-danger fw-semibold">
                                        {{ $p->heure_depart ? \Carbon\Carbon::createFromTimeString($p->heure_depart)->format('H:i') : '—' }}
                                    </td>
                                    <td>
                                        @if($p->heures_travaillees !== null)
                                            <span class="badge bg-{{ $p->couleur_heures }}">{{ $p->heures_travaillees_format }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($p->heures_sup > 0)
                                            <span class="text-primary fw-semibold">+{{ number_format($p->heures_sup, 2) }}h</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($p->heures_manquantes > 0)
                                            <span class="text-danger fw-semibold">-{{ number_format($p->heures_manquantes, 2) }}h</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $p->type_jour }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $p->couleur_statut }}">{{ $p->statut }}</span>
                                    </td>
                                    <td>
                                        @if($p->statut === 'EN_ATTENTE')
                                            <div class="d-flex gap-1 justify-content-center">
                                                <form action="{{ route('pointages.valider', $p->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="statut" value="VALIDE">
                                                    <button type="submit" class="btn btn-success btn-sm" title="Valider">
                                                        <i class="ri-check-line"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('pointages.valider', $p->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="statut" value="REFUSE">
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Refuser">
                                                        <i class="ri-close-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <small class="text-muted">
                                                @if($p->validateur)
                                                    {{ $p->validateur->nom }}<br>
                                                    {{ $p->date_validation?->format('d/m H:i') }}
                                                @else
                                                    —
                                                @endif
                                            </small>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Légende --}}
        <div class="mt-3 d-flex gap-3 flex-wrap">
            <span><span class="badge bg-success">Heures OK</span></span>
            <span><span class="badge bg-danger">Heures manquantes</span></span>
            <span><span class="badge bg-primary">Heures sup</span></span>
            <span><span class="badge bg-warning text-dark">En attente</span></span>
            <span><span class="badge bg-success">Validé</span></span>
            <span><span class="badge bg-danger">Refusé</span></span>
        </div>
    @endif
</div>
@endsection
