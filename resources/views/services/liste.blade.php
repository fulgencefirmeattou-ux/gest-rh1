@extends('layouts.base')
@section('title','liste des utilisateurs')
@section('content')
    <div class="container mt-5">
        {{-- <form action="{{ route('users.liste') }}" method="GET" class="mb-3">
            <input type="text" name="search" placeholder="Rechercher..." class="form-control" value="{{ request('search') }}">
        </form> --}}

        <h2 class="mb-4 text-start">Liste des services</h2>

        @if ($services->isEmpty())
            <p class="text-center">Aucun service trouvé.</p>
        @else
            <div id="yearly-sales-collapse" class="collapse show">
                <div class="table-responsive">
                    <table class="table table-nowrap table-hover mb-0 text-center" id="btn-editable">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Departement</th>
                                <th>Responsable</th>
                                <th>Date de création</th>
                                {{-- <th>Date d'inscription</th>
                                <th>Dernière connexion</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $index => $service)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ ucfirst($service->nom) }}  </td>
                                    <td>{{ \Illuminate\Support\Str::words($service->description, 5, '...' ) ?? '—' }}</td>
                                    <td>{{ $service->departement->nom ?? '_' }}</td>
                                    {{-- <td>{{ $departement->phone ?? '—' }}</td> --}}
                                    <td>{{  $service->responsable ? $service->responsable->nom . ' ' . $service->responsable->prenom : '_'}}</td>
                                    <td>{{ $service->created_at->format('d/m/Y') }}</td>
                                    {{-- <td>{{ $departement->date_connexion?->format('d/m/Y') ?? '-' }}</td> --}}
                                    {{-- {{ route('departements.voir', $departement->id) }} --}}
                                    <td>  
                                        <a href="{{ route('service.voir', $service->id) }}" class="tabledit-edit-button btn btn-success active" style="float: none;">
                                            <span class="mdi mdi-pencil"></span>
                                        </a>
                                        <form action="{{ route('service.destroy', $service->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer cet service ?');">
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