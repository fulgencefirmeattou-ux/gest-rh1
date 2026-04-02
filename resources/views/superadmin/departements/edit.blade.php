@extends('layouts.app')

@section('content')

<section class="row">
    <div class="col-12 col-lg-12">
        <div>
            <h2 class="mb-4 text-start">Modifier le département</h2>
            <a href="{{ route('departements.index') }}" class="btn btn-secondary mb-3">Retour à la liste</a>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('departements.update', $departement->id) }}" method="POST" class="bg-white p-4 rounded shadow-sm">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nom" class="form-label">Nom du département</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom', $departement->nom) }}" required>
                    @error('nom')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control">{{ old('description', $departement->description) }}</textarea>
                    @error('description')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="responsable_id" class="form-label">Responsable</label>
                    <select name="responsable_id" id="responsable_id" class="form-control">
                        <option value="">Aucun</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('responsable_id', $departement->responsable_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('responsable_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">Enregistrer les modifications</button>
            </form>
        </div>
    </div>
</section>

@endsection
