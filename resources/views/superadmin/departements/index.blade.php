@extends('layouts.app')

@section('content')

<div class="container mt-3">

    <h2 class="mb-4 text-start">Liste des départements</h2>
    <a href="{{ route('departements.create') }}" class="btn btn-primary mb-3">Créer un département</a>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

    @if ($departements->isEmpty())
        <p class="text-center">Aucun département trouvé.</p>
    @else
        <div id="yearly-sales-collapse" class="collapse show">
            <div class="table-responsive">
                <table class="table table-nowrap table-hover mb-0 text-center" id="btn-editable">
                    <thead class="table-dark">
                        <tr>     
                            <th>#</th>              
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Responsable</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departements as $index => $departement)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $departement->nom }}</td>
                                <td>{{ $departement->description ?? '—' }}</td>
                                <td>{{ $departement->responsable->name ?? '—' }}</td>

                                <td>  
                                    <a href="{{ route('departements.show', $departement->id) }}" class="tabledit-edit-button btn btn-success active" style="float: none;">
                                   <span class="mdi mdi-eye"></span>
                                   </a>
                                   <a href="{{ route('departements.edit', $departement->id) }}" class="tabledit-edit-button btn btn-primary active" style="float: none;">
                                       <span class="mdi mdi-pencil"></span>
                                   </a>
                                    <form action="{{ route('departements.destroy', $departement->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce département ?')"><span class="mdi mdi-trash-can"></span></button>
                                    </form>
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