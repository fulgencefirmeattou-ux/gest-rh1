<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulletinPaie extends Model
{
    protected $table = 'bulletins_paie';

    protected $fillable = [
        'employe_id',
        'mois', 'periode_debut', 'periode_fin', 'nbre_parts',
        // Gains
        'salaire_base', 'avantage_nature', 'salaire_brut',
        'brut_imposable', 'indemnite_transport',
        'cnps_plafond', 'cmu_base',
        // Charges patronales
        'is_employeur', 'fdfp_ta', 'fdfp_fpc',
        'cnps_pf_emp', 'cnps_at_emp', 'cnps_retraite_emp', 'cmu_emp',
        'total_charges_fiscales_emp', 'total_charges_sociales_emp', 'total_charges_patronales',
        // Retenues salariales
        'retenue_is', 'retenue_cn', 'retenue_igr', 'retenue_cnps', 'retenue_cmu',
        'total_retenues',
        // Totaux
        'salaire_net', 'net_a_payer',
        // Cumul
        'cumul_gains', 'cumul_retenues',
        // Footer
        'base_conge', 'jours_fiscaux', 'brut_imposable_cumul',
        'mode_reglement', 'reference_virement',
        // Meta
        'statut', 'pdf_path',
    ];

    protected $casts = [
        'periode_debut' => 'date',
        'periode_fin'   => 'date',
    ];

    // ─── Relations ───────────────────────────────────────────────

    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }

    // ─── Taux légaux CI (modifiables si nécessaire) ──────────────

    const IS_TAUX           = 0.012;   // 1,20 %
    const FDFP_TA_TAUX      = 0.004;   // 0,40 %
    const FDFP_FPC_TAUX     = 0.006;   // 0,60 %
    const CNPS_PF_TAUX      = 0.0575;  // 5,75 %
    const CNPS_AT_TAUX      = 0.05;    // 5,00 %
    const CNPS_RETRAITE_TAUX = 0.077;  // 7,70 %
    const CMU_TAUX          = 0.50;    // 50,00 %
    const CNPS_SALARIE_TAUX = 0.063;   // 6,30 %
    const CNPS_PLAFOND_DEF  = 70000;
    const CMU_BASE_DEF      = 1000;

    // ─── Calcul automatique ──────────────────────────────────────

    /**
     * Calcule toutes les cotisations CI à partir des données brutes.
     * Retourne un tableau complet prêt à être inséré en base.
     */
    public static function calculer(array $data): array
    {
        $salaireBase      = (float) ($data['salaire_base']      ?? 0);
        $avantageNature   = (float) ($data['avantage_nature']   ?? 0);
        $indemTransport   = (float) ($data['indemnite_transport'] ?? 0);
        $nbreParts        = (float) ($data['nbre_parts']        ?? 1);
        $cnpsPlafond      = (float) ($data['cnps_plafond']      ?? self::CNPS_PLAFOND_DEF);
        $cmuBase          = (float) ($data['cmu_base']          ?? self::CMU_BASE_DEF);

        $salaireBrut      = $salaireBase + $avantageNature;
        $brutImposable    = (float) ($data['brut_imposable'] ?? $salaireBrut);
        $cnpsBaseP        = min($salaireBrut, $cnpsPlafond);

        // ── Charges patronales ────────────────────────────────
        $isEmp            = self::arrondir($brutImposable    * self::IS_TAUX);
        $fdfpTa           = self::arrondir($brutImposable    * self::FDFP_TA_TAUX);
        $fdfpFpc          = self::arrondir($brutImposable    * self::FDFP_FPC_TAUX);
        $cnpsPf           = self::arrondir($cnpsBaseP        * self::CNPS_PF_TAUX);
        $cnpsAt           = self::arrondir($cnpsBaseP        * self::CNPS_AT_TAUX);
        $cnpsRetraiteEmp  = self::arrondir($salaireBrut      * self::CNPS_RETRAITE_TAUX);
        $cmuEmp           = self::arrondir($cmuBase          * self::CMU_TAUX);

        $totalFiscEmp     = $isEmp + $fdfpTa + $fdfpFpc;
        $totalSocEmp      = $cnpsPf + $cnpsAt + $cnpsRetraiteEmp + $cmuEmp;
        $totalPatronales  = $totalFiscEmp + $totalSocEmp;

        // ── Retenues salariales ───────────────────────────────
        $retenueIs        = self::arrondir($brutImposable * self::IS_TAUX);
        $retenueCn        = self::calculerCN($brutImposable);
        $retenueIgr       = self::calculerIGR($brutImposable, $nbreParts);
        $retenueCnps      = self::arrondir($salaireBrut * self::CNPS_SALARIE_TAUX);
        $retenueCmu       = self::arrondir($cmuBase     * self::CMU_TAUX);

        $totalRetenues    = $retenueIs + $retenueCn + $retenueIgr + $retenueCnps + $retenueCmu;
        $salaireNet       = $salaireBrut - $totalRetenues;
        $netAPayer        = $salaireNet + $indemTransport;

        $gains   = $salaireBrut + $indemTransport;
        $retenues = $totalRetenues;

        return array_merge($data, [
            'salaire_brut'               => $salaireBrut,
            'brut_imposable'             => $brutImposable,
            'cnps_plafond'               => $cnpsPlafond,
            'is_employeur'               => $isEmp,
            'fdfp_ta'                    => $fdfpTa,
            'fdfp_fpc'                   => $fdfpFpc,
            'cnps_pf_emp'                => $cnpsPf,
            'cnps_at_emp'                => $cnpsAt,
            'cnps_retraite_emp'          => $cnpsRetraiteEmp,
            'cmu_emp'                    => $cmuEmp,
            'total_charges_fiscales_emp' => $totalFiscEmp,
            'total_charges_sociales_emp' => $totalSocEmp,
            'total_charges_patronales'   => $totalPatronales,
            'retenue_is'                 => $retenueIs,
            'retenue_cn'                 => $retenueCn,
            'retenue_igr'                => $retenueIgr,
            'retenue_cnps'               => $retenueCnps,
            'retenue_cmu'                => $retenueCmu,
            'total_retenues'             => $totalRetenues,
            'salaire_net'                => $salaireNet,
            'net_a_payer'                => $netAPayer,
            'cumul_gains'                => $data['cumul_gains']    ?? $gains,
            'cumul_retenues'             => $data['cumul_retenues'] ?? $retenues,
        ]);
    }

    /**
     * Contribution Nationale (CN) — barème progressif mensuel CI
     */
    public static function calculerCN(float $brutImposable): float
    {
        if ($brutImposable <= 50000) return 0;

        if ($brutImposable <= 130000) {
            return self::arrondir(($brutImposable - 50000) * 0.015);
        }
        if ($brutImposable <= 200000) {
            return self::arrondir((80000 * 0.015) + (($brutImposable - 130000) * 0.05));
        }
        return self::arrondir((80000 * 0.015) + (70000 * 0.05) + (($brutImposable - 200000) * 0.10));
    }

    /**
     * IGR — Impôt Général sur le Revenu (barème CI, abattement 15 %)
     */
    public static function calculerIGR(float $brutImposable, float $nbreParts = 1): float
    {
        $nbreParts     = max(1, $nbreParts);
        $netImposable  = $brutImposable * 0.85;          // abattement 15 %
        $annuelParPart = ($netImposable * 12) / $nbreParts;

        $igr = 0;
        if ($annuelParPart <= 600000) {
            $igr = 0;
        } elseif ($annuelParPart <= 1800000) {
            $igr = ($annuelParPart - 600000) * 0.055;
        } elseif ($annuelParPart <= 3000000) {
            $igr = (1200000 * 0.055) + (($annuelParPart - 1800000) * 0.115);
        } elseif ($annuelParPart <= 4800000) {
            $igr = (1200000 * 0.055) + (1200000 * 0.115) + (($annuelParPart - 3000000) * 0.20);
        } else {
            $igr = (1200000 * 0.055) + (1200000 * 0.115) + (1800000 * 0.20) + (($annuelParPart - 4800000) * 0.35);
        }

        // Re-multiplier par les parts puis mensualiser
        return self::arrondir(($igr * $nbreParts) / 12);
    }

    private static function arrondir(float $val): float
    {
        return round($val);
    }

    // ─── Accesseurs ──────────────────────────────────────────────

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'paye'      => 'Payé',
            'valide'    => 'Validé',
            default     => 'Brouillon',
        };
    }

    public function getStatutColorAttribute(): string
    {
        return match ($this->statut) {
            'paye'   => 'success',
            'valide' => 'primary',
            default  => 'warning',
        };
    }

    public function getNomMoisAttribute(): string
    {
        return \Carbon\Carbon::createFromFormat('Y-m', $this->mois)
            ->locale('fr')
            ->isoFormat('MMMM YYYY');
    }
}
