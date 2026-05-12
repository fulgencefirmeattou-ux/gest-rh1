@extends('layouts.app')

@section('content')

 <section class="row">
        <div class="col-12 col-lg-12">
            <div class="container py-5">
                <h2 class="mb-4 text-start">Créer une permission</h2>

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

                <form action="{{ route('permissions.store') }}" method="POST" class="bg-white p-4 rounded shadow-sm">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom de la permission</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                    </div>


                    <button type="submit" class="btn btn-primary w-100">Créer la permission</button>
                </form>
                
            </div>
        </div>

    </section>
@endsection