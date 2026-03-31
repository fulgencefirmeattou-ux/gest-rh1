<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Departement;
use App\Models\Employe;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Str;
use App\Notifications\EmployeCredentials;


class EmployeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departements = Departement::all();
        $users = User::all();

        return view('employes.create-employe', compact('departements', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'user_id'       => 'required|exists:users,id',
    //         'nom' => 'required',
    //         'prenom' => 'required',
    //         'email' => 'required|email|unique:users',
    //         'phone' => 'required',
    //         'photo' => 'nullable|image|mimes:JPG,jpg,jpeg,png|max:5120',
    //         'departement_id'=> 'required|exists:departements,id',
    //         'type_contrat' => 'required|string',
    //         'service_id'    => 'required|exists:services,id',
    //         'poste'         => 'required|string|max:255',
    //         'date_embauche' => 'required|date',
    //         'salaire'       => 'required|numeric|min:0',
    //         'duree'         => 'nullable|integer',
    //         'date_fin'      => 'nullable|date',
    //     ],[
    //         'user_id.required' => 'Selectionnez un utilisateur avant de debuter.',
    //         'departement_id.required' => 'Le departement est obligatoire.',
    //         'service_id.required' =>'selectionnez le service',
    //         'poste.required' => 'renseignez le poste',
    //         'date_embauche' => 'renseignez la date d\'embauche',
    //         'salaire' => 'renseignez le salaire',
    //         'type_contrat'=> 'selectionnez le contrat qui vous lie',
    //         'photo.image' => 'Le fichier doit être une image.',
    //         'photo.mimes' => 'Seuls les fichiers JPG ou PNG sont autorisés.',
    //         'photo.max' => 'La taille maximale autorisée est de 5 Mo.',
    //     ]);

    //     if($validator->fails()){
    //         return redirect()->back()
    //               ->withErrors($validator)
    //               ->withInput();
    //     }

    //     // $photoPath = null;
    //     $photoPath = null; // On initialise par défaut

    //     if ($request->hasFile('photo')) {
    //         $photoName = time() . '.' . $request->photo->extension();
    //         $request->photo->move(public_path('images/users'), $photoName);
    //         $photoPath = 'images/users/' . $photoName;
    //     }

    //     // Exemple : dans ton contrôleur avant de créer un nouvel employé
    //     // if (Employe::where('user_id', $request->user_id)->exists()) {
    //     //     return back()->with('error', 'Cet utilisateur est déjà employé.');
    //     // }


    //     Employe::create([
    //         'user_id'       => $request->user_id,
    //         'matricule' => $request->matricule,
    //         'nom' => $request->nom,
    //         'prenom' => $request->prenom,
    //         'email' => $request->email,
    //         'phone' => $request->phone,
    //         'photo' => $photoPath, 
    //         'service_id'    => $request->service_id,
    //         'type_contrat' => $request->type_contrat,
    //         'poste'         => $request->poste,
    //         'date_embauche' => $request->date_embauche,
    //         'salaire'       => $request->salaire,
    //         'duree'         => $request->duree,
    //         'date_fin'      => $request->date_fin,
    //     ]);


    //     return redirect('/admin/create-employe')->with('success', 'Employe créé avec succès.');
    // }


    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'user_id'       => 'required|exists:users,id',
            'matricule' => 'nullable|unique:employes,matricule',
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|unique:employes,email',
            'phone' => 'required',
            'photo' => 'nullable|image|mimes:JPG,jpg,jpeg,png|max:5120',
            'departement_id'=> 'required|exists:departements,id',
            // 'type_contrat' => 'required|string',
            'service_id' => 'required|exists:services,id',
            'poste' => 'required|string|max:255',
            'date_embauche' => 'required|date',
            // 'salaire' => 'required|numeric|min:0',
            // 'duree' => 'nullable|integer',
            // 'date_fin' => 'nullable|date',
        ],[
            'departement_id.required' => 'Le departement est obligatoire.',
            'service_id.required' =>'selectionnez le service',
            'poste.required' => 'renseignez le poste',
            'date_embauche' => 'renseignez la date d\'embauche',
            // 'salaire' => 'renseignez le salaire',
            // 'type_contrat'=> 'selectionnez le contrat qui vous lie',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.mimes' => 'Seuls les fichiers JPG ou PNG sont autorisés.',
            'photo.max' => 'La taille maximale autorisée est de 5 Mo.',
            'email.unique' => "L'email est déjà utilisé pour un autre employé.",
            'matricule.unique' => "Le matricule est déjà utilisé pour un autre employé.",
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'phone.required' => 'Le téléphone est obligatoire.',
            // 'user_id.required' => 'Selectionnez un utilisateur avant de debuter.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // PHOTO
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoName = time() . '.' . $request->photo->extension();
            $request->photo->move(public_path('images/users'), $photoName);
            $photoPath = 'images/users/' . $photoName;
        }

        // 🔥 GENERATION AUTOMATIQUE DU MATRICULE
        $matricule = $this->generateMatricule($request->nom, $request->date_embauche);

        // 🔥 CREATION DE L'EMPLOYE
        $employe = Employe::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'phone' => $request->phone,
            'photo' => $photoPath, 
            'service_id' => $request->service_id,
            // 'type_contrat' => $request->type_contrat,
            'poste' => $request->poste,
            'date_embauche' => $request->date_embauche,
            // 'salaire' => $request->salaire,
            // 'duree' => $request->duree,
            // 'date_fin' => $request->date_fin,
            'matricule' => $matricule,
        ]);

        // 🔥 GENERATION MOT DE PASSE TEMPORAIRE
        $passwordTemp = Str::random(10);

        // 🔥 CREATION AUTOMATIQUE DU USER
       $user = User::create([
            'employe_id' => $employe->id,
            'login' => $matricule,
            'email' =>  $request->email,
            'password' => bcrypt($passwordTemp),
            'role' => 'employe',
            'must_change_password' => true,
        ]);

        $user->employe()->associate($employe);
        $user->save();

        // 🔥 ENVOI DU MAIL DE CONNEXION
        $user->notify(new EmployeCredentials($matricule, $passwordTemp));

        return redirect()
            ->back()
            ->with('success', 'Employé et compte utilisateur créés avec succès. Identifiants envoyés.');
    }

    private function generateMatricule($nom, $date_embauche)
    {
        $prefix = strtoupper(substr($nom, 0, 3)); // ex: KOU
        $date = \Carbon\Carbon::parse($date_embauche)->format('dmy'); // ex: 05042025
        $count = \App\Models\Employe::whereDate('created_at', now()->toDateString())->count() + 1; // compteur journalier
        $counter = str_pad($count, 2, '0', STR_PAD_LEFT); // ex: 01, 02
        return $prefix . $date . '-' . $counter; // KOU05042025-01
    }


    


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $employes = Employe::with(['service.departement'])->get();
        return view('employes.liste', compact('employes'));
    }


    public function getServicesByDepartement($id)
    {
        $services = Service::where('departement_id', $id)->get(['id', 'nom']);
        return response()->json($services);
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
        $employes = Employe::findOrFail($id);
        $departements = Departement::all();
        $users = User::all();
        $services = collect();
        if ($employes->departement_id) {
            $services = Service::where('departement_id', $employes->departement_id)->get();
        }
        return view('employes.voir', compact('employes','departements', 'users', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       $employes = Employe::findOrFail($id);
       $validated = $request->validate([
            // 'user_id'       => 'required|exists:users,id',
            'departement_id'=> 'nullable|exists:departements,id',
            // 'type_contrat' => 'required|string',
            'service_id'    => 'nullable|exists:services,id',
            'poste'         => 'required|string|max:255',
            'date_embauche' => 'required|date',
            // 'salaire'       => 'required|numeric|min:0',
            // 'duree'         => 'nullable|integer',
            // 'date_fin'      => 'nullable|date',
        ],[
            // 'user_id.required' => 'Selectionnez un utilisateur avant de debuter.',
            'departement_id.required' => 'Le departement est obligatoire.',
            'service_id.required' =>'selectionnez le service',
            'poste.required' => 'renseignez le poste',
            'date_embauche' => 'renseignez la date d\'embauche',
            // 'salaire' => 'renseignez le salaire',
            // 'type_contrat'=> 'selectionnez le contrat qui vous lie'
        ]);
      
        $employes->update($validated);

        return redirect()->route('employe.liste',$id)->with('success','Modification de l\'employe reussi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
