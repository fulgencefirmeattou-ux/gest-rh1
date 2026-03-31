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
            <li><a href="{{ route('employe.liste') }}">Liste des employés</a></li> --}}
            <li><a href="{{ route('conges.voir') }}">Demander un congés</a></li>
            <li class="side-nav-item">
                <a href="{{ route('conges.approbation.service') }}" class="side-nav-link">
                    {{-- <i class="ri-file-check-line"></i> --}}
                    <span>Validation Congés (Service)</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('conges.traiter.liste-service') }}" class="side-nav-link">
                    {{-- <i class="ri-file-check-line"></i> --}}
                    <span>Demande traitée</span>
                </a>
            </li>
        </ul>
    </div>
</li>
{{-- <li class="side-nav-item">
    <a href="{{ route('conges.approbation.service') }}" class="side-nav-link">
        {{-- <i class="ri-file-check-line"></i>
        <span>Validation Congés (Service)</span>
    </a>
</li> --}}
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