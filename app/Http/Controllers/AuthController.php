<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ContactAdminMail;


class AuthController extends Controller
{
    // Afficher la page de connexion
    public function showLoginForm()
    {
        return view('authentification.login');
    }

    // Traitement de la connexion
    public function login(Request $request)
        {
            $request->validate([
                'login' => 'required',
                'password' => 'required'
            ]);

            $loginInput = $request->login;

            // Rechercher par login ou email
            $user = User::where('login', $loginInput)
                        ->orWhere('email', $loginInput)
                        ->first();

            if (!$user) {
                return back()->withErrors(['login' => 'Identifiants incorrects']);
            }

            // Déterminer le bon champ
            $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'login';

            $credentials = [$field => $loginInput, 'password' => $request->password];

            // Tentative de connexion
            if (Auth::attempt($credentials)) {

                // ➜ ENREGISTRER LA DERNIÈRE CONNEXION
                $user->date_connexion = now();
                $user->save();

                // ➜ Si l’utilisateur doit changer son mdp
                if ($user->must_change_password) {
                    return redirect()->route('password.force');
                }

                return redirect()->intended(route('admin.dashboard'));
            }

            return back()->withErrors(['login' => 'Identifiants incorrects']);
        }



        // $message = [
        // 'email.required' => 'Veuillez entrer votre adresse e-mail.',
        // 'email.email' => 'L’adresse e-mail n’est pas valide.',
        // 'password.required' => 'Veuillez entrer votre mot de passe.',
        // ];

        // $credentials = $request->validate([
        //     'email' => 'required|email|',
        //     'password' => 'required',
        // ], $message);

        

        // if (Auth::attempt($credentials)) {
        //     $user = Auth::user();
        //     $user->update(['date_connexion' => now()]);
        //     return redirect()->intended('/admin/dashboard');
        // }

        // return back()->withErrors(['email' => 'Identifiants incorrects.']);


   



    public function showForcePassword() { return view('authentification.force-password'); }

    public function updateForcePassword(Request $request) {
        $request->validate(['password'=>'required|min:8|confirmed']);
        $user = auth()->user();
        $user->password = bcrypt($request->password);
        $user->must_change_password = false;
        $user->save();
        return redirect()->route('admin.dashboard')->with('success','Mot de passe mis à jour.');

    }









    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'vous avez été deconnecté.');
    }

    // =============================
    // SECTION ADMIN — CRÉER COMPTE
    // =============================


    public function askInscription()
    {
        return view('authentification.contact_admin');
    }

    public function send(Request $request)
        {
            $request->validate([
                'nom' => 'required|string',
                'email' => 'required|email',
                'message' => 'required|string',
            ]);

            Mail::to('yabo.firm@gmail.com')->send(new ContactAdminMail($request->all()));

            return back()->with('success', 'Votre demande a été envoyée à l’administrateur.');
        }

   


    // =============================
    // MOT DE PASSE OUBLIÉ
    // =============================
    public function showForgotForm()
    {
        return view('authentification.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $message = [
        'email.required' => 'Veuillez entrer votre adresse e-mail.',
        'email.email' => 'L’adresse e-mail n’est pas valide.',
        ];

        $request->validate(['email' => 'required|email'], $message);
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Lien de réinitialisation envoyé.')
            : back()->withErrors(['email' => 'Erreur lors de l’envoi du lien.']);
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('authentification.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        // Validation des champs
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Réinitialisation du mot de passe
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        // Vérification du statut
       if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with('success', 'Votre mot de passe a été réinitialisé avec succès ! Vous pouvez maintenant vous connecter.');
        } else {
            $messages = [
                Password::INVALID_USER => 'Aucun compte ne correspond à cette adresse e-mail.',
                Password::INVALID_TOKEN => 'Le lien de réinitialisation est invalide ou expiré.',
                Password::RESET_THROTTLED => 'Veuillez patienter avant de réessayer.',
            ];

            return back()->withErrors([
                'email' => $messages[$status] ?? 'Erreur inconnue, veuillez réessayer.'
            ]);
        }

    }
}

