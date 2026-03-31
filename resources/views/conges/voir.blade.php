@extends('layouts.base')
@section('title','liste des congés')
@section('content')
    <div class="container mt-5">
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
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-4 text-start">Mes demandes de congés</h2>
            <a href="{{ route('conges.create-conge') }}" class="btn btn-primary mb-3 ">Nouvelle demande</a>
        </div>
            @if ($conges->isEmpty())
                <p class="text-center">Aucune demande de congé trouvé.</p>
            @else
                <div id="yearly-sales-collapse" class="collapse show">
                    <div class="table-responsive">
                        <table class="table table-nowrap table-hover mb-0 text-center" id="btn-editable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Type</th>
                                    <th>Debut</th>
                                    <th>Fin</th>
                                    <th>Nombre de jours</th>
                                    <th>Date de reprise</th>
                                    <th>Statut</th>
                                    <th>Commentaire</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($conges as $index => $conge)
                                    <tr>
                                        <td>{{ $conge->type_conge }}</td>
                                        <td>{{ $conge->date_debut_conge->format('d/m/Y') }}</td>
                                        <td>{{ $conge->date_fin_conge->format('d/m/Y') }}</td>
                                        <td>{{ $conge->jours_ouvres }} jours</td>
                                        <td>{{ $conge->date_retour->format('d/m/Y') ?? '_' }} </td>
                                        <td>{{ $conge->statut }}</td>
                                        <td>{{ \Illuminate\Support\Str::words($conge->commentaire, 5 , '...') ?? '_' }}</td>
                                        <td>
                                            <a href="{{ route('conges.details',$conge->id ) }}" class="btn btn-sm btn-info">
                                                Voir
                                            </a>

                                            @if ($conge->statut === 'modification_requested')
                                            <a href="#" class="btn btn-sm btn-warning">
                                                Modifier
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

            

        {{-- <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Debut</th>
                    <th>Fin</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($conges as $conge)
                <tr>
                    <td>{{ $conge->type_conge }}</td>
                    <td>{{ $conge->date_debut_conge }}</td>
                    <td>{{ $conge->date_fin_conge }}</td>
                    <td>{{ $conge->statut }}</td>
                    <td>
                        {{-- <a href="{{ route('conges.show', $conge->id) }}" class="btn btn-sm btn-info">
                            Voir
                        </a>

                        @if ($leave->status === 'modification_requested')
                        <a href="{{ route('leaves.edit', $leave->id) }}" class="btn btn-sm btn-warning">
                            Modifier
                        </a>
                        @endif 
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table> --}}
    </div>
@endsection
