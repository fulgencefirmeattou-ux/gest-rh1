<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //afficher la liste des permissions
        $permissions = Permission::paginate(7);
        return view("superadmin.permissions.index", compact("permissions"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view("superadmin.permissions.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ], [
            'name.required' => 'Le nom de la permission est obligatoire.',
            'name.unique'   => 'Cette permission existe déjà.',
        ]);

        Permission::create(['name' => $request->name, 'guard_name' => 'web']);

        return redirect()->route('permissions.index')->with('success', 'Permission créée avec succès.');    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //afficher le formulaire de modification d'une permission
        $permission = Permission::findOrFail($id);
        return view("superadmin.permissions.edit", compact("permission"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //enregistrer les modifications d'une permission
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
        ], [
            'name.required' => 'Le nom de la permission est obligatoire.',
            'name.unique'   => 'Cette permission existe déjà.',
        ]);
        Permission::findOrFail($id)->update(['name'=> $request->name,'guard_name'=> 'web']);
        return redirect()->route('permissions.index')->with('success','Permission modifiée avec succès.');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //delete permission
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return redirect()->route('permissions.index')->with('success','Permission supprimée avec succès.');
    }
}


