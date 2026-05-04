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
    @php
        $soldeMax          = max(1, $employe->solde_permissions ?? 10);
        $permisUtilisees   = $absences->where('type_absence', 'permission_courte')->where('statut', 'validee')->count();
        $permisEnAttente   = $absences->where('type_absence', 'permission_courte')->where('statut', 'en_attente')->count();
        $permisRestantes   = max(0, $soldeMax - $permisUtilisees);
        $pctUsed           = round(($permisUtilisees / $soldeMax) * 100);
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 bg-warning-subtle p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fw-semibold text-muted small text-uppercase">Permissions</span>
                    <i class="ri-time-line text-warning fs-18"></i>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="fw-bold fs-2 text-warning">{{ $permisRestantes }}</span>
                    <span class="text-muted small">/ {{ $soldeMax }} restantes</span>
                </div>
                <div class="progress my-2" style="height:6px;">
                    <div class="progress-bar bg-warning" style="width:{{ $pctUsed }}%;"></div>
                </div>
                <div class="d-flex justify-content-between">
                    <small class="text-muted">{{ $permisUtilisees }} validée(s)</small>
                    @if($permisEnAttente > 0)
                        <small class="text-warning"><i class="ri-time-line me-1"></i>{{ $permisEnAttente }} en attente</small>
                    @endif
                </div>
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