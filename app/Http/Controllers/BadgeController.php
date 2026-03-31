<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Employe;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
 

    public function telecharger($id)
    {
        $employe = Employe::findOrFail($id);

        $pdf = Pdf::loadView('employes.badge', compact('employe')); // taille badge

        return $pdf->download('badge-'.$employe->code_badge.'.pdf');
    }

}
