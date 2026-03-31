@extends('layouts.base')
@section('title','créer un utilisateur')
@section('content')
<div class="container mt-5">
    {{-- <form action="{{ route('users.liste') }}" method="GET" class="mb-3">
        <input type="text" name="search" placeholder="Rechercher..." class="form-control" value="{{ request('search') }}">
    </form> --}}

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h2 class="mb-4 text-start">Liste des utilisateurs</h2>

    @if ($users->isEmpty())
        <p class="text-center">Aucun utilisateur trouvé.</p>
    @else
        <div id="yearly-sales-collapse" class="collapse show">
            <div class="table-responsive">
                @php(auth()->user()->refresh())

                <table class="table table-nowrap table-hover mb-0 text-center" id="btn-editable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            {{-- <th>Nom</th> --}}
                            <th>login</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Date d'inscription</th>
                            <th>Dernière connexion</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                {{-- <td>{{ ucfirst($user->nom) }} {{ ucfirst($user->prenom) ?? '_' }} </td> --}}
                                <td>{{ $user->login ?? '—' }}</td>
                                <td>{{ $user->email ?? '—' }}</td>
                                <td>{{ $user->role ?? 'Utilisateur' }}</td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>{{ $user->date_connexion ? $user->date_connexion->format('d/m/Y H:i') : 'Jamais connecté' }}</td>

                                <td>  
                                    <a href="{{ route('users.voir', $user->id) }}" class="tabledit-edit-button btn btn-success active" style="float: none;">
                                        <span class="mdi mdi-pencil"></span>
                                    </a>
                                    <!-- Bouton supprimer -->
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger " title="Supprimer">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
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