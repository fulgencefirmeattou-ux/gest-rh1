@extends('layouts.base')
@section('title','créer un utilisateur')
@section('content')
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="container py-5">
            <h2 class="mb-4 text-start">Créer un nouvel utilisateur</h2>

            {{-- Message de succès --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Messages d’erreur --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif


            <form action="{{ route('admin.createUser') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
                @csrf

                {{-- <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" name="prenom" id="prenom" class="form-control" value="{{ old('prenom') }}" required>
                    </div>
                </div> --}}

                <div class="mb-3">
                    <label for="emailadresse" class="form-label">Nom utilisateur</label>
                    <input class="form-control @error('login') is-invalid @enderror" name="login" type="text" placeholder="Nom utilisateur" id="emailaddress"  value="{{ old('login') }}">
                </div>
                <div class="mb-3">
                    <div class="mb-1">
                        <label for="emailaddress" class="form-label text-dark">Votre Email </label>
                        <input class="form-control @error('email') is-invalid @enderror" name="email" type="email" id="emailaddress"  value="{{ old('email') }}" required=""
                            placeholder="Entrer votre email"> 
                    </div>
                    @error('email')
                        <div class="text-danger small mb-2">{{ $message }}</div>
                    @enderror
                </div>

                {{-- <div class="mb-3">
                    <label for="phone" class="form-label">Téléphone</label>
                    <input type="tel" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" required>
                </div> --}}

                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Rôle</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="">-- Choisir un rôle --</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                        <option value="employe" {{ old('role') == 'employe' ? 'selected' : '' }}>Employé</option>
                        {{-- <option value="responsable" {{ old('role') == 'responsable' ? 'selected' : '' }}>Responsable</option> --}}
                        <option value="dg" {{ old('role') == 'DG' ? 'selected' : '' }}>DG</option>
                        <option value="rh" {{ old('role') == 'RH' ? 'selected' : '' }}>RH</option>
                    </select>
                </div>
                {{-- <div class="mb-3">
                    <label for="photo">Photo de profil :</label>
                    <input type="file" name="photo" id="photo" class="form-control" accept="image/*"
                    onchange="if(this.files[0].size > 5242880){ alert('Fichier trop volumineux ! Max 5 Mo'); this.value=''; }">
                </div> --}}
                <button type="submit" class="btn btn-primary w-100">Créer l’utilisateur</button>
            </form>
        </div>
        </div>
        {{-- <div class="col-12 col-lg-3">
            <div class="card">
                <div class="card-body py-4 px-5">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-xl">
                            <img src="assets/images/faces/1.jpg" alt="Face 1">
                        </div>
                        <div class="ms-3 name">
                            <h5 class="font-bold">John Duck</h5>
                            <h6 class="text-muted mb-0">@johnducky</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Recent Messages</h4>
                </div>
                <div class="card-content pb-4">
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg">
                            <img src="assets/images/faces/4.jpg">
                        </div>
                        <div class="name ms-4">
                            <h5 class="mb-1">Hank Schrader</h5>
                            <h6 class="text-muted mb-0">@johnducky</h6>
                        </div>
                    </div>
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg">
                            <img src="assets/images/faces/5.jpg">
                        </div>
                        <div class="name ms-4">
                            <h5 class="mb-1">Dean Winchester</h5>
                            <h6 class="text-muted mb-0">@imdean</h6>
                        </div>
                    </div>
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg">
                            <img src="assets/images/faces/1.jpg">
                        </div>
                        <div class="name ms-4">
                            <h5 class="mb-1">John Dodol</h5>
                            <h6 class="text-muted mb-0">@dodoljohn</h6>
                        </div>
                    </div>
                    <div class="px-4">
                        <button class='btn btn-block btn-xl btn-light-primary font-bold mt-3'>Start
                            Conversation</button>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Visitors Profile</h4>
                </div>
                <div class="card-body">
                    <div id="chart-visitors-profile"></div>
                </div>
            </div>
        </div> --}}
    </section>
@endsection