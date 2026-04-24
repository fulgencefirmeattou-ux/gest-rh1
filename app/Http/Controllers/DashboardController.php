<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\Contrat;
use App\Models\BulletinPaie;
use App\Models\Departement;
use App\Models\Pointage;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // ── Statistiques principales ──────────────────────────────────────────
        $totalEmployes    = Employe::count();
        $totalDepartements = Departement::count();
        $contratsActifs   = Contrat::where('statut', 'actif')->count();
        $totalUtilisateurs = User::count();

        // Masse salariale du mois courant
        $masseSalarialeMois = BulletinPaie::whereMonth('periode_debut', $now->month)
            ->whereYear('periode_debut', $now->year)
            ->sum('net_a_payer');

        // Bulletins générés ce mois
        $bulletinsMois = BulletinPaie::whereMonth('periode_debut', $now->month)
            ->whereYear('periode_debut', $now->year)
            ->count();

        // Nouveaux employés ce mois
        $nouveauxEmployesMois = Employe::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        // ── Contrats expirant dans 30 jours ──────────────────────────────────
        $contratsExpirantBientot = Contrat::with('employe')
            ->where('statut', 'actif')
            ->whereNotNull('date_fin')
            ->whereBetween('date_fin', [$now, $now->copy()->addDays(30)])
            ->orderBy('date_fin')
            ->limit(5)
            ->get();

        // ── Derniers employés ajoutés ─────────────────────────────────────────
        $derniersEmployes = Employe::with(['departement', 'poste'])
            ->latest()
            ->limit(6)
            ->get();

        // ── Pointage aujourd'hui ──────────────────────────────────────────────
        $pointagesAujourdhui = Pointage::whereDate('date', today())->count();
        $pointagesPresents   = Pointage::whereDate('date', today())
            ->whereNotNull('heure_arrivee')
            ->count();

        // ── Masse salariale sur 6 mois (pour graphique) ───────────────────────
        $masseSalarialeParMois = [];
        $labelsGraphique       = [];
        for ($i = 5; $i >= 0; $i--) {
            $mois = $now->copy()->subMonths($i);
            $labelsGraphique[] = $mois->translatedFormat('M Y');
            $masseSalarialeParMois[] = (int) BulletinPaie::whereMonth('periode_debut', $mois->month)
                ->whereYear('periode_debut', $mois->year)
                ->sum('net_a_payer');
        }

        // ── Répartition par département ───────────────────────────────────────
        $repartitionDept = Employe::selectRaw('departement_id, count(*) as total')
            ->groupBy('departement_id')
            ->with('departement')
            ->get()
            ->map(fn($e) => [
                'nom'   => $e->departement->nom ?? 'N/A',
                'total' => $e->total,
            ]);

        return view('superadmin.dashboard', compact(
            'totalEmployes',
            'totalDepartements',
            'contratsActifs',
            'totalUtilisateurs',
            'masseSalarialeMois',
            'bulletinsMois',
            'nouveauxEmployesMois',
            'contratsExpirantBientot',
            'derniersEmployes',
            'pointagesAujourdhui',
            'pointagesPresents',
            'masseSalarialeParMois',
            'labelsGraphique',
            'repartitionDept'
        ));
    }
}
