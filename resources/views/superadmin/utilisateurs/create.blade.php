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
                <label for="name" class="form-label">Nom et Prénoms</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Rôle</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="employe">Employé</option>
                    <option value="admin">Administrateur</option>
                    <option value="rh">Ressources Humaines</option>
                    <option value="dg">Directeur Général</option>
                    <option value="responsable_service">Responsable de Service</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
            </form>
    </div>
    
</section>

@endsection