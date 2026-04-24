@extends('layouts.app')
@section('title', 'Validation des absences')

@section('content')
<div class="container-fluid mt-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="ri-file-list-3-line me-2"></i>Absences en attente de traitement</h2>
        <a href="{{ route('justificatifs.absence.traiter') }}" class="btn btn-outline-secondary btn-sm">
            <i class="ri-history-line me-1"></i>Absences traitées
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Employé</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Heures</th>
                            <th>Motif</th>
                            <th>Justificatif</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absences as $absence)
                            <tr>
                                <td class="fw-semibold">{{ $absence->employe->nom }} {{ $absence->employe->prenom }}</td>
                                <td>
                                    {{ $absence->date_absence->format('d/m/Y') }}
                                    @if($absence->date_fin_absence)
                                        <span class="text-muted">→ {{ $absence->date_fin_absence->format('d/m/Y') }}</span>
                                    @endif
                                </td>
                                <td><span class="badge bg-secondary">{{ $absence->typeLabel() }}</span></td>
                                <td class="small text-muted">
                                    {{ $absence->heure_debut ? $absence->heure_debut . ' → ' . ($absence->heure_fin ?? '?') : '—' }}
                                </td>
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
                                    <div class="d-flex gap-1 justify-content-center">
                                        {{-- Valider --}}
                                        <form method="POST" action="{{ route('absence.approve', $absence->id) }}">
                                            @csrf
                                            <input type="hidden" name="commentaire_rh" value="">
                                            <button class="btn btn-success btn-sm" title="Valider">
                                                <i class="ri-check-line"></i>
                                            </button>
                                        </form>

                                        {{-- Rejeter avec commentaire --}}
                                        <button class="btn btn-danger btn-sm" title="Rejeter"
                                                data-bs-toggle="modal" data-bs-target="#rejectModal{{ $absence->id }}">
                                            <i class="ri-close-line"></i>
                                        </button>
                                    </div>

                                    {{-- Modal rejet --}}
                                    <div class="modal fade" id="rejectModal{{ $absence->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('absence.reject', $absence->id) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h6 class="modal-title">Rejeter l'absence — {{ $absence->employe->nom }}</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label class="form-label">Commentaire (optionnel)</label>
                                                        <textarea name="commentaire_rh" class="form-control" rows="3" placeholder="Motif du rejet..."></textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                                                        <button class="btn btn-danger btn-sm">Rejeter</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="ri-checkbox-circle-line fs-24 text-success"></i>
                                    <p class="mb-0 mt-1">Aucune absence en attente.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
