@extends('layouts.app')

@section('content')

<div class="container mt-3">
    {{-- <form action="{{ route('users.liste') }}" method="GET" class="mb-3">
        <input type="text" name="search" placeholder="Rechercher..." class="form-control" value="{{ request('search') }}">
    </form> --}}

    <h2 class="mb-4 text-start">Liste des rôles</h2>

     <a href="{{ route('roles.create') }}" class="btn btn-secondary mb-3">
                    Ajouter un rôle
                </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    

    @if ($roles->isEmpty())
        <p class="text-center">Vous avez aucune rôle</p>
    @else
        <div id="yearly-sales-collapse" class="collapse show">
            <div class="table-responsive">
                <table class="table table-nowrap table-hover mb-0 text-center" id="btn-editable">
                    <thead class="table-dark">
                        <tr>
                            <th>Rôle</th>
                            <th>Permissions</th>
                            <th>Actions</th>
                        
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td class="text-center align-middle fw-bold">{{ $role->name }}</td>
                                <td>
                                    {{-- @foreach($role->permissions as $permission)
                                        <span class="badge bg-info text-dark">{{ $permission->name }}</span>
                                    @endforeach --}}

                                    <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px;">
                                        @foreach($role->permissions as $permission)
                                            <div>{{ $permission->name }}</div>
                                        @endforeach
                                    </div>
                                </td>

                                <td class="text-center align-middle">
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-primary">
                                        <span class="mdi mdi-pencil"></span>
                                    </a>
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette permission ?')"><span class="mdi mdi-trash-can"></span></button>
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