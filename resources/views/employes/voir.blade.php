@extends('layouts.base')
@section('title', 'créer un employé')
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
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
                                {{-- <p class="text-muted mb-0"><small>California, United States</small></p>--}}
                            </div>
                        </div>
                        {{-- <div class="col-sm-6">
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                <button type="button" class="btn btn-soft-danger">
                                    <i class="ri-settings-2-line align-text-bottom me-1 fs-16 lh-1"></i>
                                    Edit Profile
                                </button>
                                <a class="btn btn-soft-info" href="#"> <i class="ri-check-double-fill fs-18 me-1 lh-1"></i> Following</a>
                            </div>
                        </div>  --}}
                    </div>
                </div> 
                <!--/ meta -->
            </div>
        </div> 
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
                                        aria-controls="home" aria-selected="true" href="#aboutme">Detail de l'employé</a>
                                </li>
                                {{-- <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#user-activities" type="button" role="tab"
                                        aria-controls="home" aria-selected="true"
                                        href="#user-activities">Activities</a></li> --}}
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#edit-profile" type="button" role="tab"
                                        aria-controls="home" aria-selected="true"
                                        href="#edit-profile">Modifier l'employer</a></li>
                                {{-- <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#projects" type="button" role="tab"
                                        aria-controls="home" aria-selected="true"
                                        href="#projects">Projects</a></li> --}}
                            </ul>

                            <div class="tab-content m-0 p-4">
                                <div class="tab-pane active" id="aboutme" role="tabpanel"
                                    aria-labelledby="home-tab" tabindex="0">
                                    <div class="profile-desk">
                                        <h5 class="text-uppercase fs-16 ">Nom et prenom : <b class="text-dark fs-16 lowercase">{{ ucfirst($employes->nom) }} {{ ucfirst($employes->prenom) ?? '_' }} </b></h5> <br>
                                        <div class="designation mb-4"> 
                                            <h5 class="text-uppercase fs-16 ">Email : <b class="text-dark text-lowercase fs-16"> {{ ($employes->email) ?? '_' }} </b></h5><br>
                                            <h5 class="text-uppercase fs-16 ">Téléphone : <b class="text-dark text-lowercase fs-16"> {{ ($employes->phone) ?? '_' }} </b></h5><br>
                                            <h5 class="text-uppercase fs-16 ">Departement : <b class="text-dark text-lowercase fs-16"> {{ ($employes->service->departement->nom ?? '_') }} </b></h5><br>
                                            <p class="text-muted fs-16">
                                           {{-- {{ $employes->service->departement->description ?? '_'}} --}}
                                        </p>
                                        </div>
                                        

                                        <h5 class="mt-4 fs-17 text-dark">Information</h5>
                                        <table class="table table-condensed mb-0 border-top">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Service</th>
                                                    <td>
                                                        {{ $employes->service->nom ?? '_' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Poste</th>
                                                    <td>
                                                        {{ $employes->poste ?? '_' }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th scope="row">Type de contrat</th>
                                                    <td class="ng-binding">{{ $employes->type_contrat ?? '_' }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Date d'embauche</th>
                                                    <td>
                                                        {{ $employes->date_embauche ? $employes->date_embauche->format('d/m/Y') : '-' }}
                                                    </td>
                                                </tr>
                                                {{-- <tr>
                                                    <th scope="row">Date de debut</th>
                                                    <td>
                                                        {{ $employes->date_debut ? $employes->date_debut->format('d/m/Y') : '-' }}
                                                    </td>
                                                </tr> --}}
                                                <tr>
                                                    <th scope="row">Date de fin</th>
                                                    <td>
                                                        {{ $employes->date_fin ? $employes->date_fin->format('d/m/Y') : '-' }}
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div> <!-- end profile-desk -->
                                </div> <!-- about-me --> 

                                <!-- Activities -->
                                {{-- <div id="user-activities" class="tab-pane">
                                    <div class="timeline-2">
                                        <div class="time-item">
                                            <div class="item-info ms-3 mb-3">
                                                <div class="text-muted">5 minutes ago</div>
                                                <p><strong><a href="#" class="text-info">John
                                                            Doe</a></strong>Uploaded a photo</p>
                                                <img src="assets/images/small/small-3.jpg" alt=""
                                                    height="40" width="60" class="rounded-1">
                                                <img src="assets/images/small/small-4.jpg" alt=""
                                                    height="40" width="60" class="rounded-1">
                                            </div>
                                        </div>

                                        <div class="time-item">
                                            <div class="item-info ms-3 mb-3">
                                                <div class="text-muted">30 minutes ago</div>
                                                <p><a href="" class="text-info">Lorem</a> commented your
                                                    post.
                                                </p>
                                                <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing
                                                        elit.
                                                        Aliquam laoreet tellus ut tincidunt euismod. "</em>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="time-item">
                                            <div class="item-info ms-3 mb-3">
                                                <div class="text-muted">59 minutes ago</div>
                                                <p><a href="" class="text-info">Jessi</a> attended a meeting
                                                    with<a href="#" class="text-success">John Doe</a>.</p>
                                                <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing
                                                        elit.
                                                        Aliquam laoreet tellus ut tincidunt euismod. "</em>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="time-item">
                                            <div class="item-info ms-3 mb-3">
                                                <div class="text-muted">5 minutes ago</div>
                                                <p><strong><a href="#" class="text-info">John
                                                            Doe</a></strong> Uploaded 2 new photos</p>
                                                <img src="assets/images/small/small-2.jpg" alt=""
                                                    height="40" width="60" class="rounded-1">
                                                <img src="assets/images/small/small-1.jpg" alt=""
                                                    height="40" width="60" class="rounded-1">
                                            </div>
                                        </div>

                                        <div class="time-item">
                                            <div class="item-info ms-3 mb-3">
                                                <div class="text-muted">30 minutes ago</div>
                                                <p><a href="" class="text-info">Lorem</a> commented your
                                                    post.
                                                </p>
                                                <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing
                                                        elit.
                                                        Aliquam laoreet tellus ut tincidunt euismod. "</em>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="time-item">
                                            <div class="item-info ms-3 mb-3">
                                                <div class="text-muted">59 minutes ago</div>
                                                <p><a href="" class="text-info">Jessi</a> attended a meeting
                                                    with<a href="#" class="text-success">John Doe</a>.</p>
                                                <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing
                                                        elit.
                                                        Aliquam laoreet tellus ut tincidunt euismod. "</em>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                                <!-- Informations personnelles -->
                                <div id="edit-profile" class="tab-pane">
                                    <div class="user-profile-content">
                                        <form action="{{ route('employe.update', $employes->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="row row-cols-1">

                                                {{-- UTILISATEUR --}}
                                                {{-- <div class="mb-3">
                                                    <label for="user_id" class="form-label">Utilisateur</label>
                                                    <select name="user_id" id="user_id" class="form-select" required>
                                                        <option value="">Sélectionnez un utilisateur</option>
                                                        @foreach ($users as $user)
                                                            <option 
                                                                value="{{ $user->id }}"
                                                                {{ old('user_id', $employes->user_id ?? '') == $user->id ? 'selected' : '' }}>
                                                                {{ ucfirst($user->nom) }} {{ ucfirst($user->prenom) }} ({{ $user->email ?? $user->phone }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div> --}}

                                                
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label for="nom" class="form-label">Nom</label>
                                                            <input type="text" name="nom" id="nom" class="form-control" value="{{ $employes->nom }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="prenom" class="form-label">Prénom</label>
                                                            <input type="text" name="prenom" id="prenom" class="form-control" value="{{ $employes->prenom }}" required>
                                                        </div>
                                                    </div> 
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label for="emailaddress" class="form-label">Votre Email </label>
                                                            <input class="form-control @error('email') is-invalid @enderror" name="email" type="email" id="emailaddress"  value="{{ $employes->email }}" required=""
                                                                placeholder="Entrer votre email"> 
                                                        </div>
                                                        {{-- @error('email')
                                                            <div class="text-danger small mb-2">{{ $message }}</div>
                                                        @enderror --}}
                                                        <div class="col-md-6">
                                                            <label for="phone" class="form-label">Téléphone</label>
                                                            <input type="tel" name="phone" id="phone" class="form-control" value="{{ $employes->phone }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="photo">Photo de profil :</label>
                                                        
                                                        {{-- Affichage de l'ancienne photo --}}
                                                        @if($employes->photo)
                                                            <div class="mb-2">
                                                                <img src="{{ asset($employes->photo) }}" alt="Photo actuelle" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                                            </div>
                                                        @endif

                                                        <input type="file" name="photo" id="photo" class="form-control" accept="image/*"
                                                            onchange="if(this.files[0].size > 5242880){ alert('Fichier trop volumineux ! Max 5 Mo'); this.value=''; }">
                                                    </div>


                                                {{-- DÉPARTEMENT --}}
                                                {{-- <div class="mb-3">
                                                    <label for="departement_id" class="form-label">Département</label>
                                                    <select name="departement_id" id="departement_id" class="form-select" required>
                                                        <option value="">
                                                            {{ $employes->departement->nom ?? 'Sélectionnez un département' }}
                                                        </option>
                                                        @foreach($departements as $departement)
                                                            <option 
                                                                value="{{ $departement->id }}" 
                                                                {{ old('departement_id', $employes->departement_id ?? '') == $departement->id ? 'selected' : '' }}>
                                                                {{ $departement->nom }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div> --}}

                                                <div class="mb-3">
                                                    <label for="departement_id" class="form-label">Département</label>
                                                    <select name="departement_id" id="departement_id" class="form-select" required>
                                                        @foreach($departements as $departement)
                                                            <option 
                                                                value="{{ $departement->id }}" 
                                                                {{ (old('departement_id', $employes->departement_id) == $departement->id) ? 'selected' : '' }}>
                                                                {{ $departement->nom }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                                {{-- SERVICE --}}
                                                {{-- <div class="mb-3">
                                                    <label for="service_id" class="form-label">Service</label>
                                                    <select name="service_id" id="service_id" class="form-select" required>
                                                        @if($services->count())
                                                            @foreach($services as $service)
                                                                <option value="{{ $service->id }}" 
                                                                    {{ old('service_id', $employes->service_id) == $service->id ? 'selected' : '' }}>
                                                                    {{ $service->nom }}
                                                                </option>
                                                            @endforeach
                                                        @else
                                                            <option value="">-- Sélectionnez d’abord un département --</option>
                                                        @endif
                                                    </select>
                                                </div> --}}

                                                <div class="mb-3">
                                                    <label for="service_id" class="form-label">Service</label>
                                                    <select name="service_id" id="service_id" class="form-select" required>
                                                        @php
                                                            // Assurer que le service actuel de l'employé est présent
                                                            $currentServiceId = old('service_id', $employes->service_id);
                                                        @endphp

                                                        @if($currentServiceId && !$services->contains('id', $currentServiceId))
                                                            @php
                                                                $currentService = \App\Models\Service::find($currentServiceId);
                                                            @endphp
                                                            @if($currentService)
                                                                <option value="{{ $currentService->id }}" selected>
                                                                    {{ $currentService->nom }}
                                                                </option>
                                                            @endif
                                                        @endif

                                                        @foreach($services as $service)
                                                            <option 
                                                                value="{{ $service->id }}" 
                                                                {{ ($currentServiceId == $service->id) ? 'selected' : '' }}>
                                                                {{ $service->nom }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>




                                                {{-- POSTE --}}
                                                <div class="mb-3">
                                                    <label for="poste" class="form-label">Poste</label>
                                                    <input type="text" name="poste" class="form-control" 
                                                        value="{{ old('poste', $employes->poste ?? '') }}" required>
                                                </div>

                                                {{-- TYPE DE CONTRAT --}}
                                                <div class="form-group mb-3">
                                                    <label for="type_contrat">Type de contrat</label>
                                                    <select name="type_contrat" id="type_contrat" class="form-control" required>
                                                        <option value="">Sélectionnez un type de contrat</option>
                                                        @foreach(['CDI', 'CDD', 'Stage'] as $type)
                                                            <option value="{{ $type }}" {{ old('type_contrat', $employes->type_contrat) == $type ? 'selected' : '' }}>
                                                                {{ $type }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                {{-- DATES --}}
                                                <div class="mb-3">
                                                    <label for="date_embauche" class="form-label">Date d'embauche</label>
                                                    <input type="date" name="date_embauche" id="date_embauche" class="form-control"
                                                        value="{{ old('date_embauche', \Carbon\Carbon::parse($employes->date_embauche)->format('Y-m-d')) }}" required>
                                                       
                                                </div>

                                                <div class="form-group mb-3" id="duree_field" style="display: none;">
                                                    <label for="duree">Durée (en mois)</label>
                                                    <input type="number" name="duree" id="duree" class="form-control"
                                                        value="{{ old('duree', $employes->duree ?? '') }}" min="1">
                                                </div>

                                                <div class="form-group mb-3" id="date_fin_field" style="display: none;">
                                                    <label for="date_fin">Date de fin</label>
                                                    <input type="date" name="date_fin" id="date_fin" class="form-control"
                                                        value="{{ old('date_fin', $employes->date_fin ?? '') }}" readonly>
                                                </div>

                                                {{-- SALAIRE --}}
                                                <div class="form-group mb-3">
                                                    <label for="salaire" class="form-label">Salaire</label>
                                                    <input type="number" name="salaire" class="form-control"
                                                        value="{{ old('salaire', $employes->salaire ?? '') }}" required>
                                                </div>

                                            </div>

                                            <button class="btn btn-primary w-100" type="submit">
                                                <i class="ri-save-line me-1 fs-16 lh-1"></i> Modifier
                                            </button>
                                        </form>
                                    </div>
                                </div>


                                <!-- profile -->
                                {{-- <div id="projects" class="tab-pane">
                                    <div class="row m-t-10">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Project Name</th>
                                                            <th>Start Date</th>
                                                            <th>Due Date</th>
                                                            <th>Status</th>
                                                            <th>Assign</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>Velonic Admin</td>
                                                            <td>01/01/2015</td>
                                                            <td>07/05/2015</td>
                                                            <td><span class="badge bg-info">Work
                                                                    in Progress</span></td>
                                                            <td>Techzaa</td>
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td>Velonic Frontend</td>
                                                            <td>01/01/2015</td>
                                                            <td>07/05/2015</td>
                                                            <td><span
                                                                    class="badge bg-success">Pending</span>
                                                            </td>
                                                            <td>Techzaa</td>
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td>Velonic Admin</td>
                                                            <td>01/01/2015</td>
                                                            <td>07/05/2015</td>
                                                            <td><span class="badge bg-pink">Done</span>
                                                            </td>
                                                            <td>Techzaa</td>
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td>Velonic Frontend</td>
                                                            <td>01/01/2015</td>
                                                            <td>07/05/2015</td>
                                                            <td><span class="badge bg-purple">Work
                                                                    in Progress</span></td>
                                                            <td>Techzaa</td>
                                                        </tr>
                                                        <tr>
                                                            <td>5</td>
                                                            <td>Velonic Admin</td>
                                                            <td>01/01/2015</td>
                                                            <td>07/05/2015</td>
                                                            <td><span class="badge bg-warning">Coming
                                                                    soon</span></td>
                                                            <td>Techzaa</td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

    </div>

    <script>
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
    </script>



@endsection