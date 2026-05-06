<?php

namespace Database\Seeders;

use App\Models\Absence;
use App\Models\BulletinPaie;
use App\Models\DemandeConge;
use App\Models\Employe;
use App\Models\HistoriqueConge;
use App\Models\Pointage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class Seed2025Seeder extends Seeder
{
    const ANNEE          = 2025;
    const HEURES_JOURNEE = 8.0;

    // Profils bulletins : [avantage_nature, indemnite_transport, nbre_parts, mode_reglement]
    const PROFILS_BP = [
        1  => [23472,  25000, 1.00, 'Virement'],
        2  => [0,      25000, 2.00, 'Virement'],
        3  => [30000,  25000, 1.50, 'Virement'],
        4  => [0,      20000, 1.00, 'Virement'],
        5  => [15000,  25000, 2.00, 'Virement'],
        6  => [0,      20000, 1.00, 'Espèces'],
        7  => [0,      20000, 1.00, 'Virement'],
        8  => [0,      15000, 1.00, 'Espèces'],
        9  => [20000,  25000, 2.50, 'Virement'],
        10 => [10000,  25000, 1.00, 'Virement'],
        11 => [0,      15000, 1.00, 'Espèces'],
        12 => [35000,  25000, 2.00, 'Virement'],
        13 => [0,      20000, 1.00, 'Virement'],
        14 => [12000,  25000, 1.00, 'Virement'],
        15 => [18000,  25000, 1.50, 'Virement'],
    ];

    const SCENARIOS = [
        'normal'       => ['08:00', '17:00', '12:00', '13:00'],
        'avance'       => ['07:30', '17:00', '12:00', '13:00'],
        'tres_avance'  => ['07:00', '17:00', '12:00', '13:00'],
        'tardif'       => ['08:30', '17:00', '12:00', '13:00'],
        'tres_tardif'  => ['09:00', '17:00', '12:00', '13:00'],
        'sup_soir'     => ['08:00', '17:30', '12:00', '13:00'],
        'long'         => ['07:30', '18:00', '12:00', '13:00'],
        'demi_journee' => ['08:00', '12:00', null,    null],
    ];

    // Jours fériés CI 2025
    const FERIES_2025 = [
        '2025-01-01', // Nouvel An
        '2025-04-18', // Vendredi Saint
        '2025-04-21', // Lundi de Pâques
        '2025-05-01', // Fête du Travail
        '2025-05-29', // Ascension
        '2025-06-06', // Aïd el-Adha (Tabaski)
        '2025-06-09', // Lundi de Pentecôte
        '2025-08-07', // Fête Nationale CI
        '2025-08-15', // Assomption
        '2025-09-04', // Maouloud
        '2025-11-01', // Toussaint
        '2025-11-15', // Jour de la Paix CI
        '2025-12-25', // Noël
    ];

    // Cycle de pointage par employé (index 0-14), 20 scénarios qui se répètent
    const PLANS_CYCLE = [
        // Koné — très régulier, quelques avances
        0  => ['normal','normal','normal','avance','normal','normal','normal','avance','normal','normal',
                'normal','avance','sup_soir','normal','normal','avance','normal','normal','normal','avance'],
        // Yao — retards et absences sporadiques
        1  => ['normal','tardif','normal','normal','absent','normal','tardif','normal','normal','tardif',
                'normal','absent','normal','sup_soir','tardif','normal','absent','normal','tardif','normal'],
        // Assi — beaucoup d'heures sup
        2  => ['avance','long','normal','sup_soir','tres_avance','long','avance','normal','sup_soir','long',
                'tres_avance','avance','sup_soir','long','avance','sup_soir','long','tres_avance','long','sup_soir'],
        // Traoré — demi-journées et absences
        3  => ['normal','normal','demi_journee','normal','absent','normal','demi_journee','normal','absent','normal',
                'demi_journee','absent','normal','normal','demi_journee','absent','normal','demi_journee','normal','absent'],
        // Diomandé — profil mixte
        4  => ['normal','avance','normal','sup_soir','normal','avance','tardif','normal','absent','sup_soir',
                'normal','avance','normal','sup_soir','tardif','avance','normal','sup_soir','normal','avance'],
        // Silué — régulier avec heures sup
        5  => ['normal','normal','sup_soir','avance','normal','normal','sup_soir','avance','normal','normal',
                'sup_soir','normal','avance','normal','sup_soir','avance','normal','normal','sup_soir','avance'],
        // Bamba — retards chroniques
        6  => ['tardif','tardif','normal','tres_tardif','tardif','normal','tardif','tres_tardif','normal','tardif',
                'tres_tardif','tardif','normal','tardif','tres_tardif','normal','tardif','tres_tardif','tardif','normal'],
        // Diallo — nombreuses absences (stage)
        7  => ['normal','absent','absent','normal','absent','normal','absent','normal','demi_journee','absent',
                'normal','absent','demi_journee','normal','absent','normal','absent','demi_journee','absent','normal'],
        // Coulibaly — très productif
        8  => ['tres_avance','long','avance','long','tres_avance','long','avance','sup_soir','long','tres_avance',
                'avance','long','tres_avance','sup_soir','long','avance','long','tres_avance','avance','long'],
        // Ouedraogo — stable, demi-journées
        9  => ['normal','normal','demi_journee','avance','normal','normal','demi_journee','avance','normal','normal',
                'avance','demi_journee','normal','normal','avance','normal','demi_journee','avance','normal','normal'],
        // Koffi — alternant (1 jour sur 2)
        10 => ['normal','absent','normal','absent','normal','absent','normal','absent','normal','absent',
                'normal','absent','normal','absent','normal','absent','normal','absent','normal','absent'],
        // N'Guessan — solide, leadership
        11 => ['normal','normal','avance','long','normal','normal','avance','sup_soir','normal','avance',
                'long','normal','avance','normal','sup_soir','avance','normal','long','normal','avance'],
        // Touré — ponctuele avec légères heures sup
        12 => ['avance','normal','avance','normal','sup_soir','avance','normal','avance','sup_soir','normal',
                'avance','sup_soir','avance','normal','avance','sup_soir','normal','avance','normal','sup_soir'],
        // Gnamba — irrégulier (retards + absences + sup)
        13 => ['tardif','absent','sup_soir','tardif','normal','absent','avance','tardif','normal','sup_soir',
                'tardif','absent','long','tardif','normal','absent','sup_soir','tardif','absent','normal'],
        // Meité — chef de projet, fiable
        14 => ['normal','normal','tardif','avance','normal','normal','tardif','avance','normal','normal',
                'avance','normal','normal','tardif','avance','normal','normal','avance','normal','tardif'],
    ];

    // ─────────────────────────────────────────────────────────────────────────
    public function run(): void
    {
        $this->genererBulletins();
        $this->genererPointages();
        $this->genererConges();
        $this->genererAbsences();
    }

    // ─────────────────────────────────────────────────────────────────────────
    private function genererBulletins(): void
    {
        $employes = Employe::orderBy('id')->get();
        $count    = 0;

        // Jan→Oct = paye | Nov = valide | Dec = brouillon
        $moisConfig = [];
        for ($m = 1; $m <= 12; $m++) {
            $moisStr = sprintf('%d-%02d', self::ANNEE, $m);
            $statut  = $m <= 10 ? 'paye' : ($m === 11 ? 'valide' : 'brouillon');
            $ref     = $m <= 11 ? sprintf('%05d', $m) : null;
            $moisConfig[] = [$moisStr, $m, $statut, $ref];
        }

        foreach ($employes as $pos => $employe) {
            $profil = self::PROFILS_BP[$pos + 1] ?? [0, 25000, 1.00, 'Virement'];
            [$avantageNature, $indemTransport, $nbreParts, $modeReglement] = $profil;
            $dateEmbauche = Carbon::parse($employe->date_embauche);

            foreach ($moisConfig as [$mois, $moisNum, $statut, $refBase]) {
                $debut = Carbon::createFromFormat('Y-m', $mois)->startOfMonth();
                $fin   = $debut->copy()->endOfMonth();

                if ($fin->lt($dateEmbauche)) continue;
                if (BulletinPaie::where('employe_id', $employe->id)->where('mois', $mois)->exists()) continue;

                $data = BulletinPaie::calculer([
                    'employe_id'          => $employe->id,
                    'mois'                => $mois,
                    'periode_debut'       => $debut->toDateString(),
                    'periode_fin'         => $fin->toDateString(),
                    'nbre_parts'          => $nbreParts,
                    'salaire_base'        => $employe->salaire,
                    'avantage_nature'     => $avantageNature,
                    'indemnite_transport' => $indemTransport,
                    'brut_imposable'      => null,
                    'cnps_plafond'        => 70000,
                    'cmu_base'            => 1000,
                ]);

                $gainsMensuel  = $data['salaire_brut'] + $indemTransport;
                $retenuMensuel = $data['total_retenues'];

                $data['cumul_gains']          = $gainsMensuel  * $moisNum;
                $data['cumul_retenues']       = $retenuMensuel * $moisNum;
                $data['brut_imposable_cumul'] = $data['brut_imposable'] * $moisNum;
                $data['jours_fiscaux']        = 30 * $moisNum;
                $data['base_conge']           = round($data['salaire_brut'] * $moisNum * 0.0417, 0);
                $data['mode_reglement']       = $modeReglement;
                $data['reference_virement']   = $refBase
                    ? $refBase . str_pad($employe->id, 10, '0', STR_PAD_LEFT) . '00'
                    : null;
                $data['statut']   = $statut;
                $data['pdf_path'] = null;

                BulletinPaie::create($data);
                $count++;
            }
        }

        $this->command->info("✓ {$count} bulletins 2025 générés.");
    }

    // ─────────────────────────────────────────────────────────────────────────
    private function genererPointages(): void
    {
        $employes   = User::where('role', 'employe')->orderBy('id')->take(15)->get();
        $validateur = User::whereIn('role', ['admin', 'rh'])->first();
        $feries     = collect(self::FERIES_2025);
        $count      = 0;

        $debut = Carbon::create(self::ANNEE, 1, 1);
        $fin   = Carbon::create(self::ANNEE, 12, 31);

        foreach ($employes as $empIndex => $employe) {
            $plan    = self::PLANS_CYCLE[$empIndex] ?? self::PLANS_CYCLE[0];
            $planLen = count($plan);

            $employeModel = Employe::where('user_id', $employe->id)->first();
            $dateEmbauche = $employeModel
                ? Carbon::parse($employeModel->date_embauche)
                : $debut->copy();

            $jourOuvre = 0;

            for ($date = $debut->copy(); $date->lte($fin); $date->addDay()) {
                if ($date->isWeekend())      continue;
                if ($date->lt($dateEmbauche)) continue;
                if (Pointage::where('employe_id', $employe->id)->where('date', $date->toDateString())->exists()) continue;

                $isFerie = $feries->contains($date->toDateString());

                if ($isFerie) {
                    Pointage::create([
                        'employe_id'         => $employe->id,
                        'date'               => $date->toDateString(),
                        'absent'             => false,
                        'type_jour'          => 'FERIE',
                        'statut'             => 'VALIDE',
                        'valide_par'         => $validateur?->id,
                        'date_validation'    => $date->copy()->setTime(8, 0),
                        'heures_travaillees' => null,
                        'heures_sup'         => 0,
                        'heures_manquantes'  => 0,
                    ]);
                    $jourOuvre++;
                    $count++;
                    continue;
                }

                $scenario = $plan[$jourOuvre % $planLen];
                $jourOuvre++;

                if ($scenario === 'absent') {
                    Pointage::create([
                        'employe_id'         => $employe->id,
                        'date'               => $date->toDateString(),
                        'absent'             => true,
                        'type_jour'          => 'NORMAL',
                        'statut'             => 'VALIDE',
                        'valide_par'         => $validateur?->id,
                        'date_validation'    => $date->copy()->setTime(18, 0),
                        'heures_travaillees' => null,
                        'heures_sup'         => 0,
                        'heures_manquantes'  => self::HEURES_JOURNEE,
                    ]);
                    $count++;
                    continue;
                }

                [$arrivee, $depart, $debutPause, $finPause] = self::SCENARIOS[$scenario];

                $totalMin = Carbon::createFromTimeString($arrivee)
                    ->diffInMinutes(Carbon::createFromTimeString($depart));

                $pauseMin = 0;
                if ($debutPause && $finPause) {
                    $pauseMin = Carbon::createFromTimeString($debutPause)
                        ->diffInMinutes(Carbon::createFromTimeString($finPause));
                } elseif ($totalMin >= 360) {
                    $pauseMin = 60;
                }

                $heuresTravaillees = round(($totalMin - $pauseMin) / 60, 2);
                $heuresSup         = max(0, round($heuresTravaillees - self::HEURES_JOURNEE, 2));
                $heuresManquantes  = max(0, round(self::HEURES_JOURNEE - $heuresTravaillees, 2));

                Pointage::create([
                    'employe_id'         => $employe->id,
                    'date'               => $date->toDateString(),
                    'absent'             => false,
                    'heure_arrivee'      => $arrivee . ':00',
                    'heure_depart'       => $depart . ':00',
                    'heure_debut_pause'  => $debutPause ? $debutPause . ':00' : null,
                    'heure_fin_pause'    => $finPause   ? $finPause . ':00'   : null,
                    'heures_travaillees' => $heuresTravaillees,
                    'heures_sup'         => $heuresSup,
                    'heures_manquantes'  => $heuresManquantes,
                    'type_jour'          => 'NORMAL',
                    'statut'             => 'VALIDE',
                    'valide_par'         => $validateur?->id,
                    'date_validation'    => $date->copy()->setTime(18, 0),
                ]);
                $count++;
            }
        }

        $this->command->info("✓ {$count} pointages 2025 générés.");
    }

    // ─────────────────────────────────────────────────────────────────────────
    private function genererConges(): void
    {
        $employes    = Employe::with('user')->orderBy('id')->get();
        $approbateur = User::whereIn('role', ['admin', 'rh'])->first();
        $count       = 0;

        // [idx_employe(0-based), type, debut, fin, statut, raison, commentaire]
        $demandes = [
            [0,  'conge_paye',        '2025-01-06', '2025-01-17', 'approuvee',           'Congés annuels.',                        'Congés annuels validés.'],
            [2,  'conge_paye',        '2025-02-10', '2025-02-21', 'approuvee',           'Congés annuels.',                        'Congés approuvés.'],
            [4,  'maladie',           '2025-02-24', '2025-02-28', 'approuvee',           'Certificat médical joint.',              'Arrêt médical validé.'],
            [1,  'permission_courte', '2025-03-13', '2025-03-13', 'approuvee',           'Rendez-vous administratif.',             'Permission accordée.'],
            [6,  'conge_paye',        '2025-03-17', '2025-03-28', 'approuvee',           'Congés annuels.',                        'Congés annuels validés.'],
            [3,  'exceptionnel',      '2025-04-07', '2025-04-09', 'approuvee',           'Décès d\'un proche.',                    'Congé exceptionnel accordé.'],
            [12, 'maladie',           '2025-05-05', '2025-05-09', 'approuvee',           'Grippe saisonnière, certificat fourni.', 'Arrêt médical validé.'],
            [14, 'permission_courte', '2025-06-19', '2025-06-19', 'approuvee',           'Consultation médicale.',                 'Permission accordée.'],
            [8,  'conge_paye',        '2025-07-07', '2025-07-18', 'approuvee',           'Congés annuels.',                        'Congés annuels validés.'],
            [11, 'conge_paye',        '2025-07-14', '2025-07-25', 'approuvee',           'Congés annuels.',                        'Congés approuvés.'],
            [5,  'conge_paye',        '2025-08-04', '2025-08-15', 'approuvee',           'Congés annuels.',                        'Congés annuels validés.'],
            [9,  'conge_paye',        '2025-09-08', '2025-09-19', 'approuvee',           'Congés annuels.',                        'Congés annuels approuvés.'],
            [3,  'conge_paye',        '2025-10-13', '2025-10-24', 'rejetee',             'Congés annuels.',                        'Effectif insuffisant sur la période.'],
            [5,  'conge_paye',        '2025-11-03', '2025-11-14', 'attente_dg',          'Congés annuels.',                        null],
            [10, 'conge_paye',        '2025-11-10', '2025-11-21', 'attente_departement', 'Congés annuels.',                        null],
            [13, 'permission_courte', '2025-12-11', '2025-12-11', 'attente_service',     'Formalités administratives.',            null],
            [2,  'conge_paye',        '2025-12-22', '2026-01-02', 'attente_dg',          'Congés de fin d\'année.',                null],
        ];

        foreach ($demandes as [$idx, $type, $debut, $fin, $statut, $raison, $commentaire]) {
            $employe = $employes->get($idx);
            if (! $employe) continue;
            if (DemandeConge::where('employe_id', $employe->id)->where('date_debut_conge', $debut)->exists()) continue;

            $dateRetour = Carbon::parse($fin)->addDays(1)->toDateString();

            $demande = DemandeConge::create([
                'employe_id'       => $employe->id,
                'type_conge'       => $type,
                'date_debut_conge' => $debut,
                'date_fin_conge'   => $fin,
                'date_retour'      => $dateRetour,
                'raison'           => $raison,
                'statut'           => $statut,
                'commentaire'      => $commentaire,
            ]);

            if (in_array($statut, ['approuvee', 'rejetee', 'modification_demandee']) && $approbateur) {
                $decision = match ($statut) {
                    'approuvee'             => 'approuve',
                    'rejetee'               => 'rejete',
                    'modification_demandee' => 'modification_demandee',
                    default                 => 'approuve',
                };
                HistoriqueConge::create([
                    'demande_conge_id' => $demande->id,
                    'approver_id'      => $approbateur->id,
                    'etape'            => 'dg_rh',
                    'decision'         => $decision,
                    'commentaire'      => $commentaire,
                    'approved_at'      => Carbon::parse($debut)->subDays(3),
                ]);
            }

            $count++;
        }

        $this->command->info("✓ {$count} demandes de congé 2025 générées.");
    }

    // ─────────────────────────────────────────────────────────────────────────
    private function genererAbsences(): void
    {
        $employes  = Employe::orderBy('id')->get();
        $traitePar = User::whereIn('role', ['admin', 'rh'])->value('id');
        $count     = 0;

        // [idx(0-based), type, date, date_fin, h_debut, h_fin, motif, statut]
        $absences = [
            [1,  'retard',                '2025-01-08', null,         '08:00', '09:00', 'Problème de transport en commun.',       'validee'],
            [3,  'maladie',               '2025-01-20', '2025-01-22', null,    null,    'Fièvre et fatigue intense.',             'validee'],
            [6,  'retard',                '2025-02-05', null,         '08:00', '08:45', 'Circulation très dense.',                'validee'],
            [4,  'maladie',               '2025-02-24', '2025-02-26', null,    null,    'Hospitalisation courte durée.',          'validee'],
            [0,  'permission_courte',     '2025-03-10', null,         '14:00', '16:00', 'Démarches bancaires urgentes.',          'validee'],
            [9,  'rendez_vous',           '2025-03-21', null,         '10:00', '12:00', 'Rendez-vous spécialiste.',               'validee'],
            [7,  'absence_non_justifiee', '2025-04-02', null,         null,    null,    null,                                     'rejetee'],
            [13, 'retard',                '2025-04-14', null,         '08:00', '09:30', 'Panne de véhicule sur la voie rapide.',  'validee'],
            [2,  'permission_courte',     '2025-05-14', null,         '09:00', '11:00', 'Convocation administrative.',           'validee'],
            [5,  'rendez_vous',           '2025-05-28', null,         '10:00', '11:30', 'Rendez-vous chez le dentiste.',          'validee'],
            [11, 'maladie',               '2025-06-09', '2025-06-11', null,    null,    'Paludisme (certificat fourni).',         'validee'],
            [8,  'retard',                '2025-06-23', null,         '08:00', '08:30', 'Retard train, lignes perturbées.',       'validee'],
            [14, 'absence_non_justifiee', '2025-07-07', null,         null,    null,    null,                                     'en_attente'],
            [3,  'permission_courte',     '2025-08-25', null,         '14:00', '17:00', 'Formalités scolaires pour les enfants.','validee'],
            [1,  'retard',                '2025-09-15', null,         '08:00', '09:15', 'Embouteillages importants.',             'validee'],
            [10, 'absence_non_justifiee', '2025-10-06', null,         null,    null,    null,                                     'en_attente'],
            [12, 'maladie',               '2025-10-20', '2025-10-22', null,    null,    'Grippe (arrêt médical joint).',          'validee'],
            [6,  'retard',                '2025-11-10', null,         '08:00', '09:00', 'Incident de transport.',                 'validee'],
            [9,  'permission_courte',     '2025-12-08', null,         '08:00', '10:00', 'Convocation à la préfecture.',           'validee'],
            [4,  'retard',                '2025-12-15', null,         '08:00', '08:45', 'Transport en commun en panne.',          'en_attente'],
        ];

        foreach ($absences as [$idx, $type, $date, $dateFin, $hDeb, $hFin, $motif, $statut]) {
            $employe = $employes->get($idx);
            if (! $employe) continue;
            if (Absence::where('employe_id', $employe->id)->where('date_absence', $date)->exists()) continue;

            Absence::create([
                'employe_id'       => $employe->id,
                'type_absence'     => $type,
                'date_absence'     => $date,
                'date_fin_absence' => $dateFin,
                'heure_debut'      => $hDeb,
                'heure_fin'        => $hFin,
                'motif'            => $motif,
                'statut'           => $statut,
                'commentaire_rh'   => match ($statut) {
                    'validee' => 'Traité par le service RH.',
                    'rejetee' => 'Absence non justifiée — refusée.',
                    default   => null,
                },
                'traite_par'       => in_array($statut, ['validee', 'rejetee']) ? $traitePar : null,
                'traite_le'        => in_array($statut, ['validee', 'rejetee'])
                    ? Carbon::parse($date)->addDay()
                    : null,
            ]);

            $count++;
        }

        $this->command->info("✓ {$count} absences 2025 générées.");
    }
}
