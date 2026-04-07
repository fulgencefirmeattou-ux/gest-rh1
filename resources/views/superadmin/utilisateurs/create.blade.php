@extends('layouts.app')

@section('content')

<section>
    <div class="container mt-5">
        <h2 class="mb-4 text-start">Créer un utilisateur</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <form action="{{ route('utilisateurs.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" name="nom" id="nom" @error('nom') is-invalid @enderror class="form-control" value="{{ old('nom') }}" required>
                @error('nom')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" name="prenom" id="prenom" @error('prenom') is-invalid @enderror class="form-control" value="{{ old('prenom') }}" required>
                @error('prenom')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" @error('email') is-invalid @enderror class="form-control" value="{{ old('email') }}" required>
                @error('email')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" name="password" id="password" @error('password') is-invalid @enderror class="form-control" required>
                @error('password')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Rôle</label>
                <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                    <option value="" disabled selected>-- Sélectionner un rôle --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
            </form>
    </div>

</section>

@endsection
