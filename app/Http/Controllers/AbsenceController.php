<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\User;
use App\Notifications\AbsenceNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AbsenceController extends Controller
{
    private function autoriserRhDg(): void
    {
        if (!in_array(auth()->user()->role, ['dg', 'rh', 'admin'])) {
            abort(403, "Accès non autorisé.");
        }
    }

    private function notifierRhDg(Absence $absence, string $type): void
    {
        User::whereIn('role', ['rh', 'dg', 'admin'])->get()
            ->each(fn($user) => $user->notify(new AbsenceNotification($absence, $type)));
    }

    public function index()
    {
        $employe = auth()->user()->employe;
        if (!$employe) {
            return back()->withErrors(['employe' => "Vous n'êtes pas enregistré comme employé."]);
        }
        $absences = Absence::where('employe_id', $employe->id)->orderBy('date_absence', 'desc')->get();
        return view('superadmin.justificatifs.listeAbsence', compact('absences', 'employe'));
    }

    public function create()
    {
        $employe = auth()->user()->employe;
        return view('superadmin.justificatifs.absence', compact('employe'));
    }

    public function store(Request $request)
    {
        $employe = auth()->user()->employe;
        if (!$employe) {
            return back()->withErrors(['employe' => "Vous n'êtes pas enregistré comme employé."]);
        }

        if ($request->type_absence === 'permission_courte' && $employe->soldePermissionsRestant() <= 0) {
            return back()->withErrors(['type_absence' => "Solde de permissions épuisé ({$employe->permissions_prises}/10 utilisées)."])->withInput();
        }

        $request->validate([
            'type_absence'     => 'required|in:maladie,retard,permission_courte,absence_non_justifiee,rendez_vous,autre',
            'date_absence'     => 'required|date',
            'date_fin_absence' => 'nullable|date|after_or_equal:date_absence',
            'heure_debut'      => 'nullable|date_format:H:i',
            'heure_fin'        => 'nullable|date_format:H:i',
            'motif'            => 'nullable|string|max:1000',
            'justificatif'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('justificatif')) {
            $path = $request->file('justificatif')->store('justificatifs_absence', 'public');
        }

        $absence = Absence::create([
            'employe_id'       => $employe->id,
            'type_absence'     => $request->type_absence,
            'date_absence'     => $request->date_absence,
            'date_fin_absence' => $request->date_fin_absence,
            'heure_debut'      => $request->heure_debut,
            'heure_fin'        => $request->heure_fin,
            'motif'            => $request->motif,
            'justificatif'     => $path,
            'statut'           => 'en_attente',
        ]);

        $this->notifierRhDg($absence, 'nouvelle');

        return redirect()->route('justificatifs.absence.liste')
            ->with('success', 'Absence déclarée. Les RH ont été notifiés.');
    }

    public function rhIndex()
    {
        $this->autoriserRhDg();
        $absences = Absence::with('employe')->where('statut', 'en_attente')->orderBy('date_absence', 'desc')->get();
        return view('superadmin.justificatifs.validation', compact('absences'));
    }

    public function approve(Request $request, $id)
    {
        $this->autoriserRhDg();
        $request->validate(['commentaire_rh' => 'nullable|string|max:500']);

        $absence = Absence::with('employe')->findOrFail($id);
        $absence->update([
            'statut'         => 'validee',
            'commentaire_rh' => $request->commentaire_rh,
            'traite_par'     => auth()->id(),
            'traite_le'      => now(),
        ]);

        if ($absence->type_absence === 'permission_courte' && $absence->employe) {
            $absence->employe->increment('permissions_prises');
        }

        if ($absence->employe?->user) {
            $absence->employe->user->notify(new AbsenceNotification($absence, 'validee'));
        }

        return back()->with('success', 'Absence validée.');
    }

    public function reject(Request $request, $id)
    {
        $this->autoriserRhDg();
        $request->validate(['commentaire_rh' => 'nullable|string|max:500']);

        $absence = Absence::with('employe')->findOrFail($id);
        $absence->update([
            'statut'         => 'rejetee',
            'commentaire_rh' => $request->commentaire_rh,
            'traite_par'     => auth()->id(),
            'traite_le'      => now(),
        ]);

        if ($absence->employe?->user) {
            $absence->employe->user->notify(new AbsenceNotification($absence, 'rejetee'));
        }

        return back()->with('success', 'Absence rejetée.');
    }

    public function ListeDemandeAbsenceTraiter()
    {
        $this->autoriserRhDg();
        $absences = Absence::with('employe')->whereIn('statut', ['validee', 'rejetee'])->orderBy('updated_at', 'desc')->get();
        return view('superadmin.justificatifs.absence-traiter', compact('absences'));
    }

    public function show($id)
    {
        $absence = Absence::with('employe')->findOrFail($id);
        return view('superadmin.justificatifs.absence-details', compact('absence'));
    }

    public function resubmit(Request $request, $id)
    {
        $employe = auth()->user()->employe;
        $absence = Absence::findOrFail($id);

        if ($absence->employe_id !== $employe?->id) abort(403);
        if ($absence->statut !== 'en_attente') {
            return back()->with('error', 'Seules les absences en attente peuvent être modifiées.');
        }

        $request->validate([
            'type_absence' => 'required|in:maladie,retard,permission_courte,absence_non_justifiee,rendez_vous,autre',
            'date_absence' => 'required|date',
            'motif'        => 'nullable|string|max:1000',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $path = $absence->justificatif;
        if ($request->hasFile('justificatif')) {
            if ($path) Storage::disk('public')->delete($path);
            $path = $request->file('justificatif')->store('justificatifs_absence', 'public');
        }

        $absence->update([
            'type_absence' => $request->type_absence,
            'date_absence' => $request->date_absence,
            'motif'        => $request->motif,
            'justificatif' => $path,
        ]);

        return redirect()->route('justificatifs.absence.liste')->with('success', 'Absence mise à jour.');
    }

    public function download(Absence $absence)
    {
        if (!$absence->justificatif || !Storage::disk('public')->exists($absence->justificatif)) {
            abort(404);
        }
        return Storage::disk('public')->download($absence->justificatif);
    }
}
