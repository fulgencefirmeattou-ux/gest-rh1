<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contrat;
use App\Models\Employe;
use App\Models\ContratPrime;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ContratController extends Controller
{
    public function index()
    {
        $contrats = Contrat::with('employe')->latest()->paginate(8);
        return view('superadmin.contrats.index', compact('contrats'));
    }

    public function create()
    {
        $employes = Employe::orderBy('nom')->get();
        return view('superadmin.contrats.create', compact('employes'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'employe_id'        => 'required|exists:employes,id',
            'type_contrat'      => 'required|string',
            'date_debut'        => 'required|date',
            'date_fin'          => 'nullable|date|after:date_debut',
            'salaire_base'      => 'required|numeric|min:0',
            'heures_par_semaine' => 'nullable|numeric|min:1',
        ]);

        // Désactiver les autres contrats actifs de cet employé
        Contrat::where('employe_id', $r->employe_id)
            ->update(['statut' => 'inactif']);

        $contrat = Contrat::create(
            $r->only([
                'employe_id', 'type_contrat', 'date_debut', 'date_fin',
                'salaire_base', 'mode_calcul', 'heures_par_semaine',
            ]) + ['statut' => 'actif']
        );

        // Enregistrer les primes
        if ($r->filled('primes')) {
            foreach ($r->input('primes') as $p) {
                if (empty($p['montant'])) continue;
                ContratPrime::create([
                    'contrat_id' => $contrat->id,
                    'libelle'    => $p['libelle'],
                    'montant'    => floatval($p['montant']),
                ]);
            }
        }

        // Générer le PDF
        $this->genererPdf($contrat->load('employe.poste', 'primes'));

        return redirect()->route('contrats.index')
            ->with('success', 'Contrat créé et PDF généré avec succès.');
    }

    public function show(Contrat $contrat)
    {
        $contrat->load('employe.poste', 'primes');
        return view('superadmin.contrats.show', compact('contrat'));
    }

    public function edit(Contrat $contrat)
    {
        $employes = Employe::orderBy('nom')->get();
        $contrat->load('primes');
        return view('superadmin.contrats.edit', compact('contrat', 'employes'));
    }

    public function update(Request $r, Contrat $contrat)
    {
        $r->validate([
            'type_contrat'      => 'required|string',
            'date_debut'        => 'required|date',
            'date_fin'          => 'nullable|date|after:date_debut',
            'salaire_base'      => 'required|numeric|min:0',
            'heures_par_semaine' => 'nullable|numeric|min:1',
        ]);

        $contrat->update($r->only([
            'type_contrat', 'date_debut', 'date_fin',
            'salaire_base', 'mode_calcul', 'heures_par_semaine', 'statut',
        ]));

        // Mettre à jour les primes : suppression puis recréation
        $contrat->primes()->delete();
        if ($r->filled('primes')) {
            foreach ($r->input('primes') as $p) {
                if (empty($p['montant'])) continue;
                ContratPrime::create([
                    'contrat_id' => $contrat->id,
                    'libelle'    => $p['libelle'],
                    'montant'    => floatval($p['montant']),
                ]);
            }
        }

        // Régénérer le PDF
        $this->genererPdf($contrat->load('employe.poste', 'primes'));

        return redirect()->route('contrats.show', $contrat->id)
            ->with('success', 'Contrat mis à jour et PDF régénéré.');
    }

    public function destroy(Contrat $contrat)
    {
        if ($contrat->pdf_path) {
            Storage::disk('public')->delete($contrat->pdf_path);
        }
        $contrat->delete();

        return redirect()->route('contrats.index')
            ->with('success', 'Contrat supprimé.');
    }

    public function downloadPdf(Contrat $contrat)
    {
        $contrat->load('employe.poste', 'primes');

        if ($contrat->pdf_path && Storage::disk('public')->exists($contrat->pdf_path)) {
            return Storage::disk('public')->download($contrat->pdf_path);
        }

        $pdf = Pdf::loadView('superadmin.contrats.pdf', compact('contrat'));
        return $pdf->stream("contrat_{$contrat->id}.pdf");
    }

    private function genererPdf(Contrat $contrat): void
    {
        $pdf = Pdf::loadView('superadmin.contrats.pdf', compact('contrat'));
        $filename  = "contrat_{$contrat->id}.pdf";
        $directory = storage_path('app/public/contrats');

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        file_put_contents("{$directory}/{$filename}", $pdf->output());

        $contrat->update(['pdf_path' => "contrats/{$filename}"]);
    }
}
