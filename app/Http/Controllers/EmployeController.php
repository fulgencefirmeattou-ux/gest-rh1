<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departement;
use App\Models\Employe;
use App\Models\Poste;
use App\Models\TypeContrat;
use App\Models\User;

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
            'nom'                    => 'required|string|max:255',
            'prenom'                 => 'required|string|max:255',
            'civilite'               => 'nullable|string|max:10',
            'nationalite'            => 'nullable|string|max:100',
            'situation_matrimoniale' => 'nullable|string|max:50',
            'nombre_enfants'         => 'nullable|integer|min:0',
            'date_naissance'         => 'nullable|date',
            'lieu_naissance'         => 'nullable|string|max:255',
            'telephone'              => 'required|string|max:20',
            'email'                  => 'required|email|unique:employes,email|unique:users,email',
            'password'               => 'required|string|min:8|confirmed',
            'adresse'                => 'nullable|string|max:500',
            'photo_profil'           => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'curriculum_vitae'       => 'nullable|mimes:pdf,doc,docx|max:10240',
            'lettre_motivation'      => 'nullable|mimes:pdf,doc,docx|max:10240',
            'salaire'                => 'required|integer|min:0',
            'departement_id'         => 'required|exists:departements,id',
            'poste_id'               => 'required|exists:postes,id',
            'type_contrat_id'        => 'required|exists:type_contrats,id',
            'date_embauche'          => 'required|date',
        ], [
            'nom.required'              => 'Le nom est obligatoire.',
            'prenom.required'           => 'Le prénom est obligatoire.',
            'telephone.required'        => 'Le téléphone est obligatoire.',
            'email.required'            => "L'email est obligatoire.",
            'email.unique'              => "Cet email est déjà utilisé.",
            'password.required'         => 'Le mot de passe est obligatoire.',
            'password.min'              => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed'        => 'La confirmation du mot de passe ne correspond pas.',
            'salaire.required'          => 'Le salaire est obligatoire.',
            'salaire.integer'           => 'Le salaire doit être un nombre entier.',
            'departement_id.required'   => 'Le département est obligatoire.',
            'poste_id.required'         => 'Le poste est obligatoire.',
            'type_contrat_id.required'  => 'Le type de contrat est obligatoire.',
            'date_embauche.required'    => "La date d'embauche est obligatoire.",
            'photo_profil.image'        => 'Le fichier doit être une image.',
            'photo_profil.mimes'        => 'Seuls les formats JPG et PNG sont autorisés.',
            'photo_profil.max'          => 'La photo ne doit pas dépasser 5 Mo.',
            'curriculum_vitae.mimes'    => 'Le CV doit être en PDF, DOC ou DOCX.',
            'curriculum_vitae.max'      => 'Le CV ne doit pas dépasser 10 Mo.',
            'lettre_motivation.mimes'   => 'La lettre doit être en PDF, DOC ou DOCX.',
            'lettre_motivation.max'     => 'La lettre ne doit pas dépasser 10 Mo.',
        ]);

        if ($request->hasFile('photo_profil')) {
            $photoName = time() . '.' . $request->photo_profil->extension();
            $request->photo_profil->move(public_path('images/employes'), $photoName);
            $validated['photo_profil'] = 'images/employes/' . $photoName;
        }

        if ($request->hasFile('curriculum_vitae')) {
            $cvName = time() . '_cv.' . $request->curriculum_vitae->extension();
            $request->curriculum_vitae->move(public_path('documents/employes'), $cvName);
            $validated['curriculum_vitae'] = 'documents/employes/' . $cvName;
        }

        if ($request->hasFile('lettre_motivation')) {
            $lmName = time() . '_lm.' . $request->lettre_motivation->extension();
            $request->lettre_motivation->move(public_path('documents/employes'), $lmName);
            $validated['lettre_motivation'] = 'documents/employes/' . $lmName;
        }

        $matricule = $this->generateMatricule($request->nom, $request->date_embauche);
        $validated['matricule'] = $matricule;

        // Création automatique du compte utilisateur
        $user = User::create([
            'nom'      => $request->nom,
            'prenom'   => $request->prenom,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'login'    => $matricule,
            'role'     => 'employe',
        ]);

        $validated['user_id'] = $user->id;
        unset($validated['password'], $validated['password_confirmation']);

        $employe = Employe::create($validated);

        // Lier l'employé au compte utilisateur
        $user->update(['employe_id' => $employe->id]);

        return redirect()->route('employes.index')->with('success', "Employé créé avec succès. Identifiant de connexion : {$matricule}");
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
            'nom'                    => 'required|string|max:255',
            'prenom'                 => 'required|string|max:255',
            'civilite'               => 'nullable|string|max:10',
            'nationalite'            => 'nullable|string|max:100',
            'situation_matrimoniale' => 'nullable|string|max:50',
            'nombre_enfants'         => 'nullable|integer|min:0',
            'date_naissance'         => 'nullable|date',
            'lieu_naissance'         => 'nullable|string|max:255',
            'telephone'              => 'required|string|max:20',
            'email'                  => 'required|email|unique:employes,email,' . $id,
            'adresse'                => 'nullable|string|max:500',
            'photo_profil'           => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'curriculum_vitae'       => 'nullable|mimes:pdf,doc,docx|max:10240',
            'lettre_motivation'      => 'nullable|mimes:pdf,doc,docx|max:10240',
            'salaire'                => 'required|integer|min:0',
            'departement_id'         => 'required|exists:departements,id',
            'poste_id'               => 'required|exists:postes,id',
            'type_contrat_id'        => 'required|exists:type_contrats,id',
            'date_embauche'          => 'required|date',
        ], [
            'nom.required'             => 'Le nom est obligatoire.',
            'prenom.required'          => 'Le prénom est obligatoire.',
            'telephone.required'       => 'Le téléphone est obligatoire.',
            'email.required'           => "L'email est obligatoire.",
            'email.unique'             => "L'email est déjà utilisé pour un autre employé.",
            'salaire.required'         => 'Le salaire est obligatoire.',
            'departement_id.required'  => 'Le département est obligatoire.',
            'poste_id.required'        => 'Le poste est obligatoire.',
            'type_contrat_id.required' => 'Le type de contrat est obligatoire.',
            'date_embauche.required'   => "La date d'embauche est obligatoire.",
            'curriculum_vitae.mimes'   => 'Le CV doit être en PDF, DOC ou DOCX.',
            'lettre_motivation.mimes'  => 'La lettre doit être en PDF, DOC ou DOCX.',
        ]);

        if ($request->hasFile('photo_profil')) {
            $photoName = time() . '.' . $request->photo_profil->extension();
            $request->photo_profil->move(public_path('images/employes'), $photoName);
            $validated['photo_profil'] = 'images/employes/' . $photoName;
        }

        if ($request->hasFile('curriculum_vitae')) {
            $cvName = time() . '_cv.' . $request->curriculum_vitae->extension();
            $request->curriculum_vitae->move(public_path('documents/employes'), $cvName);
            $validated['curriculum_vitae'] = 'documents/employes/' . $cvName;
        }

        if ($request->hasFile('lettre_motivation')) {
            $lmName = time() . '_lm.' . $request->lettre_motivation->extension();
            $request->lettre_motivation->move(public_path('documents/employes'), $lmName);
            $validated['lettre_motivation'] = 'documents/employes/' . $lmName;
        }

        $employe->update($validated);

        return redirect()->route('employes.index')->with('success', "Modification de l'employé réussie.");
    }

    public function destroy(string $id)
    {
        $employe = Employe::findOrFail($id);
        $employe->delete();
        return redirect()->route('employes.index')->with('success', 'Employé archivé avec succès.');
    }

    public function trashed()
    {
        $employes = Employe::onlyTrashed()->with(['departement', 'poste'])->get();
        return view('superadmin.employe.trashed', compact('employes'));
    }

    public function restore(string $id)
    {
        $employe = Employe::onlyTrashed()->findOrFail($id);
        $employe->restore();
        return redirect()->route('employes.trashed')->with('success', 'Employé restauré avec succès.');
    }

    public function forceDelete(string $id)
    {
        $employe = Employe::onlyTrashed()->findOrFail($id);
        $employe->forceDelete();
        return redirect()->route('employes.trashed')->with('success', 'Employé supprimé définitivement.');
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
