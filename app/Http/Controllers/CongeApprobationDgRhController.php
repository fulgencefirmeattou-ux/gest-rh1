<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DemandeConge;
use App\Models\Employe;
use App\Models\HistoriqueConge;


class CongeApprobationDgRhController extends Controller
{
    public function dgRhIndex()
    {
        $conges = DemandeConge::whereIn('statut', ['attente_dg', 'attente_rh'])->get();

        return view('conges.approbation.dgRh', compact('conges'));
    }

    public function approve($id)
    {
        $conge = DemandeConge::findOrFail($id);

        $user = auth()->user();
        // $employe = $user->employe;
        $role = $user->role;

        // if (!$employe) {
        //     return back()->with('error', 'Aucun employé trouvé.');
        // }

       if (!in_array($user->role, ['dg', 'rh'])) {
            abort(403, "Vous n'êtes pas autorisé à traiter cette demande.");
        }

        if ($conge->statut === 'attente_dg') {
            HistoriqueConge::create([
                'demande_conge_id' => $conge->id,
                'approver_id' => $employe->id,
                'etape' => 'dg',
            ]);

            $conge->statut = 'attente_dg';
            $conge->save();

            return back()->with('success', 'Demande de congé approuvée avec succès.');
        } elseif ($conge->statut === 'attente_rh') {
            HistoriqueConge::create([
                'demande_conge_id' => $conge->id,
                'approver_id' => $employe->id,
                'etape' => 'rh',
            ]);

            $conge->statut = 'approuve';
            $conge->save();

            return back()->with('success', 'Demande de congé approuvée par le  avec succès.');
        } else {
            return back()->with('error', 'Statut de demande de congé invalide pour l\'approbation.');
        }
    }

    public function reject($id)
    {
        $conge = DemandeConge::findOrFail($id);

        $user = auth()->user();
        $role = $user->role;

       

        if (!in_array($user->role, ['dg', 'rh'])) {
            abort(403, "Vous n'êtes pas autorisé à traiter cette demande.");
        }

        $conge->statut = 'rejetee';
        $conge->save();

        return back()->with('success', 'Demande de congé rejetée avec succès.');
    }

    public function ListeDemandeCongeTraiter(){
        $user = auth()->user();
        $role = $user->role;

       if (!in_array($user->role, ['dg', 'rh'])) {
            abort(403, "Vous n'êtes pas autorisé à accéder à cette section.");
        }

        $conges = DemandeConge::whereIn('statut', ['approuvee','rejetee'])->get();
        return view('conges.traiter.liste-dg', compact('conges'));
    }

}
