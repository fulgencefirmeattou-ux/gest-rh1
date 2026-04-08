<?php

namespace App\Http\Controllers;

use App\Models\Pointage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PointageController extends Controller
{
    // ─── Journée de référence ─────────────────────────────────────
    public const HEURES_JOURNEE = 8.0;

    // ══════════════════════════════════════════════════════════════
    //  POINTAGE MOBILE (employé)
    // ══════════════════════════════════════════════════════════════

    /** Affiche le tableau de pointage de tous les employés pour aujourd'hui */
    public function index()
    {
        $today    = Carbon::today();
        $employes = User::orderBy('nom')->get();

        // Pointages du jour indexés par employe_id
        $pointages = Pointage::where('date', $today->toDateString())
            ->get()
            ->keyBy('employe_id');

        return view('superadmin.pointage.index', compact('employes', 'pointages', 'today'));
    }

    /** Enregistre manuellement les heures saisies pour un employé */
    public function sauvegarder(Request $request)
    {
        $request->validate([
            'employe_id'       => 'required|exists:users,id',
            'heure_arrivee'    => 'required|date_format:H:i',
            'heure_depart'     => 'nullable|date_format:H:i|after:heure_arrivee',
            'heure_debut_pause'=> 'nullable|date_format:H:i',
            'heure_fin_pause'  => 'nullable|date_format:H:i',
        ], [
            'heure_arrivee.required'    => "L'heure d'arrivée est obligatoire.",
            'heure_arrivee.date_format' => "Format invalide (HH:MM).",
            'heure_depart.date_format'  => "Format invalide (HH:MM).",
            'heure_depart.after'        => "L'heure de départ doit être après l'arrivée.",
        ]);

        $today    = Carbon::today()->toDateString();
        $employe  = User::findOrFail($request->employe_id);

        $pointage = Pointage::firstOrNew([
            'employe_id' => $employe->id,
            'date'       => $today,
        ]);

        $pointage->statut    = $pointage->statut    ?? 'EN_ATTENTE';
        $pointage->type_jour = $pointage->type_jour ?? 'NORMAL';

        $pointage->heure_arrivee     = $request->heure_arrivee     ? $request->heure_arrivee . ':00'     : null;
        $pointage->heure_depart      = $request->heure_depart      ? $request->heure_depart . ':00'      : null;
        $pointage->heure_debut_pause = $request->heure_debut_pause ? $request->heure_debut_pause . ':00' : null;
        $pointage->heure_fin_pause   = $request->heure_fin_pause   ? $request->heure_fin_pause . ':00'   : null;

        if ($pointage->heure_arrivee && $pointage->heure_depart) {
            $pointage->calculerHeures();
        }

        $pointage->save();

        $nom = $employe->nom . ' ' . $employe->prenom;
        return back()->with('success', "Pointage de {$nom} enregistré." . ($pointage->heures_travaillees !== null ? ' Heures : ' . $pointage->heures_travaillees_format : ''));
    }

    // ══════════════════════════════════════════════════════════════
    //  DASHBOARD RH
    // ══════════════════════════════════════════════════════════════

    /** Dashboard RH : liste des pointages de tous les employés */
    public function dashboard(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $employeId = $request->input('employe_id');

        $query = Pointage::with('employe')
            ->where('date', $date)
            ->orderBy('created_at', 'desc');

        if ($employeId) {
            $query->where('employe_id', $employeId);
        }

        $pointages = $query->get();
        $employes  = User::orderBy('nom')->get();

        return view('superadmin.pointage.dashboard', compact('pointages', 'employes', 'date', 'employeId'));
    }

    /** Valider ou refuser un pointage (RH/Manager) */
    public function valider(Request $request, int $id)
    {
        $request->validate([
            'statut' => 'required|in:VALIDE,REFUSE',
        ]);

        $pointage = Pointage::findOrFail($id);
        $pointage->statut          = $request->statut;
        $pointage->valide_par      = auth()->id();
        $pointage->date_validation = Carbon::now();
        $pointage->save();

        $label = $request->statut === 'VALIDE' ? 'validé' : 'refusé';
        return back()->with('success', "Pointage {$label}.");
    }

    // ══════════════════════════════════════════════════════════════
    //  STATISTIQUES MENSUELLES
    // ══════════════════════════════════════════════════════════════

    public function statistiques(Request $request)
    {
        $mois   = (int) $request->input('mois', Carbon::today()->month);
        $annee  = (int) $request->input('annee', Carbon::today()->year);
        $employeId = $request->input('employe_id');

        $query = Pointage::query()
            ->whereMonth('date', $mois)
            ->whereYear('date', $annee)
            ->with('employe');

        if ($employeId) {
            $query->where('employe_id', $employeId);
        }

        // Agrégats par employé
        $stats = $query->get()
            ->groupBy('employe_id')
            ->map(function ($pointages) {
                $employe = $pointages->first()->employe;
                return [
                    'employe'            => $employe,
                    'jours_travailles'   => $pointages->whereNotNull('heure_arrivee')->count(),
                    'heures_travaillees' => round($pointages->sum('heures_travaillees'), 2),
                    'heures_sup'         => round($pointages->sum('heures_sup'), 2),
                    'heures_manquantes'  => round($pointages->sum('heures_manquantes'), 2),
                    'jours_absence'      => $pointages->where('type_jour', 'ABSENCE')->count(),
                ];
            })->values();

        $employes = User::orderBy('nom')->get();
        $annees   = range(Carbon::today()->year, Carbon::today()->year - 3);

        return view('superadmin.pointage.statistiques', compact('stats', 'employes', 'mois', 'annee', 'annees', 'employeId'));
    }

    // ══════════════════════════════════════════════════════════════
    //  CALENDRIER DE PRÉSENCE
    // ══════════════════════════════════════════════════════════════

    public function calendrier(Request $request)
    {
        $mois      = (int) $request->input('mois', Carbon::today()->month);
        $annee     = (int) $request->input('annee', Carbon::today()->year);
        $employeId = $request->input('employe_id', auth()->id());

        $employe = User::findOrFail($employeId);
        $employes = User::orderBy('nom')->get();

        // Nombre de jours dans le mois
        $nbJours  = Carbon::createFromDate($annee, $mois, 1)->daysInMonth;
        $premiers = Carbon::createFromDate($annee, $mois, 1);

        // Récupère tous les pointages du mois pour cet employé
        $pointages = Pointage::where('employe_id', $employeId)
            ->whereMonth('date', $mois)
            ->whereYear('date', $annee)
            ->get()
            ->keyBy(fn($p) => Carbon::parse($p->date)->format('Y-m-d'));

        $annees = range(Carbon::today()->year, Carbon::today()->year - 3);

        return view('superadmin.pointage.calendrier', compact(
            'employe', 'employes', 'pointages',
            'mois', 'annee', 'annees', 'nbJours', 'premiers', 'employeId'
        ));
    }

    // ══════════════════════════════════════════════════════════════
    //  EXPORT PDF
    // ══════════════════════════════════════════════════════════════

    /** Export PDF des pointages d'un mois */
    public function exportPdfPointages(Request $request)
    {
        $mois      = (int) $request->input('mois', Carbon::today()->month);
        $annee     = (int) $request->input('annee', Carbon::today()->year);
        $employeId = $request->input('employe_id');

        $query = Pointage::with('employe')
            ->whereMonth('date', $mois)
            ->whereYear('date', $annee)
            ->orderBy('employe_id')
            ->orderBy('date');

        if ($employeId) {
            $query->where('employe_id', $employeId);
        }

        $pointages = $query->get();
        $nomMois   = Carbon::createFromDate($annee, $mois, 1)->locale('fr')->monthName;

        $pdf = Pdf::loadView('superadmin.pointage.pdf.pointages', compact('pointages', 'mois', 'annee', 'nomMois'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("pointages_{$nomMois}_{$annee}.pdf");
    }

    /** Export PDF du calendrier de présence */
    public function exportPdfCalendrier(Request $request)
    {
        $mois      = (int) $request->input('mois', Carbon::today()->month);
        $annee     = (int) $request->input('annee', Carbon::today()->year);
        $employeId = $request->input('employe_id', auth()->id());

        $employe = User::findOrFail($employeId);
        $nbJours = Carbon::createFromDate($annee, $mois, 1)->daysInMonth;

        $pointages = Pointage::where('employe_id', $employeId)
            ->whereMonth('date', $mois)
            ->whereYear('date', $annee)
            ->get()
            ->keyBy(fn($p) => Carbon::parse($p->date)->format('Y-m-d'));

        $nomMois = Carbon::createFromDate($annee, $mois, 1)->locale('fr')->monthName;

        $pdf = Pdf::loadView('superadmin.pointage.pdf.calendrier', compact('employe', 'pointages', 'mois', 'annee', 'nbJours', 'nomMois'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("calendrier_{$employe->nom}_{$nomMois}_{$annee}.pdf");
    }
}
