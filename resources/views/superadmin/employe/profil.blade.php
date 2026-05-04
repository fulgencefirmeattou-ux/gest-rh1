@extends('layouts.app')
@section('title', 'Mon profil')

@section('content')
<div class="container-fluid">

    {{-- En-tête --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-1">Mon profil</h4>
                    <p class="text-muted mb-0">
                        <i class="ri-calendar-line me-1"></i>
                        {{ \Carbon\Carbon::now()->translatedFormat('l d F Y') }}
                    </p>
                </div>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
                    <li class="breadcrumb-item active">Mon profil</li>
                </ol>
            </div>
        </div>
    </div>

    @php
        $roleLabels = [
            'admin'   => ['label' => 'Administrateur',         'color' => 'danger'],
            'rh'      => ['label' => 'Responsable RH',         'color' => 'primary'],
            'dg'      => ['label' => 'Directeur Général',      'color' => 'dark'],
            'employe' => ['label' => 'Employé',                'color' => 'secondary'],
            'default' => ['label' => ucfirst($user->role ?? '—'), 'color' => 'secondary'],
        ];
        $roleInfo = $roleLabels[$user->role] ?? $roleLabels['default'];
    @endphp

    <div class="row g-4">

        {{-- ── Colonne gauche : carte identité ── --}}
        <div class="col-lg-4">

            {{-- Carte principale --}}
            <div class="card text-center">
                <div class="card-body py-4">
                    {{-- Avatar --}}
                    @if($employe && $employe->photo_profil)
                        <img src="{{ asset('storage/' . $employe->photo_profil) }}"
                             class="rounded-circle mb-3 border border-3 border-light shadow-sm"
                             style="width:100px;height:100px;object-fit:cover;" alt="Photo profil">
                    @else
                        <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-3 border border-3 border-light shadow-sm"
                             style="width:100px;height:100px;">
                            <span class="text-white fw-bold fs-3">
                                {{ strtoupper(substr($user->prenom ?? '?', 0, 1)) }}{{ strtoupper(substr($user->nom ?? '?', 0, 1)) }}
                            </span>
                        </div>
                    @endif

                    <h5 class="fw-bold mb-1">{{ $user->prenom }} {{ $user->nom }}</h5>

                    @if($employe)
                        <p class="text-muted mb-1">{{ $employe->poste->name ?? '—' }}</p>
                        @if($employe->matricule)
                            <span class="badge bg-secondary-subtle text-secondary px-3 py-1 mb-2">
                                <i class="ri-fingerprint-line me-1"></i>{{ $employe->matricule }}
                            </span>
                        @endif
                    @endif

                    <div class="mt-1">
                        <span class="badge bg-{{ $roleInfo['color'] }} px-3 py-1">
                            <i class="ri-shield-user-line me-1"></i>{{ $roleInfo['label'] }}
                        </span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex flex-column gap-2 text-start px-2">
                        @if($user->email)
                        <div class="d-flex align-items-center gap-2 small">
                            <i class="ri-mail-line text-muted fs-16"></i>
                            <span class="text-truncate">{{ $user->email }}</span>
                        </div>
                        @endif
                        @if($user->login)
                        <div class="d-flex align-items-center gap-2 small">
                            <i class="ri-user-line text-muted fs-16"></i>
                            <span>{{ $user->login }}</span>
                        </div>
                        @endif
                        @if($employe && $employe->telephone)
                        <div class="d-flex align-items-center gap-2 small">
                            <i class="ri-phone-line text-muted fs-16"></i>
                            <span>{{ $employe->telephone }}</span>
                        </div>
                        @endif
                        @if($employe && $employe->adresse)
                        <div class="d-flex align-items-center gap-2 small">
                            <i class="ri-map-pin-line text-muted fs-16"></i>
                            <span>{{ $employe->adresse }}</span>
                        </div>
                        @endif
                        @if($user->date_creation)
                        <div class="d-flex align-items-center gap-2 small">
                            <i class="ri-calendar-line text-muted fs-16"></i>
                            <span>Compte créé le {{ $user->date_creation->format('d/m/Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
        {{-- fin colonne gauche --}}

        {{-- ── Colonne droite : Informations du compte ── --}}
        <div class="col-lg-8">

            {{-- Alertes --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3">
                    <i class="ri-checkbox-circle-line fs-18"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card h-100">
                <div class="card-header bg-transparent pt-3 d-flex align-items-center justify-content-between">
                    <h6 class="card-title mb-0">
                        <i class="ri-account-circle-line me-2 text-primary"></i>Informations du compte
                    </h6>
                    <button type="button" class="btn btn-outline-warning btn-sm"
                            data-bs-toggle="modal" data-bs-target="#modalMotDePasse">
                        <i class="ri-lock-password-line me-1"></i>Modifier le mot de passe
                    </button>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-0">Prénom</label>
                            <p class="fw-semibold mb-0">{{ $user->prenom ?? '—' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-0">Nom</label>
                            <p class="fw-semibold mb-0">{{ $user->nom ?? '—' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-0">Email</label>
                            <p class="fw-semibold mb-0">{{ $user->email ?? '—' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-0">Login</label>
                            <p class="fw-semibold mb-0">{{ $user->login ?? '—' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-0">Rôle</label>
                            <p class="fw-semibold mb-0">
                                <span class="badge bg-{{ $roleInfo['color'] }}">{{ $roleInfo['label'] }}</span>
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-0">Compte créé le</label>
                            <p class="fw-semibold mb-0">
                                {{ $user->date_creation ? $user->date_creation->format('d/m/Y') : '—' }}
                            </p>
                        </div>
                        @if($user->date_connexion)
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-0">Dernière connexion</label>
                            <p class="fw-semibold mb-0">{{ $user->date_connexion->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
        {{-- fin colonne droite --}}

        {{-- ── Ligne inférieure : Infos personnelles + professionnelles (employé uniquement) ── --}}
        @if($employe)
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-transparent pt-3">
                    <h6 class="card-title mb-0">
                        <i class="ri-user-line me-2 text-primary"></i>Informations personnelles
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-0">Civilité</label>
                            <p class="fw-semibold mb-0">{{ $employe->civilite ?? '—' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-0">Nationalité</label>
                            <p class="fw-semibold mb-0">{{ $employe->nationalite ?? '—' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-0">Date de naissance</label>
                            <p class="fw-semibold mb-0">
                                {{ $employe->date_naissance ? $employe->date_naissance->format('d/m/Y') : '—' }}
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-0">Lieu de naissance</label>
                            <p class="fw-semibold mb-0">{{ $employe->lieu_naissance ?? '—' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-0">Situation matrimoniale</label>
                            <p class="fw-semibold mb-0">{{ $employe->situation_matrimoniale ?? '—' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-0">Nombre d'enfants</label>
                            <p class="fw-semibold mb-0">{{ $employe->nombre_enfants ?? '0' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-0">Téléphone</label>
                            <p class="fw-semibold mb-0">{{ $employe->telephone ?? '—' }}</p>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small mb-0">Adresse</label>
                            <p class="fw-semibold mb-0">{{ $employe->adresse ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-transparent pt-3">
                    <h6 class="card-title mb-0">
                        <i class="ri-briefcase-line me-2 text-success"></i>Informations professionnelles
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-0">Matricule</label>
                            <p class="fw-semibold mb-0">{{ $employe->matricule ?? '—' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-0">Date d'embauche</label>
                            <p class="fw-semibold mb-0">
                                {{ $employe->date_embauche ? $employe->date_embauche->format('d/m/Y') : '—' }}
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-0">Département</label>
                            <p class="fw-semibold mb-0">{{ $employe->departement->nom ?? '—' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-0">Service</label>
                            <p class="fw-semibold mb-0">{{ $employe->service->nom ?? '—' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-0">Poste</label>
                            <p class="fw-semibold mb-0">{{ $employe->poste->name ?? '—' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-0">Type de contrat</label>
                            <p class="fw-semibold mb-0">{{ $employe->typeContrat->nom ?? '—' }}</p>
                        </div>
                        @if($employe->date_embauche)
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-0">Ancienneté</label>
                            <p class="fw-semibold mb-0">
                                {{ $employe->date_embauche->diffForHumans(null, true) }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>

</div>

{{-- ── Modal : Modifier le mot de passe ── --}}
<div class="modal fade" id="modalMotDePasse" tabindex="-1" aria-labelledby="modalMotDePasseLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('employe.profil.password') }}">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title" id="modalMotDePasseLabel">
                        <i class="ri-lock-password-line me-2 text-warning"></i>Modifier le mot de passe
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mot de passe actuel <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="currentPassword"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   placeholder="••••••••">
                            <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="currentPassword">
                                <i class="ri-eye-line"></i>
                            </button>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nouveau mot de passe <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="new_password" id="newPassword"
                                   class="form-control @error('new_password') is-invalid @enderror"
                                   placeholder="••••••••">
                            <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="newPassword">
                                <i class="ri-eye-line"></i>
                            </button>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">Minimum 8 caractères.</div>
                    </div>

                    <div class="mb-1">
                        <label class="form-label fw-semibold">Confirmer le nouveau mot de passe <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="new_password_confirmation" id="confirmPassword"
                                   class="form-control"
                                   placeholder="••••••••">
                            <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="confirmPassword">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="ri-save-line me-1"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.toggle-pw').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(this.dataset.target);
            var icon  = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('ri-eye-line', 'ri-eye-off-line');
            } else {
                input.type = 'password';
                icon.classList.replace('ri-eye-off-line', 'ri-eye-line');
            }
        });
    });

    @if($errors->has('current_password') || $errors->has('new_password') || $errors->has('new_password_confirmation') || session('open_password_modal'))
        document.addEventListener('DOMContentLoaded', function () {
            new bootstrap.Modal(document.getElementById('modalMotDePasse')).show();
        });
    @endif
</script>
@endpush

@endsection
