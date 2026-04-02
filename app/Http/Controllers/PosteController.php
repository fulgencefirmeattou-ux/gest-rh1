<?php

namespace App\Http\Controllers;

use App\Models\Poste;
use Illuminate\Http\Request;

class PosteController extends Controller
{
    public function index()
    {
        $postes = Poste::all();
        return view('superadmin.postes.index', compact('postes'));
    }

    public function create()
    {
        return view('superadmin.postes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:postes,name',
            'description' => 'nullable|string|max:255',
        ]);

        Poste::create([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('postes.index')->with('success', 'Poste créé avec succès.');
    }

   
   

    public function show(string $id)
    {
        $poste = Poste::findOrFail($id);
        return view('superadmin.postes.show', compact('poste'));
    }

    public function edit(string $id)
    {
        $poste = Poste::findOrFail($id);
        return view('superadmin.postes.edit', compact('poste'));
    }

    public function update(Request $request, string $id)
    {
        $poste = Poste::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255|unique:postes,name,' . $id,
            'description' => 'nullable|string|max:255',
        ]);

        $poste->update([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('postes.index')->with('success', 'Poste mis à jour avec succès.');
    }

    public function destroy(string $id)
    {
        $poste=Poste::findOrFail($id);
        $poste->delete();
        return redirect()->route('postes.index')->with('success', 'Poste supprimé avec succès.');
    }
}
