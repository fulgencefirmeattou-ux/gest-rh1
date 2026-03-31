<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function showCreateUserForm()
    {
        return view('users.create-user');
    }



    public function createUser(Request $request)
    {
        $validator= Validator::make($request->all(),[
            // 'nom' => 'required',
            // 'prenom' => 'required',
            'login' => 'required|unique:users',
            'email' => 'nullable|unique:users',
            'role' => 'required',
            // 'photo' => 'nullable|image|mimes:JPG,jpg,jpeg,png|max:5120',
            'password' => 'required|min:8',
        ],[
        'login.required' => 'Le nom utilisateur est obligatoire.',
        'login.unique' => 'Ce nom d\'utililisateur existe dejà',
        'email.unique' => 'Cette adresse email est déjà utilisée.',
        'role.required' => 'Le rôle de l\'utilisateur est obligatoire.',
        'password.required' => 'Le mot de passe est obligatoire.',
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
    ]);

        if($validator->fails()){
            return redirect()->back()
                  ->withErrors($validator)
                  ->withInput();
        }

        // $photoPath = null;
        // $photoPath = null; // On initialise par défaut

        // if ($request->hasFile('photo')) {
        //     $photoName = time() . '.' . $request->photo->extension();
        //     $request->photo->move(public_path('images/users'), $photoName);
        //     $photoPath = 'images/users/' . $photoName;
        // }

        User::create([
            // 'nom' => $request->nom,
            // 'prenom' => $request->prenom,
            'login' => $request->login,
            'email' => $request->email,
            'role' => $request->role,
            // 'photo' => $photoPath, // peut être null si pas d'image
            'password' => Hash::make($request->password),
            'date_creation' => now(),
           
        ]);

        // $search = $request->input('search');
        // $users = User::when($search, function($query, $search) {
        //     return $query->where('name', 'like', "%{$search}%")
        //                 ->orWhere('email', 'like', "%{$search}%")
        //                 ->orWhere('number', 'like', "%{$search}%");
        // })->paginate(10);


        return redirect('/admin/users/create')->with('success', 'Utilisateur créé avec succès.');
    }

    public function liste()
    {
        // Récupère tous les utilisateurs
        $users = User::all();

        // Envoie les données à la vue
        return view('users.liste', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.voir', compact('user'));
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validation
        $validated = $request->validate([
            // 'nom' => 'required|string|max:255',
            // 'prenom' => 'required|string|max:255',
            // 'phone' => 'required|string|min:10',
            'login' => 'required|string|max:255|unique:users,login,' . $id,
            'email' => 'nullable|email|unique:users,email,' . $id,
            'role' => 'nullable|string',
            'password' => 'nullable|min:8',
            // 'photo' => 'nullable|image|mimes:JPG,jpg,jpeg,png|max:5120',
        ],[
        // 'photo.image' => 'Le fichier doit être une image.',
        // 'photo.mimes' => 'Seuls les fichiers JPG ou PNG sont autorisés.',
        // 'photo.max' => 'La taille maximale autorisée est de 5 Mo.',
        // 'nom.required' => 'Modification du nom requise.',
        // 'prenom.required' => 'Modification du prenom requise.',
        'login.required' => 'Modification du login requise.',
        'email.unique' => 'Email doit être unique.',
        'role.required' => 'Modification du role requise.',
        ]);

        // Gestion du profil

        if (empty($validated['role'])) {
            $validated['role'] = $user->role;
        }


        // Gestion de la photo
        // if ($request->hasFile('photo')) {
        //     $photoName = time() . '.' . $request->photo->extension();
        //     $request->photo->move(public_path('images/users'), $photoName);
        //     $validated['photo'] = 'images/users/' . $photoName;
        // }

        // Gestion du mot de passe
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Mise à jour en base
        $user->update($validated);

        return redirect()->route('users.voir', $id)
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.liste')->with('success', 'Utilisateur supprimé avec succès.');
    }

    // private function generateMatricule($nom, $dateEmbauche)
    // {
    //     $prefix = strtoupper(substr($nom, 0, 3)); // KOU
    //     $date = \Carbon\Carbon::parse($dateEmbauche)->format('dmy'); // 010124
    //     $baseMatricule = $prefix . $date; // KOU010124

    //     $matricule = $baseMatricule;
    //     $counter = 1;

    //     while (User::where('matricule', $matricule)->exists()) {
    //         $matricule = $baseMatricule . '-' . $counter;
    //         $counter++;
    //     }

    //     return $matricule;
    // }

}
