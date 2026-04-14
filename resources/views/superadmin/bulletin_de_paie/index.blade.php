@extends('layouts.app')

@section('content')
<div class="container-fluid mt-3">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="ri-file-text-line me-2"></i>Bulletins de paie</h2>
        <a href="{{ route('bulletins.create') }}" class="btn btn-primary btn-sm">
            <i class="ri-add-line me-1"></i>Nouveau bulletin
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">{{ $errors->first() }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    {{-- Filtres --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label mb-1 small">Employé</label>
                    <select name="employe_id" class="form-select form-select-sm">
                        <option value="">— Tous —</option>
                        @foreach($employes as $emp)
                            <option value="{{ $emp->id }}" {{ request('employe_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->nom }} {{ $emp->prenom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1 small">Mois</label>
                    <input type="month" name="mois" value="{{ request('mois') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1 small">Statut</label>
                    <select name="statut" class="form-select form-select-sm">
                        <option value="">— Tous —</option>
                        <option value="brouillon" {{ request('statut') === 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                        <option value="valide"    {{ request('statut') === 'valide'    ? 'selected' : '' }}>Validé</option>
                        <option value="paye"      {{ request('statut') === 'paye'      ? 'selected' : '' }}>Payé</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="ri-filter-line me-1"></i>Filtrer</button>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('bulletins.index') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Employé</th>
                            <th>Matricule</th>
                            <th>Mois</th>
                            <th class="text-end">Salaire brut</th>
                            <th class="text-end">Retenues</th>
                            <th class="text-end">Net à payer</th>
                            <th class="text-center">Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bulletins as $b)
                            <tr>
                                <td class="text-muted small">{{ $b->id }}</td>
                                <td class="fw-semibold">{{ $b->employe->nom ?? '—' }} {{ $b->employe->prenom ?? '' }}</td>
                                <td class="small text-muted">{{ $b->employe->matricule ?? '—' }}</td>
                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $b->mois)->locale('fr')->isoFormat('MMMM YYYY') }}</td>
                                <td class="text-end">{{ number_format($b->salaire_brut, 0, ',', ' ') }} F</td>
                                <td class="text-end text-danger">{{ number_format($b->total_retenues, 0, ',', ' ') }} F</td>
                                <td class="text-end fw-bold text-success">{{ number_format($b->net_a_payer, 0, ',', ' ') }} F</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $b->statut_color }}">{{ $b->statut_label }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('bulletins.show', $b) }}" class="btn btn-outline-primary btn-sm" title="Voir">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('bulletins.download', $b) }}" class="btn btn-outline-danger btn-sm" title="PDF">
                                            <i class="ri-file-pdf-line"></i>
                                        </a>
                                        @if($b->statut === 'brouillon')
                                            <form method="POST" action="{{ route('bulletins.valider', $b) }}">
                                                @csrf
                                                <button class="btn btn-outline-success btn-sm" title="Valider">
                                                    <i class="ri-check-line"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if($b->statut === 'valide')
                                            <form method="POST" action="{{ route('bulletins.payer', $b) }}">
                                                @csrf
                                                <button class="btn btn-success btn-sm" title="Marquer payé">
                                                    <i class="ri-money-dollar-circle-line"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if($b->statut !== 'paye')
                                            <form method="POST" action="{{ route('bulletins.destroy', $b) }}" onsubmit="return confirm('Supprimer ce bulletin ?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm" title="Supprimer">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Aucun bulletin trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($bulletins->hasPages())
            <div class="card-footer">{{ $bulletins->links() }}</div>
        @endif
    </div>

</div>
@endsection
