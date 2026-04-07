<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
         $users = User::all();
        return view("superadmin.utilisateurs.index", compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view("superadmin.utilisateurs.create", compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom'      => 'required|string|max:255',
            'prenom'   => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role'     => ['required', Rule::exists('roles', 'name')],
        ],[
            'nom.required' => 'Le champ nom est obligatoire.',
            'prenom.required' => 'Le champ prénom est obligatoire.',
            'email.required' => 'Le champ email est obligatoire.',
            'email.email' => 'Le champ email doit être une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.required' => 'Le champ mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'role.required' => 'Le champ rôle est obligatoire.',
            'role.exists' => 'Le rôle sélectionné est invalide.',
        ]);

        User::create([
            'nom'    => $request->nom,
            'prenom' => $request->prenom,
            'email'  => $request->email,
            'password' => bcrypt($request->password),
            'role'   => $request->role,
        ]);

        return redirect()->route('utilisateurs.index')->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('superadmin.utilisateurs.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user  = User::findOrFail($id);
        $roles = Role::all();
        return view('superadmin.utilisateurs.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'nom'      => 'required|string|max:255',
            'prenom'   => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'role'     => ['required', Rule::exists('roles', 'name')],
        ]);
        User::find($id)->update([
            'nom'    => $request->nom,
            'prenom' => $request->prenom,
            'email'  => $request->email,
            'password' => $request->password ? bcrypt($request->password) : User::find($id)->password,
            'role'   => $request->role,
        ]);
        return redirect()->route('utilisateurs.index')->with('success', 'Utilisateur modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('utilisateurs.index')->with('success', 'Utilisateur archivé avec succès.');
    }

    public function trashed()
    {
        $users = User::onlyTrashed()->get();
        return view('superadmin.utilisateurs.trashed', compact('users'));
    }

    public function restore(string $id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->route('utilisateurs.trashed')->with('success', 'Utilisateur restauré avec succès.');
    }

    public function forceDelete(string $id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();
        return redirect()->route('utilisateurs.trashed')->with('success', 'Utilisateur supprimé définitivement.');
    }
}