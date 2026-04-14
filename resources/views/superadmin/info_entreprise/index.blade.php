@extends('layouts.app')

@section('content')
<div class="container-fluid mt-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="ri-building-line me-2"></i>Informations de l'entreprise</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="ri-checkbox-circle-line me-1"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white fw-semibold py-2">
                    <i class="ri-edit-line me-1"></i>Fiche entreprise
                </div>
                <div class="card-body">

                    <form method="POST" action="{{ route('info-entreprise.update') }}">
                        @csrf
                        @method('PUT')

                        {{-- Champs --}}
                        <div class="row g-3">

                            <div class="col-12">
                                <label class="form-label small fw-semibold">Nom de l'entreprise</label>
                                <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                       value="{{ old('nom', $info->nom) }}"
                                       placeholder="Ex : FIRME ATTOU & CO">
                                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-semibold">Adresse</label>
                                <input type="text" name="adresse" class="form-control @error('adresse') is-invalid @enderror"
                                       value="{{ old('adresse', $info->adresse) }}"
                                       placeholder="Ex : 17 BP 184 Abidjan 17">
                                @error('adresse')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-semibold">Siège social</label>
                                <input type="text" name="siege_social" class="form-control @error('siege_social') is-invalid @enderror"
                                       value="{{ old('siege_social', $info->siege_social) }}"
                                       placeholder="Ex : Bingerville - Cité FDFP - Villa 67">
                                @error('siege_social')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">N° CNPS</label>
                                <input type="text" name="num_cnps" class="form-control @error('num_cnps') is-invalid @enderror"
                                       value="{{ old('num_cnps', $info->num_cnps) }}"
                                       placeholder="Ex : XXXXXX">
                                @error('num_cnps')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">N° Contribuable</label>
                                <input type="text" name="num_contribuable" class="form-control @error('num_contribuable') is-invalid @enderror"
                                       value="{{ old('num_contribuable', $info->num_contribuable) }}"
                                       placeholder="Ex : XXXXXXXX">
                                @error('num_contribuable')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">E-mail</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $info->email) }}"
                                       placeholder="Ex : contact@entreprise.ci">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Téléphone</label>
                                <input type="text" name="telephone" class="form-control @error('telephone') is-invalid @enderror"
                                       value="{{ old('telephone', $info->telephone) }}"
                                       placeholder="Ex : +225 07 56 47 46">
                                @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                        </div>

                        <div class="mt-4 d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Sauvegarder
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            {{-- Aperçu --}}
            {{-- @if($info->exists)
            <div class="card shadow-sm mt-3">
                <div class="card-header py-2 fw-semibold">
                    <i class="ri-eye-line me-1"></i>Aperçu (tel qu'affiché sur les bulletins)
                </div>
                <div class="card-body" style="font-size:13px;">
                    <div class="fw-bold text-primary mb-2">{{ $info->nom ?: '—' }}</div>
                    <table class="table table-borderless table-sm mb-0" style="font-size:12px; max-width:400px;">
                        <tr><td class="fw-semibold pe-2 py-0">ADRESSE :</td><td class="py-0">{{ $info->adresse ?: '—' }}</td></tr>
                        <tr><td class="fw-semibold pe-2 py-0">SIÈGE SOCIAL :</td><td class="py-0">{{ $info->siege_social ?: '—' }}</td></tr>
                        <tr><td class="fw-semibold pe-2 py-0">N° CNPS :</td><td class="py-0">{{ $info->num_cnps ?: '—' }}</td></tr>
                        <tr><td class="fw-semibold pe-2 py-0">N° CONTRIBUABLE :</td><td class="py-0">{{ $info->num_contribuable ?: '—' }}</td></tr>
                        <tr><td class="fw-semibold pe-2 py-0">E-mail :</td><td class="py-0">{{ $info->email ?: '—' }}</td></tr>
                        <tr><td class="fw-semibold pe-2 py-0">TÉLÉPHONE :</td><td class="py-0">{{ $info->telephone ?: '—' }}</td></tr>
                    </table>
                </div>
            </div>
            @endif --}}

        </div>
    </div>

</div>
@endsection
