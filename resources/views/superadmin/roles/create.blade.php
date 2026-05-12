@extends('layouts.app')

@section('content')

<section class="row">
        <div class="col-12 col-lg-12">
            <div class="container py-3">
                <h2 class="mb-4 text-start">Créer un rôle</h2>

               

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('roles.store') }}" method="POST" class="bg-white p-4 rounded shadow-sm">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom du rôle</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                        
                    </div>

                    <div class="mb-3">
                        <label for="permissions" class="form-label">Permissions</label>
                        <div class="row g-3">
                            @foreach($permissions as $permission)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm-{{ $permission->id }}">
                                        <label class="form-check-label" for="perm-{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>


                    <button type="submit" class="btn btn-primary w-100">Créer le rôle</button>
                </form>
                
            </div>
        </div>

    </section>

@endsection