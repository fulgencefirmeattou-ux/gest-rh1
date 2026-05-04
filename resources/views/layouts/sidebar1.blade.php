@php
    $user       = auth()->user();
    $role       = $user->sidebarRole();

    $isSuperAdmin = $role === 'super-admin';
    $isAdmin      = $role === 'admin';
    $isRH         = $role === 'rh';
    $isRespDept   = $role === 'responsable_departement';
    $isRespServ   = $role === 'responsable_service';

    $peutGererRH      = $isSuperAdmin || $isRH;
    $peutGererFinance = $isSuperAdmin || $isRH || $isAdmin;
    $peutValiderDgRh  = $isSuperAdmin || $isRH || $isAdmin;
    $aEmployeId       = $user->employe_id !== null;

    $aMenuCongesAbsences = $aEmployeId || $peutValiderDgRh || $isRespDept || $isRespServ;
@endphp

<div class="leftside-menu">

    <div class="h-100" id="leftside-menu-container" data-simplebar>
        <ul class="side-nav">

            <li class="side-nav-title text-uppercase text-center">Menu</li>

            {{-- ── Tableau de bord (tous) ── --}}
            <li class="side-nav-item">
                <a href="{{ route('admin.dashboard') }}" class="side-nav-link">
                    <i class="ri-dashboard-3-line"></i>
                    <span>Tableau de bord</span>
                </a>
            </li>
            {{-- ── Mon profil (tous) ── --}}
            {{-- <li class="side-nav-item">
                <a href="{{ route('employe.profil') }}" class="side-nav-link">
                    <i class="ri-user-line"></i>
                    <span>Mon profil</span>
                </a>
            </li> --}}
            {{-- ── Gestion RH (admin + rh) ── --}}
            @if($peutGererRH)
                <li class="side-nav-title text-uppercase text-center">Gestion RH</li>

                <li class="side-nav-item">
                    <a href="{{ route('utilisateurs.index') }}" class="side-nav-link">
                        <i class="ri-user-settings-line"></i>
                        <span>Utilisateurs</span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{ route('employes.index') }}" class="side-nav-link">
                        <i class="ri-group-line"></i>
                        <span>Employés</span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{ route('departements.index') }}" class="side-nav-link">
                        <i class="ri-building-2-line"></i>
                        <span>Départements</span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{ route('postes.index') }}" class="side-nav-link">
                        <i class="ri-briefcase-line"></i>
                        <span>Postes</span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{ route('type_contrats.index') }}" class="side-nav-link">
                        <i class="ri-file-list-2-line"></i>
                        <span>Types de contrats</span>
                    </a>
                </li>
                
            @endif

            {{-- ── Finance (admin + rh + dg) ── --}}
            @if($peutGererFinance)
                <li class="side-nav-title text-uppercase text-center">Finance & Paie</li>

                <li class="side-nav-item">
                    <a href="{{ route('contrats.index') }}" class="side-nav-link">
                        <i class="ri-file-text-line"></i>
                        <span>Contrats</span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{ route('bulletins.index') }}" class="side-nav-link">
                        <i class="ri-money-dollar-circle-line"></i>
                        <span>Bulletins de paie</span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{ route('pointages.index') }}" class="side-nav-link">
                        <i class="ri-time-line"></i>
                        <span>Pointage</span>
                    </a>
                </li>
                
            @endif

            {{-- ── Absences & Congés ── --}}
            @if($aMenuCongesAbsences)
                <li class="side-nav-title text-uppercase text-center">Absences & Congés</li>

                <li class="side-nav-item">
                    <a href="#sidebarAbsences" data-bs-toggle="collapse" class="side-nav-link">
                        <i class="ri-calendar-check-line"></i>
                        <span>Absences & Congés</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarAbsences">
                        <ul class="side-nav-second-level">

                            {{-- Employé : ses propres congés/absences --}}
                            @if($aEmployeId)
                                <li>
                                    <a href="{{ route('conges.voir') }}">
                                        <i class="ri-plane-line me-1"></i> Mes congés
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('justificatifs.absence.liste') }}">
                                        <i class="ri-error-warning-line me-1"></i> Mes absences
                                    </a>
                                </li>
                            @endif

                            {{-- Responsable service : valider les congés du service --}}
                            @if($isRespServ)
                                <li>
                                    <a href="{{ route('conges.approbation.service') }}">
                                        <i class="ri-check-double-line me-1"></i> Valider — Service
                                    </a>
                                </li>
                            @endif

                            {{-- Responsable département : valider les congés du département --}}
                            @if($isRespDept)
                                <li>
                                    <a href="{{ route('conges.approbation.departement') }}">
                                        <i class="ri-checkbox-multiple-line me-1"></i> Valider — Département
                                    </a>
                                </li>
                            @endif

                            {{-- DG / RH / Admin : validation finale et gestion absences --}}
                            @if($peutValiderDgRh)
                                <li>
                                    <a href="{{ route('conges.approbation.dgRh') }}">
                                        <i class="ri-checkbox-circle-line me-1"></i> Valider — DG/RH
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('justisificatifs.absence.rh') }}">
                                        <i class="ri-file-list-3-line me-1"></i> Traiter absences
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </div>
                </li>
                
            @endif

            {{-- ── Administration système (super-admin uniquement) ── --}}
            @if($isSuperAdmin)
                <li class="side-nav-title text-uppercase text-center">Administration</li>

                <li class="side-nav-item">
                    <a href="{{ route('utilisateurs.index') }}" class="side-nav-link">
                        <i class="ri-user-settings-line"></i>
                        <span>Utilisateurs</span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{ route('roles.index') }}" class="side-nav-link">
                        <i class="ri-shield-user-line"></i>
                        <span>Rôles</span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{ route('permissions.index') }}" class="side-nav-link">
                        <i class="ri-lock-line"></i>
                        <span>Permissions</span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{ route('info-entreprise.index') }}" class="side-nav-link">
                        <i class="ri-building-line"></i>
                        <span>Info entreprise</span>
                    </a>
                </li>
            @endif

        </ul>
    </div>
</div>
