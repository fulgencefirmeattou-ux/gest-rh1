@extends('layouts.base')
@section('title', 'Justificatifs d\'absence')
@section('content')
<section class="row">
    <div class="col-12 col-lg-12">
        <div class="container py-5">
            <h2 class="mb-4 text-start">Justifier une absence</h2>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif


            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('justificatifs.store') }}" method="POST" class="bg-white p-4 rounded shadow-sm" enctype="multipart/form-data">
                @csrf

                <div class="row mb-3">
                    <div class="">
                        <label for="type" class="form-label">Type d'absence</label>
                        <select name="type_absence" id="type" class="form-control" required>
                            <option value="">-- Sélectionnez un type --</option>
                            <option value="maladie" {{ old('type_absence') == 'maladie' ? 'selected' : '' }}>Maladie</option>
                            <option value="retard" {{ old('type_absence') == 'retard' ? 'selected' : ''}}>Retard</option>
                            <option value="absence_non_justifiee" {{ old('type_absence')  == 'retard' ? 'selected' : '' }}>Absence non justifié</option>
                            <option value="rendez_vous" {{ old('type_absence') == 'rendez_vous' ? 'selected' : '' }}>Rendez-vous</option>
                            <option value="autre" {{ old('type_absence') == 'autre' ? 'selected' :'' }}>Autre</option>
                        </select>
                        {{-- <input type="text" name="" id="type" class="form-control" value="{{ old('nom') }}" required> --}}
                    </div>
                </div> 
                
                <div class="mb-3">
                    <label for="date_absence" class="form-label">Date d'absence</label>
                    <input type="date" name="date_absence" id="date_absence" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="motif" class="form-label">Motif</label>
                    <textarea name="motif" id="motif" class="form-control" >{{ old('motif') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="justificatif" class="form-label">Justificatif</label>
                     <input type="file" name="justificatif" class="form-control">
                </div>

                {{-- <div class="form-group mb-3" id="date_fin_field" style="display: none;">
                    <label>Justificatif (optionnel)</label>
                    <input type="file" name="justificatif" class="form-control">
                </div> --}}

                {{-- <div class="form-group mb-3">
                    <label for="salaire" class="form-label">Salaire</label>
                    <input type="number" name="salaire" class="form-control" required>
                </div> --}}

                <button type="submit" class="btn btn-primary">Soumettre</button>
            </form>
        </div>
    </div>
</section>
@endsection