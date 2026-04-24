<div class="leftside-menu">

    <!-- Logo etc... (inchangé) -->

    <div class="h-100" id="leftside-menu-container" data-simplebar>
        <ul class="side-nav">

            <li class="side-nav-title">Main</li>

            {{-- Accessible à tous --}}
            <li class="side-nav-item">
                {{-- <a href="{{ route('admin.dashboard') }}" class="side-nav-link">
                <i class="ri-dashboard-3-line"></i>
                <span>Tableau de bord</span>
                </a> --}}

                <a href="{{ route('admin.dashboard') }}" class="side-nav-link">
                    <i class="ri-dashboard-3-line"></i>
                    <span>Tableau de bord</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('employes.index') }}" class="side-nav-link">
                    <i class="ri-calendar-line"></i>
                    <span>Employés</span>
                    {{-- <span class="menu-arrow"></span> --}}
                </a>
            </li>

            <li class="side-nav-item">
            <li class="side-nav-item">
                <a href="{{ route('departements.index') }}" class="side-nav-link">
                    <i class="ri-calendar-line"></i>
                    <span>Départements</span>
                    {{-- <span class="menu-arrow"></span> --}}
                </a>

            </li>

            <li class="side-nav-item">
                <a href="{{ route('postes.index') }}" class="side-nav-link">
                    <i class="ri-calendar-line"></i>
                    <span>Postes</span>
                    {{-- <span class="menu-arrow"></span> --}}
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('type_contrats.index') }}" class="side-nav-link">
                    <i class="ri-calendar-line"></i>
                    <span>Types de contrats</span>
                    {{-- <span class="menu-arrow"></span> --}}
                </a>

            </li>

            <li class="side-nav-item">
                <a href="{{ route('utilisateurs.index') }}" class="side-nav-link">
                    <i class="ri-calendar-line"></i>
                    <span>Utilisateurs</span>
                    {{-- <span class="menu-arrow"></span> --}}
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('contrats.index') }}" class="side-nav-link">
                    <i class="ri-calendar-line"></i>
                    <span>Contrats</span>
                    {{-- <span class="menu-arrow"></span> --}}
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('pointages.index') }}" class="side-nav-link">
                    <i class="ri-calendar-line"></i>
                    <span>Pointage</span>
                    {{-- <span class="menu-arrow"></span> --}}
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('bulletins.index') }}" class="side-nav-link">
                    <i class="ri-file-text-line"></i>
                    <span>Bulletins de paie</span>
                </a>
            </li>
            @php
                $user        = auth()->user();
                $peutValiderDgRh        = $user->isDG() || $user->isRH() || $user->isAdmin();
                $peutTraiterAbsences    = $user->isDG() || $user->isRH() || $user->isAdmin();
                $aMenuAbsences          = ($user->employe_id !== null) || $peutValiderDgRh || $peutTraiterAbsences;
            @endphp

            @if($aMenuAbsences)
            <li class="side-nav-item">
                <a href="#sidebarAbsences" data-bs-toggle="collapse" class="side-nav-link">
                    <i class="ri-calendar-check-line"></i>
                    <span>Absences & Congés</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarAbsences">
                    <ul class="side-nav-second-level">

                        @if($user->employe_id !== null)
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

@if($peutValiderDgRh)
                        <li>
                            <a href="{{ route('conges.approbation.dgRh') }}">
                                <i class="ri-checkbox-circle-line me-1"></i> Valider — DG/RH
                            </a>
                        </li>
                        @endif

                        @if($peutTraiterAbsences)
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

            <li class="side-nav-item">
                <a href="{{ route('roles.index') }}" class="side-nav-link">
                    <i class="ri-calendar-line"></i>
                    <span>Rôles</span>
                    {{-- <span class="menu-arrow"></span> --}}
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('permissions.index') }}" class="side-nav-link">
                    <i class="ri-calendar-line"></i>
                    <span>Permissions</span>
                    {{-- <span class="menu-arrow"></span> --}}
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('info-entreprise.index') }}" class="side-nav-link">
                    <i class="ri-calendar-line"></i>
                    <span>Info entreprise</span>
                    {{-- <span class="menu-arrow"></span> --}}
                </a>
            </li>









            {{-- @switch(auth()->user()->sidebarRole())
                @case('employe')
                    @include('sidebars.employe')
                    @break

                @case('responsable_service')
                    @include('sidebars.responsable-service')
                    @break

                @case('responsable_departement')
                    @include('sidebars.responsable-departement')
                    @break

                @case('rh')
                    @include('sidebars.rh')
                    @break

                @case('dg')
                    @include('sidebars.dg')
                    @break

                @case('admin')
                    @include('sidebars.admin')
                    @break
            @endswitch --}}

        </ul>
    </div>
