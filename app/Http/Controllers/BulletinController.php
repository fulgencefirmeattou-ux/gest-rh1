<?php

namespace App\Http\Controllers;

use App\Models\BulletinPaie;
use App\Models\Employe;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class BulletinController extends Controller
{
    // ══════════════════════════════════════════════════════════════
    //  LISTE
    // ══════════════════════════════════════════════════════════════

    public function index(Request $request)
    {
        $query = BulletinPaie::with('employe')->orderBy('mois', 'desc');

        if ($request->filled('employe_id')) {
            $query->where('employe_id', $request->employe_id);
        }
        if ($request->filled('mois')) {
            $query->where('mois', $request->mois);
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $bulletins = $query->paginate(20)->withQueryString();
        $employes  = Employe::orderBy('nom')->get();

        return view('superadmin.bulletin_de_paie.index', compact('bulletins', 'employes'));
    }

    // ══════════════════════════════════════════════════════════════
    //  FORMULAIRE DE CRÉATION
    // ══════════════════════════════════════════════════════════════

    public function create()
    {
        $employes = Employe::orderBy('nom')->get();
        $moisCourant = Carbon::today()->format('Y-m');

        return view('superadmin.bulletin_de_paie.create', compact('employes', 'moisCourant'));
    }

    // ══════════════════════════════════════════════════════════════
    //  API AJAX : retourne les infos de l'employé pour pré-remplir
    // ══════════════════════════════════════════════════════════════

    public function infoEmploye(int $id)
    {
        $employe = Employe::with(['contrats' => fn($q) => $q->where('statut', 'actif')->latest()])->findOrFail($id);
        $contrat = $employe->contrats->first();

        return response()->json([
            'salaire_base'        => $contrat?->salaire_base ?? $employe->salaire ?? 0,
            'type_contrat'        => $contrat?->type_contrat ?? '—',
            'matricule'           => $employe->matricule,
            'nom'                 => $employe->nom,
            'prenom'              => $employe->prenom,
            'date_embauche'       => $employe->date_embauche?->format('d/m/Y'),
            'date_naissance'      => $employe->date_naissance?->format('d/m/Y'),
        ]);
    }

    // ══════════════════════════════════════════════════════════════
    //  API AJAX : calcule les montants en temps réel
    // ══════════════════════════════════════════════════════════════

    public function calculer(Request $request)
    {
        $data = $request->only([
            'salaire_base', 'avantage_nature', 'indemnite_transport',
            'brut_imposable', 'nbre_parts', 'cnps_plafond', 'cmu_base',
        ]);
        return response()->json(BulletinPaie::calculer($data));
    }

    // ══════════════════════════════════════════════════════════════
    //  GÉNÉRATION / ENREGISTREMENT
    // ══════════════════════════════════════════════════════════════

    public function store(Request $request)
    {
        $request->validate([
            'employe_id'          => 'required|exists:employes,id',
            'mois'                => 'required|date_format:Y-m',
            'salaire_base'        => 'required|numeric|min:0',
            'avantage_nature'     => 'nullable|numeric|min:0',
            'indemnite_transport' => 'nullable|numeric|min:0',
            'brut_imposable'      => 'nullable|numeric|min:0',
            'nbre_parts'          => 'nullable|numeric|min:0.5',
        ]);

        // Vérifie doublon
        $existe = BulletinPaie::where('employe_id', $request->employe_id)
            ->where('mois', $request->mois)
            ->where('statut', 'paye')
            ->exists();

        if ($existe) {
            return back()->withErrors(['mois' => 'Un bulletin payé existe déjà pour cet employé ce mois-ci.'])->withInput();
        }

        // Période
        $debut = Carbon::createFromFormat('Y-m', $request->mois)->startOfMonth();
        $fin   = $debut->copy()->endOfMonth();

        $data = array_merge($request->only([
            'employe_id', 'mois',
            'salaire_base', 'avantage_nature', 'indemnite_transport',
            'brut_imposable', 'nbre_parts', 'cnps_plafond', 'cmu_base',
            'retenue_is', 'retenue_cn', 'retenue_igr', 'retenue_cnps', 'retenue_cmu',
            'is_employeur', 'fdfp_ta', 'fdfp_fpc',
            'cnps_pf_emp', 'cnps_at_emp', 'cnps_retraite_emp', 'cmu_emp',
            'mode_reglement', 'reference_virement',
            'base_conge', 'jours_fiscaux', 'brut_imposable_cumul',
            'cumul_gains', 'cumul_retenues',
        ]), [
            'periode_debut' => $debut->toDateString(),
            'periode_fin'   => $fin->toDateString(),
            'statut'        => 'brouillon',
        ]);

        // Si l'admin a soumis les montants calculés manuellement on les garde,
        // sinon on recalcule tout automatiquement.
        if (!$request->filled('retenue_igr')) {
            $data = BulletinPaie::calculer($data);
        } else {
            // Recalcule uniquement les totaux
            $data['salaire_brut']               = ($data['salaire_base'] ?? 0) + ($data['avantage_nature'] ?? 0);
            $data['total_charges_fiscales_emp']  = ($data['is_employeur'] ?? 0) + ($data['fdfp_ta'] ?? 0) + ($data['fdfp_fpc'] ?? 0);
            $data['total_charges_sociales_emp']  = ($data['cnps_pf_emp'] ?? 0) + ($data['cnps_at_emp'] ?? 0) + ($data['cnps_retraite_emp'] ?? 0) + ($data['cmu_emp'] ?? 0);
            $data['total_charges_patronales']    = $data['total_charges_fiscales_emp'] + $data['total_charges_sociales_emp'];
            $data['total_retenues']              = ($data['retenue_is'] ?? 0) + ($data['retenue_cn'] ?? 0) + ($data['retenue_igr'] ?? 0) + ($data['retenue_cnps'] ?? 0) + ($data['retenue_cmu'] ?? 0);
            $data['salaire_net']                 = $data['salaire_brut'] - $data['total_retenues'];
            $data['net_a_payer']                 = $data['salaire_net'] + ($data['indemnite_transport'] ?? 0);
        }

        $bulletin = BulletinPaie::create($data);

        return redirect()->route('bulletins.show', $bulletin)
            ->with('success', 'Bulletin créé avec succès.');
    }

    // ══════════════════════════════════════════════════════════════
    //  AFFICHAGE
    // ══════════════════════════════════════════════════════════════

    public function show(BulletinPaie $bulletin)
    {
        $bulletin->load('employe');
        return view('superadmin.bulletin_de_paie.show', compact('bulletin'));
    }

    // ══════════════════════════════════════════════════════════════
    //  VALIDER / PAYER
    // ══════════════════════════════════════════════════════════════

    public function valider(BulletinPaie $bulletin)
    {
        if ($bulletin->statut === 'paye') {
            return back()->withErrors('Ce bulletin est déjà payé.');
        }
        $bulletin->update(['statut' => 'valide']);
        return back()->with('success', 'Bulletin validé.');
    }

    public function payer(BulletinPaie $bulletin)
    {
        if ($bulletin->statut === 'paye') {
            return back()->withErrors('Ce bulletin est déjà payé.');
        }

        $bulletin->update(['statut' => 'paye']);

        // Générer et stocker le PDF
        $pdf  = Pdf::loadView('superadmin.bulletin_de_paie.pdf.bulletin', compact('bulletin'))
            ->setPaper('a4', 'portrait');
        $path = 'bulletins/bulletin_' . $bulletin->id . '.pdf';
        Storage::disk('public')->put($path, $pdf->output());
        $bulletin->update(['pdf_path' => $path]);

        return redirect()->route('bulletins.index')
            ->with('success', 'Bulletin payé et PDF généré.');
    }

    // ══════════════════════════════════════════════════════════════
    //  HISTORIQUE PAIEMENTS D'UN EMPLOYÉ
    // ══════════════════════════════════════════════════════════════

    public function historiqueEmploye(Employe $employe)
    {
        $bulletins = BulletinPaie::where('employe_id', $employe->id)
            ->orderBy('mois', 'desc')
            ->get();

        $totalPaye      = $bulletins->where('statut', 'paye')->sum('net_a_payer');
        $totalBrut      = $bulletins->where('statut', 'paye')->sum('salaire_brut');
        $totalRetenues  = $bulletins->where('statut', 'paye')->sum('total_retenues');

        return view('superadmin.bulletin_de_paie.historique_employe', compact(
            'employe', 'bulletins', 'totalPaye', 'totalBrut', 'totalRetenues'
        ));
    }

    // ══════════════════════════════════════════════════════════════
    //  SUPPRESSION
    // ══════════════════════════════════════════════════════════════

    public function destroy(BulletinPaie $bulletin)
    {
        if ($bulletin->statut === 'paye') {
            return back()->withErrors('Impossible de supprimer un bulletin payé.');
        }
        $bulletin->delete();
        return redirect()->route('bulletins.index')
            ->with('success', 'Bulletin supprimé.');
    }

    // ══════════════════════════════════════════════════════════════
    //  TÉLÉCHARGEMENT PDF
    // ══════════════════════════════════════════════════════════════

    public function downloadPdf(BulletinPaie $bulletin)
    {
        $bulletin->load('employe');

        if ($bulletin->pdf_path && Storage::disk('public')->exists($bulletin->pdf_path)) {
            return response()->download(storage_path('app/public/' . $bulletin->pdf_path));
        }

        $pdf = Pdf::loadView('superadmin.bulletin_de_paie.pdf.bulletin', compact('bulletin'))
            ->setPaper('a4', 'portrait');

        $nom = 'bulletin_' . $bulletin->employe->nom . '_' . $bulletin->mois . '.pdf';
        return $pdf->download($nom);
    }
}
