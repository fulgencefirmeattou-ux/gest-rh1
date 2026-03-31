@extends('layouts.base')
@section('title','liste des employés')
@section('content')
<div class="container mt-5">
    {{-- <form action="{{ route('users.liste') }}" method="GET" class="mb-3">
        <input type="text" name="search" placeholder="Rechercher..." class="form-control" value="{{ request('search') }}">
    </form> --}}

    <h2 class="mb-4 text-start">Liste des employés</h2>

    @if ($employes->isEmpty())
        <p class="text-center">Aucun employé trouvé.</p>
    @else
        <div id="yearly-sales-collapse" class="collapse show">
            <div class="table-responsive">
                <table class="table table-nowrap table-hover mb-0 text-center" id="btn-editable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Poste</th>
                            <th>Service</th>
                            {{-- <th>Departement</th>
                            <th>Type de contrat</th>
                            <th>Date d'embauche</th>
                            <th>Date de fin de contrat</th> --}}
                            <th>Date d'enregistrement</th>
                            {{-- <th>Dernière connexion</th> --}}
                            {{-- <th>Action</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employes as $index => $employe)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $employe->matricule }}</td>
                                <td>{{ ucfirst($employe->nom) }} {{ ucfirst($employe->prenom) ?? '_' }} </td>
                                <td>{{ $employe->email ?? '—' }}</td>
                                <td>{{ $employe->phone ?? '—' }}</td>
                                <td>{{ $employe->poste ?? 'aucun poste' }}</td>
                                <td>{{ $employe->service->nom ?? '_' }}</td>
                                {{-- <td>{{ $employe->service->departement->nom ?? '_' }}</td>
                                <td>{{ $employe->type_contrat ?? '-' }}</td> --}}
                                {{-- <td>{{ $employe->date_embauche ? $employe->date_embauche->format('d/m/Y') : '-' }}</td>
                                <td>{{ $employe->date_fin ? $employe->date_fin->format('d/m/Y') : '-' }}</td> --}}
                                <td>{{ $employe->created_at->format('d/m/Y') }}</td>
                                {{-- <td>{{ $employe->user->date_connexion?->format('d/m/Y') ?? '-' }}</td> --}}
                                {{-- {{ route('users.voir', $user->id) }} --}}
                                {{-- <td>  <a href="{{ route('employe.voir', $employe->id) }}" class="tabledit-edit-button btn btn-success active" style="float: none;">
                                   <span class="mdi mdi-pencil"></span>
                                   </a>
                                </td> --}}
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>        
        </div>
        
    @endif
</div>
@endsection