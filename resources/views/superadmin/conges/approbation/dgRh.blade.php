@extends('layouts.app')
@section('title','liste des congés')
@section('content')
    <div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-4 text-start">Demandes à valider — Dg/Rh</h2>
            {{-- <a href="{{ route('conges.create-conge') }}" class="btn btn-primary mb-3 ">Nouvelle demande</a> --}}
        </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($conges->isEmpty())
                <p class="text-center">Aucune demande de congé trouvé.</p>
            @else
                <div id="yearly-sales-collapse" class="collapse show">
                    <div class="table-responsive">
                        <table class="table table-nowrap table-hover mb-0 text-center" id="btn-editable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Employé</th>
                                    <th>Type</th>
                                    <th>Debut</th>
                                    <th>Fin</th>
                                    <th>Nbre de jour</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($conges as $index => $conge)
                                    <tr>
                                        <td>{{ $conge->employe->nom }} {{ $conge->employe->prenom }}</td>
                                        <td><span class="badge bg-secondary">{{ $conge->typeLabel() }}</span></td>
                                        <td>{{ $conge->date_debut_conge->format('d/m/Y')  }}</td>
                                        <td>{{ $conge->date_fin_conge->format('d/m/Y')  }}</td>
                                        <td>{{ $conge->jours_ouvres }} jours</td>
                                        <td><span class="badge bg-{{ $conge->statutColor() }}">{{ $conge->statutLabel() }}</span></td>
                                        {{-- <td>
                                           
                                            <form method="POST" action="/service/conge/{{ $conge->id }}/approve">
                                                @csrf
                                                <button class="btn btn-success btn-sm">Approuver</button>
                                            </form>

                                            <form method="POST" action="/service/conge/{{ $conge->id }}/modify">
                                                @csrf
                                                <textarea name="commentaire" class="form-control mt-1" placeholder="Demande de modification"></textarea>
                                                <button class="btn btn-warning btn-sm mt-1">Demander une modification</button>
                                            </form>

                                            <form method="POST" action="/service/conge/{{ $conge->id }}/reject">
                                                @csrf
                                                <textarea name="commentaire" class="form-control mt-1" placeholder="Motif du rejet"></textarea>
                                                <button class="btn btn-danger btn-sm mt-1">Rejeter</button>
                                            </form>
                                        </td> --}}
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">

                                                {{-- Approuver --}}
                                                <form method="POST" action="{{ route('dg.conge.traiter', $conge->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="action" value="approve">
                                                    <button class="btn btn-success btn-sm" title="Approuver">
                                                        <i class="ri-check-line"></i>
                                                    </button>
                                                </form>

                                                {{-- Rejeter --}}
                                                <button class="btn btn-danger btn-sm" title="Rejeter"
                                                        data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $conge->id }}">
                                                    <i class="ri-close-line"></i>
                                                </button>
                                            </div>

                                            {{-- Modal rejet --}}
                                            <div class="modal fade" id="rejectModal-{{ $conge->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="POST" action="{{ route('dg.conge.traiter', $conge->id) }}">
                                                            @csrf
                                                            <input type="hidden" name="action" value="reject">
                                                            <div class="modal-header">
                                                                <h6 class="modal-title">Rejeter — {{ $conge->employe->nom }} {{ $conge->employe->prenom }}</h6>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <label class="form-label">Motif du rejet (optionnel)</label>
                                                                <textarea name="commentaire" class="form-control" rows="3" placeholder="Expliquez pourquoi..."></textarea>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                                                                <button class="btn btn-danger btn-sm"><i class="ri-close-line me-1"></i>Rejeter</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
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