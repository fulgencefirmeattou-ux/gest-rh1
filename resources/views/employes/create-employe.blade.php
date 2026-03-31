@extends('layouts.base')
@section('title','Créer un employé')
@section('content')

<section class="row">
    <div class="col-12 col-lg-12">
        <div class="container py-5">
            <h2 class="mb-4 text-start">Enregistrer un employé</h2>

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

            <form action="{{ route('employe.create') }}" method="POST" class="bg-white p-4 rounded shadow-sm" enctype="multipart/form-data">
                @csrf

                {{-- <div class="mb-3">
                    <label for="user_id" class="form-label">Utilisateur</label>
                    <select name="user_id" id="user_id" class="form-select" required>
                        <option value="">-- Sélectionner un utilisateur --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ ucfirst($user->nom) }} {{ ucfirst($user->prenom) }} ({{ $user->email ?? $user->phone }})</option>
                        @endforeach
                    </select>
                </div> --}}

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" name="prenom" id="prenom" class="form-control" value="{{ old('prenom') }}" required>
                    </div>
                </div> 
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="emailaddress" class="form-label">Votre Email </label>
                        <input class="form-control @error('email') is-invalid @enderror" name="email" type="email" id="emailaddress"  value="{{ old('email') }}" required=""
                            placeholder="Entrer votre email"> 
                    </div>
                    {{-- @error('email')
                        <div class="text-danger small mb-2">{{ $message }}</div>
                    @enderror --}}
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Téléphone</label>
                        <input type="tel" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="photo">Photo de profil :</label>
                    <input type="file" name="photo" id="photo" class="form-control" accept="image/*"
                    onchange="if(this.files[0].size > 5242880){ alert('Fichier trop volumineux ! Max 5 Mo'); this.value=''; }">
                </div>
                

                <div class="mb-3">
                    <label for="departement_id" class="form-label">Département</label>
                    <select name="departement_id" id="departement_id" class="form-select" required>
                        <option value="">-- Choisir un département --</option>
                        @foreach($departements as $departement)
                            <option value="{{ $departement->id }}">{{ $departement->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="service_id" class="form-label">Service</label>
                    <select name="service_id" id="service_id" class="form-select" disabled>
                        <option value="">-- Sélectionnez d’abord un département --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="poste" class="form-label">Poste</label>
                    <input type="text" name="poste" class="form-control" required>
                </div>
                {{-- <div class="form-group mb-3">
                    <label for="type_contrat">Type de contrat</label>
                    <select name="type_contrat" id="type_contrat" class="form-control" required>
                        <option value="">-- Sélectionnez un type --</option>
                        <option value="CDI" {{ old('type_contrat', $employe->type_contrat ?? '') == 'CDI' ? 'selected' : '' }}>CDI</option>
                        <option value="CDD" {{ old('type_contrat', $employe->type_contrat ?? '') == 'CDD' ? 'selected' : '' }}>CDD</option>
                        <option value="Stage" {{ old('type_contrat', $employe->type_contrat ?? '') == 'Stage' ? 'selected' : '' }}>Stage</option>
                    </select>
                </div> --}}

                <div class="mb-3">
                    <label for="date_embauche" class="form-label">Date d'embauche</label>
                    <input type="date" name="date_embauche" id="date_embauche" class="form-control" required>
                </div>

                {{-- <div class="form-group mb-3" id="duree_field" style="display: none;">
                    <label for="duree">Durée (en mois)</label>
                    <input type="number" name="duree" id="duree" class="form-control"
                        value="{{ old('duree', $employe->duree ?? '') }}" min="1">
                </div> --}}

                {{-- <div class="form-group mb-3" id="date_fin_field" style="display: none;">
                    <label for="date_fin">Date de fin</label>
                    <input type="date" name="date_fin" id="date_fin" class="form-control"
                        value="{{ old('date_fin', $employe->date_fin ?? '') }}" readonly>
                </div>

                <div class="form-group mb-3">
                    <label for="salaire" class="form-label">Salaire</label>
                    <input type="number" name="salaire" class="form-control" required>
                </div> --}}

                <button type="submit" class="btn btn-primary">Créer l'employé</button>
            </form>
        </div>
    </div>
</section>

<script>
    document.getElementById('departement_id').addEventListener('change', function () {
        const departementId = this.value;
        const serviceSelect = document.getElementById('service_id');
        serviceSelect.innerHTML = '<option value="">Chargement...</option>';
        serviceSelect.setAttribute('disabled', true);

        if (departementId) {
            fetch(`/departements/${departementId}/services`)
                .then(response => response.json())
                .then(data => {
                    serviceSelect.removeAttribute('disabled');
                    serviceSelect.innerHTML = '<option value="">-- Sélectionner un service --</option>';
                    data.forEach(service => {
                        serviceSelect.innerHTML += `<option value="${service.id}">${service.nom}</option>`;
                    });
                });
        } else {
            serviceSelect.innerHTML = '<option value="">-- Sélectionner un service --</option>';
        }
    });
</script>
{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeContrat = document.getElementById('type_contrat');
        const dureeField = document.getElementById('duree_field');
        const dateFinField = document.getElementById('date_fin_field');
        const dureeInput = document.getElementById('duree');
        const dateDebut = document.getElementById('date_embauche');
        const dateFin = document.getElementById('date_fin');

        // Afficher/masquer les champs selon le type
        function toggleFields() {
            if (typeContrat.value === 'CDD' || typeContrat.value === 'Stage') {
                dureeField.style.display = 'block';
                dateFinField.style.display = 'block';
            } else {
                dureeField.style.display = 'none';
                dateFinField.style.display = 'none';
                dureeInput.value = '';
                dateFin.value = '';
            }
        }

        // Calcul automatique de la date de fin
        function updateDateFin() {
            if (dateDebut.value && dureeInput.value) {
                let debut = new Date(dateDebut.value);
                debut.setMonth(debut.getMonth() + parseInt(dureeInput.value));
                let fin = debut.toISOString().split('T')[0];
                dateFin.value = fin;
            }
        }

        typeContrat.addEventListener('change', toggleFields);
        dureeInput.addEventListener('input', updateDateFin);
        dateDebut.addEventListener('change', updateDateFin);

        toggleFields(); // exécuter au chargement
    });
</script> --}}


@endsection
