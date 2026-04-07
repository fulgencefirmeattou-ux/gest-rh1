@extends('layouts.app')

@section('content')

 <section class="row mt-3">
        <div class="col-12 col-lg-12">
            <div>
                <h2 class="mb-4 text-start">Modifier un utilisateur</h2>
                <a href="{{ route('utilisateurs.index') }}" class="btn btn-secondary mb-3">Retour à la liste</a>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('utilisateurs.update', $user->id) }}" method="POST" class="bg-white p-4 rounded shadow-sm">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom', $user->nom) }}" required>
                        @error('nom')<small class="invalid-feedback">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" name="prenom" id="prenom" class="form-control @error('prenom') is-invalid @enderror" value="{{ old('prenom', $user->prenom) }}" required>
                        @error('prenom')<small class="invalid-feedback">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')<small class="invalid-feedback">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe <small class="text-muted">(laisser vide pour ne pas changer)</small></label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')<small class="invalid-feedback">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle</label>
                        <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                            <option value="" disabled>-- Sélectionner un rôle --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role', $user->role) == $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')<small class="invalid-feedback">{{ $message }}</small>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Enregistrer les modifications</button>
                </form>
            </div>
        </div>

    </section>

@endsection
