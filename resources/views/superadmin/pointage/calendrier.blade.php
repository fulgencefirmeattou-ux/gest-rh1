@extends('layouts.app')

@section('content')
<div class="container-fluid mt-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="ri-calendar-line me-2"></i>Calendrier de présence</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('pointages.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ri-dashboard-line me-1"></i>Dashboard
            </a>
            <a href="{{ route('pointages.statistiques') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ri-bar-chart-line me-1"></i>Statistiques
            </a>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('pointages.calendrier') }}" class="row g-2 align-items-end">
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
                        @foreach($employes as $emp)
                            <option value="{{ $emp->id }}" {{ $employeId == $emp->id ? 'selected' : '' }}>
                                {{ $emp->nom }} {{ $emp->prenom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="ri-filter-line me-1"></i>Afficher
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Export PDF --}}
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold">
            {{ $employe->nom }} {{ $employe->prenom }} —
            {{ \Carbon\Carbon::createFromDate($annee, $mois, 1)->locale('fr')->isoFormat('MMMM YYYY') }}
        </h5>
        <a href="{{ route('pointages.export.calendrier', ['employe_id' => $employeId, 'mois' => $mois, 'annee' => $annee]) }}"
           class="btn btn-outline-danger btn-sm">
            <i class="ri-file-pdf-line me-1"></i>Export PDF
        </a>
    </div>

    {{-- Légende --}}
    <div class="d-flex gap-3 mb-3 flex-wrap">
        <span><span class="badge" style="background:#198754">Présent / Heures OK</span></span>
        <span><span class="badge bg-danger">Absent / Heures manquantes</span></span>
        <span><span class="badge bg-primary">Heures sup</span></span>
        <span><span class="badge bg-warning text-dark">Congé / Férié</span></span>
        <span><span class="badge bg-light text-dark border">Week-end</span></span>
    </div>

    {{-- Calendrier --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0 text-center" style="min-width: 900px;">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-start ps-3" style="min-width:140px;">Jour</th>
                            <th>Arrivée</th>
                            <th>Départ</th>
                            <th>Pause</th>
                            <th>Travaillées</th>
                            <th>Sup</th>
                            <th>Manquantes</th>
                            <th>Absent</th>
                            <th>Type</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($jour = 1; $jour <= $nbJours; $jour++)
                            @php
                                $dateStr = sprintf('%04d-%02d-%02d', $annee, $mois, $jour);
                                $date    = \Carbon\Carbon::parse($dateStr);
                                $isWeekend = $date->isWeekend();
                                $p       = $pointages->get($dateStr);

                                // Couleur de la ligne
                                if ($isWeekend) {
                                    $rowClass = 'table-light';
                                } elseif (!$p || !$p->heure_arrivee) {
                                    $rowClass = 'table-danger'; // absent
                                } elseif ($p && $p->heures_sup > 0) {
                                    $rowClass = 'table-primary'; // heures sup
                                } elseif ($p && $p->heures_manquantes > 0) {
                                    $rowClass = 'table-warning'; // manquantes
                                } elseif ($p && in_array($p->type_jour, ['CONGE', 'FERIE'])) {
                                    $rowClass = 'table-warning';
                                } else {
                                    $rowClass = 'table-success'; // OK
                                }
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td class="text-start ps-3 fw-semibold">
                                    {{ $date->locale('fr')->isoFormat('ddd D') }}
                                    @if($isWeekend)
                                        <span class="badge bg-secondary ms-1 fw-normal" style="font-size:10px;">WE</span>
                                    @endif
                                </td>
                                <td>{{ $p && $p->heure_arrivee ? \Carbon\Carbon::createFromTimeString($p->heure_arrivee)->format('H:i') : ($isWeekend ? '—' : '<span class="text-danger">✗</span>') }}</td>
                                <td>{{ $p && $p->heure_depart ? \Carbon\Carbon::createFromTimeString($p->heure_depart)->format('H:i') : '—' }}</td>
                                <td class="small">
                                    @if($p && $p->heure_debut_pause && $p->heure_fin_pause)
                                        {{ \Carbon\Carbon::createFromTimeString($p->heure_debut_pause)->format('H:i') }}
                                        → {{ \Carbon\Carbon::createFromTimeString($p->heure_fin_pause)->format('H:i') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="fw-bold">
                                    @if($p && $p->heures_travaillees !== null)
                                        {{ $p->heures_travaillees_format }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @if($p && $p->heures_sup > 0)
                                        <span class="text-primary">+{{ number_format($p->heures_sup, 2) }}h</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @if($p && $p->heures_manquantes > 0)
                                        <span class="text-danger">-{{ number_format($p->heures_manquantes, 2) }}h</span>
                                    @elseif(!$isWeekend && (!$p || !$p->heure_arrivee))
                                        <span class="text-danger">-8.00h</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @if($isWeekend)
                                        <span class="text-muted">—</span>
                                    @elseif($p?->absent)
                                        <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#dc3545;" title="Absent"></span>
                                    @else
                                        <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#198754;" title="Présent"></span>
                                    @endif
                                </td>
                                <td>
                                    @if($p)
                                        <span class="badge bg-secondary" style="font-size:10px;">{{ $p->type_jour }}</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @if($p)
                                        <span class="badge bg-{{ $p->couleur_statut }}" style="font-size:10px;">{{ $p->statut }}</span>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
