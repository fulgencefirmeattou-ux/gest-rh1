@extends('layouts.app')
@section('title', 'Tableau de bord — DG')

@section('content')
<div class="container-fluid">

    {{-- En-tête --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-1">Tableau de bord <span class="badge bg-dark ms-2 fs-12">Direction</span></h4>
                    <p class="text-muted mb-0"><i class="ri-calendar-line me-1"></i>{{ \Carbon\Carbon::now()->translatedFormat('l d F Y') }}</p>
                </div>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">DG</a></li>
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
                    <h6 class="text-uppercase mt-0 fw-semibold opacity-75">Effectif total</h6>
                    <h2 class="my-2 fw-bold">{{ $totalEmployes }}</h2>
                    <p class="mb-0 small">Employés actifs</p>
                </div>
                <a href="{{ route('employes.index') }}" class="card-footer bg-white bg-opacity-10 text-white text-decoration-none d-flex align-items-center justify-content-between px-3 py-2 small">
                    <span>Voir l'effectif</span><i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card widget-flat h-100" style="background:linear-gradient(135deg,#f77e53,#e05b2d);">
                <div class="card-body text-white">
                    <div class="float-end"><i class="ri-money-dollar-circle-line" style="font-size:2rem;opacity:.6;"></i></div>
                    <h6 class="text-uppercase mt-0 fw-semibold opacity-75">Masse salariale</h6>
                    <h2 class="my-2 fw-bold" style="font-size:1.4rem;">{{ number_format($masseSalarialeMois, 0, ',', ' ') }}</h2>
                    <p class="mb-0 small"><span class="badge bg-white bg-opacity-25 me-1">{{ $bulletinsMois }}</span>bulletins ce mois</p>
                </div>
                <a href="{{ route('bulletins.index') }}" class="card-footer bg-white bg-opacity-10 text-white text-decoration-none d-flex align-items-center justify-content-between px-3 py-2 small">
                    <span>Voir les bulletins</span><i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card widget-flat h-100" style="background:linear-gradient(135deg,#47ad77,#2e8b57);">
                <div class="card-body text-white">
                    <div class="float-end"><i class="ri-time-line" style="font-size:2rem;opacity:.6;"></i></div>
                    <h6 class="text-uppercase mt-0 fw-semibold opacity-75">Présents aujourd'hui</h6>
                    <h2 class="my-2 fw-bold">{{ $pointagesPresents }}</h2>
                    <p class="mb-0 small">Employés pointés</p>
                </div>
                <a href="{{ route('pointages.dashboard') }}" class="card-footer bg-white bg-opacity-10 text-white text-decoration-none d-flex align-items-center justify-content-between px-3 py-2 small">
                    <span>Voir le pointage</span><i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card widget-flat h-100" style="background:linear-gradient(135deg,#9c5de8,#7340d8);">
                <div class="card-body text-white">
                    <div class="float-end"><i class="ri-calendar-check-line" style="font-size:2rem;opacity:.6;"></i></div>
                    <h6 class="text-uppercase mt-0 fw-semibold opacity-75">Congés à valider</h6>
                    <h2 class="my-2 fw-bold">{{ $congesEnAttente }}</h2>
                    <p class="mb-0 small">En attente de décision</p>
                </div>
                <a href="{{ route('conges.approbation.dgRh') }}" class="card-footer bg-white bg-opacity-10 text-white text-decoration-none d-flex align-items-center justify-content-between px-3 py-2 small">
                    <span>Valider</span><i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Graphiques --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header bg-transparent border-bottom-0 pt-3">
                    <h5 class="card-title mb-0"><i class="ri-bar-chart-2-line me-2 text-primary"></i>Masse salariale — 6 derniers mois</h5>
                </div>
                <div class="card-body pt-0">
                    <div id="chart-salaire" style="min-height:260px;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-transparent border-bottom-0 pt-3">
                    <h5 class="card-title mb-0"><i class="ri-pie-chart-line me-2 text-info"></i>Effectif par département</h5>
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

    {{-- Congés à valider --}}
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent d-flex align-items-center justify-content-between pt-3">
                    <h5 class="card-title mb-0"><i class="ri-calendar-check-line me-2 text-warning"></i>Congés en attente de validation</h5>
                    <a href="{{ route('conges.approbation.dgRh') }}" class="btn btn-sm btn-primary">Traiter</a>
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
                                    <tr><th class="ps-3">Employé</th><th>Type</th><th>Du</th><th>Au</th><th>Durée</th><th>Soumis le</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($congesAValider as $c)
                                    <tr>
                                        <td class="ps-3 fw-semibold">{{ $c->employe->prenom ?? '—' }} {{ $c->employe->nom ?? '' }}</td>
                                        <td><span class="badge bg-info-subtle text-info">{{ $c->typeLabel() }}</span></td>
                                        <td class="small">{{ $c->date_debut_conge?->format('d/m/Y') }}</td>
                                        <td class="small">{{ $c->date_fin_conge?->format('d/m/Y') }}</td>
                                        <td><span class="badge bg-secondary-subtle text-secondary">{{ $c->jours_ouvres }} j</span></td>
                                        <td class="small text-muted">{{ $c->created_at->diffForHumans() }}</td>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var labels  = @json($labelsGraphique);
    var valeurs = @json($masseSalarialeParMois);

    new ApexCharts(document.querySelector('#chart-salaire'), {
        chart: { type: 'area', height: 260, toolbar: { show: false } },
        series: [{ name: 'Net à payer (FCFA)', data: valeurs }],
        xaxis: { categories: labels, labels: { style: { fontSize: '11px' } } },
        yaxis: { labels: { formatter: v => new Intl.NumberFormat('fr-FR').format(v) } },
        colors: ['#f77e53'],
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } },
        stroke: { curve: 'smooth', width: 2 },
        tooltip: { y: { formatter: v => new Intl.NumberFormat('fr-FR').format(v) + ' FCFA' } },
        dataLabels: { enabled: false },
        grid: { borderColor: '#f1f3fa' },
    }).render();

    @if($repartitionDept->isNotEmpty())
    new ApexCharts(document.querySelector('#chart-dept'), {
        chart: { type: 'donut', height: 220 },
        series: @json($repartitionDept->pluck('total')),
        labels: @json($repartitionDept->pluck('nom')),
        colors: ['#3bc0c3','#5b73e8','#f77e53','#47ad77','#f7bc53','#e05b8b','#9c5de8'],
        legend: { show: false },
        dataLabels: { enabled: false },
        plotOptions: { pie: { donut: { size: '60%' } } },
        tooltip: { y: { formatter: v => v + ' employé(s)' } },
    }).render();
    @endif
});
</script>
@endpush
