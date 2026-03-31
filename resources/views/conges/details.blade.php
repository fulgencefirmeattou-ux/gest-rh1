@extends('layouts.base')
@section('title', 'voir ma demande de congé')
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
                {{-- <div class="profile-user-box">
                    <div class="row">
                        {{-- <div class="col-sm-6">
                            <div class="profile-user-img"><img src="{{ asset ( $employes->user->photo )}}" alt=""
                                    class="avatar-lg rounded-circle"></div>
                            <div class="">
                                <h4 class="mt-4 fs-17 ellipsis">{{ ucfirst($employes->user->nom) }} {{ ucfirst($employes->user->prenom) ?? '_' }}</h4>
                                <p class="font-13"> {{ $employes->user->email ?? '—' }}</p>
                                {{-- <p class="text-muted mb-0"><small>California, United States</small></p>
                            </div>
                        </div> --}}
                        {{-- <div class="col-sm-6">
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                <button type="button" class="btn btn-soft-danger">
                                    <i class="ri-settings-2-line align-text-bottom me-1 fs-16 lh-1"></i>
                                    Edit Profile
                                </button>
                                <a class="btn btn-soft-info" href="#"> <i class="ri-check-double-fill fs-18 me-1 lh-1"></i> Following</a>
                            </div>
                        </div>  
                    </div>
                </div>  --}}
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
                                        aria-controls="home" aria-selected="true" href="#aboutme">Detail de ma demande</a>
                                </li>
                                {{-- <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#user-activities" type="button" role="tab"
                                        aria-controls="home" aria-selected="true"
                                        href="#user-activities">Activities</a></li> --}}
                                @php
                                    $isApproved = in_array($conge->statut, ['attente_departement','attente_dg','approuvee','rejetee']);
                                @endphp

                                <li class="nav-item"><a class="nav-link  {{ $isApproved ? 'disabled text-muted' : '' }}" 
                                        @if (!$isApproved)
                                            
                                            data-bs-toggle="tab"
                                            data-bs-target="#edit-profile" type="button" role="tab"
                                            aria-controls="home" aria-selected="true"
                                            href="#edit-profile"
                                        @endif
                                        >Modifier ma demande</a>
                                </li>
                                {{-- <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#projects" type="button" role="tab"
                                        aria-controls="home" aria-selected="true"
                                        href="#projects">Projects</a></li> --}}
                            </ul>

                            <div class="tab-content m-0 p-4">
                                <div class="tab-pane active" id="aboutme" role="tabpanel"
                                    aria-labelledby="home-tab" tabindex="0">
                                    <div class="profile-desk">
                                        <h5 class="text-uppercase fs-16 ">Nom et prenom : <b class="text-dark fs-16 lowercase">{{ ucfirst($conge->employe->nom) }} {{ ucfirst($conge->employe->prenom) ?? '_' }} </b></h5> <br>
                                        <div class="designation mb-4"> 
                                            <h5 class="text-uppercase fs-16 ">Email : <b class="text-dark text-lowercase fs-16"> {{ ($conge->employe->email) ?? '_' }} </b></h5><br>
                                            <h5 class="text-uppercase fs-16 ">Téléphone : <b class="text-dark text-lowercase fs-16"> {{ ($conge->employe->phone) ?? '_' }} </b></h5><br>
                                            <h5 class="text-uppercase fs-16 ">Departement : <b class="text-dark text-lowercase fs-16"> {{ ($conge->employe->service->departement->nom ?? '_') }} </b></h5><br>
                                            <p class="text-muted fs-16">
                                           {{-- {{ $employes->service->departement->description ?? '_'}} --}}
                                        </p>
                                        </div>
                                        

                                        <h5 class="mt-4 fs-17 text-dark">Information</h5>
                                        <table class="table table-condensed mb-0 border-top">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Type de conge</th>
                                                    <td>{{ $conge->type_conge }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Date de debut des congés </th>
                                                    <td>{{  $conge->date_debut_conge ? $conge->date_debut_conge->format('d/m/Y') : '-' }}</td>
                                                </tr>

                                                <tr>
                                                    <th scope="row">Date de fin des congés</th>
                                                    <td>{{$conge->date_fin_conge ? $conge->date_fin_conge->format('d/m/Y') : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Nombre de jours</th>
                                                    <td>{{$conge->jours_ouvres ?? '-' }} jours</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Date de reprise des congés</th>
                                                    <td>{{$conge->date_retour ? $conge->date_retour->format('d/m/Y') : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Statut de la demande</th>
                                                    <td>{{ $conge->statut }}</td>
                                                    
                                                </tr>
                                                <tr>
                                                    <th scope="row">Commentaire</th>
                                                    <td>{{ $conge->commentaire ?? '_' }} </td>
                                                </tr>
                                                {{-- <tr>
                                                    <th scope="row">Date de fin</th>
                                                    <td>
                                                        {{ $employes->date_fin ? $employes->date_fin->format('d/m/Y') : '-' }}
                                                    </td>
                                                </tr> --}}

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
                                        @php
                                            $disabled = $isApproved ? 'disabled' : '';
                                        @endphp
                                        <form action="{{ route('conges.resubmit', $conge->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            {{-- Type de congé --}}
                                            <label>Type de congé</label>
                                            <select name="type_conge" class="form-select" required {{ $disabled }}>
                                                <option value="annuel" {{ old('type_conge', $conge->type_conge) == 'annuel' ? 'selected' : '' }}>Annuel</option>
                                                <option value="special" {{ old('type_conge', $conge->type_conge) == 'special' ? 'selected' : '' }}>Spécial</option>
                                                <option value="exceptionnel" {{ old('type_conge', $conge->type_conge) == 'exceptionnel' ? 'selected' : '' }}>Exceptionnel</option>
                                            </select>
                                            <br>

                                            {{-- Date début --}}
                                            <label>Date de début</label>
                                            <input 
                                                type="date" 
                                                name="date_debut_conge" 
                                                class="form-control" 
                                                value="{{ old('date_debut_conge', $conge->date_debut_conge?->format('Y-m-d')) }}"
                                                {{ $disabled }}
                                            >
                                            <br>

                                            {{-- Date fin --}}
                                            <label>Date de fin</label>
                                            <input 
                                                type="date" 
                                                name="date_fin_conge" 
                                                class="form-control" 
                                                value="{{ old('date_fin_conge', $conge->date_fin_conge?->format('Y-m-d')) }}"
                                                {{ $disabled }}
                                            >
                                            <br>

                                            <label>Date de retour</label>
                                            <input 
                                                type="date" 
                                                name="date_retour" 
                                                class="form-control" 
                                                value="{{ old('date_retour', $conge->date_retour?->format('Y-m-d')) }}"
                                                {{ $disabled }}
                                            />
                                            <br>
                                            {{-- Motif --}}
                                            <label>Motif</label>
                                            <textarea name="raison" class="form-control" {{ $disabled }}>{{ old('raison', $conge->raison) }}</textarea>
                                            <br>

                                            <button class="btn btn-primary w-100">Soumettre ma demande</button>
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