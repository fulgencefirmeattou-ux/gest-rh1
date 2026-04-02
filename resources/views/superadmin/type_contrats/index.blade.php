@extends('layouts.app')

@section('content')

<div class="container mt-3">
    <h2 class="mb-4 text-start">Liste des types de contrat</h2>
    <a href="{{ route('type_contrats.create') }}" class="btn btn-primary mb-3">Créer un type de contrat</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($type_contrats->isEmpty())
        <p class="text-center">Aucun type de contrat trouvé.</p>
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
                    @foreach ($type_contrats as $index => $type_contrat)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $type_contrat->name }}</td>
                            <td>{{ $type_contrat->description ?? '—' }}</td>
                            <td>
                                <a href="{{ route('type_contrats.show', $type_contrat->id) }}" class="btn btn-success btn-sm">
                                    <span class="mdi mdi-eye"></span>
                                </a>
                                <a href="{{ route('type_contrats.edit', $type_contrat->id) }}" class="btn btn-primary btn-sm">
                                    <span class="mdi mdi-pencil"></span>
                                </a>
                                <form action="{{ route('type_contrats.destroy', $type_contrat->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce type de contrat ?')">
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
