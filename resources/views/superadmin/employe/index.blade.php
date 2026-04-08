@extends('layouts.app')

@section('content')

<div class="container mt-3">

    <h2 class="mb-4 text-start">Liste des employés</h2>
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('employes.create') }}" class="btn btn-primary">Ajouter un employé</a>
        <a href="{{ route('employes.trashed') }}" class="btn btn-outline-secondary">
            <span class="mdi mdi-delete-restore"></span> Corbeille
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($employes->isEmpty())
        <p class="text-center">Aucun employé trouvé.</p>
    @else
        <div class="table-responsive">
            <table class="table table-sm table-nowrap table-hover mb-0 text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Matricule</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Poste</th>
                        <th>Département</th>
                        <th>Type de contrat</th>
                        <th>Date d'embauche</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employes as $employe)
                        <tr>
                            <td>{{ $employes->firstItem() + $loop->index }}</td>
                            <td>{{ $employe->matricule }}</td>
                            <td>{{ ucfirst($employe->nom) }} {{ ucfirst($employe->prenom) }}</td>
                            <td>{{ $employe->email ?? '—' }}</td>
                            <td>{{ $employe->telephone ?? '—' }}</td>
                            <td>{{ $employe->poste->name ?? '—' }}</td>
                            <td>{{ $employe->departement->nom ?? '—' }}</td>
                            <td>{{ $employe->typeContrat->name ?? '—' }}</td>
                            <td>{{ $employe->date_embauche ? \Carbon\Carbon::parse($employe->date_embauche)->format('d/m/Y') : '—' }}</td>
                            <td>
                                <a href="{{ route('employes.show', $employe->id) }}" class="btn btn-sm btn-success">
                                    <span class="mdi mdi-eye"></span>
                                </a>
                                <a href="{{ route('employes.edit', $employe->id) }}" class="btn btn-sm btn-primary">
                                    <span class="mdi mdi-pencil"></span>
                                </a>
                                <form action="{{ route('employes.destroy', $employe->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-warning" onclick="return confirm('Archiver cet utilisateur ?')"><span class="mdi mdi-archive"></span></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $employes->links() }}
        </div>
    @endif
</div>

@endsection
