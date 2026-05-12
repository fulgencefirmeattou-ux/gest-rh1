@extends('layouts.app')

@section('content')

<div class="container mt-3">

    <h2 class="mb-4 text-start">Modifier le contrat</h2>
    <a href="{{ route('contrats.show', $contrat->id) }}" class="btn btn-secondary mb-3">Retour</a>

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
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Contrat de {{ $contrat->employe->nom }} {{ $contrat->employe->prenom }}</h5>
            <span class="badge bg-{{ $contrat->statut === 'actif' ? 'success' : 'secondary' }}">
                {{ strtoupper($contrat->statut) }}
            </span>
        </div>

        <div class="card-body">
            <form action="{{ route('contrats.update', $contrat->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Employé (lecture seule) --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Employé</label>
                    <input type="text" class="form-control bg-light"
                           value="{{ $contrat->employe->nom }} {{ $contrat->employe->prenom }}" disabled>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Type de contrat <span class="text-danger">*</span></label>
                        <select name="type_contrat" id="type_contrat" class="form-select @error('type_contrat') is-invalid @enderror">
                            <option value="">-- Choisir --</option>
                            @foreach(['CDI', 'CDD', 'Stage', 'Consultant'] as $type)
                                <option value="{{ $type }}" {{ old('type_contrat', $contrat->type_contrat) === $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Statut</label>
                        <select name="statut" class="form-select">
                            @foreach(['actif', 'inactif', 'annule'] as $s)
                                <option value="{{ $s }}" {{ old('statut', $contrat->statut) === $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Date de début <span class="text-danger">*</span></label>
                        <input type="date" name="date_debut" class="form-control @error('date_debut') is-invalid @enderror"
                               value="{{ old('date_debut', \Carbon\Carbon::parse($contrat->date_debut)->format('Y-m-d')) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Date de fin</label>
                        <input type="date" name="date_fin" id="date_fin" class="form-control"
                               value="{{ old('date_fin', $contrat->date_fin ? \Carbon\Carbon::parse($contrat->date_fin)->format('Y-m-d') : '') }}">
                        <small class="text-muted">Laisser vide pour un CDI</small>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Salaire de base (FCFA) <span class="text-danger">*</span></label>
                        <input type="number" name="salaire_base" class="form-control @error('salaire_base') is-invalid @enderror"
                               value="{{ old('salaire_base', $contrat->salaire_base) }}" min="0" >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Mode de calcul</label>
                        <select name="mode_calcul" class="form-select">
                            <option value="mensuel" {{ old('mode_calcul', $contrat->mode_calcul) === 'mensuel' ? 'selected' : '' }}>Mensuel</option>
                            <option value="horaire" {{ old('mode_calcul', $contrat->mode_calcul) === 'horaire' ? 'selected' : '' }}>Horaire</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label fw-semibold">Heures de travail / semaine</label>
                    <input type="number" name="heures_par_semaine" class="form-control"
                           value="{{ old('heures_par_semaine', $contrat->heures_par_semaine ?? 40) }}" min="1" max="168">
                </div>

                {{-- Primes --}}
                <div class="mt-4 mb-2 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Primes associées</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-prime">+ Ajouter une prime</button>
                </div>

                <div id="primes-container">
                    @foreach($contrat->primes as $i => $prime)
                        <div class="row mb-2 prime-row">
                            <div class="col-md-6">
                                <input type="text" name="primes[{{ $i }}][libelle]"
                                       class="form-control" placeholder="Libellé"
                                       value="{{ old("primes.$i.libelle", $prime->libelle) }}">
                            </div>
                            <div class="col-md-4">
                                <input type="number" name="primes[{{ $i }}][montant]"
                                       class="form-control" placeholder="Montant (FCFA)"
                                       value="{{ old("primes.$i.montant", $prime->montant) }}" min="0">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-prime">Supprimer</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('contrats.show', $contrat->id) }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer &amp; Régénérer PDF</button>
                </div>

            </form>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('primes-container');
    const btnAdd    = document.getElementById('btn-add-prime');
    let index       = {{ $contrat->primes->count() }};

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

    const typeSelect   = document.getElementById('type_contrat');
    const dateFinInput = document.getElementById('date_fin');

    typeSelect.addEventListener('change', function () {
        if (this.value === 'CDI') dateFinInput.value = '';
    });
});
</script>

@endsection
