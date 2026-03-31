@extends('layouts.base')
@section('title','liste des employés')
@section('content')
<div class="container mt-5">
    {{-- <form action="{{ route('users.liste') }}" method="GET" class="mb-3">
        <input type="text" name="search" placeholder="Rechercher..." class="form-control" value="{{ request('search') }}">
    </form> --}}

    <h2 class="mb-4 text-start">Liste des paie</h2>

    @if ($bulletins->isEmpty())
        <p class="text-center">Aucun paiement en attente.</p>
    @else
        <div id="yearly-sales-collapse" class="collapse show">
            <div class="table-responsive">
                <table class="table table-nowrap table-hover mb-0 text-center" id="btn-editable">
                    <thead class="table-dark">
                        <tr>
                            <th>Employé</th>
                            <th>Mois</th>
                            <th>Salaire Net</th>
                            <th>Prime</th>
                            <th>CNPS</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bulletins as $bulletin)
                            <tr>
                                <td>{{ $bulletin->employe->nom }}</td>
                                <td>{{ $bulletin->mois }}</td>
                                <td>{{ number_format($bulletin->net_a_payer,0,' ',' ') }} FCFA</td>
                                <td>{{ number_format($bulletin->items->where('type', 'prime')->sum('montant'),0,' ',' ') }} FCFA</td>
                                <td>{{ number_format($bulletin->items->where('type', 'cotisation')->sum('montant'),0,' ',' ') }} FCFA</td>
                                <td>
                                    <span class="badge bg-{{ $bulletin->statut=='payé'?'success':'warning' }}">
                                        {{ ucfirst($bulletin->statut) }}
                                    </span>
                                </td>
                              
                                
                                <td>
                                    @if($bulletin->statut=='brouillon')
                                        <a href="{{ route('bulletins.edit',$bulletin) }}" class="btn btn-sm btn-primary">
                                            Modifier
                                        </a>
                                    @else
                                        <a href="{{ route('bulletins.download',$bulletin) }}" class="btn btn-sm btn-success">
                                            PDF
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>        
        </div>
        
    @endif
</div>
@endsection