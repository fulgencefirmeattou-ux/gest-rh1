<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DemandeConge;
use App\Models\Employe;
use App\Models\HistoriqueConge;
use App\Models\Departement;

// class CongeApprobationDepartementController extends Controller
// {
//     public function departementIndex()
//     {
//         $user = auth()->user();

//         if (!$user->employe) {
//             return back()->with('error', 'Aucun employé trouvé pour cet utilisateur.');
//         }

//         $employe = $user->employe;

//         $service = $employe->service;

//         if (!$service || !$service->departement) {
//             return back()->with('error', 'Aucun département trouvé pour cet employé.');
//         }

//         $departement = $service->departement;

      
//         if (!$isChefDepartement = Departement::where('responsable_id', $employe->id)->exists()) {
//             return back()->with('error', 'Vous n\'êtes pas autorisé à accéder à cette section.');
//         }

       
//         $conges = DemandeConge::whereHas('employe.service', function ($query) use ($departement) {
//             $query->where('departement_id', $departement->id);
//         })
//         ->where('statut', 'attente_departement') 
//         ->get();

//         return view('conges.approbation.departement', compact('conges'));
//     }

//     public function ListeDemandeCongeTraiter(){
//         $user = auth()->user();
//         $employe = $user->employe;

//         $service = $employe->service;

//         $departement = $service->departement;
//         $conges = DemandeConge::whereHas('employe.service', function ($query) use ($departement) {
//             $query->where('departement_id', $departement->id);
//         })->whereIn('statut', ['approuvee','rejetee'])->get();
//         return view('conges.traiter.liste', compact('conges'));
//     }

//     public function approve($id)
//     {
//         $conge = DemandeConge::findOrFail($id);

//         if (auth()->id() !== $conge->employe->service->departement->responsable_id) {
//             abort(403);
//         }

//         HistoriqueConge::create([
//             'demande_conge_id' => $conge->id,
//             'approver_id' => auth()->id(),
//             'etape' => 'departement',
//         ]);

//         $conge->statut = 'attente_dg';
//         $conge->save();

//         return redirect()->back()->with('success', 'La demande de congé a été approuvée avec succès.');
//     }

//     public function reject(Request $request, $id)
//     {
//         $conge = DemandeConge::findOrFail($id);

//         if (auth()->id() !== $conge->employe->service->departement->responsable_id) {
//             abort(403);
//         }

//         HistoriqueConge::create([
//             'demande_conge_id' => $conge->id,
//             'approver_id' => auth()->id(),
//             'etape' => 'departement',
//             'decision' => 'rejet',
//             'commentaire' => $request->commentaire,
//         ]);

//         $conge->statut = 'rejetee';
//         $conge->commentaire = $request->commentaire;
//         $conge->save();

//         return redirect()->back()->with('success', 'La demande de congé a été rejetée avec succès.');
//     }

//     // public function trait
// } 
