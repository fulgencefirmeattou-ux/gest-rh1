@extends('layouts.app')

@section('content')

<div class="container mt-3">
    <h2 class="mb-4 text-start">Fiche employé</h2>
    <div class="mb-3">
        <a href="{{ route('employes.index') }}" class="btn btn-secondary">Retour à la liste</a>
        <a href="{{ route('employes.edit', $employe->id) }}" class="btn btn-primary">Modifier</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="bg-white p-4 rounded shadow-sm">

        @if($employe->photo_profil)
            <div class="mb-3 text-center">
                <img src="{{ asset($employe->photo_profil) }}" alt="Photo" class="rounded-circle" style="width:120px;height:120px;object-fit:cover;">
            </div>
        @endif

        <h5 class="text-uppercase mb-3">{{ ucfirst($employe->nom) }} {{ ucfirst($employe->prenom) }}</h5>

        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>Matricule</th>
                    <td>{{ $employe->matricule }}</td>
                </tr>
                <tr>
                    <th>Civilité</th>
                    <td>{{ $employe->civilite ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Nationalité</th>
                    <td>{{ $employe->nationalite ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Situation matrimoniale</th>
                    <td>{{ $employe->situation_matrimoniale ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Nombre d'enfants</th>
                    <td>{{ $employe->nombre_enfants }}</td>
                </tr>
                <tr>
                    <th>Date de naissance</th>
                    <td>{{ $employe->date_naissance ? \Carbon\Carbon::parse($employe->date_naissance)->format('d/m/Y') : '—' }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $employe->email ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Téléphone</th>
                    <td>{{ $employe->telephone ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Adresse</th>
                    <td>{{ $employe->adresse ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Département</th>
                    <td>{{ $employe->departement->nom ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Poste</th>
                    <td>{{ $employe->poste->name ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Type de contrat</th>
                    <td>{{ $employe->typeContrat->name ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Date d'embauche</th>
                    <td>{{ $employe->date_embauche ? \Carbon\Carbon::parse($employe->date_embauche)->format('d/m/Y') : '—' }}</td>

                </tr>
                <tr>
                    <th>Créé le</th>
                    <td>{{ $employe->created_at ? $employe->created_at->format('d/m/Y H:i') : '—' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
