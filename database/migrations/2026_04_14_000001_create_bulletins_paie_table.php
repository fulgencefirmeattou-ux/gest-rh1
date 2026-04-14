<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bulletins_paie', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained('employes')->cascadeOnDelete();

            // Période
            $table->string('mois', 7);           // "2024-01"
            $table->date('periode_debut');
            $table->date('periode_fin');
            $table->decimal('nbre_parts', 5, 2)->default(1.00);

            // ── GAINS ────────────────────────────────────────────
            $table->decimal('salaire_base', 12, 2)->default(0);
            $table->decimal('avantage_nature', 12, 2)->default(0);
            $table->decimal('salaire_brut', 12, 2)->default(0);
            $table->decimal('brut_imposable', 12, 2)->default(0);
            $table->decimal('indemnite_transport', 12, 2)->default(0);

            // Bases de calcul (modifiables)
            $table->decimal('cnps_plafond', 12, 2)->default(70000);
            $table->decimal('cmu_base', 12, 2)->default(1000);

            // ── CHARGES PATRONALES (Taux P.P) ────────────────────
            $table->decimal('is_employeur', 12, 2)->default(0);
            $table->decimal('fdfp_ta', 12, 2)->default(0);
            $table->decimal('fdfp_fpc', 12, 2)->default(0);
            $table->decimal('cnps_pf_emp', 12, 2)->default(0);
            $table->decimal('cnps_at_emp', 12, 2)->default(0);
            $table->decimal('cnps_retraite_emp', 12, 2)->default(0);
            $table->decimal('cmu_emp', 12, 2)->default(0);
            $table->decimal('total_charges_fiscales_emp', 12, 2)->default(0);
            $table->decimal('total_charges_sociales_emp', 12, 2)->default(0);
            $table->decimal('total_charges_patronales', 12, 2)->default(0);

            // ── RETENUES SALARIALES ──────────────────────────────
            $table->decimal('retenue_is', 12, 2)->default(0);
            $table->decimal('retenue_cn', 12, 2)->default(0);
            $table->decimal('retenue_igr', 12, 2)->default(0);
            $table->decimal('retenue_cnps', 12, 2)->default(0);
            $table->decimal('retenue_cmu', 12, 2)->default(0);
            $table->decimal('total_retenues', 12, 2)->default(0);

            // ── TOTAUX ───────────────────────────────────────────
            $table->decimal('salaire_net', 12, 2)->default(0);
            $table->decimal('net_a_payer', 12, 2)->default(0);

            // ── CUMUL PAIE ───────────────────────────────────────
            $table->decimal('cumul_gains', 12, 2)->nullable();
            $table->decimal('cumul_retenues', 12, 2)->nullable();

            // ── FOOTER ───────────────────────────────────────────
            $table->decimal('base_conge', 12, 2)->nullable();
            $table->integer('jours_fiscaux')->nullable();
            $table->decimal('brut_imposable_cumul', 12, 2)->nullable();
            $table->string('mode_reglement', 50)->nullable();
            $table->string('reference_virement', 100)->nullable();

            // ── META ─────────────────────────────────────────────
            $table->string('statut', 20)->default('brouillon'); // brouillon | valide | paye
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bulletins_paie');
    }
};
