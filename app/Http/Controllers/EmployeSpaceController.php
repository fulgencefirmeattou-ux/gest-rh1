<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        $user = auth()->user();
        return view('superadmin.employe.profil', [
            'user'    => $user,
            'employe' => $user->employe,
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

        return view('superadmin.employe.bulletins', [
            'bulletins' => $employe->bulletins()->latest()->get()
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'new_password.required'     => 'Le nouveau mot de passe est obligatoire.',
            'new_password.min'          => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
            'new_password.confirmed'    => 'La confirmation ne correspond pas au nouveau mot de passe.',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.'])
                ->with('open_password_modal', true);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('employe.profil')
            ->with('success', 'Mot de passe modifié avec succès.');
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

