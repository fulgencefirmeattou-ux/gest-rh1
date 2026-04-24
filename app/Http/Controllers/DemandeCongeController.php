<?php

namespace App\Http\Controllers;

use App\Models\DemandeConge;
use App\Models\Employe;
use App\Models\HistoriqueConge;
use App\Models\Service;
use App\Models\Departement;
use App\Models\User;
use App\Notifications\CongeNotification;
use Illuminate\Http\Request;

class DemandeCongeController extends Controller
{
    private function employeOuAbort(): Employe
    {
        $employe = auth()->user()->employe;
        if (!$employe) abort(403);
        return $employe;
    }

    private function enregistrerHistorique(DemandeConge $conge, string $etape, string $decision, ?string $commentaire): void
    {
        HistoriqueConge::create([
            'demande_conge_id' => $conge->id,
            'approver_id'      => auth()->id(),
            'etape'            => $etape,
            'decision'         => $decision,
            'commentaire'      => $commentaire,
            'approved_at'      => now(),
        ]);
    }

    private function notifierProchainApprobateur(DemandeConge $conge): void
    {
        if ($conge->statut === 'attente_departement') {
            $service = $conge->employe->service;
            if ($service?->departement?->responsable_id) {
                User::find($service->departement->responsable_id)
                    ?->notify(new CongeNotification($conge, 'etape_suivante'));
            }
        } elseif ($conge->statut === 'attente_dg') {
            User::whereIn('role', ['dg', 'rh'])->get()
                ->each(fn($u) => $u->notify(new CongeNotification($conge, 'etape_suivante')));
        }
    }

    public function index()
    {
        $employe = $this->employeOuAbort();
        $conges  = DemandeConge::where('employe_id', $employe->id)->orderBy('created_at', 'desc')->get();
        return view('superadmin.conges.voir', compact('conges', 'employe'));
    }

    public function create()
    {
        $employe = $this->employeOuAbort();
        return view('superadmin.conges.create-conge', compact('employe'));
    }

    public function store(Request $request)
    {
        $employe = $this->employeOuAbort();
        $request->validate([
            'type_conge'       => 'required|in:conge_paye,maladie,permission_courte,exceptionnel,special,autre',
            'date_debut_conge' => 'required|date',
            'date_fin_conge'   => 'required|date|after_or_equal:date_debut_conge',
            'date_retour'      => 'nullable|date|after_or_equal:date_fin_conge',
            'raison'           => 'nullable|string|max:1000',
        ]);

        if ($request->type_conge === 'permission_courte' && $employe->soldePermissionsRestant() <= 0) {
            return back()->withErrors(['type_conge' => 'Solde de permissions epuise (' . $employe->permissions_prises . '/10).'])->withInput();
        }

        $service      = $employe->service;
        $departement  = $service?->departement;
        $hasChefService = $service && $service->responsable_id && $service->responsable_id !== $employe->id;
        $hasChefDept    = $departement && $departement->responsable_id && $departement->responsable_id !== $employe->id;

        if ($hasChefService) {
            $statutInitial = 'attente_service';
        } elseif ($hasChefDept) {
            $statutInitial = 'attente_departement';
        } else {
            $statutInitial = 'attente_dg';
        }

        $conge = DemandeConge::create([
            'employe_id'       => $employe->id,
            'type_conge'       => $request->type_conge,
            'date_debut_conge' => $request->date_debut_conge,
            'date_fin_conge'   => $request->date_fin_conge,
            'date_retour'      => $request->date_retour,
            'raison'           => $request->raison,
            'statut'           => $statutInitial,
        ]);

        if ($statutInitial === 'attente_service' && $employe->service?->responsable_id) {
            User::whereHas('employe', fn($q) => $q->where('id', $employe->service->responsable_id))
                ->first()?->notify(new CongeNotification($conge, 'nouvelle'));
        } elseif ($statutInitial === 'attente_departement' && $employe->departement?->responsable_id) {
            User::find($employe->departement->responsable_id)?->notify(new CongeNotification($conge, 'nouvelle'));
        } else {
            User::whereIn('role', ['dg', 'rh'])->get()->each(fn($u) => $u->notify(new CongeNotification($conge, 'nouvelle')));
        }

        return redirect()->route('conges.voir')->with('success', 'Demande envoyee avec succes.');
    }

    public function show($id)
    {
        $user  = auth()->user();
        $conge = DemandeConge::with(['employe', 'historiques.approver'])->findOrFail($id);
        return view('superadmin.conges.details', compact('conge', 'user'));
    }

