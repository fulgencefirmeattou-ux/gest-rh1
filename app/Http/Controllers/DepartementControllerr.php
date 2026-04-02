<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Departement;
use App\Models\Employe;
use App\Models\DemandeConge;
use App\Models\HistoriqueConge;


class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

            // // Vérifier le rôle
            // if (!in_array($user->role, ['admin', 'dg'])) {
            //     return back()->withErrors([
            //         'authorization' => "Vous n'êtes pas autorisé à accéder à cette page."
            //     ]);
            // }
        $employes = Employe::with('user')->get();
        return view('departements.create-departement', compact('employes'));
    }

    public function listeEmploye($id)
    {
      $departements = Departement::with('employes.service')->findOrFail($id);
      $employes = $departements->employes;
      return view('departements.listeEmploye', compact('employes', 'departements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'nom'=>'required',
            'description'=>'nullable',
            'responsable_id'=>'nullable|exists:employes,id',

        ],[
            'nom.required' => 'Le nom du département est obligatoire.',
            'responsable_id.exists' => 'Le responsable sélectionné est invalide.',
        ]);

        if($validator->fails()){
            return redirect()->back()
                  ->withErrors($validator)
                  ->withInput();
        }

        Departement::create([
            'nom'=>$request->nom,
            'description'=>$request->description,
            'responsable_id'=>$request->responsable_id,
            'date_creation' => now(),
        ]);

        return redirect('/admin/create-departement')->with('success', 'Departement créé avec succès.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $departements = Departement::all();
       return view('departements.liste', compact('departements'));
    }

  

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $departements = Departement::findOrFail($id);
        $employes = Employe::with('user')->get();
        return view('departements.voir', compact('departements', 'employes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $departements = Departement::findOrFail($id);
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'responsable_id' => 'required|string',
        ],[
        'nom.required' => 'Modification du nom de departement requise.',
        'description.required' => 'veuillez ajouter une modification.',
        'responsable_id.required' => 'Sélectionnez un responsable de departement',
        ]);


        $departements->update($validated);

        return redirect()->route('departement.voir', $id)
            ->with('success', 'Departement mis à jour avec succès.');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $departements = Departement::findOrFail($id);
        $departements->delete();
        return redirect()->route('departements.liste')->with('success', 'Utilisateur supprimé avec succès.');
    }


    public function departementIndex()
    {
        $user = auth()->user();

        if (!$user->employe) {
            return back()->with('error', 'Aucun employé trouvé pour cet utilisateur.');
        }

        $employe = $user->employe;

        $service = $employe->service;

        if (!$service || !$service->departement) {
            return back()->with('error', 'Aucun département trouvé pour cet employé.');
        }

        $departement = $service->departement;

      
        if (!$isChefDepartement = Departement::where('responsable_id', $employe->id)->exists()) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à accéder à cette section.');
        }

       
        $conges = DemandeConge::whereHas('employe.service', function ($query) use ($departement) {
            $query->where('departement_id', $departement->id);
        })
        ->where('statut', 'attente_departement') 
        ->get();

        return view('conges.approbation.departement', compact('conges'));
    }

    public function ListeDemandeCongeTraiter(){
        $user = auth()->user();
        $employe = $user->employe;

        $service = $employe->service;

        $departement = $service->departement;
        $conges = DemandeConge::whereHas('employe.service', function ($query) use ($departement) {
            $query->where('departement_id', $departement->id);
        })->whereIn('statut', ['attente_dg','approuvee','rejetee'])->get();
        return view('conges.traiter.liste', compact('conges'));
    }
}
