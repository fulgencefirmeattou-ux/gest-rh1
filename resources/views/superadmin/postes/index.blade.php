@extends('layouts.app')

@section('content')

<div class="container mt-3">
    <h2 class="mb-4 text-start">Liste des postes</h2>
    <a href="{{ route('postes.create') }}" class="btn btn-primary mb-3">Créer un poste</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($postes->isEmpty())
        <p class="text-center">Aucun poste trouvé.</p>
    @else
        <div class="table-responsive">
            <table class="table table-nowrap table-hover mb-0 text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($postes as $poste)
                        <tr>
                            <td>{{ $postes->firstItem() + $loop->index }}</td>
                            <td>{{ $poste->name }}</td>
                            <td>{{ $poste->description ?? '—' }}</td>
                            <td>
                                <a href="{{ route('postes.show', $poste->id) }}" class="btn btn-success btn-sm">
                                    <span class="mdi mdi-eye"></span>
                                </a>
                                <a href="{{ route('postes.edit', $poste->id) }}" class="btn btn-primary btn-sm">
                                    <span class="mdi mdi-pencil"></span>
                                </a>
                                <form action="{{ route('postes.destroy', $poste->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce poste ?')">
                                        <span class="mdi mdi-trash-can"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $postes->links() }}
        </div>
    @endif
</div>

@endsection
