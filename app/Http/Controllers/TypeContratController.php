<?php

namespace App\Http\Controllers;

use App\Models\TypeContrat;
use Illuminate\Http\Request;

class TypeContratController extends Controller
{
    public function index()
    {
        $type_contrats = TypeContrat::all();
        return view('superadmin.type_contrats.index', compact('type_contrats'));
    }

    public function create()
    {
        return view('superadmin.type_contrats.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:type_contrats,name',
            'description' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Le nom du type de contrat est obligatoire.',
            'name.unique'   => 'Ce type de contrat existe déjà.',
        ]);

        TypeContrat::create([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('type_contrats.index')->with('success', 'Type de contrat créé avec succès.');
    }

    public function show(string $id)
    {
        $type_contrat = TypeContrat::findOrFail($id);
        return view('superadmin.type_contrats.show', compact('type_contrat'));
    }

    public function edit(string $id)
    {
        $type_contrat = TypeContrat::findOrFail($id);
        return view('superadmin.type_contrats.edit', compact('type_contrat'));
    }

    public function update(Request $request, string $id)
    {
        $type_contrat = TypeContrat::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255|unique:type_contrats,name,' . $id,
            'description' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Le nom du type de contrat est obligatoire.',
            'name.unique'   => 'Ce type de contrat existe déjà.',
        ]);

        $type_contrat->update([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('type_contrats.index')->with('success', 'Type de contrat mis à jour avec succès.');
    }

    public function destroy(string $id)
    {
        TypeContrat::findOrFail($id)->delete();
        return redirect()->route('type_contrats.index')->with('success', 'Type de contrat supprimé avec succès.');
    }
}
