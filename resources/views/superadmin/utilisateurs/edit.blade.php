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

                <form action="{{ route('utilisateurs.update', $user->id) }}" method="POST" class="bg-white p-4 rounded shadow-sm">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>   
                        @enderror
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>   
                        @enderror
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" name="password" id="password" class="form-control">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <label for="role" class="form-label">Rôle</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="employe" {{ old('role', $user->role) == 'employe' ? 'selected' : '' }}>Employé</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                            <option value="rh" {{ old('role', $user->role) == 'rh' ? 'selected' : '' }}>Ressources Humaines</option>
                            <option value="dg" {{ old('role', $user->role) == 'dg' ? 'selected' : '' }}>Directeur Général</option>
                            <option value="responsable_service" {{ old('role', $user->role) == 'responsable_service' ? 'selected' : '' }}>Responsable de Service</option>
                        </select>



                    </div>
                    <button type="submit" class="btn btn-primary w-100">Enregistrer les modifications</button>
                </form>
            </div>
        </div>

    </section>

@endsection