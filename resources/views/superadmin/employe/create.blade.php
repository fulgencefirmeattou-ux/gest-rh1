@extends('layouts.app')

@section('content')

<section>
    <div class="container mt-5">
        <h2 class="mb-4 text-start">Enregistrer un employé</h2>
        <a href="{{ route('employes.index') }}" class="btn btn-secondary mb-3">Retour à la liste</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('employes.store') }}" method="POST" class="bg-white p-4 rounded shadow-sm" enctype="multipart/form-data">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom') }}" required>
                    @error('nom')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-6">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" name="prenom" id="prenom" class="form-control" value="{{ old('prenom') }}" required>
                    @error('prenom')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="civilite" class="form-label">Civilité</label>
                    <select name="civilite" id="civilite" class="form-select">
                        <option value="">—</option>
                        @foreach(['M.', 'Mme', 'Mlle'] as $c)
                            <option value="{{ $c }}" {{ old('civilite') == $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="nationalite" class="form-label">Nationalité</label>
                    <input type="text" name="nationalite" id="nationalite" class="form-control" value="{{ old('nationalite') }}">
                </div>
                <div class="col-md-4">
                    <label for="situation_matrimoniale" class="form-label">Situation matrimoniale</label>
                    <select name="situation_matrimoniale" id="situation_matrimoniale" class="form-select">
                        <option value="">—</option>
                        @foreach(['Célibataire', 'Marié(e)', 'Divorcé(e)', 'Veuf/Veuve'] as $s)
                            <option value="{{ $s }}" {{ old('situation_matrimoniale') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nombre_enfants" class="form-label">Nombre d'enfants</label>
                    <input type="number" name="nombre_enfants" id="nombre_enfants" class="form-control" value="{{ old('nombre_enfants', 0) }}" min="0">
                </div>
                <div class="col-md-4">
                    <label for="date_naissance" class="form-label">Date de naissance</label>
                    <input type="date" name="date_naissance" id="date_naissance" class="form-control" value="{{ old('date_naissance') }}">
                </div>
                <div class="col-md-4">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input type="tel" name="telephone" id="telephone" class="form-control" value="{{ old('telephone') }}" required>
                    @error('telephone')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-6">
                    <label for="adresse" class="form-label">Adresse</label>
                    <input type="text" name="adresse" id="adresse" class="form-control" value="{{ old('adresse') }}">
                </div>
            </div>

            <div class="mb-3">
                <label for="photo_profil" class="form-label">Photo de profil</label>
                <input type="file" name="photo_profil" id="photo_profil" class="form-control" accept="image/*"
                    onchange="if(this.files[0].size > 5242880){ alert('Fichier trop volumineux ! Max 5 Mo'); this.value=''; }">
                @error('photo_profil')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="departement_id" class="form-label">Département</label>
                    <select name="departement_id" id="departement_id" class="form-select" required>
                        <option value="">— Choisir —</option>
                        @foreach($departements as $departement)
                            <option value="{{ $departement->id }}" {{ old('departement_id') == $departement->id ? 'selected' : '' }}>
                                {{ $departement->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('departement_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-4">
                    <label for="poste_id" class="form-label">Poste</label>
                    <select name="poste_id" id="poste_id" class="form-select" required>
                        <option value="">— Choisir —</option>
                        @foreach($postes as $poste)
                            <option value="{{ $poste->id }}" {{ old('poste_id') == $poste->id ? 'selected' : '' }}>
                                {{ $poste->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('poste_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-4">
                    <label for="type_contrat_id" class="form-label">Type de contrat</label>
                    <select name="type_contrat_id" id="type_contrat_id" class="form-select" required>
                        <option value="">— Choisir —</option>
                        @foreach($typeContrats as $tc)
                            <option value="{{ $tc->id }}" {{ old('type_contrat_id') == $tc->id ? 'selected' : '' }}>
                                {{ $tc->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('type_contrat_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="date_embauche" class="form-label">Date d'embauche</label>
                <input type="date" name="date_embauche" id="date_embauche" class="form-control" value="{{ old('date_embauche') }}" required>
                @error('date_embauche')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <button type="submit" class="btn btn-primary">Créer l'employé</button>
        </form>
    </div>
</section>

@endsection
