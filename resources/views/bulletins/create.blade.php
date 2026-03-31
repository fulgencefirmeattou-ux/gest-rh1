@extends('layouts.base')
@section('title','Créer un bulletin de paie')
@section('content')
<div class="container">

    <div class="card shadow-sm mt-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Créer un bulletin de paie</h5> 
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('bulletins.generate') }}" method="POST">
                @csrf
                {{-- Employé --}}
                <div class="mb-3">
                    <label class="form-label">Employé</label>
                    <select name="employe_id" class="form-select" id="employe" required>
                        <option value="">-- Sélectionner --</option>
                        @foreach($employes as $emp)
                            @if($emp->contrats->first())
                                <option 
                                    value="{{ $emp->id }}"
                                    data-salaire="{{ $emp->contrats->first()->salaire_base }}">
                                    {{ $emp->nom }} {{ $emp->prenom }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Salaire de base</label>
                    <input type="number" class="form-control" id="salaire_base" readonly>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Mois</label>
                        <input type="month" name="mois" class="form-control" required>
                    </div>
                    {{-- <div class="col-md-6 mb-3">
                        <label class="form-label">Salaire de base</label>
                        <input type="number" step="0.01" name="salaire_base" class="form-control"  required>
                    </div> --}}
                </div>
                {{-- Heures supplémentaires --}}
                <h6 class="mt-2 mb-3">Heures supplémentaires</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Nombre d'heures</label>
                        <input type="number" step="0.01" name="total_heures_sup" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Montant</label>
                        <input type="number" step="0.01" name="montant_heures_sup" class="form-control">
                    </div>
                </div>
                {{-- Primes --}}
                <h6 class="mt-2">Primes</h6>
                <div id="primes-list">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <input type="text" name="primes[0][libelle]" class="form-control" placeholder="Libellé">
                        </div>
                        <div class="col-md-6">
                            <input type="number" step="0.01" name="primes[0][montant]" class="form-control" placeholder="Montant">
                        </div>

                    </div>
                   
                </div>
                <div class="col-md-12 text-end mt-2">
                    <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="addPrime()">+ Ajouter une prime</button>
                </div>
                {{-- Retenues --}}
                <h6 class="mt-2">Retenues</h6>
                <div id="retenues-list">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <input type="text" name="retenues[0][libelle]" class="form-control" placeholder="Libellé">
                        </div>
                        <div class="col-md-6">
                            <input type="number" step="0.01" name="retenues[0][montant]" class="form-control" placeholder="Montant">
                        </div>

                    </div>
                   
                </div>
                <div class="col-md-12 text-end mt-2">
                    <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="addRetenues()">+ Ajouter une retenue</button>
                </div>


                {{-- <h6 class="mt-4">Retenues</h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>CNPS</label>
                        <input type="number" step="0.01" name="cnps" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Impôt / Autres retenues</label>
                        <input type="number" step="0.01" name="autres_retenues" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Côtisations</label>
                        <input type="number" step="0.01" name="cotisations" class="form-control">
                    </div>
                </div> --}}
                <div class="text-end mt-4">
                    <button class="btn btn-primary">Générer le bulletin</button>
                    <a href="{{ route('bulletins.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('employe').addEventListener('change', function(){
        const salaire = this.options[this.selectedIndex].dataset.salaire || 0;
        document.getElementById('salaire_base').value = salaire;
    });
</script>

<script>
    let primeIndex = 1;

    function addPrime() {
        const list = document.getElementById('primes-list');

        list.insertAdjacentHTML('beforeend', `
            <div class="row mb-2">
                <div class="col-md-6">
                    <input type="text"
                        name="primes[${primeIndex}][libelle]"
                        class="form-control"
                        placeholder="Libellé">
                </div>
                <div class="col-md-6">
                    <input type="number" step="0.01"
                        name="primes[${primeIndex}][montant]"
                        class="form-control"
                        placeholder="Montant">
                </div>
            </div>
        `);

        primeIndex++;
    }

    document.getElementById('employe_select').addEventListener('change', function () {

        const option = this.options[this.selectedIndex];

        document.getElementById('salaire_base').value =
            option.dataset.salaire
            ? Number(option.dataset.salaire).toLocaleString('fr-FR') + ' FCFA'
            : '';
    });
</script>

<script>
    let retenueIndex = 1;

    function addRetenues() {
        const list = document.getElementById('retenues-list');

        list.insertAdjacentHTML('beforeend', `
            <div class="row mb-2">
                <div class="col-md-6">
                    <input type="text"
                        name="retenues[${retenueIndex}][libelle]"
                        class="form-control"
                        placeholder="Libellé">
                </div>
                <div class="col-md-6">
                    <input type="number" step="0.01"
                        name="retenues[${retenueIndex}][montant]"
                        class="form-control"
                        placeholder="Montant">
                </div>
            </div>
        `);

        retenueIndex++;
    }

    document.getElementById('employe_select').addEventListener('change', function () {

        const option = this.options[this.selectedIndex];

        document.getElementById('salaire_base').value =
            option.dataset.salaire
            ? Number(option.dataset.salaire).toLocaleString('fr-FR') + ' FCFA'
            : '';
    });
</script>

@endsection

