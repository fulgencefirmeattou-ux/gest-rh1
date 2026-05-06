<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\BulletinPaie;
use App\Models\Contrat;
use App\Models\DemandeConge;
use App\Models\Departement;
use App\Models\Employe;
use App\Models\Pointage;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return match ($user->sidebarRole()) {
            'super-admin'             => $this->dashboardAdmin(),
            'rh'                      => $this->dashboardRH(),
            'admin'                   => $this->dashboardDG(),
            'responsable_departement' => $this->dashboardDept($user),
            'responsable_service'     => $this->dashboardService($user),
            default                   => $this->dashboardEmploye($user),
        };
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ADMIN
    // ─────────────────────────────────────────────────────────────────────────
    private function dashboardAdmin()
    {
        $now = Carbon::now();

        $totalEmployes       = Employe::count();
        $totalDepartements   = Departement::count();
        $contratsActifs      = Contrat::where('statut', 'actif')->count();
        $totalUtilisateurs   = User::count();

        $masseSalarialeMois  = BulletinPaie::whereMonth('periode_debut', $now->month)->whereYear('periode_debut', $now->year)->sum('net_a_payer');
        $bulletinsMois       = BulletinPaie::whereMonth('periode_debut', $now->month)->whereYear('periode_debut', $now->year)->count();
        $nouveauxEmployesMois = Employe::whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->count();

        $contratsExpirantBientot = Contrat::with('employe')
            ->where('statut', 'actif')->whereNotNull('date_fin')
            ->whereBetween('date_fin', [$now, $now->copy()->addDays(30)])
            ->orderBy('date_fin')->limit(5)->get();

        $derniersEmployes = Employe::with(['departement', 'poste'])->latest()->limit(6)->get();

        $pointagesAujourdhui = Pointage::whereDate('date', today())->count();
        $pointagesPresents   = Pointage::whereDate('date', today())->whereNotNull('heure_arrivee')->count();

        [$masseSalarialeParMois, $labelsGraphique] = $this->tendanceSalariale($now);
        $donneesParAnnee = $this->detailParAnnee();

        $repartitionDept = $this->repartitionDepartements();

        return view('superadmin.dashboard', compact(
            'totalEmployes', 'totalDepartements', 'contratsActifs', 'totalUtilisateurs',
            'masseSalarialeMois', 'bulletinsMois', 'nouveauxEmployesMois',
            'contratsExpirantBientot', 'derniersEmployes',
            'pointagesAujourdhui', 'pointagesPresents',
            'masseSalarialeParMois', 'labelsGraphique', 'repartitionDept', 'donneesParAnnee'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // RH
    // ─────────────────────────────────────────────────────────────────────────
    private function dashboardRH()
    {
        $now = Carbon::now();

        $totalEmployes     = Employe::count();
        $contratsActifs    = Contrat::where('statut', 'actif')->count();
        $congesEnAttente   = DemandeConge::where('statut', 'attente_dg')->count();
        $absencesEnAttente = Absence::where('statut', 'en_attente')->count();

        $congesAValider = DemandeConge::with('employe')
            ->where('statut', 'attente_dg')->latest()->limit(5)->get();

        $absencesATraiter = Absence::with('employe')
            ->where('statut', 'en_attente')->latest()->limit(5)->get();

        $contratsExpirantBientot = Contrat::with('employe')
            ->where('statut', 'actif')->whereNotNull('date_fin')
            ->whereBetween('date_fin', [$now, $now->copy()->addDays(30)])
            ->orderBy('date_fin')->limit(5)->get();

        $nouveauxEmployesMois = Employe::whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->count();

        return view('superadmin.dashboard-rh', compact(
            'totalEmployes', 'contratsActifs', 'congesEnAttente', 'absencesEnAttente',
            'congesAValider', 'absencesATraiter',
            'contratsExpirantBientot', 'nouveauxEmployesMois'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // DG
    // ─────────────────────────────────────────────────────────────────────────
    private function dashboardDG()
    {
        $now = Carbon::now();

        $totalEmployes      = Employe::count();
        $masseSalarialeMois = BulletinPaie::whereMonth('periode_debut', $now->month)->whereYear('periode_debut', $now->year)->sum('net_a_payer');
        $bulletinsMois      = BulletinPaie::whereMonth('periode_debut', $now->month)->whereYear('periode_debut', $now->year)->count();
        $pointagesPresents  = Pointage::whereDate('date', today())->whereNotNull('heure_arrivee')->count();
        $congesEnAttente    = DemandeConge::where('statut', 'attente_dg')->count();

        $congesAValider = DemandeConge::with('employe')
            ->where('statut', 'attente_dg')->latest()->limit(6)->get();

        [$masseSalarialeParMois, $labelsGraphique] = $this->tendanceSalariale($now);
        $donneesParAnnee = $this->detailParAnnee();
        $repartitionDept = $this->repartitionDepartements();

        return view('superadmin.dashboard-dg', compact(
            'totalEmployes', 'masseSalarialeMois', 'bulletinsMois',
            'pointagesPresents', 'congesEnAttente',
            'congesAValider', 'masseSalarialeParMois', 'labelsGraphique', 'repartitionDept', 'donneesParAnnee'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // RESPONSABLE DÉPARTEMENT
    // ─────────────────────────────────────────────────────────────────────────
    private function dashboardDept(User $user)
    {
        $departement = Departement::where('responsable_id', $user->employe_id)->first();

        if (!$departement) {
            return view('superadmin.dashboard-dept', [
                'departement'      => null,
                'totalEmployesDept' => 0,
                'congesEnAttente'  => 0,
                'absencesMois'     => 0,
                'congesAValider'   => collect(),
                'employesDept'     => collect(),
            ]);
        }

        $ids = Employe::where('departement_id', $departement->id)->pluck('id');

        $totalEmployesDept = $ids->count();
        $congesEnAttente   = DemandeConge::whereIn('employe_id', $ids)->where('statut', 'attente_departement')->count();
        $absencesMois      = Absence::whereIn('employe_id', $ids)->whereMonth('created_at', now()->month)->count();

        $congesAValider = DemandeConge::with('employe')
            ->whereIn('employe_id', $ids)->where('statut', 'attente_departement')
            ->latest()->limit(5)->get();

        $employesDept = Employe::where('departement_id', $departement->id)
            ->with(['poste', 'service'])->latest()->limit(8)->get();

        return view('superadmin.dashboard-dept', compact(
            'departement', 'totalEmployesDept', 'congesEnAttente',
            'absencesMois', 'congesAValider', 'employesDept'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // RESPONSABLE SERVICE
    // ─────────────────────────────────────────────────────────────────────────
    private function dashboardService(User $user)
    {
        $service = Service::where('responsable_id', $user->employe_id)->first();

        if (!$service) {
            return view('superadmin.dashboard-serv', [
                'service'           => null,
                'totalEmployesServ' => 0,
                'congesEnAttente'   => 0,
                'absencesMois'      => 0,
                'congesAValider'    => collect(),
                'employesServ'      => collect(),
            ]);
        }

        $ids = Employe::where('service_id', $service->id)->pluck('id');

        $totalEmployesServ = $ids->count();
        $congesEnAttente   = DemandeConge::whereIn('employe_id', $ids)->where('statut', 'attente_service')->count();
        $absencesMois      = Absence::whereIn('employe_id', $ids)->whereMonth('created_at', now()->month)->count();

        $congesAValider = DemandeConge::with('employe')
            ->whereIn('employe_id', $ids)->where('statut', 'attente_service')
            ->latest()->limit(5)->get();

        $employesServ = Employe::where('service_id', $service->id)
            ->with(['poste'])->latest()->limit(8)->get();

        return view('superadmin.dashboard-serv', compact(
            'service', 'totalEmployesServ', 'congesEnAttente',
            'absencesMois', 'congesAValider', 'employesServ'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // EMPLOYÉ
    // ─────────────────────────────────────────────────────────────────────────
    private function dashboardEmploye(User $user)
    {
        $employe = Employe::find($user->employe_id);

        if (!$employe) {
            return view('superadmin.dashboard-employe', [
                'employe'           => null,
                'soldeConges'       => 0,
                'congesPris'        => 0,
                'soldePermissions'  => 0,
                'absencesMois'      => 0,
                'totalBulletins'    => 0,
                'dernieresDemandes' => collect(),
                'dernieresAbsences' => collect(),
            ]);
        }

        $soldeConges      = $employe->solde_conges ?? 0;
        $congesPris       = $employe->conges_pris ?? 0;
        $soldePermissions = max(0, ($employe->solde_permissions ?? 0) - ($employe->permissions_prises ?? 0));
        $absencesMois     = Absence::where('employe_id', $employe->id)->whereMonth('created_at', now()->month)->count();
        $totalBulletins   = BulletinPaie::where('employe_id', $employe->id)->count();

        $dernieresDemandes = DemandeConge::where('employe_id', $employe->id)->latest()->limit(5)->get();
        $dernieresAbsences = Absence::where('employe_id', $employe->id)->latest()->limit(5)->get();

        return view('superadmin.dashboard-employe', compact(
            'employe', 'soldeConges', 'congesPris', 'soldePermissions',
            'absencesMois', 'totalBulletins',
            'dernieresDemandes', 'dernieresAbsences'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers partagés
    // ─────────────────────────────────────────────────────────────────────────
    private function tendanceSalariale(Carbon $now): array
    {
        $valeurs = [];
        $labels  = [];

        $annees = BulletinPaie::selectRaw('YEAR(periode_debut) as annee')
            ->groupBy('annee')
            ->orderBy('annee')
            ->pluck('annee');

        if ($annees->isEmpty()) {
            $annees = collect([$now->year]);
        }

        foreach ($annees as $annee) {
            $labels[]  = (string) $annee;
            $valeurs[] = (int) BulletinPaie::whereYear('periode_debut', $annee)
                ->sum('net_a_payer');
        }

        return [$valeurs, $labels];
    }

    private function detailParAnnee(): array
    {
        $annees = BulletinPaie::selectRaw('YEAR(periode_debut) as annee')
            ->groupBy('annee')
            ->orderBy('annee')
            ->pluck('annee');

        $data = [];
        foreach ($annees as $annee) {
            $mensuel = [];
            for ($m = 1; $m <= 12; $m++) {
                $mensuel[] = (int) BulletinPaie::whereYear('periode_debut', $annee)
                    ->whereMonth('periode_debut', $m)
                    ->sum('net_a_payer');
            }
            $data[(string) $annee] = $mensuel;
        }
        return $data;
    }

    private function repartitionDepartements()
    {
        return Employe::selectRaw('departement_id, count(*) as total')
            ->groupBy('departement_id')
            ->with('departement')
            ->get()
            ->map(fn($e) => ['nom' => $e->departement->nom ?? 'N/A', 'total' => $e->total]);
    }
}
