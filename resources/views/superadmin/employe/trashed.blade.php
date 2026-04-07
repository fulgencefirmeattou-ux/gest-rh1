@extends('layouts.app')

@section('content')

<div class="container mt-3">
    <h2 class="mb-4 text-start">Corbeille — Employés archivés</h2>
    <a href="{{ route('employes.index') }}" class="btn btn-secondary mb-3">Retour à la liste</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($employes->isEmpty())
        <p class="text-center">Aucun employé archivé.</p>
    @else
        <div class="table-responsive">
            <table class="table table-nowrap table-hover mb-0 text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Matricule</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Poste</th>
                        <th>Département</th>
                        <th>Archivé le</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employes as $index => $employe)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $employe->matricule }}</td>
                            <td>{{ ucfirst($employe->nom) }} {{ ucfirst($employe->prenom) }}</td>
                            <td>{{ $employe->email ?? '—' }}</td>
                            <td>{{ $employe->poste->name ?? '—' }}</td>
                            <td>{{ $employe->departement->nom ?? '—' }}</td>
                            <td>{{ $employe->deleted_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <form action="{{ route('employes.restore', $employe->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Restaurer cet employé ?')">
                                        <span class="mdi mdi-restore"></span>
                                    </button>
                                </form>
                                <form action="{{ route('employes.forceDelete', $employe->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer définitivement ? Cette action est irréversible.')">
                                        <span class="mdi mdi-trash-can"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
