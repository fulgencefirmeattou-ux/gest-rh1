@extends('layouts.base')
@section('title', 'créer un utilisateur')
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
                            <div class="profile-user-img"><img src="{{ asset ( $user->photo )}}" alt=""
                                    class="avatar-lg rounded-circle"></div>
                            <div class="">
                                <h4 class="mt-4 fs-17 ellipsis">{{ ucfirst($user->nom) }} {{ ucfirst($user->prenom) ?? '_' }}</h4>
                                <p class="font-13"> {{ $user->email ?? '—' }}</p>
                                {{-- <p class="text-muted mb-0"><small>California, United States</small></p> --}}
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
                        </div> --}}
                    </div>
                </div>
                <!--/ meta -->
            </div>
        </div>
        <!-- end row -->

        <div class="row">
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
                                {{-- <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#aboutme" type="button" role="tab"
                                        aria-controls="home" aria-selected="true" href="#aboutme">About</a>
                                </li> --}}
                                {{-- <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#user-activities" type="button" role="tab"
                                        aria-controls="home" aria-selected="true"
                                        href="#user-activities">Activities</a></li> --}}
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#edit-profile" type="button" role="tab"
                                        aria-controls="home" aria-selected="true"
                                        href="#edit-profile">Informations personnelles</a></li>
                                {{-- <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#projects" type="button" role="tab"
                                        aria-controls="home" aria-selected="true"
                                        href="#projects">Projects</a></li> --}}
                            </ul>

                            <div class="tab-content m-0 p-4">
                                {{-- <div class="tab-pane active" id="aboutme" role="tabpanel"
                                    aria-labelledby="home-tab" tabindex="0">
                                    <div class="profile-desk">
                                        <h5 class="text-uppercase fs-17 text-dark">Johnathan Deo</h5>
                                        <div class="designation mb-4">PRODUCT DESIGNER (UX / UI / Visual
                                            Interaction)</div>
                                        <p class="text-muted fs-16">
                                            I have 10 years of experience designing for the web, and
                                            specialize
                                            in the areas of user interface design, interaction design,
                                            visual
                                            design and prototyping. I’ve worked with notable startups
                                            including
                                            Pearl Street Software.
                                        </p>

                                        <h5 class="mt-4 fs-17 text-dark">Contact Information</h5>
                                        <table class="table table-condensed mb-0 border-top">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Url</th>
                                                    <td>
                                                        <a href="#" class="ng-binding">
                                                            www.example.com
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Email</th>
                                                    <td>
                                                        <a href="" class="ng-binding">
                                                            jonathandeo@example.com
                                                        </a>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th scope="row">Phone</th>
                                                    <td class="ng-binding">(123)-456-7890</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Skype</th>
                                                    <td>
                                                        <a href="#" class="ng-binding">
                                                            jonathandeo123
                                                        </a>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div> <!-- end profile-desk -->
                                </div> <!-- about-me --> --}}

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
                                <div id="edit-profile" class="tab-pane active">
                                    <div class="user-profile-content">
                                        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="row row-cols-sm-2 row-cols-1">
                                                {{-- <div class="mb-2">
                                                    <label class="form-label"  for="nom">Nom</label>
                                                    <input type="text" name="nom" value="{{ $user->nom }}" id="nom"
                                                        class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"  for="prenom">Prenom</label>
                                                    <input type="text" value="{{ $user->prenom }}" name="prenom"
                                                        id="prenom" class="form-control">
                                                </div> --}}
                                                <div class="mb-3">
                                                    <label for="emailadresse" class="form-label">Nom utilisateur</label>
                                                    <input class="form-control @error('login') is-invalid @enderror" name="login" type="text" placeholder="Nom utilisateur" id="emailaddress"  value="{{ $user->login }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"  for="email">Email</label>
                                                    <input type="email" value="{{ $user->email }}" name="email"
                                                        id="email" class="form-control">
                                                </div>
                                                {{-- <div class="mb-3">
                                                    <label class="form-label"  for="phone">Téléphone</label>
                                                    <input type="tel" value="{{ $user->phone }}" name="phone"
                                                        id="phone" class="form-control">
                                                </div> --}}
                                                {{-- <div class="mb-3">
                                                    <label class="form-label"
                                                        for="Username">Username</label>
                                                    <input type="text" value="john" id="Username"
                                                        class="form-control">
                                                </div> --}}
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="password">Mot de passe</label>
                                                    <input type="password" name="password" placeholder="8 Caractères"
                                                        id="password" class="form-control">
                                                </div>
                                                {{-- <div class="mb-3">
                                                    <label class="form-label" for="PasswordConfirm">Confirmer le mot de passe</label>
                                                    <input type="password" name="password_confirmation" placeholder="Confirmez le mot de passe" id="PasswordConfirm" class="form-control">
                                                </div> --}}
                                                <div class="mb-3">
                                                    <label for="role" class="form-label">Rôle</label>
                                                    <select name="role" id="role" class="form-select" required>
                                                        <option value="" disabled>-- Sélectionner un rôle --</option>
                                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrateur</option>
                                                        <option value="dg" {{ $user->role === 'dg' ? 'selected' : '' }}>DG</option>
                                                        <option value="employe" {{ $user->role === 'employe' ? 'selected' : '' }}>Employé</option>
                                                        <option value="responsable" {{ $user->role === 'responsable' ? 'selected' : '' }}>Responsable</option>
                                                        <option value="rh" {{ $user->role === 'rh' ? 'selected' : '' }}>RH</option>
                                                    </select>
                                                </div>

                                                {{-- <div class="mb-3">
                                                    <label for="photo" class="mb-1">Photo de profil :</label>
                                                    <input type="file" name="photo" id="photo" class="form-control" accept="image/*"
                                                    onchange="if(this.files[0].size > 5242880){ alert('Fichier trop volumineux ! Max 5 Mo'); this.value=''; }">
                                                </div> --}}
                                                {{-- <div class="col-sm-12 mb-3">
                                                    <label class="form-label" for="AboutMe">About Me</label>
                                                    <textarea style="height: 125px;" id="AboutMe"
                                                        class="form-control">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</textarea>
                                                </div> --}}
                                            </div>
                                            <button class="btn btn-primary w-100" type="submit"><i
                                                    class="ri-save-line me-1 fs-16 lh-1"></i> Modifier </button>
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

@endsection