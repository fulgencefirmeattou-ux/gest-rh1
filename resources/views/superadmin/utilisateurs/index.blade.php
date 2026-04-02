@extends('layouts.app')

@section('content')

<section class="container mt-3">

    <h2 class="mb-4 text-start">Liste des employés</h2>
    <a href="{{ route('utilisateurs.create') }}" class="btn btn-primary mb-3">Créer un utilisateur</a>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

    @if ($users->isEmpty())
        <p class="text-center">Aucun utilisateur trouvé.</p>
    @else
        <div id="yearly-sales-collapse" class="collapse show">
            <div class="table-responsive">
                <table class="table table-nowrap table-hover mb-0 text-center" id="btn-editable">
                    <thead class="table-dark">
                        <tr>     
                            <th>#</th>              
                            <th>Nom et Prénoms</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email ?? '—' }}</td>
                                <td>{{ $user->role ?? '—' }}</td>

                                <td>  
                                    <a href="{{ route('utilisateurs.show', $user->id) }}" class="tabledit-edit-button btn btn-success active" style="float: none;">
                                   <span class="mdi mdi-eye"></span>
                                   </a>
                                   <a href="{{ route('utilisateurs.edit', $user->id) }}" class="tabledit-edit-button btn btn-primary active" style="float: none;">
                                       <span class="mdi mdi-pencil"></span>
                                   </a>
                                    <form action="{{ route('utilisateurs.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')"><span class="mdi mdi-trash-can"></span></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>        
        </div>
        
    @endif
</section>

@endsection