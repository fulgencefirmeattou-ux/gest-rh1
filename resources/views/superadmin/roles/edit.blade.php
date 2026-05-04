@extends('layouts.app')

@section('content')

 <section class="row">
        <div class="col-12 col-lg-12">
            <div class="container py-3">
                <h2 class="mb-4 text-start">Modifier le rôle</h2>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('roles.update', $role->id) }}" method="POST" class="bg-white p-4 rounded shadow-sm">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom du rôle</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $role->name) }}" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="permissions" class="form-label">Permissions</label>
                        <div class="row g-3">
                           @foreach ($permissions as $permission)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm-{{ $permission->id }}"
                                        {{ in_array($permission->name, $role->permissions->pluck('name')->toArray()) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perm-{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>


                    <button type="submit" class="btn btn-primary w-100">Enregistrer les modifications</button>
                </form>
            </div>
        </div>

    </section>

@endsection