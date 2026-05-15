@extends('layouts.app')

@section('content')
<div class="container-fluid mt-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-0"><i class="ri-time-line me-2"></i>Pointage</h2>
            <p class="text-muted mb-0 small">{{ $date->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span class="fw-bold fs-4 text-dark" id="horloge">--:--:--</span>
            <a href="{{ route('pointages.dashboard', ['date' => $date->toDateString()]) }}" class="btn btn-outline-secondary btn-sm">
                <i class="ri-dashboard-line me-1"></i>Dashboard
            </a>
            <a href="{{ route('pointages.statistiques') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ri-bar-chart-line me-1"></i>Statistiques
            </a>
            <a href="{{ route('pointages.calendrier') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ri-calendar-line me-1"></i>Calendrier
            </a>
        </div>
    </div>

    {{-- Sélecteur de date --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('pointages.index') }}" class="d-flex align-items-center gap-3">
                <label class="form-label mb-0 fw-semibold text-nowrap">
                    <i class="ri-calendar-line me-1"></i>Date de pointage
                </label>
                <input type="date" name="date" class="form-control form-control-sm" style="max-width:180px;" value="{{ $date->toDateString() }}" required>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="ri-filter-line me-1"></i>Afficher
                </button>
                @if($date->toDateString() !== \Carbon\Carbon::today()->toDateString())
                <a href="{{ route('pointages.index') }}" class="btn btn-outline-secondary btn-sm">
                    Aujourd'hui
                </a>
                @endif
            </form>
        </div>
    </div>

    {{-- Alertes --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Info pause auto --}}
    <div class="alert alert-info py-2 mb-3">
        <i class="ri-information-line me-1"></i>
        Si aucune pause n'est saisie et que la présence est ≥ 6h, une pause de <strong>1h</strong> est déduite automatiquement.
        Journée de référence : <strong>8h</strong>.
    </div>

    {{-- Tableau --}}
    @if($employes->isEmpty())
    <div class="text-center text-muted py-5">Aucun employé trouvé.</div>
    @else

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-hover mb-0 align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-start ps-3" style="min-width:160px;">Employé</th>
                            <th style="min-width:80px;">Absent(e)</th>
                            <th style="min-width:110px;">
                                <span class="text-success">Heure d'entrée</span> <span class="text-danger">*</span>
                            </th>
                            <th style="min-width:110px;">
                                <span class="text-warning">Début pause</span>
                            </th>
                            <th style="min-width:110px;">
                                <span class="text-warning">Fin pause</span>
                            </th>
                            <th style="min-width:110px;">
                                <span class="text-danger">Heure de sortie</span> <span class="text-danger">*</span>
                            </th>
                            <th style="min-width:90px;">Travaillées</th>
                            <th style="min-width:70px;">Sup</th>
                            <th style="min-width:90px;">Manquantes</th>
                            <th style="min-width:90px;">Statut</th>
                            <th style="min-width:80px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employes as $employe)
                        @php
                        $p = $pointages->get($employe->id);
                        @endphp
                        <tr>
                            <td class="text-start ps-3 fw-semibold">
                                {{ $employe->nom }} {{ $employe->prenom }}
                                @if($employe->employe)
                                <br><small class="text-muted fw-normal">{{ $employe->employe->matricule }}</small>
                                @endif
                            </td>

                            <form action="{{ route('pointages.sauvegarder') }}" method="POST">
                                @csrf
                                <input type="hidden" name="employe_id" value="{{ $employe->id }}">
                                <input type="hidden" name="date" value="{{ $date->toDateString() }}">

                                {{-- Absent --}}
                                <td>
                                    <div class="form-check d-flex justify-content-center">
                                        <input type="checkbox" name="absent" id="absent_{{ $employe->id }}" class="form-check-input absent-toggle" value="1" data-id="{{ $employe->id }}" {{ $p?->absent ? 'checked' : '' }}>
                                    </div>
                                </td>

                                {{-- Heure d'entrée --}}
                                <td>
                                    <input type="time" name="heure_arrivee" class="form-control form-control-sm text-center" @error('heure_arrivee') is-invalid @enderror value="{{ $p?->heure_arrivee ? \Carbon\Carbon::createFromTimeString($p->heure_arrivee)->format('H:i') : '' }}">
                                </td>

                                {{-- Début pause --}}
                                <td>
                                    <input type="time" name="heure_debut_pause" class="form-control form-control-sm text-center" value="{{ $p?->heure_debut_pause ? \Carbon\Carbon::createFromTimeString($p->heure_debut_pause)->format('H:i') : '' }}">
                                </td>

                                {{-- Fin pause --}}
                                <td>
                                    <input type="time" name="heure_fin_pause" class="form-control form-control-sm text-center" value="{{ $p?->heure_fin_pause ? \Carbon\Carbon::createFromTimeString($p->heure_fin_pause)->format('H:i') : '' }}">
                                </td>

                                {{-- Heure de sortie --}}
                                <td>
                                    <input type="time" name="heure_depart" class="form-control form-control-sm text-center" @error('heure_depart') is-invalid @enderror value="{{ $p?->heure_depart ? \Carbon\Carbon::createFromTimeString($p->heure_depart)->format('H:i') : '' }}">
                        
                                </td>

                                {{-- Heures travaillées --}}
                                <td>
                                    @if($p && $p->heures_travaillees !== null)
                                    <span class="badge bg-{{ $p->couleur_heures }}">{{ $p->heures_travaillees_format }}</span>
                                    @else
                                    <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Sup --}}
                                <td>
                                    @if($p && $p->heures_sup > 0)
                                    <span class="text-primary fw-semibold">+{{ number_format($p->heures_sup, 2) }}h</span>
                                    @else
                                    <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Manquantes --}}
                                <td>
                                    @if($p && $p->heures_manquantes > 0)
                                    <span class="text-danger fw-semibold">-{{ number_format($p->heures_manquantes, 2) }}h</span>
                                    @else
                                    <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Statut --}}
                                <td>
                                    @if($p)
                                    <span class="badge bg-{{ $p->couleur_statut }}">{{ $p->statut }}</span>
                                    @else
                                    <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Boutons action --}}
                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <button type="submit" class="btn btn-primary btn-sm" title="Enregistrer">
                                            <i class="ri-save-line"></i>
                                        </button>
                                        @if($p)
                                        <button type="button" class="btn btn-outline-danger btn-sm" title="Annuler l'enregistrement" onclick="annulerPointage({{ $p->id }}, '{{ $date->toDateString() }}')">
                                            <i class="ri-close-line"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </form>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Légende --}}
    <div class="mt-3 d-flex gap-3 flex-wrap small">
        <span><span class="badge bg-success">Heures OK</span></span>
        <span><span class="badge bg-primary">Heures sup</span></span>
        <span><span class="badge bg-danger">Heures manquantes</span></span>
        <span><span class="badge bg-warning text-dark">En attente</span></span>
        <span><span class="badge bg-success">Validé</span></span>
        <span><span class="badge bg-danger">Refusé</span></span>
    </div>
    @endif
</div>

{{-- Formulaire caché pour annuler --}}
<form id="form-annuler" action="" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
    var baseAnnulerUrl = "{{ url('pointages') }}";

    function annulerPointage(id, date) {
        if (!confirm('Annuler l\'enregistrement du pointage de ce jour ?')) return;
        var form = document.getElementById('form-annuler');
        form.action = baseAnnulerUrl + '/' + id + '/annuler?date=' + date;
        form.submit();
    }

    function updateHorloge() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('horloge').textContent = h + ':' + m + ':' + s;
    }
    updateHorloge();
    setInterval(updateHorloge, 1000);

    // Grise les champs heure quand "Absent" est coché
    function toggleHeures(checkbox) {
        const row = checkbox.closest('tr');
        const inputs = row.querySelectorAll('input[type="time"]');
        inputs.forEach(input => {
            input.disabled = checkbox.checked;
            input.required = !checkbox.checked && input.name !== 'heure_fin_pause' && input.name !== 'heure_debut_pause';
            if (checkbox.checked) input.value = '';
        });
    }

    document.querySelectorAll('.absent-toggle').forEach(cb => {
        toggleHeures(cb); // état initial
        cb.addEventListener('change', () => toggleHeures(cb));
    });

</script>
@endsection
