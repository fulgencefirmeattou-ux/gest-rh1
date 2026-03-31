@extends('layouts.base')

@section('title', 'Créer un contrat')

@section('content')
<div class="container mt-4">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Création d’un contrat</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('contrats.store') }}" method="POST">
                @csrf

                {{-- Employé --}}
                <div class="mb-3">
                    <label class="form-label">Employé</label>
                    <select name="employe_id" class="form-select" required>
                        <option value="">-- Sélectionner un employé --</option>
                        @foreach ($employes as $employe)
                            <option value="{{ $employe->id }}">
                                {{ $employe->nom }} {{ $employe->prenom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Type de contrat --}}
                <div class="mb-3">
                    <label class="form-label">Type de contrat</label>
                    <select name="type_contrat" class="form-select" id="type_contrat" required>
                        <option value="">-- Choisir --</option>
                        <option value="CDI">CDI</option>
                        <option value="CDD">CDD</option>
                        <option value="Stage">Stage</option>
                        <option value="Consultant">Consultant</option>
                    </select>
                </div>

                {{-- Dates --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date de début</label>
                        <input type="date" name="date_debut" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date de fin</label>
                        <input type="date" name="date_fin" class="form-control">
                        <small class="text-muted">Laisser vide pour un CDI</small>
                    </div>
                </div>

                {{-- <div class="form-group mb-3" id="duree_field" style="display: none;">
                    <label for="duree">Durée (en mois)</label>
                    <input type="number" name="duree" id="duree" class="form-control"
                        value="{{ old('duree', $employe->duree ?? '') }}" min="1">
                </div> 

                <div class="form-group mb-3" id="date_fin_field" style="display: none;">
                    <label for="date_fin">Date de fin</label>
                    <input type="date" name="date_fin" id="date_fin" class="form-control"
                        value="{{ old('date_fin', $employe->date_fin ?? '') }}" readonly>
                </div> --}}

                {{-- Salaire --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Salaire de base (FCFA)</label>
                        <input type="number" name="salaire_base" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mode de calcul</label>
                        <select name="mode_calcul" class="form-select" required>
                            <option value="mensuel">Mensuel</option>
                            <option value="horaire">Horaire</option>
                        </select>
                    </div>
                </div>

                {{-- Heures --}}
                <div class="mb-3">
                    <label class="form-label">Heures de travail / semaine</label>
                    <input type="number" name="heures_par_semaine" class="form-control" value="40">
                </div>

                {{-- Boutons --}}
                <div class="d-flex justify-content-end">
                    <a href="{{ route('contrats.index') }}" class="btn btn-secondary me-2">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Enregistrer le contrat
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

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
