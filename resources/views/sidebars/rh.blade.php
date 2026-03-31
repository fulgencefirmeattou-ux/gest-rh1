<li class="side-nav-item">
    <a href="{{ route('employe.contrat') }}" class="side-nav-link">
        <i class="ri-file-list-3-line"></i>
        <span>Mes documents</span> 
        {{-- <span class="menu-arrow"></span> --}}
    </a>

    {{-- <div class="collapse" id="sidebarDoc">
        <ul class="side-nav-second-level">
            <li class="side-nav-item">
                <a href="{{ route('employe.contrat') }}" class="side-nav-link">
                    
                    <span>Mes contrats</span>
                </a>
            </li>
        </ul>
    </div> --}}
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
                    {{-- <i class="ri-file-shield-line"></i> --}}
                    <span>Validation finale Congés</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('conges.traiter.liste-dg') }}" class="side-nav-link">
                    {{-- <i class="ri-task-line"></i> --}}
                    <span>Congés traités</span>
                </a>
            </li>
            <li><a href="{{ route('justisificatifs.absence.rh') }}">valider une absence</a></li>
            <li><a href="{{ route('justificatifs.absence.traiter') }}">Absences traité</a></li>
        </ul>
    </div>
</li>
<li class="side-nav-item">
    <a data-bs-toggle="collapse" href="#sidebarRecrutement" class="side-nav-link">
        <i class="ri-folder-user-line"></i>
        <span>Recrutements</span> 
        <span class="menu-arrow"></span>
    </a>

    <div class="collapse" id="sidebarRecrutement">
        <ul class="side-nav-second-level">
            <li><a href="{{ route('recrutement.create') }}">Créer une offre</a></li>
            <li><a href="{{ route('recrutement.liste') }}">Liste des offres</a></li>
            {{-- <li><a href="{{ route('contrats.create') }}">Créer un contrat</a></li>
            <li><a href="{{ route('contrats.index') }}">Liste des contrats</a></li> --}}
        </ul>
    </div>
</li>