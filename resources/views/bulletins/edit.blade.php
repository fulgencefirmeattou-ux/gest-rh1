@extends('layouts.base')
@section('title','Modifier le bulletin')

@section('content')
<div class="container">

    <div class="card shadow mt-5">
        <div class="card-header bg-primary  text-white">
            <h5>Bulletin - {{ $bulletin->employe->nom }} {{ $bulletin->employe->prenom }}</h5>
        </div>

        <div class="card-body">

            {{-- Salaire --}}
            <div class="mb-3">
                <label>Salaire de base</label>
                <input type="number" class="form-control" value="{{ $bulletin->salaire_base }}" readonly>
            </div>

            {{-- Primes --}}
            <h6>Primes</h6>
            @foreach($bulletin->items->where('type','prime') as $item)
                <div class="row mb-2">
                    <div class="col-md-8">
                        <input class="form-control" value="{{ $item->libelle }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <input class="form-control" value="{{ $item->montant }}" readonly>
                    </div>
                </div>
            @endforeach

            {{-- total heures supplémentaires --}}

            <h6 class="mt-3">Heures supplémentaires</h6>
            <div class="row mb-2">
                <div class="col-md-8">
                    <input class="form-control" value="heures supplémentaires" readonly>
                </div>
                <div class="col-md-4">
                    <input class="form-control" value="{{ $bulletin->montant_heures_sup }}" readonly>
                </div>
            </div>

            {{-- Retenues --}}
            <h6 class="mt-3">Retenues</h6>
            @foreach($bulletin->items->where('type','retenue') as $item)
                <div class="row mb-2">
                    <div class="col-md-8">
                        <input class="form-control" value="{{ $item->libelle }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <input class="form-control" value="{{ $item->montant }}" readonly>
                    </div>
                </div>
            @endforeach

            {{-- <div class="row mb-2">
                <div class="col-md-8">
                    <input class="form-control" value="Cotisations" readonly>
                </div>
                <div class="col-md-4">
                    <input class="form-control" value="{{ $bulletin->cotisations }}" readonly>
                </div>
                
            </div> --}}
            {{-- <div class="row mb-2">
                <div class="col-md-8">
                    <input class="form-control" value="CNPS" readonly>
                </div>
                <div class="col-md-4">
                    <input class="form-control" value="{{ $bulletin->montant_cnps }}" readonly>
                </div>
                
            </div> --}}



            <hr>


            <div class=" ms-3 ">
                <h5>Net à payer : {{ number_format($bulletin->net_a_payer,0,',',' ') }} FCFA</h5>
            </div>


            {{-- Paiement --}}
            
            <div class="m-3">
                <form method="POST" action="{{ route('bulletins.payer',$bulletin) }}">
                    @csrf
                    <button class="btn btn-primary w-100">
                        Marquer comme payé
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
