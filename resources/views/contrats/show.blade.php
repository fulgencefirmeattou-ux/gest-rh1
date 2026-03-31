@extends('layouts.base')
@section('title', 'créer un utilisateur')
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        {{-- <div class="row">
            <div class="col-sm-12">
                <div class="profile-bg-picture"
                    style="background-image:url({{ url('assets/images/bg-profile.jpg') }})">
                    <span class="picture-bg-overlay"></span>
                    <!-- overlay -->
                </div>
                <!-- meta -->
                <div class="profile-user-box">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="profile-user-img"><img src="{{ asset ( $employes->photo )}}" alt=""
                                    class="avatar-lg rounded-circle"></div>
                            <div class="">
                                <h4 class="mt-4 fs-17 ellipsis">{{ ucfirst($employes->nom) }} {{ ucfirst($employes->prenom) ?? '_' }}</h4>
                                <p class="font-13"> {{ $employes->email ?? '—' }}</p>
                                {{-- <p class="text-muted mb-0"><small>California, United States</small></p>
                            </div>
                        </div>
                       
                    </div>
                </div> 
                <!--/ meta -->
            </div>
        </div>  --}}
        <!-- end row -->

        <div class="row mt-4">
            <div class="col-sm-12">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card p-0">
                    <div class="card-body p-0">
                        <div class="profile-content">
                            <ul class="nav nav-underline nav-justified gap-0">
                                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#aboutme" type="button" role="tab"
                                        aria-controls="home" aria-selected="true" href="#aboutme">Detail du contrat</a>
                                </li>
                        
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#edit-profile" type="button" role="tab"
                                        aria-controls="home" aria-selected="true"
                                        href="#edit-profile">Modifier le contrat</a>
                                </li>
                              
                            </ul>

                            <div class="tab-content m-0 p-4">
                                <div class="tab-pane active" id="aboutme" role="tabpanel"
                                    aria-labelledby="home-tab" tabindex="0">
                                    <div class="profile-desk">
                                        <div class="card shadow">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h4>Contrat de {{ $contrat->employe->nom }} {{ $contrat->employe->prenom }}</h4>

                                                <div>
                                                    <a href="{{ asset($contrat->pdf_path) }}" target="_blank" class="btn btn-primary">
                                                        📄 Voir le PDF
                                                    </a>
                                                    {{-- <a href="{{ route('contrats.edit', $contrat->id) }}" class="btn btn-primary">
                                                        ✏ Modifier
                                                    </a> --}}
                                                </div>
                                            </div>

                                            <div class="card-body">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th>Type de contrat</th>
                                                        <td>{{ $contrat->type_contrat }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Date de début</th>
                                                        <td>{{ $contrat->date_debut }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Date de fin</th>
                                                        <td>{{ $contrat->date_fin ?? '—' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Salaire de base</th>
                                                        <td>{{ number_format($contrat->salaire_base, 0, ',', ' ') }} FCFA</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Heures / semaine</th>
                                                        <td>{{ $contrat->heures_par_semaine }} h</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Mode de calcul</th>
                                                        <td>{{ ucfirst($contrat->mode_calcul) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Statut</th>
                                                        <td>
                                                            <span class="badge bg-{{ $contrat->statut == 'actif' ? 'success' : 'secondary' }}">
                                                                {{ strtoupper($contrat->statut) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </table>

                                                {{-- PRIMES --}}
                                                <h5 class="mt-4">Primes associées</h5>
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Libellé</th>
                                                            <th>Montant</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($contrat->primes as $prime)
                                                            <tr>
                                                                <td>{{ $prime->libelle }}</td>
                                                                <td>{{ number_format($prime->montant, 0, ',', ' ') }} FCFA</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="2" class="text-center text-muted">
                                                                    Aucune prime
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="card-footer text-end">
                                                <a href="{{ route('contrats.index') }}" class="btn btn-secondary">
                                                    ⬅ Retour
                                                </a>
                                            </div>
                                        </div>
                                    </div> 
                                </div> 

                               
                                <!-- Informations personnelles -->
                                <div id="edit-profile" class="tab-pane">
                                    <div class="user-profile-content">
                                        <form method="POST" action="{{ route('contrats.update', $contrat->id) }}">
                                            @csrf
                                            @method('PUT')

                                            <div class="card-body">

                                                <div class="mb-3">
                                                    <label class="form-label">Employé</label>
                                                    <input type="text" class="form-control" 
                                                        value="{{ $contrat->employe->nom }} {{ $contrat->employe->prenom }}" disabled>
                                                </div>

                                                {{-- <div class="mb-3">
                                                    <label class="form-label">Type de contrat</label>
                                                    <select name="type_contrat" class="form-select">
                                                        <option value="CDI" {{ $contrat->type_contrat=='CDI'?'selected':'' }}>CDI</option>
                                                        <option value="CDD" {{ $contrat->type_contrat=='CDD'?'selected':'' }}>CDD</option>
                                                        <option value="Stage" {{ $contrat->type_contrat=='Stage'?'selected':'' }}>Stage</option>
                                                    </select>
                                                </div> --}}
                                                <div class="form-group mb-3">
                                                    <label for="type_contrat">Type de contrat</label>
                                                    <select name="type_contrat" id="type_contrat" class="form-select" >
                                                        <option value="">Sélectionnez un type de contrat</option>
                                                        @foreach(['CDI', 'CDD', 'Stage'] as $type)
                                                            <option value="{{ $type }}" {{ old('type_contrat', $contrat->type_contrat) == $type ? 'selected' : '' }}>
                                                                {{ $type }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                {{-- DATES --}}
                                                

                                                <div class="row">
                                                    <div class="mb-3">
                                                        <label for="date_debut" class="form-label">Date de début</label>
                                                        <input type="date" name="date_debut" id="date_embauche" class="form-control"
                                                            value="{{ old('date_debut', \Carbon\Carbon::parse($contrat->date_debut)->format('Y-m-d')) }}" >
                                                        
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label>Date fin</label>
                                                        <input type="date" name="date_fin" class="form-control"
                                                                value="{{ old('date_fin', $contrat->date_fin ? \Carbon\Carbon::parse($contrat->date_fin)->format('Y-m-d') : '') }}">                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label>Salaire de base</label>
                                                        <input type="number" name="salaire_base" class="form-control"
                                                            value="{{ $contrat->salaire_base }}">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label>Heures / semaine</label>
                                                        <input type="number" name="heures_par_semaine" class="form-control"
                                                            value="{{ $contrat->heures_par_semaine ?? 40 }}">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Mode de calcul</label>
                                                    <select name="mode_calcul" class="form-select">
                                                        <option value="mensuel" {{ $contrat->mode_calcul=='mensuel'?'selected':'' }}>
                                                            Mensuel
                                                        </option>
                                                        <option value="horaire" {{ $contrat->mode_calcul=='horaire'?'selected':'' }}>
                                                            Horaire
                                                        </option>
                                                    </select>
                                                </div>

                                                {{-- PRIMES --}}
                                                <h5 class="mt-4">Primes</h5>
                                                @foreach($contrat->primes as $i => $prime)
                                                    <div class="row mb-2">
                                                        <div class="col-md-6">
                                                            <input type="text" name="primes[{{ $i }}][libelle]"
                                                                class="form-control" value="{{ $prime->libelle }}">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="number" name="primes[{{ $i }}][montant]"
                                                                class="form-control" value="{{ $prime->montant }}">
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>

                                            <div class="card-footer text-end">
                                                <button class="btn btn-primary">
                                                    Enregistrer & Régénérer PDF
                                                </button>
                                                <a href="{{ route('contrats.show',$contrat->id) }}" class="btn btn-secondary">
                                                    Annuler
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                </div>


                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

    </div>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {

            const departementSelect = document.getElementById('departement_id');
            const serviceSelect = document.getElementById('service_id');
            const typeContratSelect = document.getElementById('type_contrat');
            const dureeField = document.getElementById('duree_field');
            const dateFinField = document.getElementById('date_fin_field');
            const dureeInput = document.getElementById('duree');
            const dateEmbaucheInput = document.getElementById('date_embauche');
            const dateFinInput = document.getElementById('date_fin');

            // 🔹 Service actuellement sélectionné (pour la modification)
            const selectedServiceId = "{{ $employes->service_id ?? '' }}";

            // 🔹 Initialiser le select service avec le service existant
            if(selectedServiceId) {
                // Si des options existent déjà dans le select (chargées depuis le controller)
                Array.from(serviceSelect.options).forEach(option => {
                    if(option.value == selectedServiceId) {
                        option.selected = true;
                    }
                });
            }

            // 🔹 Charger les services dynamiquement lorsqu'on change de département
            departementSelect.addEventListener('change', function () {
                const departementId = this.value;

                serviceSelect.innerHTML = '<option value="">Chargement...</option>';
                serviceSelect.setAttribute('disabled', true);

                if (departementId) {
                    fetch(`/departements/${departementId}/services`)
                        .then(response => response.json())
                        .then(data => {
                            serviceSelect.removeAttribute('disabled');
                            serviceSelect.innerHTML = '<option value="">-- Sélectionner un service --</option>';

                            data.forEach(service => {
                                const selected = service.id == selectedServiceId ? 'selected' : '';
                                serviceSelect.innerHTML += `<option value="${service.id}" ${selected}>${service.nom}</option>`;
                            });
                        });
                } else {
                    serviceSelect.innerHTML = '<option value="">-- Sélectionnez d’abord un département --</option>';
                }
            });

            // 🔹 Gestion automatique de l’affichage des champs de durée et date de fin selon le contrat
            function toggleContratFields() {
                const typeContrat = typeContratSelect.value;

                if (typeContrat === 'CDD' || typeContrat === 'Stage') {
                    dureeField.style.display = 'block';
                    dateFinField.style.display = 'block';
                    dateFinInput.removeAttribute('readonly');
                } else {
                    dureeField.style.display = 'none';
                    dateFinField.style.display = 'none';
                    dateFinInput.setAttribute('readonly', true);
                    dureeInput.value = '';
                    dateFinInput.value = '';
                }
            }

            // 🔹 Calcul automatique de la date de fin à partir de la durée et la date d’embauche
            function calculerDateFin() {
                const dateEmbauche = dateEmbaucheInput.value;
                const duree = parseInt(dureeInput.value);

                if (dateEmbauche && duree && duree > 0) {
                    const date = new Date(dateEmbauche);
                    date.setMonth(date.getMonth() + duree);
                    const yyyy = date.getFullYear();
                    const mm = String(date.getMonth() + 1).padStart(2, '0');
                    const dd = String(date.getDate()).padStart(2, '0');
                    dateFinInput.value = `${yyyy}-${mm}-${dd}`;
                } else {
                    dateFinInput.value = '';
                }
            }

            // 🔹 Événements
            typeContratSelect.addEventListener('change', toggleContratFields);
            dureeInput.addEventListener('input', calculerDateFin);
            dateEmbaucheInput.addEventListener('change', calculerDateFin);

            // 🔹 Initialisation au chargement (si CDD ou Stage déjà défini)
            toggleContratFields();
            calculerDateFin();
        });
    </script> --}}



@endsection


