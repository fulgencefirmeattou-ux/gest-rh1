<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DemandeConge;
use App\Models\Employe;
use App\Models\HistoriqueConge;
use App\Models\Service;

class CongeApprobationServiceController extends Controller
{
    // public function serviceIndex()
    // {
    //     $user = auth()->user();
    //     if (!$user->employe) {
    //         return back()->with('error', 'Aucun employé trouvé pour cet utilisateur.');
    //     }

    //     $employe = $user->employe;
    //     $service = $employe->service;

    //     if (!$service) {
    //         return back()->with('error', 'Aucun service trouvé pour cet employé.');
    //     }

    //     if ($service->responsable_id !== $employe->id) {
    //         return back()->with('error', 'Vous n\'êtes pas autorisé à accéder à cette section.');
    //     }

    //     // Récupérer les employés du service
    //     $employesId = Employe::where('service_id', $service->id)->pluck('id');

    //     // // Récupérer les demandes en attente validation chef de service
    //     // $conges = DemandeConge::whereIn('employe_id', $employesId)
    //     //                     ->where('statut', 'attente_service')
    //     //                     ->get();

    //     $conges = DemandeConge::whereHas('employe.service', function ($query) {
    //         $query->where('responsable_id', auth()->user()->employe->id);
    //     })->where('statut', 'attente_service')->get();

    //     return view('conges.approbation.service', compact('conges'));
    // }

    // public function serviceIndex()
    // {
    //     $user = auth()->user();
    //     $employe = $user->employe;

    //     if (!$employe) {
    //         return back()->with('error', 'Aucun employé trouvé.');
    //     }

    //     $service = $employe->service;

    //     if (!$service) {
    //         return back()->with('error', 'Aucun service trouvé.');
    //     }

    //     if (!$isChefService = Service::where('responsable_id', $employe->id)->exists()) {
    //         abort(403, 'Accès refusé.');
    //     }

    //     $responsableDepartementId = $service->departement->responsable_id ?? null;

    //     $idsAExclure = [                 
    //         $responsableDepartementId      
    //     ];

    
    //     $employesId = Employe::where('service_id', $service->id)
    //         ->whereNotIn('id', $idsAExclure)
    //         ->whereHas('user', function ($q) {
    //             $q->whereNotIn('role', ['dg', 'rh']); 
    //         })
    //         ->pluck('id');

    //         // dd($employesId);
    //     $conges = DemandeConge::whereIn('employe_id', $employesId)
    //         ->where('statut', 'attente_service')
    //         ->get();

    //     return view('conges.approbation.service', compact('conges'));
    // }



    // public function approve($id)
    // {
    //     $conge = DemandeConge::findOrFail($id);

    //     if (auth()->id() !== $conge->employe->service->responsable_id) {
    //         abort(403);
    //     }


    //     HistoriqueConge::create([
    //         'demande_conge_id' => $conge->id,
    //         'approver_id' => auth()->id(),
    //         'etape' => 'service',
    //         'decision' => 'approved',
    //         'approved_at' => now(),
    //     ]);

    //     $conge->update(['statut' => 'attente_departement']);

    //     return back()->with('success', 'Demande validée et envoyée au département.');
    // }


    // public function reject(Request $request, $id)
    // {
    //     $conge = DemandeConge::findOrFail($id);

    //     if (auth()->user()->employe->id !== $conge->employe->service->responsable_id) {
    //         abort(403);
    //     }

    //     $request->validate([
    //         'commentaire' => 'required|string',
    //     ]);

    //     HistoriqueConge::create([
    //         'demande_conge_id' => $conge->id,
    //         'approver_id' => auth()->id(),
    //         'etape' => 'service',
    //         'decision' => 'rejected',
    //         'commentaire' => $request->commentaire,
    //         'approved_at' => now(),
    //     ]);

    //     $conge->update(['statut' => 'rejetee']);

    //     return back()->with('success', 'Demande rejetée avec succès.');
    // }


    // public function requestModification(Request $request, $id)
    // {
    //     $conge = DemandeConge::findOrFail($id);

    //     if (auth()->user()->employe->id !== $conge->employe->service->responsable_id) {
    //         abort(403);
    //     }

    //     $request->validate([
    //         'commentaire' => 'required|string',
    //     ]);

    //     HistoriqueConge::create([
    //         'demande_conge_id' => $conge->id,
    //         'approver_id' => auth()->id(),
    //         'etape' => 'service',
    //         'decision' => 'modification_requested',
    //         'commentaire' => $request->commentaire,
    //     ]);

    //     $conge->update(['statut' => 'modification_requise']);

    //     return back()->with('success', 'Demande renvoyée à l’employé.');
    // }

    // public function ListeDemandeCongeTraiter(){
    //     $user = auth()->user();
    //     $employe = $user->employe;
    //     $service = $employe->service;

    //     $conges = DemandeConge::whereHas('employe.service', function ($query) use ($service) {
    //         $query->where('id', $service->id);
    //     })
    //     ->whereIn('statut', [
    //         'modification_demandee',
    //         'attente_departement',
    //         'approuvee',
    //         'rejetee'
    //     ])
    //     ->get();

    //     return view('conges.traiter.liste-service', compact('conges'));
    // }

}
