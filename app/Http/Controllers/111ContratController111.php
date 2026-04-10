<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contrat;
use App\Models\Employe;
use App\Models\ContratPrime;
// use PDF;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
// use Storage;

class ContratController extends Controller
{
    public function index(){ 
        $contrats = Contrat::with('employe')->latest()->paginate(12); 
        return view('contrats.index', compact('contrats')); 
    }

    public function create(){ 
        $employes = Employe::all();
        return view('contrats.create',compact('employes')); 
    }

    public function store(Request $r)
    {
        $r->validate([
            'employe_id'   => 'required',
            'date_debut'   => 'required|date',
            'salaire_base' => 'required|numeric',
            'heures_par_semaine' => 'nullable|numeric',
        ]);

        // Désactiver les autres contrats
        Contrat::where('employe_id', $r->employe_id)
            ->update(['statut' => 'inactif']);

        // Création du contrat
        $contrat = Contrat::create(
            $r->only([
                'employe_id',
                'type_contrat',
                'date_debut',
                'date_fin',
                'salaire_base',
                'mode_calcul',
                'heures_par_semaine',
            ]) + ['statut' => 'actif']
        );

        // Enregistrer les primes si présentes
        if ($r->has('primes')) {
            foreach ($r->input('primes') as $p) {
                if (empty($p['montant'])) continue;

                ContratPrime::create([
                    'contrat_id' => $contrat->id,
                    'libelle'    => $p['libelle'],
                    'montant'    => floatval($p['montant']),
                ]);
            }
        }

        // --- Génération du PDF TOUJOURS ---
        $pdf = Pdf::loadView('contrats.pdf', [
            'contrat' => $contrat->load('employe', 'primes')
        ]);

        $filename = 'contrat_' . $contrat->id . '.pdf';
        $directory = storage_path('app/public/contrats');
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        file_put_contents($directory . '/' . $filename, $pdf->output());

        $contrat->update([
            'pdf_path' => 'contrats/' . $filename
        ]);


        return redirect()
            ->route('contrats.index')
            ->with('success', 'Contrat créé et PDF généré avec succès.');
    }

    public function edit(Contrat $contrat){ 
        $employes = Employe::all();
        return view('contrats.edit',compact('contrat','employes')); 
    }

    public function update(Request $r, Contrat $contrat)
    {
        $r->validate([
            'date_debut'   => 'required|date',
            'salaire_base' => 'required|numeric',
            'heures_par_semaine'        => 'nullable|numeric',
        ]);

        // Mise à jour du contrat
        $contrat->update(
            $r->only([
                'type_contrat',
                'date_debut',
                'date_fin',
                'salaire_base',
                'mode_calcul',
                'heures_par_semaine',
                'statut',
            ])
        );

        // Régénération du PDF
        $pdf = Pdf::loadView('contrats.pdf', [
            'contrat' => $contrat->load('employe', 'primes')
        ]);

        $filename = 'contrat_' . $contrat->id . '.pdf';
        $directory = public_path('assets/contrats');

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        file_put_contents(
            $directory . '/' . $filename,
            $pdf->output()
        );

        $contrat->update([
            'pdf_path' => 'assets/contrats/' . $filename
        ]);

        return redirect()
            ->route('contrats.show', $contrat->id)
            ->with('success', 'Contrat mis à jour et PDF régénéré.');
    }



    public function show(Contrat $contrat){ 
        return view('contrats.show',compact('contrat')); 
    }

    public function downloadPdf(Contrat $contrat){
        if(!$contrat->pdf_path || !Storage::disk('public')->exists($contrat->pdf_path)){
            $pdf = Pdf::loadView('contrats.pdf', ['contrat'=>$contrat->load('employe','primes')]);
            return $pdf->stream('contrat_'.$contrat->id.'.pdf');
        }

        return Storage::disk('public')->download($contrat->pdf_path);
    }
}