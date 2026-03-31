<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BulletinPaie;
use App\Models\BulletinItem;
use App\Models\Employe;
use App\Models\Contrat;
use App\Models\Presence;
use PDF;
// use Storage;
use Illuminate\Support\Facades\Storage;

class BulletinController extends Controller
{
    public function create(){
        $employes = Employe::with(['contrats' => function($q){
        $q->where('statut', 'actif');
    }])->get();

    


    return view('bulletins.create', compact('employes'));
    }



    // Générer un bulletin pour un employé + mois (automatique)

    public function generate(Request $r)
    {
        $employe = Employe::findOrFail($r->employe_id);
        $contrat = $employe->contrats()->where('statut','actif')->firstOrFail();

        $existe = BulletinPaie::where('employe_id', $employe->id)
        ->where('contrat_id', $contrat->id)
        ->where('mois', $r->mois)
        ->where('statut', 'payé')
        ->exists();

        if ($existe) {
            return back()->withErrors(
                "Un bulletin payé existe déjà pour ce mois."
            );
        }


        $bulletin = BulletinPaie::create([
            'employe_id'   => $employe->id,
            'contrat_id'   => $contrat->id,
            'mois'         => $r->mois,
            'salaire_base' => $contrat->salaire_base,
            'statut'       => 'brouillon',
        ]);

        // Salaire
        BulletinItem::create([
            'bulletin_id' => $bulletin->id,
            'type' => 'salaire',
            'libelle' => 'Salaire de base',
            'montant' => $contrat->salaire_base
        ]);

        // Heures supplémentaires

        if ($r->total_heures_sup && $r->montant_heures_sup) {
            BulletinItem::create([
                'bulletin_id' => $bulletin->id,
                'type' => 'total_heures_sup',
                'libelle' => 'Heures supplémentaires',
                'montant' => $r->montant_heures_sup
            ]);

            $bulletin->update([
                'total_heures_sup' => $r->total_heures_sup,
                'montant_heures_sup' => $r->montant_heures_sup
            ]);
        }


        // Primes
        foreach ($r->primes ?? [] as $p) {
            if (!empty($p['montant'])) {
                BulletinItem::create([
                    'bulletin_id' => $bulletin->id,
                    'type' => 'prime',
                    'libelle' => $p['libelle'],
                    'montant' => $p['montant']
                ]);
            }
        }

        foreach($r->retenues ?? [] as $ret){
            if(!empty($ret['montant'])){
                BulletinItem::create([
                    'bulletin_id' => $bulletin->id,
                    'type' => 'retenue',
                    'libelle' => $ret['libelle'],
                    'montant' => $ret['montant']
                ]);
            }
        }

       

        
        // if ($r->cnps) {
        //     BulletinItem::create([
        //         'bulletin_id' => $bulletin->id,
        //         'type' => 'cotisation',
        //         'libelle' => 'CNPS',
        //         'montant' => $r->cnps
        //     ]);

        //     $bulletin->update([
        //         'montant_cnps' => $r->cnps,
        //         'cotisations' => $r->cnps
        //     ]);
        // }


        // if ($r->autres_retenues) {
        //     BulletinItem::create([
        //         'bulletin_id' => $bulletin->id,
        //         'type' => 'retenue',
        //         'libelle' => 'Autres retenues',
        //         'montant' => $r->autres_retenues
        //     ]);

        //     $bulletin->update([
        //         'autres_retenues' => $r->autres_retenues,
        //     ]);
        // }

        return redirect()->route('bulletins.edit', $bulletin);
    }



   



    
    public function payer(BulletinPaie $bulletin)
    {
        if ($bulletin->statut === 'payé') {
            return back();
        }

        $bulletin->recalculerTotaux();

        $bulletin->update([
            'statut' => 'payé',
        ]);

        // PDF
        $pdf = PDF::loadView('bulletins.pdf', compact('bulletin'));
        $path = 'bulletins/bulletin_'.$bulletin->id.'.pdf';
        Storage::disk('public')->put($path, $pdf->output());

        $bulletin->update(['pdf_path' => $path]);

        return redirect()
            ->route('bulletins.index')
            ->with('success','Bulletin payé et généré');
    }




    public function show(BulletinPaie $bulletin){ 
        return view('bulletins.show', compact('bulletin')); 
    }


    public function downloadPdf(BulletinPaie $bulletin){
        if(!$bulletin->pdf_path || !Storage::disk('public')->exists($bulletin->pdf_path)){
            $pdf = PDF::loadView('bulletins.pdf', ['bulletin'=>$bulletin->load('employe','items','contrat')]);
        return $pdf->stream('bulletin_'.$bulletin->id.'.pdf');
    }
    return response()->download(storage_path('app/public/'.$bulletin->pdf_path));

    }

    public function edit(BulletinPaie $bulletin)
    {
        if ($bulletin->statut === 'payé') {
            return redirect()
                ->route('bulletins.index')
                ->withErrors('Ce bulletin est déjà payé.');
        }

        $bulletin->load('employe','items','contrat');

        return view('bulletins.edit', compact('bulletin'));
    }


    public function index(){
        $bulletins = BulletinPaie::with('employe')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('bulletins.index', compact('bulletins'));
    }
}
