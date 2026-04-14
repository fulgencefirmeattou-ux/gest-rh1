<?php

namespace App\Http\Controllers;

use App\Models\InfoEntreprise;
use Illuminate\Http\Request;

class InfoEntrepriseController extends Controller
{
    public function index()
    {
        $info = InfoEntreprise::instance();
        return view('superadmin.info_entreprise.index', compact('info'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nom'             => 'nullable|string|max:255',
            'adresse'         => 'nullable|string|max:255',
            'siege_social'    => 'nullable|string|max:255',
            'num_cnps'        => 'nullable|string|max:100',
            'num_contribuable'=> 'nullable|string|max:100',
            'email'           => 'nullable|email|max:255',
            'telephone'       => 'nullable|string|max:50',
        ]);

        $info = InfoEntreprise::instance();

        $data = $request->only([
            'nom', 'adresse', 'siege_social',
            'num_cnps', 'num_contribuable',
            'email', 'telephone',
        ]);

        $info->fill($data);
        $info->save();

        return back()->with('success', 'Informations de l\'entreprise sauvegardées.');
    }
}
