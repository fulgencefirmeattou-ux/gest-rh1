<li class="side-nav-item">
    <a data-bs-toggle="collapse" href="#departementConge" class="side-nav-link">
        <i class="ri-calendar-line"></i>
        <span>Congés</span> 
        <span class="menu-arrow"></span>
    </a>

    <div class="collapse" id="departementConge">
        <ul class="side-nav-second-level">
            {{-- <li><a href="{{ route('create.service') }}">Créer un service</a></li>
            <li><a href="{{ route('services.liste') }}">Liste des services</a></li>
            <li><a href="{{ route('create.employe') }}">Créer un employé</a></li>
            <li><a href="{{ route('employe.liste') }}">Liste des employés</a></li> --}}
            <li><a href="{{ route('conges.voir') }}">Demander un congés</a></li>
            <li class="side-nav-item">
                <a href="{{ route('conges.approbation.departement') }}" class="side-nav-link">
                    {{-- <i class="ri-file-check-line"></i> --}}
                    <span>Validation Congés</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('conges.traiter.liste') }}" class="side-nav-link">
                    {{-- <i class="ri-file-check-line"></i> --}}
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
        {{-- <span class="menu-arrow"></span> --}}
    </a>
</li>
                {{-- <li class="side-nav-item">
                    <a href="{{ route('employe.contrat') }}" class="side-nav-link">
                        <i class="ri-file-list-3-line"></i>
                        <span>Mes contrats</span>
                    </a>
                </li> --}}
    @php
        $departement = auth()->user()
            ->employe
            ->departementResponsable;
    @endphp


    @if($departement)
        <li class="side-nav-item">
            <a href="{{ route('departement.liste.employes', $departement->id) }}" class="side-nav-link">
                <i class="ri-file-list-3-line"></i>
                <span>Liste Employés</span>
            </a>
        </li>
    @endif

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