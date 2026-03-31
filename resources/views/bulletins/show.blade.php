@extends('layouts.base')
@section('title','Bulletin de paie')
@section('content')
<div class="container">
    <div class="card shadow-sm mt-4">
        <div class="card-header d-flex justify-content-between">
            <h4>Bulletin – {{ $bulletin->mois }}/{{ $bulletin->annee }}</h4>
            <a href="{{ route('bulletins.download',$bulletin->id) }}" class="btn btn-primary"> Télécharger PDF</a>
        </div>
        <div class="card-body">
            <p><strong>Employé :</strong> {{ $bulletin->employe->nom }}{{ $bulletin->employe->prenom }}</p>
            <p><strong>Salaire brut :</strong> {{ number_format($bulletin->salaire_brut,0,',',' ') }} FCFA</p>
            <p><strong>Primes :</strong> {{ number_format($bulletin->total_primes,0,',',' ') }} FCFA</p>
            <p><strong>Retenues :</strong> {{ number_format($bulletin->total_retenues,0,',',' ') }} FCFA</p>
            <hr>
            <h5>Net à payer : {{ number_format($bulletin->net_a_payer,0,',','') }} FCFA</h5>
        </div>
        <div class="card-footer">
            <a href="{{ route('bulletins.index') }}" class="btn btnsecondary">Retour</a>
        </div>
    </div>
</div>
@endsection
