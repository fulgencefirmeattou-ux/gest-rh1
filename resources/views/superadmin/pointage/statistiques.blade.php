@extends('layouts.app')

@section('content')
<div class="container-fluid mt-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="ri-bar-chart-line me-2"></i>Statistiques mensuelles</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('pointages.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ri-dashboard-line me-1"></i>Dashboard
            </a>
            <a href="{{ route('pointages.calendrier') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ri-calendar-line me-1"></i>Calendrier
            </a>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('pointages.statistiques') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label mb-1 small">Mois</label>
                    <select name="mois" class="form-select form-select-sm">
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" {{ $mois == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromDate(null, $m, 1)->locale('fr')->monthName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1 small">Année</label>
                    <select name="annee" class="form-select form-select-sm">
                        @foreach($annees as $a)
                            <option value="{{ $a }}" {{ $annee == $a ? 'selected' : '' }}>{{ $a }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label mb-1 small">Employé</label>
                    <select name="employe_id" class="form-select form-select-sm">
                        <option value="">— Tous —</option>
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
        <a href="{{ route('pointages.export.pointages', ['mois' => $mois, 'annee' => $annee, 'employe_id' => $employeId]) }}"
           class="btn btn-outline-danger btn-sm">
            <i class="ri-file-pdf-line me-1"></i>Export PDF
        </a>
    </div>

    {{-- Tableau stats --}}
    @if($stats->isEmpty())
        <div class="text-center text-muted py-5">
            <i class="ri-inbox-line fs-1 d-block mb-2"></i>
            Aucune donnée pour cette période.
        </div>
    @else
        {{-- Résumé global --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body py-3">
                        <div class="fs-2 fw-bold text-success">{{ $stats->sum('heures_travaillees') }}h</div>
                        <small class="text-muted">Total heures travaillées</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body py-3">
                        <div class="fs-2 fw-bold text-primary">{{ $stats->sum('heures_sup') }}h</div>
                        <small class="text-muted">Total heures sup</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body py-3">
                        <div class="fs-2 fw-bold text-danger">{{ $stats->sum('heures_manquantes') }}h</div>
                        <small class="text-muted">Total heures manquantes</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body py-3">
                        <div class="fs-2 fw-bold text-secondary">{{ $stats->sum('heures_absence') }}h</div>
                        <small class="text-muted">Total heures d'absence</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">
                Détail par employé —
                {{ \Carbon\Carbon::createFromDate($annee, $mois, 1)->locale('fr')->isoFormat('MMMM YYYY') }}
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-3">Employé</th>
                                <th class="text-center">Jours travaillés</th>
                                <th class="text-center">Heures travaillées</th>
                                <th class="text-center">Heures sup</th>
                                <th class="text-center">Heures manquantes</th>
                                <th class="text-center">Absences</th>
                                <th class="text-center">Calendrier</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats as $s)
                                @php
                                    $couleurH = $s['heures_sup'] > 0 ? 'primary' : ($s['heures_manquantes'] > 0 ? 'danger' : 'success');
                                @endphp
                                <tr>
                                    <td class="ps-3 fw-semibold">{{ $s['employe']->nom ?? '—' }} {{ $s['employe']->prenom ?? '' }}</td>
                                    <td class="text-center">{{ $s['jours_travailles'] }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $couleurH }}">{{ $s['heures_travaillees'] }}h</span>
                                    </td>
                                    <td class="text-center">
                                        @if($s['heures_sup'] > 0)
                                            <span class="text-primary fw-semibold">+{{ $s['heures_sup'] }}h</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($s['heures_manquantes'] > 0)
                                            <span class="text-danger fw-semibold">-{{ $s['heures_manquantes'] }}h</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($s['heures_absence'] > 0)
                                            <span class="badge bg-warning text-dark">
                                                {{ $s['heures_absence'] }}h
                                                <small class="opacity-75">({{ $s['jours_absence'] }}j)</small>
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($s['employe'])
                                            <a href="{{ route('pointages.calendrier', ['employe_id' => $s['employe']->id, 'mois' => $mois, 'annee' => $annee]) }}"
                                               class="btn btn-outline-secondary btn-sm">
                                                <i class="ri-calendar-line"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
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
