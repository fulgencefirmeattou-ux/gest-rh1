@extends('layouts.app')
@section('title', 'Tableau de bord')

@section('content')
<div class="container-fluid">

    {{-- ── En-tête ──────────────────────────────────────────────────────── --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-1">Tableau de bord</h4>
                    <p class="text-muted mb-0">
                        <i class="ri-calendar-line me-1"></i>
                        {{ \Carbon\Carbon::now()->translatedFormat('l d F Y') }}
                    </p>
                </div>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">RH</a></li>
                    <li class="breadcrumb-item active">Tableau de bord</li>
                </ol>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Cartes KPI ────────────────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">

        <div class="col-xxl-3 col-sm-6">
            <div class="card widget-flat h-100" style="background: linear-gradient(135deg,#3bc0c3,#23909c);">
                <div class="card-body text-white">
                    <div class="float-end">
                        <i class="ri-group-line" style="font-size:2rem;opacity:.6;"></i>
                    </div>
                    <h6 class="text-uppercase mt-0 fw-semibold opacity-75">Employés</h6>
                    <h2 class="my-2 fw-bold">{{ $totalEmployes }}</h2>
                    <p class="mb-0 small">
                        <span class="badge bg-white bg-opacity-25 me-1">+{{ $nouveauxEmployesMois }}</span>
                        ce mois-ci
                    </p>
                </div>
                <a href="{{ route('employes.index') }}" class="card-footer bg-white bg-opacity-10 text-white text-decoration-none d-flex align-items-center justify-content-between px-3 py-2 small">
                    <span>Voir les employés</span><i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="card widget-flat h-100" style="background: linear-gradient(135deg,#5b73e8,#3d5bd8);">
                <div class="card-body text-white">
                    <div class="float-end">
                        <i class="ri-file-text-line" style="font-size:2rem;opacity:.6;"></i>
                    </div>
                    <h6 class="text-uppercase mt-0 fw-semibold opacity-75">Contrats actifs</h6>
                    <h2 class="my-2 fw-bold">{{ $contratsActifs }}</h2>
                    <p class="mb-0 small">
                        <span class="badge bg-white bg-opacity-25 me-1">{{ $contratsExpirantBientot->count() }}</span>
                        expirent dans 30 j.
                    </p>
                </div>
                <a href="{{ route('contrats.index') }}" class="card-footer bg-white bg-opacity-10 text-white text-decoration-none d-flex align-items-center justify-content-between px-3 py-2 small">
                    <span>Voir les contrats</span><i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="card widget-flat h-100" style="background: linear-gradient(135deg,#f77e53,#e05b2d);">
                <div class="card-body text-white">
                    <div class="float-end">
                        <i class="ri-money-dollar-circle-line" style="font-size:2rem;opacity:.6;"></i>
                    </div>
                    <h6 class="text-uppercase mt-0 fw-semibold opacity-75">Masse salariale</h6>
                    <h2 class="my-2 fw-bold">{{ number_format($masseSalarialeMois, 0, ',', ' ') }}</h2>
                    <p class="mb-0 small">
                        <span class="badge bg-white bg-opacity-25 me-1">{{ $bulletinsMois }}</span>
                        bulletins ce mois
                    </p>
                </div>
                <a href="{{ route('bulletins.index') }}" class="card-footer bg-white bg-opacity-10 text-white text-decoration-none d-flex align-items-center justify-content-between px-3 py-2 small">
                    <span>Voir les bulletins</span><i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="card widget-flat h-100" style="background: linear-gradient(135deg,#47ad77,#2e8b57);">
                <div class="card-body text-white">
                    <div class="float-end">
                        <i class="ri-time-line" style="font-size:2rem;opacity:.6;"></i>
                    </div>
                    <h6 class="text-uppercase mt-0 fw-semibold opacity-75">Pointage aujourd'hui</h6>
                    <h2 class="my-2 fw-bold">{{ $pointagesPresents }}</h2>
                    <p class="mb-0 small">
                        <span class="badge bg-white bg-opacity-25 me-1">{{ $pointagesAujourdhui }}</span>
                        pointages total
                    </p>
                </div>
                <a href="{{ route('pointages.dashboard') }}" class="card-footer bg-white bg-opacity-10 text-white text-decoration-none d-flex align-items-center justify-content-between px-3 py-2 small">
                    <span>Voir le pointage</span><i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>

    </div>
    {{-- fin KPI --}}

    {{-- ── Graphique + Répartition ──────────────────────────────────────── --}}
    <div class="row g-3 mb-4">

        {{-- Graphique masse salariale --}}
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between bg-transparent border-bottom-0 pt-3">
                    <h5 class="card-title mb-0" id="titre-salaire">
                        <i class="ri-bar-chart-2-line me-2 text-primary"></i>Masse salariale — {{ last($labelsGraphique) }}
                    </h5>
                    <select id="select-annee" class="form-select form-select-sm w-auto">
                        @foreach($labelsGraphique as $annee)
                            <option value="{{ $annee }}" @if($loop->last) selected @endif>{{ $annee }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="card-body pt-0">
                    <div id="chart-salaire" style="min-height:260px;"></div>
                </div>
            </div>
        </div>

        {{-- Répartition par département --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-transparent border-bottom-0 pt-3">
                    <h5 class="card-title mb-0">
                        <i class="ri-pie-chart-line me-2 text-info"></i>Employés par département
                    </h5>
                </div>
                <div class="card-body pt-0">
                    @if($repartitionDept->isEmpty())
                        <p class="text-muted text-center mt-4">Aucune donnée</p>
                    @else
                        <div id="chart-dept" style="min-height:220px;"></div>
                        <ul class="list-unstyled mb-0 mt-2">
                            @foreach($repartitionDept as $item)
                            <li class="d-flex align-items-center justify-content-between py-1 border-bottom">
                                <span class="text-truncate small">{{ $item['nom'] }}</span>
                                <span class="badge bg-primary-subtle text-primary fw-semibold ms-2">{{ $item['total'] }}</span>
                            </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

    </div>
    {{-- fin graphiques --}}

    {{-- ── Alertes contrats + Derniers employés ────────────────────────── --}}
    <div class="row g-3">

        {{-- Contrats expirant bientôt --}}
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header bg-transparent d-flex align-items-center justify-content-between pt-3">
                    <h5 class="card-title mb-0">
                        <i class="ri-alarm-warning-line me-2 text-warning"></i>Contrats expirant bientôt
                    </h5>
                    <a href="{{ route('contrats.index') }}" class="btn btn-sm btn-outline-secondary">Tous</a>
                </div>
                <div class="card-body p-0">
                    @if($contratsExpirantBientot->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="ri-checkbox-circle-line fs-1 d-block mb-2 text-success"></i>
                            Aucun contrat n'expire dans les 30 prochains jours.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Employé</th>
                                        <th>Type</th>
                                        <th>Expiration</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contratsExpirantBientot as $contrat)
                                    @php
                                        $jours = now()->diffInDays($contrat->date_fin, false);
                                        $badgeClass = $jours <= 7 ? 'danger' : ($jours <= 15 ? 'warning' : 'info');
                                    @endphp
                                    <tr>
                                        <td class="ps-3 fw-semibold">
                                            {{ $contrat->employe->nom ?? '—' }} {{ $contrat->employe->prenom ?? '' }}
                                        </td>
                                        <td><span class="badge bg-secondary-subtle text-secondary">{{ $contrat->type_contrat }}</span></td>
                                        <td>
                                            <span class="badge bg-{{ $badgeClass }}-subtle text-{{ $badgeClass }}">
                                                {{ $contrat->date_fin->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="text-muted small">J-{{ $jours }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Derniers employés --}}
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header bg-transparent d-flex align-items-center justify-content-between pt-3">
                    <h5 class="card-title mb-0">
                        <i class="ri-user-add-line me-2 text-success"></i>Derniers employés enregistrés
                    </h5>
                    <a href="{{ route('employes.index') }}" class="btn btn-sm btn-outline-secondary">Tous</a>
                </div>
                <div class="card-body p-0">
                    @if($derniersEmployes->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="ri-user-line fs-1 d-block mb-2"></i>
                            Aucun employé trouvé.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Nom</th>
                                        <th>Département</th>
                                        <th>Poste</th>
                                        <th>Embauché le</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($derniersEmployes as $emp)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center gap-2">
                                                @if($emp->photo_profil)
                                                    <img src="{{ asset($emp->photo_profil) }}"
                                                         class="rounded-circle"
                                                         style="width:32px;height:32px;object-fit:cover;"
                                                         alt="">
                                                @else
                                                    <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center"
                                                         style="width:32px;height:32px;min-width:32px;">
                                                        <span class="text-primary fw-bold small">
                                                            {{ strtoupper(substr($emp->prenom, 0, 1)) }}{{ strtoupper(substr($emp->nom, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                                <span class="fw-semibold">{{ $emp->prenom }} {{ $emp->nom }}</span>
                                            </div>
                                        </td>
                                        <td class="text-muted small">{{ $emp->departement->nom ?? '—' }}</td>
                                        <td class="text-muted small">{{ $emp->poste->name ?? '—' }}</td>
                                        <td class="text-muted small">
                                            {{ $emp->date_embauche ? \Carbon\Carbon::parse($emp->date_embauche)->format('d/m/Y') : '—' }}
                                        </td>
                                        <td>
                                            <a href="{{ route('employes.show', $emp->id) }}" class="btn btn-sm btn-soft-primary py-0 px-2">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </td>
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
    {{-- fin alertes + employés --}}

</div>
{{-- fin container --}}

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Graphique masse salariale ────────────────────────────────────────
    var donneesParAnnee = @json($donneesParAnnee);
    var labelsMois      = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
    var selectAnnee     = document.getElementById('select-annee');
    var anneeDefaut     = selectAnnee.value;

    var chartSalaire = new ApexCharts(document.querySelector('#chart-salaire'), {
        chart: { type: 'area', height: 260, toolbar: { show: false }, sparkline: { enabled: false } },
        series: [{ name: 'Net à payer (FCFA)', data: donneesParAnnee[anneeDefaut] || [] }],
        xaxis: { categories: labelsMois, labels: { style: { fontSize: '11px' } } },
        yaxis: { labels: { formatter: v => new Intl.NumberFormat('fr-FR').format(v) } },
        colors: ['#5b73e8'],
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } },
        stroke: { curve: 'smooth', width: 2 },
        tooltip: { y: { formatter: v => new Intl.NumberFormat('fr-FR').format(v) + ' FCFA' } },
        dataLabels: { enabled: false },
        grid: { borderColor: '#f1f3fa' },
    });
    chartSalaire.render();

    selectAnnee.addEventListener('change', function () {
        chartSalaire.updateOptions({
            xaxis: { categories: labelsMois, labels: { style: { fontSize: '11px' } } },
            series: [{ name: 'Net à payer (FCFA)', data: donneesParAnnee[this.value] || [] }],
        });
        document.getElementById('titre-salaire').innerHTML =
            '<i class="ri-bar-chart-2-line me-2 text-primary"></i>Masse salariale — ' + this.value;
    });

    @if($repartitionDept->isNotEmpty())
    // ── Graphique répartition département ─────────────────────────────────
    var deptLabels = @json($repartitionDept->pluck('nom'));
    var deptData   = @json($repartitionDept->pluck('total'));

    var optDept = {
        chart: { type: 'donut', height: 220, toolbar: { show: false } },
        series: deptData,
        labels: deptLabels,
        colors: ['#3bc0c3','#5b73e8','#f77e53','#47ad77','#f7bc53','#e05b8b','#a45be8'],
        legend: { show: false },
        dataLabels: { enabled: false },
        plotOptions: { pie: { donut: { size: '60%' } } },
        tooltip: { y: { formatter: v => v + ' employé(s)' } },
    };
    new ApexCharts(document.querySelector('#chart-dept'), optDept).render();
    @endif

});
</script>
@endpush

@endsection
