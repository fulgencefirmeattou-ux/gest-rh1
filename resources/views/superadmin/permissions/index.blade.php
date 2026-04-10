@extends('layouts.app')

@section('content')
<div class="container mt-3">
    {{-- <form action="{{ route('users.liste') }}" method="GET" class="mb-3">
        <input type="text" name="search" placeholder="Rechercher..." class="form-control" value="{{ request('search') }}">
    </form> --}}

    <h2 class="mb-4 text-start">Liste des permissions</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    

    @if ($permissions->isEmpty())
        <p class="text-center">Vous avez aucune permission</p>
    @else
        <div id="yearly-sales-collapse" class="collapse show">
            <div class="table-responsive">
                <table class="table table-nowrap table-hover mb-0 text-center" id="btn-editable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Permission</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $permission)
                            <tr>
                                <td>{{ $permissions->firstItem() + $loop->index }}</td>
                                <td>{{ $permission->name }}</td>
                                <td>
                                    <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-sm btn-outline-primary">
                                        <span class="mdi mdi-pencil"></span>
                                    </a>
                                    <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="d-inline">
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
        <div class="mt-3">
            {{ $permissions->links() }}
        </div>
    @endif
</div>
@endsection
                               