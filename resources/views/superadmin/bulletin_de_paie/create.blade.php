@extends('layouts.app')

@section('content')
<div class="container-fluid mt-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="ri-file-add-line me-2"></i>Nouveau bulletin de paie</h2>
        <a href="{{ route('bulletins.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="ri-arrow-left-line me-1"></i>Retour
        </a>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('bulletins.store') }}" id="formBulletin">
        @csrf

        <div class="row g-3">

            {{-- ── Colonne gauche : sélection & gains ─────────────────── --}}
            <div class="col-lg-7">

                {{-- Employé & période --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header fw-semibold bg-dark text-white py-2">
                        <i class="ri-user-line me-1"></i>Employé & Période
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label small">Employé <span class="text-danger">*</span></label>
                                <select name="employe_id" id="employe_id" class="form-select form-select-sm" required>
                                    <option value="">— Sélectionner —</option>
                                    @foreach($employes as $emp)
                                    <option value="{{ $emp->id }}" {{ old('employe_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->matricule }} — {{ $emp->nom }} {{ $emp->prenom }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Mois <span class="text-danger">*</span></label>
                                <input type="month" name="mois" id="mois" class="form-control form-control-sm" value="{{ old('mois', $moisCourant) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Nbre de parts</label>
                                <input type="number" name="nbre_parts" id="nbre_parts" class="form-control form-control-sm calcul" value="{{ old('nbre_parts', 1) }}" step="0.5" min="0.5">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Mode règlement</label>
                                <select name="mode_reglement" class="form-select form-select-sm">
                                    <option value="Virement" {{ old('mode_reglement') === 'Virement' ? 'selected' : '' }}>Virement</option>
                                    <option value="Espèces" {{ old('mode_reglement') === 'Espèces'  ? 'selected' : '' }}>Espèces</option>
                                    <option value="Chèque" {{ old('mode_reglement') === 'Chèque'   ? 'selected' : '' }}>Chèque</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Référence virement</label>
                                <input type="text" name="reference_virement" class="form-control form-control-sm" value="{{ old('reference_virement') }}" placeholder="Ex: 0100 123456789">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Gains --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header fw-semibold bg-success text-white py-2">
                        <i class="ri-add-circle-line me-1"></i>Gains
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small">Salaire de base <span class="text-danger">*</span></label>
                                <input type="number" name="salaire_base" id="salaire_base" class="form-control form-control-sm calcul" value="{{ old('salaire_base') }}" min="0" step="1" required readonly style="background:#e9ecef;cursor:not-allowed;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Avantage en nature</label>
                                <input type="number" name="avantage_nature" id="avantage_nature" class="form-control form-control-sm calcul" value="{{ old('avantage_nature', 0) }}" min="0" step="1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Indemnité transport <span class="text-muted">(non imposable)</span></label>
                                <input type="number" name="indemnite_transport" id="indemnite_transport" class="form-control form-control-sm calcul" value="{{ old('indemnite_transport', 0) }}" min="0" step="1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Heures d'absence <span class="text-muted">(retenue auto)</span></label>
                                <input type="number" name="heures_absence" id="heures_absence" class="form-control form-control-sm calcul" value="{{ old('heures_absence', 0) }}" min="0" step="0.5" readonly style="background:#e9ecef;cursor:not-allowed;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Brut imposable</label>
                                <input type="number" name="brut_imposable" id="brut_imposable" class="form-control form-control-sm calcul" value="{{ old('brut_imposable') }}" min="0" step="1" placeholder="= salaire brut si vide">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Plafond CNPS (défaut 70 000)</label>
                                <input type="number" name="cnps_plafond" id="cnps_plafond" class="form-control form-control-sm calcul" value="{{ old('cnps_plafond', 70000) }}" min="0" step="1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Base CMU (défaut 1 000)</label>
                                <input type="number" name="cmu_base" id="cmu_base" class="form-control form-control-sm calcul" value="{{ old('cmu_base', 1000) }}" min="0" step="1">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Cumul paie / Footer --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header fw-semibold py-2">
                        <i class="ri-bar-chart-grouped-line me-1"></i>Cumul & Informations complémentaires
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small">Cumul gains</label>
                                <input type="number" name="cumul_gains" class="form-control form-control-sm" value="{{ old('cumul_gains') }}" min="0" step="1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Cumul retenues</label>
                                <input type="number" name="cumul_retenues" class="form-control form-control-sm" value="{{ old('cumul_retenues') }}" min="0" step="1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Brut imposable cumulé</label>
                                <input type="number" name="brut_imposable_cumul" class="form-control form-control-sm" value="{{ old('brut_imposable_cumul') }}" min="0" step="1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Base congé</label>
                                <input type="number" name="base_conge" class="form-control form-control-sm" value="{{ old('base_conge') }}" min="0" step="1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Jours fiscaux</label>
                                <input type="number" name="jours_fiscaux" class="form-control form-control-sm" value="{{ old('jours_fiscaux') }}" min="0">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── Colonne droite : cotisations & preview ──────────────── --}}
            <div class="col-lg-5">

                {{-- Aperçu des montants calculés --}}
                <div class="card shadow-sm mb-3 border-primary">
                    <div class="card-header fw-semibold bg-primary text-white py-2 d-flex justify-content-between">
                        <span><i class="ri-calculator-line me-1"></i>Calcul automatique CI</span>
                        <button type="button" id="btnCalculer" class="btn btn-light btn-sm py-0">
                            <i class="ri-refresh-line me-1"></i>Recalculer
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm table-bordered mb-0" style="font-size:12px;">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="2" class="text-center bg-success bg-opacity-10">Charges patronales (Taux P.P)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Impôt Employeur (IS 1,20 %)</td>
                                    <td class="text-end" id="p_is_employeur">—</td>
                                </tr>
                                <tr>
                                    <td>F.D.F.P / T.A (0,40 %)</td>
                                    <td class="text-end" id="p_fdfp_ta">—</td>
                                </tr>
                                <tr>
                                    <td>F.D.F.P / F.P.C (0,60 %)</td>
                                    <td class="text-end" id="p_fdfp_fpc">—</td>
                                </tr>
                                <tr class="table-light fw-semibold">
                                    <td>Total charges fiscales emp.</td>
                                    <td class="text-end" id="p_total_fisc_emp">—</td>
                                </tr>
                                <tr>
                                    <td>C.N.P.S / Prest. Familiale (5,75 %)</td>
                                    <td class="text-end" id="p_cnps_pf">—</td>
                                </tr>
                                <tr>
                                    <td>C.N.P.S / Accident Travail (5,00 %)</td>
                                    <td class="text-end" id="p_cnps_at">—</td>
                                </tr>
                                <tr>
                                    <td>C.N.P.S / Caisse Retraite (7,70 %)</td>
                                    <td class="text-end" id="p_cnps_retraite">—</td>
                                </tr>
                                <tr>
                                    <td>C.M.U / Ass. Maladie (50 %)</td>
                                    <td class="text-end" id="p_cmu_emp">—</td>
                                </tr>
                                <tr class="table-light fw-semibold">
                                    <td>Total charges sociales emp.</td>
                                    <td class="text-end" id="p_total_soc_emp">—</td>
                                </tr>
                                <tr class="fw-bold table-warning">
                                    <td>Total charges patronales</td>
                                    <td class="text-end" id="p_total_patronales">—</td>
                                </tr>
                            </tbody>
                            <thead class="table-light">
                                <tr>
                                    <th colspan="2" class="text-center bg-danger bg-opacity-10">Retenues salariales</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Retenue I.S (1,20 %)</td>
                                    <td class="text-end" id="p_retenue_is">—</td>
                                </tr>
                                <tr>
                                    <td>Retenue C.N</td>
                                    <td class="text-end" id="p_retenue_cn">—</td>
                                </tr>
                                <tr>
                                    <td>Retenue I.G.R</td>
                                    <td class="text-end" id="p_retenue_igr">—</td>
                                </tr>
                                <tr>
                                    <td>Retenue C.N.P.S (6,30 %)</td>
                                    <td class="text-end" id="p_retenue_cnps">—</td>
                                </tr>
                                <tr>
                                    <td>Retenue C.M.U (50 %)</td>
                                    <td class="text-end" id="p_retenue_cmu">—</td>
                                </tr>
                                <tr class="fw-bold table-danger text-white" style="background:#dc3545">
                                    <td>Total retenues</td>
                                    <td class="text-end" id="p_total_retenues">—</td>
                                </tr>
                                <tr>
                                    <td>Retenue absences</td>
                                    <td class="text-end text-danger fw-semibold" id="p_retenue_absences">—</td>
                                </tr>
                            </tbody>
                            <thead class="table-light">
                                <tr>
                                    <th colspan="2" class="text-center">Résultat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Salaire brut</td>
                                    <td class="text-end fw-bold" id="p_salaire_brut">—</td>
                                </tr>
                                <tr>
                                    <td>Salaire net</td>
                                    <td class="text-end fw-bold" id="p_salaire_net">—</td>
                                </tr>
                                <tr class="fw-bold" style="background:#59b489;color:#fff">
                                    <td>NET À PAYER</td>
                                    <td class="text-end" id="p_net_a_payer">—</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Champs cachés pour les montants calculés --}}
                <input type="hidden" name="is_employeur" id="h_is_employeur">
                <input type="hidden" name="fdfp_ta" id="h_fdfp_ta">
                <input type="hidden" name="fdfp_fpc" id="h_fdfp_fpc">
                <input type="hidden" name="cnps_pf_emp" id="h_cnps_pf_emp">
                <input type="hidden" name="cnps_at_emp" id="h_cnps_at_emp">
                <input type="hidden" name="cnps_retraite_emp" id="h_cnps_retraite_emp">
                <input type="hidden" name="cmu_emp" id="h_cmu_emp">
                <input type="hidden" name="retenue_is" id="h_retenue_is">
                <input type="hidden" name="retenue_cn" id="h_retenue_cn">
                <input type="hidden" name="retenue_igr" id="h_retenue_igr">
                <input type="hidden" name="retenue_cnps" id="h_retenue_cnps">
                <input type="hidden" name="retenue_cmu" id="h_retenue_cmu">
                <input type="hidden" name="retenue_absences" id="h_retenue_absences">

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>Enregistrer le bulletin
                    </button>
                </div>

            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function fmt(n) {
        if (n === undefined || n === null || n === '') return '—';
        return Number(n).toLocaleString('fr-FR') + ' F';
    }

    function remplirPreview(data) {
        const map = {
            p_is_employeur:     data.is_employeur,
            p_fdfp_ta:          data.fdfp_ta,
            p_fdfp_fpc:         data.fdfp_fpc,
            p_total_fisc_emp:   data.total_charges_fiscales_emp,
            p_cnps_pf:          data.cnps_pf_emp,
            p_cnps_at:          data.cnps_at_emp,
            p_cnps_retraite:    data.cnps_retraite_emp,
            p_cmu_emp:          data.cmu_emp,
            p_total_soc_emp:    data.total_charges_sociales_emp,
            p_total_patronales: data.total_charges_patronales,
            p_retenue_is:       data.retenue_is,
            p_retenue_cn:       data.retenue_cn,
            p_retenue_igr:      data.retenue_igr,
            p_retenue_cnps:     data.retenue_cnps,
            p_retenue_cmu:      data.retenue_cmu,
            p_total_retenues:   data.total_retenues,
            p_retenue_absences: data.retenue_absences,
            p_salaire_brut:     data.salaire_brut,
            p_salaire_net:      data.salaire_net,
            p_net_a_payer:      data.net_a_payer,
        };
        for (const [id, val] of Object.entries(map)) {
            const el = document.getElementById(id);
            if (el) el.textContent = fmt(val);
        }

        const hidden = {
            h_is_employeur:      data.is_employeur,
            h_fdfp_ta:           data.fdfp_ta,
            h_fdfp_fpc:          data.fdfp_fpc,
            h_cnps_pf_emp:       data.cnps_pf_emp,
            h_cnps_at_emp:       data.cnps_at_emp,
            h_cnps_retraite_emp: data.cnps_retraite_emp,
            h_cmu_emp:           data.cmu_emp,
            h_retenue_is:        data.retenue_is,
            h_retenue_cn:        data.retenue_cn,
            h_retenue_igr:       data.retenue_igr,
            h_retenue_cnps:      data.retenue_cnps,
            h_retenue_cmu:       data.retenue_cmu,
            h_retenue_absences:  data.retenue_absences,
        };
        for (const [id, val] of Object.entries(hidden)) {
            const el = document.getElementById(id);
            if (el) el.value = val ?? '';
        }

        const biEl = document.getElementById('brut_imposable');
        if (biEl && !biEl.value) biEl.value = data.salaire_brut ?? '';
    }

    function doCalcul() {
        const g = id => document.getElementById(id);
        const payload = {
            salaire_base:       g('salaire_base')?.value       || 0,
            avantage_nature:    g('avantage_nature')?.value    || 0,
            indemnite_transport:g('indemnite_transport')?.value|| 0,
            brut_imposable:     g('brut_imposable')?.value     || '',
            nbre_parts:         g('nbre_parts')?.value         || 1,
            cnps_plafond:       g('cnps_plafond')?.value       || 70000,
            cmu_base:           g('cmu_base')?.value           || 1000,
            heures_absence:     g('heures_absence')?.value     || 0,
        };

        fetch('{{ route('bulletins.calculer') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? '',
            },
            body: JSON.stringify(payload),
        })
        .then(r => r.json())
        .then(data => remplirPreview(data))
        .catch(() => {});
    }

    // Auto-remplir salaire et heures d'absence depuis l'employé sélectionné
    function chargerInfoEmploye() {
        const id   = document.getElementById('employe_id').value;
        const mois = document.getElementById('mois').value;
        if (!id) return;

        fetch(`/bulletins/employe/${id}/info?mois=${mois}`)
            .then(r => r.json())
            .then(data => {
                const sb = document.getElementById('salaire_base');
                if (sb) sb.value = data.salaire_base ?? '';

                const ha = document.getElementById('heures_absence');
                if (ha) ha.value = data.heures_absence ?? 0;

                doCalcul();
            })
            .catch(() => {});
    }

    document.getElementById('employe_id').addEventListener('change', chargerInfoEmploye);
    document.getElementById('mois').addEventListener('change', function () {
        if (document.getElementById('employe_id').value) chargerInfoEmploye();
    });

    document.querySelectorAll('.calcul').forEach(el => {
        el.addEventListener('input', () => setTimeout(doCalcul, 300));
    });

    document.getElementById('btnCalculer').addEventListener('click', doCalcul);

    window.addEventListener('DOMContentLoaded', () => {
        if (document.getElementById('salaire_base')?.value) doCalcul();
    });
</script>
@endpush
@endsection
