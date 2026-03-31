<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absence;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class AbsenceController extends Controller
{
    public function index(){
        $absences = Absence::where('employe_id', auth()->user()->employe->id)->get();
        return view('justificatifs.listeAbsence', compact('absences'));
    }

    public function create(){
        return view('justificatifs.absence');
    }
    public function store(Request $request)
    {
        $request->validate([
            'type_absence' => 'required',
            'date_absence' => 'required|date',
            'motif' => 'nullable|string',
            'justificatif' => 'nullable|file|max:2048',
        ]);

        $path = null;

        if ($request->hasFile('justificatif')) {
            $file = $request->file('justificatif');

            // stockage dans storage/app/public/justificatifs_absence
            $path = $file->store('justificatifs_absence', 'public');
        }

        Absence::create([
            'employe_id'   => auth()->user()->employe->id,
            'type_absence' => $request->type_absence,
            'date_absence' => $request->date_absence,
            'motif'        => $request->motif,
            'justificatif' => $path, // ✅ chemin correct
            'statut'       => 'en_attente',
        ]);

        return redirect()
            ->route('justificatifs.absence.liste')
            ->with('success', 'Absence déclarée et envoyée au responsable.');
    }


    public function rhIndex()
    {
        $user = auth()->user();
        $role = $user->role;

        if (!in_array($user->role, ['dg', 'rh'])) {
            abort(403, "Vous n'êtes pas autorisé à traiter cette demande.");
        }
        $absences = Absence::where('statut', 'en_attente')->get();

        return view('justificatifs.validation', compact('absences'));
    }


    public function approve($id)
    {
        $absence = Absence::findOrFail($id);

        $user = auth()->user();
        $role = $user->role;

        if (!in_array($user->role, ['dg', 'rh'])) {
            abort(403, "Vous n'êtes pas autorisé à traiter cette demande.");
        }

        $absence->update(['statut' => 'validee']);
        $absence->save();

        return back()->with('success', "Absence approuvée avec succès.");
    }

    public function reject($id)
    {
        $absence = Absence::findOrFail($id);

        $user = auth()->user();
        $role = $user->role;

        if (!in_array($user->role, ['dg', 'rh'])) {
            abort(403, "Vous n'êtes pas autorisé à traiter cette demande.");
        }

        $absence->update(['statut' => 'rejetee']);
        $absence->save();

        return back()->with('success', "Absence rejetée avec succès.");
    }

    public function ListeDemandeAbsenceTraiter(){
        $user = auth()->user();
        $role = $user->role;

       if (!in_array($user->role, ['dg', 'rh'])) {
            abort(403, "Vous n'êtes pas autorisé à accéder à cette section.");
        }

        $absences = Absence::whereIn('statut', ['validee','rejetee'])->get();
        return view('justificatifs.absence-traiter', compact('absences'));
    }

    public function show($id)
    {
        $absence = Absence::findOrFail($id);
        return view('justificatifs.absence-details', compact('absence'));
    }

    // public function resubmit($id)
    // {
    //     $absence = Absence::findOrFail($id);

    //     if ($absence->statut !== 'en_attente') {
    //         return back()->with('error', "Seules les absences en_attente peuvent être renvoyées.");
    //     }

    //     $absence->update(['statut' => 'en_attente']);
    //     $absence->save();

    //     return back()->with('success', "Absence renvoyée pour réévaluation.");
    // }

    public function resubmit(Request $request, $id)
    {
        $absence = Absence::findOrFail($id);

        if ($absence->statut !== 'en_attente') {
            return back()->with('error', "Seules les absences en_attente peuvent être renvoyées.");
        }

        $request->validate([
            'type_absence' => 'required',
            'date_absence' => 'required|date',
            'motif' => 'nullable|string',
            'justificatif' => 'nullable|file|max:2048',
        ]);

        $filename = $absence->justificatif; // garder l'ancien si pas de nouveau fichier

        if ($request->hasFile('justificatif')) {
            $file = $request->file('justificatif');

            $directory = storage_path('app/public/justificatifs_absence');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            $filename = 'absence_'.$absence->id.'_'.time().'.'.$file->getClientOriginalExtension();
            $file->move($directory, $filename);
            $filename = 'justificatifs_absence/' . $filename;
        }

        $absence->update([
            'type_absence' => $request->type_absence,
            'date_absence' => $request->date_absence,
            'motif' => $request->motif,
            'justificatif' => $filename,
            'statut' => 'en_attente',
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
