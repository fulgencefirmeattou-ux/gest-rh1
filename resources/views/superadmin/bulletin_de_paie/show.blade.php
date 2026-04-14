@extends('layouts.app')

@section('content')
<div class="container-fluid mt-3">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="ri-file-text-line me-2"></i>Bulletin de paie</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('bulletins.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ri-arrow-left-line me-1"></i>Retour
            </a>
            <a href="{{ route('bulletins.download', $bulletin) }}" class="btn btn-outline-danger btn-sm">
                <i class="ri-file-pdf-line me-1"></i>Télécharger PDF
            </a>
            @if($bulletin->statut === 'brouillon')
                <form method="POST" action="{{ route('bulletins.valider', $bulletin) }}">
                    @csrf
                    <button class="btn btn-outline-primary btn-sm"><i class="ri-check-line me-1"></i>Valider</button>
                </form>
            @endif
            @if($bulletin->statut === 'valide')
                <form method="POST" action="{{ route('bulletins.payer', $bulletin) }}">
                    @csrf
                    <button class="btn btn-success btn-sm"><i class="ri-money-dollar-circle-line me-1"></i>Marquer payé</button>
                </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    {{-- Statut --}}
    <div class="mb-3">
        <span class="badge bg-{{ $bulletin->statut_color }} fs-6">{{ $bulletin->statut_label }}</span>
        <span class="text-muted ms-2 small">Créé le {{ $bulletin->created_at->format('d/m/Y à H:i') }}</span>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- APERÇU BULLETIN (réplique du PDF)                         --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @php $entreprise = \App\Models\InfoEntreprise::instance(); @endphp
    <div class="card shadow-sm" style="max-width:900px;">
        <div class="card-body p-4" style="font-size:13px;">

            {{-- ── Ligne titre ────────────────────────────────── --}}
            <div class="row mb-3">
                <div class="col-6">
                    {{-- Logo / Infos société --}}
                    {{-- @if($entreprise->logo)
                        <img src="{{ Storage::url($entreprise->logo) }}" alt="Logo"
                             style="max-height:50px; max-width:150px; object-fit:contain;" class="mb-1 d-block">
                    @endif --}}
                    <div class="fw-bold fs-5 text-primary mb-1">{{ $entreprise->nom ?: config('app.name') }}</div>
                    <table class="table table-borderless table-sm mb-0" style="font-size:12px;">
                        <tr><td class="fw-semibold pe-2 py-0">ADRESSE :</td><td class="py-0">{{ $entreprise->adresse ?: '—' }}</td></tr>
                        <tr><td class="fw-semibold pe-2 py-0">SIÈGE SOCIAL :</td><td class="py-0">{{ $entreprise->siege_social ?: '—' }}</td></tr>
                        <tr><td class="fw-semibold pe-2 py-0">N° CNPS :</td><td class="py-0">{{ $entreprise->num_cnps ?: '—' }}</td></tr>
                        <tr><td class="fw-semibold pe-2 py-0">N° CONTRIBUABLE :</td><td class="py-0">{{ $entreprise->num_contribuable ?: '—' }}</td></tr>
                        <tr><td class="fw-semibold pe-2 py-0">E-mail :</td><td class="py-0">{{ $entreprise->email ?: '—' }}</td></tr>
                        <tr><td class="fw-semibold pe-2 py-0">TÉLÉPHONE :</td><td class="py-0">{{ $entreprise->telephone ?: '—' }}</td></tr>
                    </table>
                </div>
                <div class="col-6">
                    <div class="text-center fw-bold fs-5 border-bottom pb-1 mb-2">BULLETIN DE PAIE</div>
                    <div class="text-center small fw-semibold mb-2">
                        LA PAYE DU {{ $bulletin->periode_debut->format('d/m/Y') }} AU {{ $bulletin->periode_fin->format('d/m/Y') }}
                    </div>
                    <table class="table table-borderless table-sm mb-0" style="font-size:12px;">
                        <tr><td class="fw-semibold pe-2 py-0">Matricule :</td><td class="py-0">{{ $bulletin->employe->matricule }}</td></tr>
                        <tr><td class="fw-semibold pe-2 py-0">Nom :</td><td class="py-0">{{ $bulletin->employe->nom }}</td></tr>
                        <tr><td class="fw-semibold pe-2 py-0">Prénoms :</td><td class="py-0">{{ $bulletin->employe->prenom }}</td></tr>
                        <tr><td class="fw-semibold pe-2 py-0">Emploi :</td><td class="py-0">{{ $bulletin->employe->poste->name ?? '—' }}</td></tr>
                        <tr><td class="fw-semibold pe-2 py-0">Nbre de Part :</td><td class="py-0">{{ number_format($bulletin->nbre_parts, 2) }}</td></tr>
                        <tr><td class="fw-semibold pe-2 py-0">Date d'Embauche :</td><td class="py-0">{{ $bulletin->employe->date_embauche ? \Carbon\Carbon::parse($bulletin->employe->date_embauche)->locale('fr')->isoFormat('D MMMM YYYY') : '—' }}</td></tr>
                        <tr><td class="fw-semibold pe-2 py-0">Date de Naissance :</td><td class="py-0">{{ $bulletin->employe->date_naissance ? \Carbon\Carbon::parse($bulletin->employe->date_naissance)->locale('fr')->isoFormat('D MMMM YYYY') : '—' }}</td></tr>
                    </table>
                </div>
            </div>

            {{-- ── Table principale des rubriques ─────────────── --}}
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center" style="font-size:12px;">
                    <thead class="table-dark">
                        <tr>
                            <th style="width:70px">N° Rubriques</th>
                            <th class="text-start">LIBELLÉ</th>
                            <th>BASE</th>
                            <th>TAUX</th>
                            <th>GAINS</th>
                            <th>RETENUES</th>
                            <th>TAUX P.P</th>
                            <th>MONTANT P.P</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- 00010 Salaire de base --}}
                        <tr>
                            <td>00010</td><td class="text-start">SALAIRE DE BASE</td>
                            <td>{{ number_format($bulletin->salaire_base, 0, ',', ' ') }}</td>
                            <td>30</td>
                            <td class="fw-semibold">{{ number_format($bulletin->salaire_base, 0, ',', ' ') }}</td>
                            <td></td><td></td><td></td>
                        </tr>
                        {{-- 00020 Avantage en nature --}}
                        @if($bulletin->avantage_nature > 0)
                        <tr>
                            <td>00020</td><td class="text-start">AVANTAGE EN NATURE</td>
                            <td>{{ number_format($bulletin->avantage_nature, 0, ',', ' ') }}</td>
                            <td>30</td>
                            <td class="fw-semibold">{{ number_format($bulletin->avantage_nature, 0, ',', ' ') }}</td>
                            <td></td><td></td><td></td>
                        </tr>
                        @endif
                        {{-- 00500 Salaire brut --}}
                        <tr class="table-light fw-bold">
                            <td>00500</td><td class="text-start">SALAIRE BRUT</td>
                            <td></td><td></td>
                            <td>{{ number_format($bulletin->salaire_brut, 0, ',', ' ') }}</td>
                            <td></td><td></td><td></td>
                        </tr>
                        {{-- 00501 Brut imposable --}}
                        <tr class="fw-bold" style="border-bottom:2px solid #333">
                            <td>00501</td><td class="text-start">BRUT IMPOSABLE</td>
                            <td></td><td></td>
                            <td>{{ number_format($bulletin->brut_imposable, 0, ',', ' ') }}</td>
                            <td></td><td></td><td></td>
                        </tr>
                        {{-- Charges fiscales employeur --}}
                        <tr>
                            <td>00511</td><td class="text-start">Impôt Employeur</td>
                            <td>{{ number_format($bulletin->brut_imposable, 0, ',', ' ') }}</td>
                            <td></td><td></td><td></td>
                            <td>1,20 %</td>
                            <td>{{ number_format($bulletin->is_employeur, 0, ',', ' ') }}</td>
                        </tr>
                        <tr>
                            <td>00512</td><td class="text-start">F.D.F.P / T.A</td>
                            <td>{{ number_format($bulletin->brut_imposable, 0, ',', ' ') }}</td>
                            <td></td><td></td><td></td>
                            <td>0,40 %</td>
                            <td>{{ number_format($bulletin->fdfp_ta, 0, ',', ' ') }}</td>
                        </tr>
                        <tr>
                            <td>00513</td><td class="text-start">F.D.F.P / F.P.C</td>
                            <td>{{ number_format($bulletin->brut_imposable, 0, ',', ' ') }}</td>
                            <td></td><td></td><td></td>
                            <td>0,60 %</td>
                            <td>{{ number_format($bulletin->fdfp_fpc, 0, ',', ' ') }}</td>
                        </tr>
                        <tr class="table-light fw-semibold">
                            <td colspan="7" class="text-end">Total Charges Fiscales Employeurs</td>
                            <td>{{ number_format($bulletin->total_charges_fiscales_emp, 0, ',', ' ') }}</td>
                        </tr>
                        {{-- Charges sociales employeur --}}
                        <tr>
                            <td>00520</td><td class="text-start">C.N.P.S / Prestation Familiale</td>
                            <td>{{ number_format($bulletin->cnps_plafond, 0, ',', ' ') }}</td>
                            <td></td><td></td><td></td>
                            <td>5,75 %</td>
                            <td>{{ number_format($bulletin->cnps_pf_emp, 0, ',', ' ') }}</td>
                        </tr>
                        <tr>
                            <td>00521</td><td class="text-start">C.N.P.S / Accident de Travail</td>
                            <td>{{ number_format($bulletin->cnps_plafond, 0, ',', ' ') }}</td>
                            <td></td><td></td><td></td>
                            <td>5,00 %</td>
                            <td>{{ number_format($bulletin->cnps_at_emp, 0, ',', ' ') }}</td>
                        </tr>
                        <tr>
                            <td>00522</td><td class="text-start">C.N.P.S / Caisse de Retraite</td>
                            <td>{{ number_format($bulletin->salaire_brut, 0, ',', ' ') }}</td>
                            <td></td><td></td><td></td>
                            <td>7,70 %</td>
                            <td>{{ number_format($bulletin->cnps_retraite_emp, 0, ',', ' ') }}</td>
                        </tr>
                        <tr>
                            <td>00523</td><td class="text-start">CMU / ASSURANCE MALADIE</td>
                            <td>{{ number_format($bulletin->cmu_base, 0, ',', ' ') }}</td>
                            <td></td><td></td><td></td>
                            <td>50,00 %</td>
                            <td>{{ number_format($bulletin->cmu_emp, 0, ',', ' ') }}</td>
                        </tr>
                        <tr class="table-light fw-semibold">
                            <td colspan="7" class="text-end">Total Charges Sociales Employeurs</td>
                            <td>{{ number_format($bulletin->total_charges_sociales_emp, 0, ',', ' ') }}</td>
                        </tr>
                        <tr class="fw-bold">
                            <td colspan="7" class="text-end">Total Charges Patronales</td>
                            <td>{{ number_format($bulletin->total_charges_patronales, 0, ',', ' ') }}</td>
                        </tr>
                        {{-- Retenues salariales --}}
                        <tr>
                            <td>00540</td><td class="text-start">Retenue Impôt sur le Salaire</td>
                            <td>{{ number_format($bulletin->brut_imposable, 0, ',', ' ') }}</td>
                            <td>1,20 %</td>
                            <td></td>
                            <td>{{ number_format($bulletin->retenue_is, 0, ',', ' ') }}</td>
                            <td></td><td></td>
                        </tr>
                        <tr>
                            <td>00541</td><td class="text-start">Retenue Contribution Nationale</td>
                            <td>{{ number_format($bulletin->brut_imposable, 0, ',', ' ') }}</td>
                            <td></td><td></td>
                            <td>{{ number_format($bulletin->retenue_cn, 0, ',', ' ') }}</td>
                            <td></td><td></td>
                        </tr>
                        <tr>
                            <td>00542</td><td class="text-start">Retenue Impôt Général sur le Revenu</td>
                            <td>{{ number_format($bulletin->brut_imposable, 0, ',', ' ') }}</td>
                            <td></td><td></td>
                            <td>{{ number_format($bulletin->retenue_igr, 0, ',', ' ') }}</td>
                            <td></td><td></td>
                        </tr>
                        <tr>
                            <td>00543</td><td class="text-start">Retenue Caisse Nationale de Prévoyance Sociale</td>
                            <td>{{ number_format($bulletin->salaire_brut, 0, ',', ' ') }}</td>
                            <td>6,30 %</td><td></td>
                            <td>{{ number_format($bulletin->retenue_cnps, 0, ',', ' ') }}</td>
                            <td></td><td></td>
                        </tr>
                        <tr>
                            <td>00544</td><td class="text-start">Retenue Couverture Maladie Universelle</td>
                            <td>{{ number_format($bulletin->cmu_base, 0, ',', ' ') }}</td>
                            <td>50,00 %</td><td></td>
                            <td>{{ number_format($bulletin->retenue_cmu, 0, ',', ' ') }}</td>
                            <td></td><td></td>
                        </tr>
                        <tr class="table-danger fw-bold">
                            <td colspan="5" class="text-end">Total Retenues</td>
                            <td>{{ number_format($bulletin->total_retenues, 0, ',', ' ') }}</td>
                            <td colspan="2"></td>
                        </tr>
                        {{-- Salaire net --}}
                        <tr class="table-success fw-bold">
                            <td colspan="4" class="text-end">SALAIRE NET</td>
                            <td>{{ number_format($bulletin->salaire_net, 0, ',', ' ') }}</td>
                            <td colspan="3"></td>
                        </tr>
                        {{-- Indemnité transport --}}
                        @if($bulletin->indemnite_transport > 0)
                        <tr>
                            <td>00630</td><td class="text-start">INDEMNITÉ DE TRANSPORT NON IMPOSABLE</td>
                            <td>{{ number_format($bulletin->indemnite_transport, 0, ',', ' ') }}</td>
                            <td>30</td>
                            <td>{{ number_format($bulletin->indemnite_transport, 0, ',', ' ') }}</td>
                            <td colspan="3"></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- ── Cumul de paie ──────────────────────────────── --}}
            <div class="row mt-2">
                <div class="col-6">
                    <table class="table table-bordered table-sm text-center" style="font-size:12px;">
                        <tr class="table-secondary fw-bold"><td colspan="4">CUMUL DE PAIE</td></tr>
                        <tr>
                            <td class="text-start">GAINS</td>
                            <td colspan="3">{{ number_format($bulletin->cumul_gains ?? ($bulletin->salaire_brut + $bulletin->indemnite_transport), 0, ',', ' ') }} F</td>
                        </tr>
                        <tr>
                            <td class="text-start">RETENUES</td>
                            <td colspan="3">{{ number_format($bulletin->cumul_retenues ?? $bulletin->total_retenues, 0, ',', ' ') }} F</td>
                        </tr>
                    </table>
                </div>
                <div class="col-6">
                    <table class="table table-bordered table-sm text-center" style="font-size:12px;">
                        <tr>
                            <td class="fw-semibold">C.N.P.S Employé</td>
                            <td class="fw-bold text-success fs-6">NET À PAYER</td>
                        </tr>
                        <tr>
                            <td>{{ number_format($bulletin->retenue_cnps, 0, ',', ' ') }} F</td>
                            <td class="fw-bold fs-5 text-success">{{ number_format($bulletin->net_a_payer, 0, ',', ' ') }} F</td>
                        </tr>
                        <tr>
                            <td>RÈGLEMENT :</td>
                            <td>{{ $bulletin->mode_reglement ?? '—' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- ── Footer ─────────────────────────────────────── --}}
            @if($bulletin->base_conge || $bulletin->jours_fiscaux || $bulletin->brut_imposable_cumul)
            <table class="table table-bordered table-sm text-center mt-1" style="font-size:12px;">
                <tr class="table-secondary">
                    <td class="fw-semibold">Base Congé</td>
                    <td class="fw-semibold">Jours Fiscaux</td>
                    <td class="fw-semibold">Brut Imposable Cumulé</td>
                    <td class="fw-semibold">Référence Virement</td>
                </tr>
                <tr>
                    <td>{{ $bulletin->base_conge    ? number_format($bulletin->base_conge, 0, ',', ' ').' F' : '—' }}</td>
                    <td>{{ $bulletin->jours_fiscaux ?? '—' }}</td>
                    <td>{{ $bulletin->brut_imposable_cumul ? number_format($bulletin->brut_imposable_cumul, 0, ',', ' ').' F' : '—' }}</td>
                    <td>{{ $bulletin->reference_virement ?? '—' }}</td>
                </tr>
            </table>
            @endif

        </div>
    </div>

</div>
@endsection