</div>



{{-- ======================= --}}
{{-- MENU EMPLOYÉ      --}}
{{-- ======================= --}}
{{-- @if (auth()->user()->isEmploye() && !auth()->user()->isResponsableService() && !auth()->user()->isResponsableDepartement()) --}}
{{-- && !auth()->user()->isResponsableService() && !auth()->user()->isResponsableDepartement() --}}
{{-- <li class="side-nav-item">
                    <a href="{{ route('conges.voir') }}" class="side-nav-link">
<i class="ri-calendar-line"></i>
<span>CongésA</span>
<span class="menu-arrow"></span>
</a>
</li>
<li class="side-nav-item">
    <a href="{{ route('justificatifs.absence.liste') }}" class="side-nav-link">
        <i class="ri-calendar-line"></i>
        <span>Justifiés son absence</span>
        <span class="menu-arrow"></span>
    </a>
</li>
<li class="side-nav-item">
    <a href="{{ route('employe.contrat') }}" class="side-nav-link">
        <i class="ri-file-list-3-line"></i>
        <span>Mes contrats</span>
    </a>
</li>
<li class="side-nav-item">
    <a href="{{ route('employe.contrat') }}" class="side-nav-link">
        <i class="ri-file-list-3-line"></i>
        <span>Mes documents</span>
        <span class="menu-arrow"></span>
    </a>

    <div class="collapse" id="sidebarDoc">
        <ul class="side-nav-second-level">
            <li class="side-nav-item">
                <a href="{{ route('employe.contrat') }}" class="side-nav-link">

                    <span>Mes contrats</span>
                </a>
            </li>
        </ul>
    </div>
</li>

{{-- @endif --}}



{{-- ======================= --}}
{{-- MENU ADMIN / DG     --}}
{{-- ======================= --}}
{{-- @if(auth()->user()->isRole('admin') || auth()->user()->isRole('dg')) 
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#adminUsers" class="side-nav-link">
                        <i class="ri-group-2-line"></i>
                        <span> Utilisateurs </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <div class="collapse" id="adminUsers">
                        <ul class="side-nav-second-level">
                            <li><a href="{{route('admin.users.create')}}">Créer un utilisateur</a></li>
<li><a href="{{ route('users.liste') }}">Liste des utilisateurs</a></li>
<li><a href="{{ route('create.departement') }}">Créer un Département</a></li>
<li><a href="{{ route('departements.liste') }}">Liste des Départements</a></li>
<li><a href="{{ route('create.service') }}">Créer un service</a></li>
<li><a href="{{ route('services.liste') }}">Liste des services</a></li>
<li><a href="{{ route('create.employe') }}">Créer un employé</a></li>
<li><a href="{{ route('employe.liste') }}">Liste des employés</a></li>
</ul>
</div>
</li>
{{-- @endif --}}


{{-- ======================= --}}
{{-- MENU RESSOURCES HUMAINES (RH) || auth()->user()->isRole('admin')--}}

{{-- ======================= --}}
{{-- @if(auth()->user()->isRole('rh') ) 
                <li class="side-nav-item">
                    <a href="{{ route('employe.contrat') }}" class="side-nav-link">
<i class="ri-file-list-3-line"></i>
<span>Mes documents</span>
{{-- <span class="menu-arrow"></span> 
                    </a>

                    {{-- <div class="collapse" id="sidebarDoc">
                        <ul class="side-nav-second-level">
                            <li class="side-nav-item">
                                <a href="{{ route('employe.contrat') }}" class="side-nav-link">

<span>Mes contrats</span>
</a>
</li>
</ul>
</div>
</li>
<li class="side-nav-item">
    <a data-bs-toggle="collapse" href="#sidebarEmploye" class="side-nav-link">
        <i class="ri-folder-user-line"></i>
        <span>Employés</span>
        <span class="menu-arrow"></span>
    </a>

    <div class="collapse" id="sidebarEmploye">
        <ul class="side-nav-second-level">
            <li><a href="{{ route('create.employe') }}">Créer un employé</a></li>
            <li><a href="{{ route('employe.liste') }}">Liste des employés</a></li>
            <li><a href="{{ route('contrats.create') }}">Créer un contrat</a></li>
            <li><a href="{{ route('contrats.index') }}">Liste des contrats</a></li>
        </ul>
    </div>
</li>
<li class="side-nav-item">
    <a data-bs-toggle="collapse" href="#sidebarService" class="side-nav-link">
        <i class="ri-calendar-line"></i>
        <span>Services</span>
        <span class="menu-arrow"></span>
    </a>

    <div class="collapse" id="sidebarService">
        <ul class="side-nav-second-level">
            <li><a href="{{ route('create.service') }}">Créer un service</a></li>
            <li><a href="{{ route('services.liste') }}">Liste des services</a></li>
        </ul>
    </div>
</li>
<li class="side-nav-item">
    <a data-bs-toggle="collapse" href="#sidebarDepartement" class="side-nav-link">
        <i class="ri-calendar-line"></i>
        <span>Départements</span>
        <span class="menu-arrow"></span>
    </a>

    <div class="collapse" id="sidebarDepartement">
        <ul class="side-nav-second-level">
            <li><a href="{{ route('create.departement') }}">Créer un Département</a></li>
            <li><a href="{{ route('departements.liste') }}">Liste des Départements</a></li>
        </ul>
    </div>
</li>
<li class="side-nav-item">
    <a data-bs-toggle="collapse" href="#sidebarPaie" class="side-nav-link">
        <i class="ri-folder-user-line"></i>
        <span>Paie</span>
        <span class="menu-arrow"></span>
    </a>

    <div class="collapse" id="sidebarPaie">
        <ul class="side-nav-second-level">
            <li><a href="{{ route('bulletins.create') }}">Faire une paie</a></li>
            <li><a href="{{ route('bulletins.index') }}">Liste des bulletins</a></li>
        </ul>
    </div>
</li>
<li class="side-nav-item">
    <a data-bs-toggle="collapse" href="#sidebarConge" class="side-nav-link">
        <i class="ri-calendar-line"></i>
        <span>Congés</span>
        <span class="menu-arrow"></span>
    </a>

    <div class="collapse" id="sidebarConge">
        <ul class="side-nav-second-level">
            <li><a href="{{ route('conges.voir') }}">Liste des congés</a></li>
            <li class="side-nav-item">
                <a href="{{ route('conges.approbation.dgRh') }}" class="side-nav-link">
                    {{-- <i class="ri-file-shield-line"></i> 
                                    <span>Validation finale Congés</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('conges.traiter.liste-dg') }}" class="side-nav-link">
                    <i class="ri-task-line"></i>
                    <span>Congés traités</span>
                </a>
            </li>
            <li><a href="{{ route('justisificatifs.absence.rh') }}">valider une absence</a></li>
            <li><a href="{{ route('justificatifs.absence.traiter') }}">Absences traité</a></li>
        </ul>
    </div>
</li>
{{-- @endif --}}


{{-- ================================================= --}}
{{-- RESPONSABLE DE SERVICE => Valide congés du service --}}
{{-- ================================================= --}}
{{-- @if(auth()->user()->isResponsableService()) --}}
{{-- dd('(auth()->user()->isResponsableService())'); 
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#serviceConge" class="side-nav-link">
                        <i class="ri-calendar-line"></i>
                        <span>Congés</span> 
                        <span class="menu-arrow"></span>
                    </a>

                    <div class="collapse" id="serviceConge">
                        <ul class="side-nav-second-level">
                            {{-- <li><a href="{{ route('create.service') }}">Créer un service</a></li>
<li><a href="{{ route('services.liste') }}">Liste des services</a></li>
<li><a href="{{ route('create.employe') }}">Créer un employé</a></li>
<li><a href="{{ route('employe.liste') }}">Liste des employés</a></li>
<li><a href="{{ route('conges.voir') }}">Demander un congés</a></li>
<li class="side-nav-item">
    <a href="{{ route('conges.approbation.service') }}" class="side-nav-link">
        {{-- <i class="ri-file-check-line"></i>
                                    <span>Validation Congés (Service)</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('conges.traiter.liste-service') }}" class="side-nav-link">
        {{-- <i class="ri-file-check-line"></i> 
                                    <span>Demande traitée</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="side-nav-item">
                    <a href="{{ route('conges.approbation.service') }}" class="side-nav-link">
        {{-- <i class="ri-file-check-line"></i>
                        <span>Validation Congés (Service)</span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{ route('justificatifs.absence.liste') }}" class="side-nav-link">
        <i class="ri-calendar-line"></i>
        <span>Justifiés son absence</span>
        {{-- <span class="menu-arrow"></span>
                    </a>
                </li>
                {{-- <li class="side-nav-item">
                    <a href="{{ route('employe.contrat') }}" class="side-nav-link">
        <i class="ri-file-list-3-line"></i>
        <span>Mes contrats</span>
    </a>
</li>
<li class="side-nav-item">
    <a href="{{ route('employe.contrat') }}" class="side-nav-link">
        <i class="ri-file-list-3-line"></i>
        <span>Mes documents</span>
        {{-- <span class="menu-arrow"></span> 
                    </a>

                    {{-- <div class="collapse" id="sidebarDoc">
                        <ul class="side-nav-second-level">
                            <li class="side-nav-item">
                                <a href="{{ route('employe.contrat') }}" class="side-nav-link">

        <span>Mes contrats</span>
    </a>
</li>
</ul>
</div>
</li>
{{-- @endif --}}


{{-- ====================================================== --}}
{{-- RESPONSABLE DE DÉPARTEMENT => Valide congés département --}}
{{-- ====================================================== --}}
{{-- @if(auth()->user()->isResponsableDepartement()) --}}
{{-- <li class="side-nav-item">
                    <a href="{{ route('conges.approbation.departement') }}" class="side-nav-link">
<i class="ri-file-check-fill"></i>
<span>Validation Congés (Département)</span>
</a>
</li>
<li class="side-nav-item">
    <a data-bs-toggle="collapse" href="#serviceConge" class="side-nav-link">
        <i class="ri-calendar-line"></i>
        <span>Congés</span>
        <span class="menu-arrow"></span>
    </a>

    <div class="collapse" id="serviceConge">
        <ul class="side-nav-second-level">
            {{-- <li><a href="{{ route('create.service') }}">Créer un service</a>
</li>
<li><a href="{{ route('services.liste') }}">Liste des services</a></li>
<li><a href="{{ route('create.employe') }}">Créer un employé</a></li>
<li><a href="{{ route('employe.liste') }}">Liste des employés</a></li>
<li><a href="{{ route('conges.voir') }}">Demander un congés</a></li>
<li class="side-nav-item">
    <a href="{{ route('conges.approbation.departement') }}" class="side-nav-link">
        {{-- <i class="ri-file-check-line"></i> 
                                   <span>Validation Congés</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('conges.traiter.liste') }}" class="side-nav-link">
        {{-- <i class="ri-file-check-line"></i>
                                    <span>Demande traitée</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="side-nav-item">
                    <a href="{{ route('justificatifs.absence.liste') }}" class="side-nav-link">
        <i class="ri-calendar-line"></i>
        <span>Justifiés son absence</span>
        {{-- <span class="menu-arrow"></span> 
                    </a>
                </li>
                {{-- <li class="side-nav-item">
                    <a href="{{ route('employe.contrat') }}" class="side-nav-link">
        <i class="ri-file-list-3-line"></i>
        <span>Mes contrats</span>
    </a>
</li>
<li class="side-nav-item">
    <a href="{{ route('employe.contrat') }}" class="side-nav-link">
        <i class="ri-file-list-3-line"></i>
        <span>Mes documents</span>
        {{-- <span class="menu-arrow"></span>
                    </a>

                    {{-- <div class="collapse" id="sidebarDoc">
                        <ul class="side-nav-second-level">
                            <li class="side-nav-item">
                                <a href="{{ route('employe.contrat') }}" class="side-nav-link">

        <span>Mes contrats</span>
    </a>
</li>
</ul>
</div>
</li>
{{-- @endif --}}


{{-- ======================= --}}
{{-- DG ou RH -> dernière validation --}}
{{-- ======================= --}}
{{-- @if(auth()->user()->isRole('dg') ) --}}

{{-- || auth()->user()->isRole('rh') 
                    
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarDg" class="side-nav-link">
                        <i class="ri-calendar-line"></i>
                        <span>Congés</span> 
                        <span class="menu-arrow"></span>
                    </a>

                    <div class="collapse" id="sidebarDg">
                        <ul class="side-nav-second-level">
                            {{-- <li><a href="{{ route('create.service') }}">Créer un service</a></li>
<li><a href="{{ route('services.liste') }}">Liste des services</a></li>
<li><a href="{{ route('create.employe') }}">Créer un employé</a></li>
<li><a href="{{ route('employe.liste') }}">Liste des employés</a></li>
<li class="side-nav-item">
    <a href="{{ route('conges.approbation.dgRh') }}" class="side-nav-link">
        {{-- <i class="ri-file-shield-line"></i>
                                    <span>Validation finale Congés</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('conges.traiter.liste-dg') }}" class="side-nav-link">
        {{-- <i class="ri-task-line"></i>
                                    <span>Congés traités</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                
            {{-- @endif --}}
