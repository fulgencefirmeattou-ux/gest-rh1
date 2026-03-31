@extends('layouts.base')
@section('title','liste des utilisateurs')
@section('content')
    <section class="row">
        <div class="col-12 col-lg-12"></div>
            <div class="container py-5">
                <h2 class="mb-4 text-start">Nouvelle demande de congé</h2>
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
                <form method="POST" action="{{ route('conges.store') }}" class="bg-white p-4 rounded shadow-sm">
                    @csrf

                    <label>Type de congé</label>
                    <select name="type_conge" class="form-select" required>
                        <option value="annuel">Annuel</option>
                        <option value="special">Spécial</option>
                        <option value="exceptionnel">Exceptionnel</option>
                    </select><br>

                    <label>Date de début</label>
                    <input type="date" name="date_debut_conge" class="form-control"><br>

                    <label>Date de fin</label>
                    <input type="date" name="date_fin_conge" class="form-control"><br>

                    <label>Date de retour</label>
                    <input type="date" name="date_retour" class="form-control"><br>

                    <label>Motif</label>
                    <textarea name="raison" class="form-control"></textarea><br>

                    <button class="btn btn-primary w-100">Soumettre ma demande</button>
                </form>
            </div>
        </div>
    </section>
    
@endsection