<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\DemandeConge;
use App\Models\Employe;
use App\Models\User;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    public function index()
    {
        $departements = Departement::with('responsable')->get();
        return view("superadmin.departements.index", compact("departements"));
    }

    public function create()
    {
        $users = User::all();
        return view("superadmin.departements.create", compact("users"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'            => 'required|string|max:255',
            'description'    => 'nullable|string',
            'responsable_id' => 'nullable|exists:users,id',
        ], [
            'nom.required'          => 'Le nom du département est obligatoire.',
            'responsable_id.exists' => 'Le responsable sélectionné est invalide.',
        ]);
        Departement::create([
            'nom'            => $request->nom,
            'description'    => $request->description,
            'responsable_id' => $request->responsable_id,
        ]);

        return redirect()->route('departements.index')->with('success', 'Département créé avec succès.');
    }

    public function show(string $id)
    {
        $departement = Departement::with('responsable')->findOrFail($id);
        return view('superadmin.departements.show', compact('departement'));
    }

    public function edit(string $id)
    {
        $departement = Departement::findOrFail($id);
        $users = User::all();
        return view('superadmin.departements.edit', compact('departement', 'users'));
    }

    public function update(Request $request, string $id)
    {
        $departement = Departement::findOrFail($id);

        $request->validate([
            'nom'            => 'required|string|max:255',
            'description'    => 'nullable|string',
            'responsable_id' => 'nullable|exists:users,id',
        ], [
            'nom.required'          => 'Le nom du département est obligatoire.',
            'responsable_id.exists' => 'Le responsable sélectionné est invalide.',
        ]);

        $departement->update([
            'nom'            => $request->nom,
            'description'    => $request->description,
            'responsable_id' => $request->responsable_id,
        ]);

        return redirect()->route('departements.index')->with('success', 'Département mis à jour avec succès.');
    }

    public function destroy(string $id)
    {
        $departement = Departement::findOrFail($id);

        Employe::where('departement_id', $id)->each(function ($employe) {
            $employe->user?->delete();
            $employe->delete();
        });

        $departement->delete();
        return redirect()->route('departements.index')->with('success', 'Département supprimé avec succès.');
    }

    // Routes API internes
    public function employesParDepartement(string $id)
    {
        $departement = Departement::with('employes')->findOrFail($id);
        return response()->json($departement->employes);
    }

    public function listeEmploye(string $id)
    {
        $departement = Departement::with('employes')->findOrFail($id);
        return view('superadmin.departements.liste_employes', compact('departement'));
    }

    public function departementIndex()
    {
        $user    = auth()->user();
        $employe = $user->employe;

        if (!$employe) {
            return back()->with('error', 'Aucun employe trouve.');
        }

        $departement = Departement::where('responsable_id', $employe->id)->first();
        if (!$departement) {
            abort(403, 'Vous n\'etes pas responsable de departement.');
        }

        $employes_ids = $departement->employes()->pluck('employes.id');

        $conges = DemandeConge::with('employe')
            ->whereIn('employe_id', $employes_ids)
            ->where('statut', 'attente_departement')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('superadmin.conges.approbation.departement', compact('conges', 'departement'));
    }

    public function ListeDemandeCongeTraiter()
    {
        $user    = auth()->user();
        $employe = $user->employe;

        $departement = Departement::where('responsable_id', $employe?->id)->first();

        $employes_ids = $departement?->employes()->pluck('employes.id') ?? collect();

        $conges = DemandeConge::with('employe')
            ->whereIn('employe_id', $employes_ids)
            ->whereIn('statut', ['approuvee', 'rejetee', 'attente_dg', 'modification_demandee'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('superadmin.conges.traiter.liste', compact('conges'));
    }
}
