<?php

namespace App\Http\Controllers;

use App\Models\DemandeConge;
use App\Models\HistoriqueConge;
use App\Notifications\CongeNotification;
use Illuminate\Http\Request;

class CongeApprobationDgRhController extends Controller
{
    private function autoriser(): void
    {
        if (!in_array(auth()->user()->role, ['admin', 'rh', 'super-admin'])) {
            abort(403, "Acces non autorise.");
        }
    }

    public function dgRhIndex()
    {
        $this->autoriser();

        $conges = DemandeConge::with('employe')
            ->where('statut', 'attente_dg')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('superadmin.conges.approbation.dgRh', compact('conges'));
    }

    public function approve(Request $request, $id)
    {
        $this->autoriser();

        $conge = DemandeConge::with('employe')->findOrFail($id);

        $conge->update(['statut' => 'approuvee', 'commentaire' => null]);

        $employe = $conge->employe;
        if ($employe) {
            if ($conge->estPermission()) {
                $employe->increment('permissions_prises');
            } else {
                $employe->increment('conges_pris', max(1, $conge->jours_ouvres));
            }
        }

        HistoriqueConge::create([
            'demande_conge_id' => $conge->id,
            'approver_id'      => auth()->id(),
            'etape'            => 'dg_rh',
            'decision'         => 'approuve',
            'commentaire'      => null,
            'approved_at'      => now(),
        ]);

        $conge->employe?->user?->notify(new CongeNotification($conge, 'approuvee'));

        return back()->with('success', 'Conge approuve.');
    }

    public function reject(Request $request, $id)
    {
        $this->autoriser();
        $request->validate(['commentaire' => 'nullable|string|max:500']);

        $conge = DemandeConge::with('employe')->findOrFail($id);
        $conge->update(['statut' => 'rejetee', 'commentaire' => $request->commentaire]);

        HistoriqueConge::create([
            'demande_conge_id' => $conge->id,
            'approver_id'      => auth()->id(),
            'etape'            => 'dg_rh',
            'decision'         => 'rejete',
            'commentaire'      => $request->commentaire,
            'approved_at'      => now(),
        ]);

        $conge->employe?->user?->notify(new CongeNotification($conge, 'rejetee'));

        return back()->with('success', 'Conge rejete.');
    }

    public function ListeDemandeCongeTraiter()
    {
        $this->autoriser();

        $conges = DemandeConge::with('employe')
            ->whereIn('statut', ['approuvee', 'rejetee'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('superadmin.conges.traiter.liste-dg', compact('conges'));
    }
}
