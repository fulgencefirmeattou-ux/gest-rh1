<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employe;
use App\Models\BulletinPaie;

class EmployeSpaceController extends Controller
{
    //  public function dashboard()
    // {
    //     $employe = auth()->user()->employe;

    //     return view('employe.dashboard', compact('employe'));
    // }

    public function profil()
    {
        return view('employe.profil', [
            'employe' => auth()->user()->employe
        ]);
    }

    public function contrats()
    {
        
        $employes = auth()->user()->employe;

        if (!$employes) {
            abort(404, 'Employé introuvable');
        }

        // Récupère le contrat actif (assurez-vous que la relation contratActif est définie)
        $contrat = $employes->contratActif;

        return view('employes.contrat', compact('employes', 'contrat'));
    }



    public function bulletins()
    {
        $employe = auth()->user()->employe;

        return view('employe.bulletins', [
            'bulletins' => $employe->bulletins()->latest()->get()
        ]);
    }

    public function downloadBulletin(BulletinPaie $bulletin)
    {
        abort_if(
            $bulletin->employe_id !== auth()->user()->employe->id,
            403
        );

        return response()->download(
            storage_path('app/public/'.$bulletin->pdf_path)
        );
    }
}

