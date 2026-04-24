@extends('layouts.app')
@section('title', 'Justificatifs d\'absence')
@section('content')
<section class="row">
    <div class="col-12 col-lg-12">
        <div class="container py-3">
            <h2 class="mb-4 text-start">Justifier une absence</h2>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif


            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('justificatifs.store') }}" method="POST" class="bg-white p-4 rounded shadow-sm" enctype="multipart/form-data">
                @csrf

                {{-- Type --}}
                <div class="mb-3">
                    <label for="type" class="form-label fw-semibold">Type d'absence <span class="text-danger">*</span></label>
                    <select name="type_absence" id="type_absence" class="form-select" required>
                        <option value="">-- Sélectionnez un type --</option>
                        <option value="maladie"               {{ old('type_absence') == 'maladie'               ? 'selected' : '' }}>Maladie</option>
                        <option value="retard"                {{ old('type_absence') == 'retard'                ? 'selected' : '' }}>Retard</option>
                        <option value="absence_non_justifiee" {{ old('type_absence') == 'absence_non_justifiee' ? 'selected' : '' }}>Absence non justifiée</option>
                        <option value="permission_courte"     {{ old('type_absence') == 'permission_courte'     ? 'selected' : '' }}>Permission courte</option>
                        <option value="rendez_vous"           {{ old('type_absence') == 'rendez_vous'           ? 'selected' : '' }}>Rendez-vous</option>
                        <option value="autre"                 {{ old('type_absence') == 'autre'                 ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>

                {{-- Dates --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="date_absence" class="form-label fw-semibold">Date de début <span class="text-danger">*</span></label>
                        <input type="date" name="date_absence" id="date_absence" class="form-control"
                               value="{{ old('date_absence') }}" required>
                    </div>
                    <div class="col-md-6" id="date_fin_block">
                        <label for="date_fin_absence" class="form-label fw-semibold">Date de fin <span class="text-muted small">(optionnelle)</span></label>
                        <input type="date" name="date_fin_absence" id="date_fin_absence" class="form-control"
                               value="{{ old('date_fin_absence') }}">
                        <div class="form-text">Laisser vide si absence d'une seule journée.</div>
                    </div>
                </div>

{{-- Motif --}}
                <div class="mb-3">
                    <label for="motif" class="form-label fw-semibold">Motif</label>
                    <textarea name="motif" id="motif" class="form-control" rows="3">{{ old('motif') }}</textarea>
                </div>

                {{-- Justificatif --}}
                <div class="mb-4">
                    <label for="justificatif" class="form-label fw-semibold">Pièce jointe <span class="text-muted small">(PDF, image — max 2 Mo)</span></label>
                    <input type="file" name="justificatif" id="justificatif" class="form-control"
                           accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <button type="submit" class="btn btn-primary px-4">
                    <i class="ri-send-plane-line me-1"></i>Soumettre
                </button>
            </form>

            <script>
                const dateDebut = document.getElementById('date_absence');
                const dateFin   = document.getElementById('date_fin_absence');

                dateDebut.addEventListener('change', function () {
                    dateFin.min = this.value;
                    if (dateFin.value && dateFin.value < this.value) dateFin.value = this.value;
                });
            </script>
        </div>
    </div>
</section>
@endsection