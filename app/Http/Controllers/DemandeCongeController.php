<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DemandeConge;
use App\Models\Employe;
use App\Models\HistoriqueConge;
use Illuminate\Support\Facades\Auth;
use App\Models\Service;
use App\Models\Departement;

class DemandeCongeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user->employe?->id){
            return back()->withErrors([
                'employe' => "Vous n'êtes pas encore enregistré comme employé dans le système."
            ]);
        }
        $employe = Employe::find($user->employe?->id);

        $conges = DemandeConge::where('employe_id', $employe->id)->get();

        return view('conges.voir', compact('conges', 'user'));
    }

    public function create()
    {
        return view('conges.create-conge');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->employe?->id) {
            return back()->withErrors([
                'employe' => "Vous n'êtes pas encore enregistré comme employé dans le système."
            ]);
        }

        $employe = Employe::find($user->employe?->id);


        $request->validate([
            'type_conge' => 'required',
            'date_debut_conge' => 'required|date',
            'date_fin_conge' => 'required|date|after_or_equal:date_debut_conge',
            'date_retour' => 'date|after_or_equal:date_fin_conge|after_or_equal:date_debut_conge',
            'raison' => 'nullable',
        ], [
            'type_conge.required' => 'Le type de congé est obligatoire.',
            'date_debut_conge.required' => 'La date de début de congé est obligatoire.',
            'date_fin_conge.required' => 'La date de fin de congé est obligatoire.',
            'date_fin_conge.after_or_equal' => 'La date de fin de congé doit être postérieure ou égale à la date de début de congé.',
            'date_retour.after_or_equal' => 'La date de retour de congé doit être postérieure ou égale à la date de fin ou debut de congé.',
            'date_retour.date' => 'La date de retour doit être une date valide.',
            // 'date_retour.after_or_equal' => 'La date de retour doit être postérieure ou égale à une date valide.',
        ]);

        $user = auth()->user();
        $employe = $user->employe;

        if (!$employe) {
            return back()->with('error', 'Aucun employé trouvé.');
        }

        $service = $employe->service;
        $departement = $service ? $service->departement : null;

        $isChefService = Service::where('responsable_id', $employe->id)->exists();
        $isChefDepartement = Departement::where('responsable_id', $employe->id)->exists();

        if ($isChefService) {
            $statutInitial = 'attente_departement';
        } elseif ($isChefDepartement) {
            $statutInitial = 'attente_dg';
        } else {
            $statutInitial = 'attente_service';
        }

        DemandeConge::create([
            'employe_id' => $employe->id,
            'type_conge' => $request->type_conge,
            'date_debut_conge' => $request->date_debut_conge,
            'date_fin_conge' => $request->date_fin_conge,
            'date_retour' => $request->date_retour,
            'raison' => $request->raison,
            'statut' => $statutInitial
        ]);

        return back()->with('success', 'Demande envoyée au supérieur hiérachique.');
    }

    public function resubmit(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->employe?->id) {
            return back()->withErrors([
                'employe' => "Vous n'êtes pas encore enregistré comme employé dans le système."
            ]);
        }

        $employe = Employe::find($user->employe?->id);

        $conge = DemandeConge::findOrFail($id);

        if ($conge->employe_id !== $employe->id) {
            abort(403, 'Accès refusé');
        }

        $request->validate([
            'date_debut_conge' => 'required|date',
            'date_fin_conge' => 'required|date|after_or_equal:date_debut_conge',
            'date_retour' => 'date|after_or_equal:date_fin_conge|after_or_equal:date_debut_conge',
        ]);

        $conge->update([
            'date_debut_conge' => $request->date_debut_conge,
            'date_fin_conge' => $request->date_fin_conge,
            'date_retour' => $request->date_retour,
            'statut' => 'attente_service'
        ]);

        return redirect()->route('conges.voir')
            ->with('success', 'Demande renvoyée au responsable de service.');
    }

    public function show($id)
    {
       
        $user = auth()->user();
        $conge = DemandeConge::findOrFail($id);
        $employes = DemandeConge::where('employe_id', $conge->employe_id)->get();
        return view('conges.details', compact('conge', 'employes', 'user'));
    }

    public function traiterService(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,modify,reject',
            'commentaire' => 'nullable|string'
        ]);

        $conge = DemandeConge::findOrFail($id);
        $responsable = auth()->user()->employe;

        $isChefService = Service::where('responsable_id', $responsable->id)->exists();

        if (!$isChefService) {
            abort(403, "Vous n'êtes pas autorisé à traiter cette demande.");
        }

        switch ($request->action) {
            case 'approve':
                $conge->statut = 'attente_departement';
                break;

            case 'modify':
                $conge->statut = 'modification_demandee';
                $conge->commentaire = $request->commentaire;
                break;

            case 'reject':
                $conge->statut = 'rejetee';
                $conge->commentaire = $request->commentaire;
                break;
        }

        $conge->save();

        return back()->with('success', 'Action effectuée avec succès.');
    }

    public function traiterDepartement(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,modify,reject',
            'commentaire' => 'nullable|string'
        ]);

        $conge = DemandeConge::findOrFail($id);

        // Responsable de service = employe->service->responsable_id
        $responsable = auth()->user()->employe;

        $departementResponsableId = $conge->employe->service->departement->responsable_id ?? null;

        if (!$isChefDepartement = Departement::where('responsable_id', $departementResponsableId)->exists() ) {
            abort(403, "Vous n'êtes pas autorisé à traiter cette demande.");
        }


        switch ($request->action) {

            case 'approve':
                $conge->statut = 'attente_dg';
                break;

            // case 'modify':
            //     $conge->statut = 'modification_demandee';
            //     $conge->commentaire = $request->commentaire;
            //     break;

            case 'reject':
                $conge->statut = 'rejetee';
                $conge->commentaire = $request->commentaire;
                break;
        }

        $conge->save();

        return back()->with('success', 'Action effectuée avec succès.');
    }

    public function traiterDg(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'commentaire' => 'nullable|string'
        ]);

        $conge = DemandeConge::findOrFail($id);

        $user= auth()->user();
        $role = $user->role;

       if (!in_array($user->role, ['dg', 'rh'])) {
            abort(403, "Vous n'êtes pas autorisé à traiter cette demande.");
        }

        switch ($request->action) {

            case 'approve':
                $conge->statut = 'approuvee';
                break;

            case 'reject':
                $conge->statut = 'rejetee';
                $conge->commentaire = $request->commentaire;
                break;
        }

        $conge->save();

        return back()->with('success', 'Action effectuée avec succès.');
    }

}