    public function resubmit(Request $request, $id)
    {
        $employe = $this->employeOuAbort();
        $conge   = DemandeConge::findOrFail($id);
        if ($conge->employe_id !== $employe->id) abort(403);
        $request->validate([
            'date_debut_conge' => 'required|date',
            'date_fin_conge'   => 'required|date|after_or_equal:date_debut_conge',
            'date_retour'      => 'nullable|date|after_or_equal:date_fin_conge',
        ]);
        $conge->update([
            'date_debut_conge' => $request->date_debut_conge,
            'date_fin_conge'   => $request->date_fin_conge,
            'date_retour'      => $request->date_retour,
            'raison'           => $request->raison,
            'statut'           => 'attente_service',
            'commentaire'      => null,
        ]);
        return redirect()->route('conges.voir')->with('success', 'Demande renvoyee.');
    }

    public function traiterService(Request $request, $id)
    {
        $request->validate(['action' => 'required|in:approve,modify,reject', 'commentaire' => 'nullable|string|max:500']);
        $employe = auth()->user()->employe;
        if (!$employe || !Service::where('responsable_id', $employe->id)->exists()) abort(403);

        $conge = DemandeConge::with('employe')->findOrFail($id);

        // Si approbation : vérifier s'il y a un chef département sinon sauter à attente_dg
        if ($request->action === 'approve') {
            $service     = $conge->employe?->service;
            $departement = $service?->departement;
            $hasDeptChef = $departement && $departement->responsable_id;
            $approveStatut = $hasDeptChef ? 'attente_departement' : 'attente_dg';
        }

        $newStatut = match($request->action) { 'approve' => $approveStatut, 'modify' => 'modification_demandee', 'reject' => 'rejetee' };
        $decision  = match($request->action) { 'approve' => 'approuve', 'modify' => 'modification_demandee', 'reject' => 'rejete' };

        $conge->update(['statut' => $newStatut, 'commentaire' => $request->commentaire]);
        $this->enregistrerHistorique($conge, 'service', $decision, $request->commentaire);
        $conge->refresh();

        if (in_array($request->action, ['modify', 'reject'])) {
            $conge->employe?->user?->notify(new CongeNotification($conge, $request->action === 'reject' ? 'rejetee' : 'modification_demandee'));
        } else {
            $this->notifierProchainApprobateur($conge);
        }
        return back()->with('success', 'Action effectuee.');
    }

    public function traiterDepartement(Request $request, $id)
    {
        $request->validate(['action' => 'required|in:approve,reject', 'commentaire' => 'nullable|string|max:500']);
        $employe = auth()->user()->employe;
        if (!$employe || !Departement::where('responsable_id', $employe->id)->exists()) abort(403);

        $conge = DemandeConge::with('employe')->findOrFail($id);
        $newStatut = $request->action === 'approve' ? 'attente_dg' : 'rejetee';
        $conge->update(['statut' => $newStatut, 'commentaire' => $request->commentaire]);
        $this->enregistrerHistorique($conge, 'departement', $request->action === 'approve' ? 'approuve' : 'rejete', $request->commentaire);
        $conge->refresh();

        if ($request->action === 'reject') {
            $conge->employe?->user?->notify(new CongeNotification($conge, 'rejetee'));
        } else {
            $this->notifierProchainApprobateur($conge);
        }
        return back()->with('success', 'Action effectuee.');
    }

    public function traiterDg(Request $request, $id)
    {
        $request->validate(['action' => 'required|in:approve,reject', 'commentaire' => 'nullable|string|max:500']);
        if (!in_array(auth()->user()->role, ['dg', 'rh', 'admin'])) abort(403);

        $conge = DemandeConge::with('employe')->findOrFail($id);
        if ($request->action === 'approve') {
            $conge->update(['statut' => 'approuvee', 'commentaire' => null]);
            $employe = $conge->employe;
            if ($employe) {
                if ($conge->estPermission()) $employe->increment('permissions_prises');
                else $employe->increment('conges_pris', max(1, $conge->jours_ouvres));
            }
            $conge->employe?->user?->notify(new CongeNotification($conge, 'approuvee'));
        } else {
            $conge->update(['statut' => 'rejetee', 'commentaire' => $request->commentaire]);
            $conge->employe?->user?->notify(new CongeNotification($conge, 'rejetee'));
        }
        $this->enregistrerHistorique($conge, 'dg_rh', $request->action === 'approve' ? 'approuve' : 'rejete', $request->commentaire);
        return back()->with('success', 'Action effectuee.');
    }
}
