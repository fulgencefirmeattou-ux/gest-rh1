@extends('layouts.app')
@section('title','liste des absences justifiées')
@section('content')
<div class="container-fluid mt-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="ri-error-warning-line me-2 text-warning"></i>Mes absences</h2>
        <a href="{{ route('justificatifs.absence') }}" class="btn btn-primary btn-sm">
            <i class="ri-add-line me-1"></i>Déclarer une absence
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    {{-- Solde permissions --}}
    @if($employe)
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 bg-warning-subtle text-center p-3">
                <div class="fw-bold fs-4">{{ $employe->soldePermissionsRestant() }}</div>
                <small class="text-muted">Permissions restantes / {{ $employe->solde_permissions }}</small>
                <div class="progress mt-2" style="height:6px;">
                    <div class="progress-bar bg-warning" style="width:{{ $employe->solde_permissions > 0 ? ($employe->permissions_prises / $employe->solde_permissions) * 100 : 0 }}%"></div>
                </div>
                <small class="text-muted">{{ $employe->permissions_prises }} utilisée(s)</small>
            </div>
        </div>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Motif</th>
                            <th>Pièce jointe</th>
                            <th class="text-center">Statut</th>
                            <th>Commentaire RH</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absences as $absence)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $absence->typeLabel() }}</span></td>
                                <td>{{ $absence->date_absence->format('d/m/Y') }}</td>
                                <td class="small">{{ \Illuminate\Support\Str::limit($absence->motif, 40) ?? '—' }}</td>
                                <td>
                                    @if($absence->justificatif)
                                        <a href="{{ route('justificatifs.absence.download', $absence->id) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                                            <i class="ri-download-line"></i>
                                        </a>
                                    @else
                                        <span class="text-muted small">Aucun</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $absence->statutColor() }}">{{ $absence->statutLabel() }}</span>
                                </td>
                                <td class="small text-muted">{{ \Illuminate\Support\Str::limit($absence->commentaire_rh, 50) ?? '—' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('absences.details', $absence->id) }}" class="btn btn-outline-info btn-sm">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Aucune absence déclarée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection