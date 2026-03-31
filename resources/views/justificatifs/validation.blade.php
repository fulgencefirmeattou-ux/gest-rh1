@extends('layouts.base')
@section('title', 'Validation des absences')

@section('content')
<div class="container">

    <h3 class="mt-5 mb-4">Absences des employés</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div id="yearly-sales-collapse" class="collapse show">
        <div class="table-responsive">
            <table class="table table-nowrap table-hover mb-0 text-center" id="btn-editable">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Employé</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Motif</th>
                        <th>Justificatif</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($absences as $absence)
                        <tr>
                            <td>{{ $absence->employe->nom }} {{ $absence->employe->prenom }}</td>
                            <td>{{ $absence->date_absence }}</td>
                            <td>{{ $absence->type_absence }}</td>
                            <td>{{ $absence->motif ?? '-' }}</td>

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
                                @if($absence->statut == 'en_attente')
                                    <span class="badge bg-warning">En attente</span>
                                @elseif($absence->statut == 'valide')
                                    <span class="badge bg-success">Validée</span>
                                @else
                                    <span class="badge bg-danger">Refusée</span>
                                @endif
                            </td>

                            <td>
                                @if($absence->statut == 'en_attente')
                                    
                                    <!-- Valider -->
                                    <form action="{{ route('absence.approve', $absence->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-success btn-sm">Valider</button>
                                    </form>

                                    <!-- Refuser (avec modal) -->
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#refuserModal{{ $absence->id }}">
                                        Refuser
                                    </button>

                                    <!-- Modal de refus -->
                                    <div class="modal fade" id="refuserModal{{ $absence->id }}">
                                        <div class="modal-dialog">
                                            <form action="{{ route('absence.reject', $absence->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Motif du refus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <textarea name="motif_refus" class="form-control" required placeholder="Raison du refus"></textarea>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <button class="btn btn-danger">Refuser</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
    
   

</div>
@endsection
