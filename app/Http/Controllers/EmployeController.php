<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departement;
use App\Models\Employe;
use App\Models\Poste;
use App\Models\TypeContrat;

class EmployeController extends Controller
{
    public function index()
    {
        $employes = Employe::with(['departement', 'poste', 'typeContrat'])->get();
        return view('superadmin.employe.index', compact('employes'));
    }

    public function create()
    {
        $departements = Departement::all();
        $postes       = Poste::all();
        $typeContrats = TypeContrat::all();
        return view('superadmin.employe.create', compact('departements', 'postes', 'typeContrats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'              => 'required|string|max:255',
            'prenom'           => 'required|string|max:255',
            'civilite'         => 'nullable|string|max:10',
            'nationalite'      => 'nullable|string|max:100',
            'situation_matrimoniale' => 'nullable|string|max:50',
            'nombre_enfants'   => 'nullable|integer|min:0',
            'date_naissance'   => 'nullable|date',
            'telephone'        => 'required|string|max:20',
            'email'            => 'required|email|unique:employes,email',
            'adresse'          => 'nullable|string|max:500',
            'photo_profil'     => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'departement_id'   => 'required|exists:departements,id',
            'poste_id'         => 'required|exists:postes,id',
            'type_contrat_id'  => 'required|exists:type_contrats,id',
            'date_embauche'    => 'required|date',
        ], [
            'nom.required'           => 'Le nom est obligatoire.',
            'prenom.required'        => 'Le prénom est obligatoire.',
            'telephone.required'     => 'Le téléphone est obligatoire.',
            'email.required'         => "L'email est obligatoire.",
            'email.unique'           => "L'email est déjà utilisé pour un autre employé.",
            'departement_id.required'=> 'Le département est obligatoire.',
            'poste_id.required'      => 'Le poste est obligatoire.',
            'type_contrat_id.required' => 'Le type de contrat est obligatoire.',
            'date_embauche.required' => "La date d'embauche est obligatoire.",
            'photo_profil.image'     => 'Le fichier doit être une image.',
            'photo_profil.mimes'     => 'Seuls les formats JPG et PNG sont autorisés.',
            'photo_profil.max'       => 'La taille maximale est de 5 Mo.',
        ]);

        if ($request->hasFile('photo_profil')) {
            $photoName = time() . '.' . $request->photo_profil->extension();
            $request->photo_profil->move(public_path('images/employes'), $photoName);
            $validated['photo_profil'] = 'images/employes/' . $photoName;
        }

        $validated['matricule'] = $this->generateMatricule($request->nom, $request->date_embauche);

        Employe::create($validated);

        return redirect()->route('employes.index')->with('success', 'Employé créé avec succès.');
    }

    public function show(string $id)
    {
        $employe = Employe::with(['departement', 'poste', 'typeContrat'])->findOrFail($id);
        return view('superadmin.employe.show', compact('employe'));
    }

    public function edit(string $id)
    {
        $employe      = Employe::findOrFail($id);
        $departements = Departement::all();
        $postes       = Poste::all();
        $typeContrats = TypeContrat::all();
        return view('superadmin.employe.edit', compact('employe', 'departements', 'postes', 'typeContrats'));
    }

    public function update(Request $request, string $id)
    {
        $employe = Employe::findOrFail($id);

        $validated = $request->validate([
            'nom'              => 'required|string|max:255',
            'prenom'           => 'required|string|max:255',
            'civilite'         => 'nullable|string|max:10',
            'nationalite'      => 'nullable|string|max:100',
            'situation_matrimoniale' => 'nullable|string|max:50',
            'nombre_enfants'   => 'nullable|integer|min:0',
            'date_naissance'   => 'nullable|date',
            'telephone'        => 'required|string|max:20',
            'email'            => 'required|email|unique:employes,email,' . $id,
            'adresse'          => 'nullable|string|max:500',
            'photo_profil'     => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'departement_id'   => 'required|exists:departements,id',
            'poste_id'         => 'required|exists:postes,id',
            'type_contrat_id'  => 'required|exists:type_contrats,id',
            'date_embauche'    => 'required|date',
        ], [
            'nom.required'           => 'Le nom est obligatoire.',
            'prenom.required'        => 'Le prénom est obligatoire.',
            'telephone.required'     => 'Le téléphone est obligatoire.',
            'email.required'         => "L'email est obligatoire.",
            'email.unique'           => "L'email est déjà utilisé pour un autre employé.",
            'departement_id.required'=> 'Le département est obligatoire.',
            'poste_id.required'      => 'Le poste est obligatoire.',
            'type_contrat_id.required' => 'Le type de contrat est obligatoire.',
            'date_embauche.required' => "La date d'embauche est obligatoire.",
        ]);

        if ($request->hasFile('photo_profil')) {
            $photoName = time() . '.' . $request->photo_profil->extension();
            $request->photo_profil->move(public_path('images/employes'), $photoName);
            $validated['photo_profil'] = 'images/employes/' . $photoName;
        }

        $employe->update($validated);

        return redirect()->route('employes.index')->with('success', "Modification de l'employé réussie.");
    }

    public function destroy(string $id)
    {
        $employe = Employe::findOrFail($id);
        $employe->delete();
        return redirect()->route('employes.index')->with('success', 'Employé supprimé avec succès.');
    }

    private function generateMatricule(string $nom, string $date_embauche): string
    {
        $prefix  = strtoupper(substr($nom, 0, 3));
        $date    = \Carbon\Carbon::parse($date_embauche)->format('dmy');
        $count   = Employe::whereDate('created_at', now()->toDateString())->count() + 1;
        $counter = str_pad($count, 2, '0', STR_PAD_LEFT);
        return $prefix . $date . '-' . $counter;
    }
}
