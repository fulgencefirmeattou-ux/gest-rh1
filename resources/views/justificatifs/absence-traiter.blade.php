@extends('layouts.base')
@section('title', 'Traitement des absences')
@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-4 text-start">Les demandes d'absences traitées</h2>
            {{-- <a href="{{ route('justificatifs.absence') }}" class="btn btn-primary mb-3 ">Justifié mon absence</a> --}}
        </div>
            @if ($absences->isEmpty())
                <p class="text-center">Aucune demande de justificatifs d'absences traité.</p>
            @else
                <div id="yearly-sales-collapse" class="collapse show">
                    <div class="table-responsive">
                        <table class="table table-nowrap table-hover mb-0 text-center" id="btn-editable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Employé</th>
                                    <th>Type</th>
                                    <th>Date d'absence</th>
                                    {{-- <th>Fin</th> --}}
                                    {{-- <th>Nombre de jours</th> --}}
                                    {{-- <th>Date de reprise</th> --}}
                                    <th>Motif</th>
                                    <th>justificatif</th>
                                    <th>Statut</th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($absences as $index => $absence)
                                    <tr>
                                        <td>{{ $absence->employe->nom }} {{ $absence->employe->prenom }}</td>
                                        <td>{{ $absence->type_absence }}</td>
                                        <td>{{ $absence->date_absence->format('d/m/Y') }}</td>
                                        {{-- <td>{{ $conge->date_fin_conge->format('d/m/Y') }}</td> --}}
                                        {{-- <td>{{ $conge->jours_ouvres }} jours</td> --}}
                                        {{-- <td>{{ $absence->date_retour ?? '_' }} </td> --}}
                                        <td>{{ \Illuminate\Support\Str::words($absence->motif, 5 , '...') ?? '_' }}</td>
                                        <td>
                                            @if($absence->justificatif)
                                                <a href="{{ route('justificatifs.absence.download', $absence->id) }}" 
                                                target="_blank" 
                                                class="btn btn-primary">
                                                Voir le justificatif
                                                </a>
                                            @else
                                                Aucun justificatif
                                            @endif
                                        </td>
                                        <td>

                                            {{-- {{ $absence->statut }} --}}
                                            
                                            @if( $absence->statut  == 'en_attente')
                                                <span class="badge bg-warning">En attente</span>
                                            @elseif( $absence->statut == 'validee')
                                                <span class="badge bg-success">Validée</span>
                                            @else
                                                <span class="badge bg-danger">Refusée</span>
                                            @endif 
                                        </td>
                                        {{-- <td> on verra plus tard
                                            <a href="{{ route('conges.details',$conge->id ) }}" class="btn btn-sm btn-info">
                                                Voir
                                            </a>

                                            @if ($conge->statut === 'modification_requested')
                                            <a href="#" class="btn btn-sm btn-warning">
                                                Modifier
                                            </a>
                                            @endif  
                                        </td>--}}
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