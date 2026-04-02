@extends('layouts.app')

@section('content')

<section>
    <div class="container mt-5">
        <h2 class="mb-4 text-start">Créer un département</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <form action="{{ route('departements.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom') }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
            </div>
                <div class="mb-3">
                    <label for="responsable_id" class="form-label">Responsable</label>
                    <select name="responsable_id" id="responsable_id" class="form-control">
                        <option value="">Aucun</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('responsable_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            
            <button type="submit" class="btn btn-primary">Créer</button>
            </form>
    </div>
    
</section>

@endsection