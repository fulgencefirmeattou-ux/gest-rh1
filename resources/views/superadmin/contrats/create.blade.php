@extends('layouts.app')

@section('content')

<div class="container mt-3">

    <h2 class="mb-4 text-start">Créer un contrat</h2>
    <a href="{{ route('contrats.index') }}" class="btn btn-secondary mb-3">Retour à la liste</a>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('contrats.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Employé <span class="text-danger">*</span></label>
                        <select name="employe_id" id="employe_id" class="form-select @error('employe_id') is-invalid @enderror" required>
                            <option value="">-- Sélectionner un employé --</option>
                            @foreach($employes as $employe)
                                <option value="{{ $employe->id }}" {{ old('employe_id') == $employe->id ? 'selected' : '' }}>
                                    {{ $employe->nom }} {{ $employe->prenom }}
                                </option>
                            @endforeach
                        </select>
                        @error('employe_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Type de contrat <span class="text-danger">*</span></label>
                        <select name="type_contrat" id="type_contrat" class="form-select @error('type_contrat') is-invalid @enderror" required>
                            <option value="">-- Choisir --</option>
                            @foreach(['CDI', 'CDD', 'Stage', 'Consultant'] as $type)
                                <option value="{{ $type }}" {{ old('type_contrat') === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('type_contrat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Date de début <span class="text-danger">*</span></label>
                        <input type="date" name="date_debut" class="form-control @error('date_debut') is-invalid @enderror"
                               value="{{ old('date_debut') }}" required>
                        @error('date_debut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6" id="date_fin_field">
                        <label class="form-label fw-semibold">Date de fin</label>
                        <input type="date" name="date_fin" id="date_fin" class="form-control"
                               value="{{ old('date_fin') }}">
                        <small class="text-muted">Laisser vide pour un CDI</small>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Salaire de base (FCFA) <span class="text-danger">*</span></label>
                        <input type="number" name="salaire_base" id="salaire_base"
                               class="form-control @error('salaire_base') is-invalid @enderror"
                               value="{{ old('salaire_base') }}" min="0" required>
                        @error('salaire_base')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Mode de calcul</label>
                        <select name="mode_calcul" class="form-select">
                            <option value="mensuel" {{ old('mode_calcul') === 'mensuel' ? 'selected' : '' }}>Mensuel</option>
                            <option value="horaire" {{ old('mode_calcul') === 'horaire' ? 'selected' : '' }}>Horaire</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label fw-semibold">Heures de travail / semaine</label>
                    <input type="number" name="heures_par_semaine" class="form-control"
                           value="{{ old('heures_par_semaine', 40) }}" min="1" max="168">
                </div>

                {{-- Primes --}}
                <div class="mt-4 mb-2 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Primes</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-prime">+ Ajouter une prime</button>
                </div>

                <div id="primes-container"></div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('contrats.index') }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer & Générer PDF</button>
                </div>

            </form>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ---- Données employés (injectées côté serveur) ----
    const employesData = @json($employes->keyBy('id')->map(fn($e) => [
        'salaire'       => $e->salaire,
        'type_contrat'  => optional($e->typeContrat)->name,
    ]));

    const employeSelect  = document.getElementById('employe_id');
    const typeSelect     = document.getElementById('type_contrat');
    const salaireInput   = document.getElementById('salaire_base');
    const dateFinInput   = document.getElementById('date_fin');

    const GRAYED = { backgroundColor: '#e9ecef', pointerEvents: 'none', cursor: 'not-allowed' };

    function applyGray(el) {
        Object.assign(el.style, GRAYED);
    }

    function removeGray(el) {
        el.style.backgroundColor = '';
        el.style.pointerEvents   = '';
        el.style.cursor          = '';
    }

    // ---- Pré-remplissage au changement d'employé ----
    employeSelect.addEventListener('change', function () {
        const data = employesData[this.value];

        if (data) {
            // Salaire
            salaireInput.value    = data.salaire ?? '';
            salaireInput.readOnly = true;
            applyGray(salaireInput);

            // Type de contrat
            const match = data.type_contrat
                ? [...typeSelect.options].find(o => o.value.toLowerCase() === data.type_contrat.toLowerCase())
                : null;

            if (match) {
                typeSelect.value = match.value;
                applyGray(typeSelect);
            } else {
                typeSelect.value = '';
                removeGray(typeSelect);
            }
        } else {
            // Réinitialiser si aucun employé sélectionné
            salaireInput.value    = '';
            salaireInput.readOnly = false;
            removeGray(salaireInput);

            typeSelect.value = '';
            removeGray(typeSelect);
        }
    });

    // ---- Primes ----
    const container = document.getElementById('primes-container');
    const btnAdd    = document.getElementById('btn-add-prime');
    let index       = 0;

    btnAdd.addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'row mb-2 prime-row';
        row.innerHTML = `
            <div class="col-md-6">
                <input type="text" name="primes[${index}][libelle]" class="form-control" placeholder="Libellé">
            </div>
            <div class="col-md-4">
                <input type="number" name="primes[${index}][montant]" class="form-control" placeholder="Montant (FCFA)" min="0">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-prime">Supprimer</button>
            </div>`;
        container.appendChild(row);
        index++;
    });

    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-remove-prime')) {
            e.target.closest('.prime-row').remove();
        }
    });

    // ---- Masquer date_fin si CDI ----
    typeSelect.addEventListener('change', function () {
        if (this.value === 'CDI') dateFinInput.value = '';
    });
});
</script>

@endsection
