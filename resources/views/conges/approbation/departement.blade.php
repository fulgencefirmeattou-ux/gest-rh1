@extends('layouts.base')
@section('title','liste des congés')
@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-4 text-start">Demandes à valider — Responsable de departement</h2>
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
                                        <td>{{ $conge->type_conge }}</td>
                                        <td>{{ $conge->date_debut_conge->format('d/m/Y')  }}</td>
                                        <td>{{ $conge->date_fin_conge->format('d/m/Y')  }}</td>
                                        <td>{{ $conge->jours_ouvres }} jours</td>
                                        <td> {{ $conge->statut }}</td>
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
                                        <td>

                                            <!-- BTN PRINCIPAL : ouvrir menu des actions -->
                                            <div class="btn-group">
                                                <button class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Actions</button>

                                                <ul class="dropdown-menu">

                                                    <li>
                                                        <a class="dropdown-item text-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#approveModal-{{ $conge->id }}">
                                                        Approuver
                                                        </a>
                                                    </li>

                                                    {{-- <li>
                                                        <a class="dropdown-item text-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modifyModal-{{ $conge->id }}">
                                                        Demander modification
                                                        </a>
                                                    </li> --}}

                                                    <li>
                                                        <a class="dropdown-item text-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#rejectModal-{{ $conge->id }}">
                                                        Rejeter
                                                        </a>
                                                    </li>

                                                </ul>
                                            </div>

                                            <!-- MODAL APPROUVER -->
                                            <div class="modal fade" id="approveModal-{{ $conge->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="POST" action="{{ route('departement.conge.traiter', $conge->id) }}">
                                                            @csrf
                                                            <input type="hidden" name="action" value="approve">

                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Approuver la demande</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                Voulez-vous vraiment approuver cette demande ?
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                <button class="btn btn-success">Approuver</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- MODAL MODIFIER -->
                                            {{-- <div class="modal fade" id="modifyModal-{{ $conge->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="POST" action="{{ route('service.conge.traiter', $conge->id) }}">
                                                            @csrf
                                                            <input type="hidden" name="action" value="modify">

                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Demander une modification</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <label class="form-label">Commentaire :</label>
                                                                <textarea name="commentaire" class="form-control" required placeholder="Détaillez ce qui doit être modifié"></textarea>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                <button class="btn btn-warning">Envoyer</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div> --}}

                                            <!-- MODAL REJETER -->
                                            <div class="modal fade" id="rejectModal-{{ $conge->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="POST" action="{{ route('departement.conge.traiter', $conge->id) }}">
                                                            @csrf
                                                            <input type="hidden" name="action" value="reject">

                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Rejeter la demande</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <label class="form-label">Motif du rejet :</label>
                                                                <textarea name="commentaire" class="form-control" required placeholder="Expliquez pourquoi"></textarea>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                <button class="btn btn-danger">Rejeter</button>
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