<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Service;
use App\Models\Employe;
use App\Models\Departement;
use App\Models\DemandeConge;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employes = Employe::with('user')->get();
        $departements = Departement::all();
        return view('services.create-service', compact('employes', 'departements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $employes = Employe::where('service_id', $service->id)->get();
          $validator=Validator::make($request->all(),[
            'nom'=>'required',
            'description'=>'nullable',
            'responsable_id'=>'nullable|exists:employes,id',
            'departement_id'=>'required|exists:departements,id',

        ],[
            'nom.required' => 'Le nom du service est obligatoire.',
            'responsable_id.exists' => 'Le responsable sélectionné est invalide.',
        ]);

        if($validator->fails()){
            return redirect()->back()
                  ->withErrors($validator)
                  ->withInput();
        }

        Service::create([
            'nom'=>$request->nom,
            'description'=>$request->description,
            'responsable_id'=>$request->responsable_id,
            'departement_id'=>$request->departement_id,
            'date_creation' => now(),
        ]);

        return redirect('/liste/services')->with('success', 'Service créé avec succès.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
       $services = Service::all();
       return view('services.liste', compact('services'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $services = Service::findOrFail($id);
        $departements = Departement::all();
        // $employes = Employe::with('user')->get();
        $employes = Employe::where('service_id', $services->id)->get();
        return view('services.voir', compact('services', 'departements', 'employes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $services = Service::findOrFail($id);
        // $departements = Departement::all();

        $validator = $request -> validate([
            'nom'=>'required',
            'description'=>'required',
            'responsable_id'=>'nullable',
            'departement_id'=>'required',

        ],[
            'nom.required' => 'Le nom du service est obligatoire.',
            'responsable_id.exists' => 'Le responsable sélectionné est invalide.',
        ]);

        $services -> update($validator);
        return redirect()->route('services.liste', $id)->with('success', 'Service modifié avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Service::findOrFail($id);
        $service -> delete();
        return redirect()->route('services.liste')->with('success','service supprimé avec succès');
    }

    public function approbationService()
    {
        $user = auth()->user();
        $employe = $user->employe;

        if (!$employe) {
            return back()->with('error', 'Aucun employé trouvé.');
        }

        $service = $employe->service;

        if (!$service) {
            return back()->with('error', 'Aucun service trouvé.');
        }

        if (!$isChefService = Service::where('responsable_id', $employe->id)->exists()) {
            abort(403, 'Accès refusé.');
        }

        $responsableDepartementId = $service->departement->responsable_id ?? null;

        $idsAExclure = [                 
            $responsableDepartementId      
        ];

    
        $employesId = Employe::where('service_id', $service->id)
            ->whereNotIn('id', $idsAExclure)
            ->whereHas('user', function ($q) {
                $q->whereNotIn('role', ['dg', 'rh']); 
            })
            ->pluck('id');

            // dd($employesId);
        $conges = DemandeConge::whereIn('employe_id', $employesId)
            ->where('statut', 'attente_service')
            ->get();

        return view('conges.approbation.service', compact('conges'));
    }

    public function ListeDemandeCongeTraiter(){
        $user = auth()->user();
        $employe = $user->employe;
        $service = $employe->service;

        $conges = DemandeConge::whereHas('employe.service', function ($query) use ($service) {
            $query->where('id', $service->id);
        })
        ->whereIn('statut', [
            'modification_demandee',
            'attente_departement',
            'approuvee',
            'rejetee'
        ])
        ->get();

       return view('conges.traiter.liste-service', compact('conges'));
    }
}
