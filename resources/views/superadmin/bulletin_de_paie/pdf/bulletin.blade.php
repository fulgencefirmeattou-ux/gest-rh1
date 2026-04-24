<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin de paie — {{ $bulletin->employe->nom }} {{ $bulletin->employe->prenom }} — {{ $bulletin->mois }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #111;
            padding: 14px 16px;
        }

        /* ── En-tête ──────────────────────────────────────────── */
        .header { width:100%; border-collapse:collapse; margin-bottom:6px; }
        .header td { vertical-align:top; padding:2px 4px; }
        .header .col-gauche { width:48%; border:1px solid #999; padding:6px; }
        .header .col-droite { width:52%; border:1px solid #999; padding:6px; }
        .company-name { font-size:13px; font-weight:bold; color:#003399; margin-bottom:4px; }
        .bp-titre     { font-size:13px; font-weight:bold; text-align:center; border-bottom:1px solid #333; padding-bottom:3px; margin-bottom:4px; letter-spacing:1px; }
        .bp-periode   { font-size:9px; font-weight:bold; text-align:center; margin-bottom:6px; }
        .info-table   { width:100%; border-collapse:collapse; font-size:8.5px; }
        .info-table td { padding:1px 0; }
        .info-table .lbl { font-weight:bold; white-space:nowrap; padding-right:4px; }

        /* ── Table principale ─────────────────────────────────── */
        .main-table { width:100%; border-collapse:collapse; font-size:8px; margin-top:4px; }
        .main-table th {
            background:#1a1a2e; color:#fff;
            padding:4px 3px; text-align:center;
            border:1px solid #444;
        }
        .main-table td {
            padding:2px 3px;
            border:1px solid #ccc;
            text-align:center;
            white-space:nowrap;
        }
        .main-table .td-libelle { text-align:left; }
        .row-brut   { background:#e8f5e9; font-weight:bold; }
        .row-bi     { background:#e3f2fd; font-weight:bold; border-bottom:2px solid #333; }
        .row-subtot { background:#f5f5f5; font-weight:bold; }
        .row-net    { background:#c8e6c9; font-weight:bold; }
        .row-retenu { background:#ffebee; font-weight:bold; }
        .num        { text-align:right; }

        /* ── Bas de page ──────────────────────────────────────── */
        .bottom { width:100%; border-collapse:collapse; margin-top:4px; font-size:8.5px; }
        .bottom td { border:1px solid #ccc; padding:3px 5px; vertical-align:top; }
        .cumul-table { width:100%; border-collapse:collapse; font-size:8.5px; }
        .cumul-table td { border:1px solid #ccc; padding:3px 5px; }
        .net-table { width:100%; border-collapse:collapse; font-size:9px; }
        .net-table td { border:1px solid #ccc; padding:3px 5px; }
        .footer-strip { width:100%; border-collapse:collapse; margin-top:3px; font-size:8px; }
        .footer-strip td { border:1px solid #ccc; padding:3px 5px; }
        .bg-header   { background:#2c3e50; color:#fff; font-weight:bold; text-align:center; }
        .bg-net      { background:#1a7a3e; color:#fff; font-weight:bold; font-size:11px; text-align:center; }
        .text-right  { text-align:right; }
        .text-center { text-align:center; }
        .bold        { font-weight:bold; }
    </style>
</head>
<body>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- EN-TÊTE                                                        --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<table class="header">
    <tr>
        {{-- Gauche : société --}}
        @php $entreprise = \App\Models\InfoEntreprise::instance(); @endphp
        <td class="col-gauche">
            <div class="company-name">{{ $entreprise->nom ?: config('app.name') }}</div>
            <table class="info-table">
                <tr><td class="lbl">ADRESSE :</td><td>{{ $entreprise->adresse ?: '—' }}</td></tr>
                <tr><td class="lbl">SIÈGE SOCIAL :</td><td>{{ $entreprise->siege_social ?: '—' }}</td></tr>
                <tr><td class="lbl">N° CNPS :</td><td>{{ $entreprise->num_cnps ?: '—' }}</td></tr>
                <tr><td class="lbl">N° CONTRIBUABLE :</td><td>{{ $entreprise->num_contribuable ?: '—' }}</td></tr>
                <tr><td class="lbl">E-mail :</td><td>{{ $entreprise->email ?: '—' }}</td></tr>
                <tr><td class="lbl">TÉLÉPHONE :</td><td>{{ $entreprise->telephone ?: '—' }}</td></tr>
            </table>
        </td>
        {{-- Droite : bulletin --}}
        <td class="col-droite">
            <div class="bp-titre">BULLETIN DE PAIE</div>
            <div class="bp-periode">
                LA PAYE DU {{ $bulletin->periode_debut->format('d/m/Y') }}
                AU {{ $bulletin->periode_fin->format('d/m/Y') }}
            </div>
            <table class="info-table">
                <tr>
                    <td class="lbl">Matricule :</td><td>{{ $bulletin->employe->matricule }}</td>
                    <td class="lbl" style="padding-left:10px">Nbre de Part :</td><td>{{ number_format($bulletin->nbre_parts,2) }}</td>
                </tr>
                <tr>
                    <td class="lbl">Nom :</td><td>{{ $bulletin->employe->nom }}</td>
                    <td class="lbl" style="padding-left:10px">Date d'Embauche :</td><td>{{ $bulletin->employe->date_embauche ? \Carbon\Carbon::parse($bulletin->employe->date_embauche)->format('d/m/Y') : '—' }}</td>
                </tr>
                <tr>
                    <td class="lbl">Prénoms :</td><td>{{ $bulletin->employe->prenom }}</td>
                    <td class="lbl" style="padding-left:10px">Date de Naissance :</td><td>{{ $bulletin->employe->date_naissance ? \Carbon\Carbon::parse($bulletin->employe->date_naissance)->format('d/m/Y') : '—' }}</td>
                </tr>
                <tr>
                    <td class="lbl">Emploi :</td><td colspan="3">{{ $bulletin->employe->poste->name ?? '—' }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- TABLE PRINCIPALE                                               --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<table class="main-table">
    <thead>
        <tr>
            <th style="width:55px">N° Rubriques</th>
            <th style="width:auto; text-align:left">LIBELLÉ</th>
            <th style="width:70px">BASE</th>
            <th style="width:40px">TAUX</th>
            <th style="width:75px">GAINS</th>
            <th style="width:75px">RETENUES</th>
            <th style="width:55px">TAUX P.P</th>
            <th style="width:75px">MONTANT P.P</th>
        </tr>
    </thead>
    <tbody>
        {{-- 00010 Salaire de base --}}
        <tr>
            <td>00010</td>
            <td class="td-libelle">SALAIRE DE BASE</td>
            <td class="num">{{ number_format($bulletin->salaire_base, 0, ',', ' ') }}</td>
            <td>30</td>
            <td class="num bold">{{ number_format($bulletin->salaire_base, 0, ',', ' ') }}</td>
            <td></td><td></td><td></td>
        </tr>
        @if($bulletin->avantage_nature > 0)
        <tr>
            <td>00020</td>
            <td class="td-libelle">AVANTAGE EN NATURE</td>
            <td class="num">{{ number_format($bulletin->avantage_nature, 0, ',', ' ') }}</td>
            <td>30</td>
            <td class="num bold">{{ number_format($bulletin->avantage_nature, 0, ',', ' ') }}</td>
            <td></td><td></td><td></td>
        </tr>
        @endif
        {{-- 00500 Salaire brut --}}
        <tr class="row-brut">
            <td>00500</td>
            <td class="td-libelle">SALAIRE BRUT</td>
            <td></td><td></td>
            <td class="num">{{ number_format($bulletin->salaire_brut, 0, ',', ' ') }}</td>
            <td></td><td></td><td></td>
        </tr>
        {{-- 00501 Brut imposable --}}
        <tr class="row-bi">
            <td>00501</td>
            <td class="td-libelle">BRUT IMPOSABLE</td>
            <td></td><td></td>
            <td class="num">{{ number_format($bulletin->brut_imposable, 0, ',', ' ') }}</td>
            <td></td><td></td><td></td>
        </tr>
        {{-- Charges fiscales employeur --}}
        <tr>
            <td>00511</td><td class="td-libelle">Impôt Employeur</td>
            <td class="num">{{ number_format($bulletin->brut_imposable, 0, ',', ' ') }}</td>
            <td></td><td></td><td></td>
            <td>1,20 %</td>
            <td class="num">{{ number_format($bulletin->is_employeur, 0, ',', ' ') }}</td>
        </tr>
        <tr>
            <td>00512</td><td class="td-libelle">F.D.F.P / T.A</td>
            <td class="num">{{ number_format($bulletin->brut_imposable, 0, ',', ' ') }}</td>
            <td></td><td></td><td></td>
            <td>0,40 %</td>
            <td class="num">{{ number_format($bulletin->fdfp_ta, 0, ',', ' ') }}</td>
        </tr>
        <tr>
            <td>00513</td><td class="td-libelle">F.D.F.P / F.P.C</td>
            <td class="num">{{ number_format($bulletin->brut_imposable, 0, ',', ' ') }}</td>
            <td></td><td></td><td></td>
            <td>0,60 %</td>
            <td class="num">{{ number_format($bulletin->fdfp_fpc, 0, ',', ' ') }}</td>
        </tr>
        <tr class="row-subtot">
            <td colspan="7" style="text-align:right; font-style:italic;">Total Charges Fiscales Employeurs</td>
            <td class="num bold">{{ number_format($bulletin->total_charges_fiscales_emp, 0, ',', ' ') }}</td>
        </tr>
        <tr>
            <td>00520</td><td class="td-libelle">C.N.P.S / Prestation Familiale</td>
            <td class="num">{{ number_format($bulletin->cnps_plafond, 0, ',', ' ') }}</td>
            <td></td><td></td><td></td>
            <td>5,75 %</td>
            <td class="num">{{ number_format($bulletin->cnps_pf_emp, 0, ',', ' ') }}</td>
        </tr>
        <tr>
            <td>00521</td><td class="td-libelle">C.N.P.S / Accident de Travail</td>
            <td class="num">{{ number_format($bulletin->cnps_plafond, 0, ',', ' ') }}</td>
            <td></td><td></td><td></td>
            <td>5,00 %</td>
            <td class="num">{{ number_format($bulletin->cnps_at_emp, 0, ',', ' ') }}</td>
        </tr>
        <tr>
            <td>00522</td><td class="td-libelle">C.N.P.S / Caisse de Retraite</td>
            <td class="num">{{ number_format($bulletin->salaire_brut, 0, ',', ' ') }}</td>
            <td></td><td></td><td></td>
            <td>7,70 %</td>
            <td class="num">{{ number_format($bulletin->cnps_retraite_emp, 0, ',', ' ') }}</td>
        </tr>
        <tr>
            <td>00523</td><td class="td-libelle">CMU / ASSURANCE MALADIE</td>
            <td class="num">{{ number_format($bulletin->cmu_base, 0, ',', ' ') }}</td>
            <td></td><td></td><td></td>
            <td>50,00 %</td>
            <td class="num">{{ number_format($bulletin->cmu_emp, 0, ',', ' ') }}</td>
        </tr>
        <tr class="row-subtot">
            <td colspan="7" style="text-align:right; font-style:italic;">Total Charges Sociales Employeurs</td>
            <td class="num bold">{{ number_format($bulletin->total_charges_sociales_emp, 0, ',', ' ') }}</td>
        </tr>
        <tr class="bold" style="background:#e0e0e0">
            <td colspan="7" style="text-align:right;">Total Charges Patronales</td>
            <td class="num">{{ number_format($bulletin->total_charges_patronales, 0, ',', ' ') }}</td>
        </tr>
        {{-- Retenues salariales --}}
        <tr>
            <td>00540</td><td class="td-libelle">Retenue Impôt sur le Salaire</td>
            <td class="num">{{ number_format($bulletin->brut_imposable, 0, ',', ' ') }}</td>
            <td>1,20 %</td><td></td>
            <td class="num">{{ number_format($bulletin->retenue_is, 0, ',', ' ') }}</td>
            <td></td><td></td>
        </tr>
        <tr>
            <td>00541</td><td class="td-libelle">Retenue Contribution Nationale</td>
            <td class="num">{{ number_format($bulletin->brut_imposable, 0, ',', ' ') }}</td>
            <td></td><td></td>
            <td class="num">{{ number_format($bulletin->retenue_cn, 0, ',', ' ') }}</td>
            <td></td><td></td>
        </tr>
        <tr>
            <td>00542</td><td class="td-libelle">Retenue Inpôt Général sur le Revenu</td>
            <td class="num">{{ number_format($bulletin->brut_imposable, 0, ',', ' ') }}</td>
            <td></td><td></td>
            <td class="num">{{ number_format($bulletin->retenue_igr, 0, ',', ' ') }}</td>
            <td></td><td></td>
        </tr>
        <tr>
            <td>00543</td><td class="td-libelle">Retenue Caisse Nationale de Prévoyance Sociale</td>
            <td class="num">{{ number_format($bulletin->salaire_brut, 0, ',', ' ') }}</td>
            <td>6,30 %</td><td></td>
            <td class="num">{{ number_format($bulletin->retenue_cnps, 0, ',', ' ') }}</td>
            <td></td><td></td>
        </tr>
        <tr>
            <td>00544</td><td class="td-libelle">Retenue Couverture Maladie Universelle</td>
            <td class="num">{{ number_format($bulletin->cmu_base, 0, ',', ' ') }}</td>
            <td>50,00 %</td><td></td>
            <td class="num">{{ number_format($bulletin->retenue_cmu, 0, ',', ' ') }}</td>
            <td></td><td></td>
        </tr>
        <tr class="row-retenu">
            <td colspan="4" style="text-align:right;">Total Retenues</td>
            <td></td>
            <td class="num">{{ number_format($bulletin->total_retenues, 0, ',', ' ') }}</td>
            <td colspan="2"></td>
        </tr>
        {{-- Salaire net --}}
        <tr class="row-net">
            <td colspan="4" style="text-align:right;">SALAIRE NET</td>
            <td class="num">{{ number_format($bulletin->salaire_net, 0, ',', ' ') }}</td>
            <td colspan="3"></td>
        </tr>
        {{-- Indemnité transport --}}
        @if($bulletin->indemnite_transport > 0)
        <tr>
            <td>00630</td>
            <td class="td-libelle">INDEMNITÉ DE TRANSPORT NON IMPOSABLE</td>
            <td class="num">{{ number_format($bulletin->indemnite_transport, 0, ',', ' ') }}</td>
            <td>30</td>
            <td class="num">{{ number_format($bulletin->indemnite_transport, 0, ',', ' ') }}</td>
            <td colspan="3"></td>
        </tr>
        @endif
        @if($bulletin->retenue_absences > 0)
        <tr style="background:#fff3cd;">
            <td>00640</td>
            <td class="td-libelle">RETENUE SUR SALAIRE HEURES D'ABSENCE</td>
            <td class="num">{{ number_format($bulletin->heures_absence, 1, ',', ' ') }} h</td>
            <td></td><td></td>
            <td class="num" style="color:#c0392b;font-weight:bold;">{{ number_format($bulletin->retenue_absences, 0, ',', ' ') }}</td>
            <td colspan="2"></td>
        </tr>
        @endif
    </tbody>
</table>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- BAS DE PAGE                                                    --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<table class="bottom" style="margin-top:5px;">
    <tr>
        {{-- Cumul de paie --}}
        <td style="width:50%; vertical-align:top;">
            <table class="cumul-table">
                <tr><td colspan="2" class="bg-header">CUMUL DE PAIE</td></tr>
                <tr>
                    <td style="width:50%">GAINS</td>
                    <td class="text-right bold">{{ number_format($bulletin->cumul_gains ?? ($bulletin->salaire_brut + $bulletin->indemnite_transport), 0, ',', ' ') }}</td>
                </tr>
                <tr>
                    <td>RETENUES</td>
                    <td class="text-right bold">{{ number_format($bulletin->cumul_retenues ?? $bulletin->total_retenues, 0, ',', ' ') }}</td>
                </tr>
                <tr>
                    <td>C.N</td>
                    <td class="text-right">{{ number_format($bulletin->retenue_cn, 0, ',', ' ') }}</td>
                </tr>
                <tr>
                    <td>I.G.R</td>
                    <td class="text-right">{{ number_format($bulletin->retenue_igr, 0, ',', ' ') }}</td>
                </tr>
            </table>
        </td>
        {{-- Net à payer --}}
        <td style="width:50%; vertical-align:top;">
            <table class="net-table">
                <tr>
                    <td style="width:50%">C.N.P.S Employé</td>
                    <td class="text-right bold">{{ number_format($bulletin->retenue_cnps, 0, ',', ' ') }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="bg-net">NET À PAYER : {{ number_format($bulletin->net_a_payer, 0, ',', ' ') }} F CFA</td>
                </tr>
                <tr>
                    <td>RÈGLEMENT :</td>
                    <td>{{ $bulletin->mode_reglement ?? '—' }}</td>
                </tr>
                <tr>
                    <td>Virement :</td>
                    <td>{{ $bulletin->reference_virement ?? '—' }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{-- Footer extra --}}
@if($bulletin->base_conge || $bulletin->jours_fiscaux || $bulletin->brut_imposable_cumul)
<table class="footer-strip">
    <tr class="bold" style="background:#ddd;">
        <td style="width:25%">Base Congé</td>
        <td style="width:25%">Jours Fiscaux</td>
        <td style="width:25%">I.S</td>
        <td style="width:25%">Brut Imposable</td>
    </tr>
    <tr>
        <td class="text-right">{{ $bulletin->base_conge ? number_format($bulletin->base_conge, 0, ',', ' ') : '—' }}</td>
        <td class="text-center">{{ $bulletin->jours_fiscaux ?? '—' }}</td>
        <td class="text-right">{{ number_format($bulletin->retenue_is, 0, ',', ' ') }}</td>
        <td class="text-right">{{ $bulletin->brut_imposable_cumul ? number_format($bulletin->brut_imposable_cumul, 0, ',', ' ') : number_format($bulletin->brut_imposable, 0, ',', ' ') }}</td>
    </tr>
</table>
@endif

<div style="margin-top:10px; font-size:7px; color:#888; text-align:center;">
    Document généré automatiquement — {{ config('app.name') }} — {{ now()->format('d/m/Y à H:i') }}
</div>

</body>
</html>
