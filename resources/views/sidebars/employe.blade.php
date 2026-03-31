<li class="side-nav-item">
    <a href="{{ route('conges.voir') }}" class="side-nav-link">
        <i class="ri-calendar-line"></i>
        <span>CongésA</span> 
        {{-- <span class="menu-arrow"></span> --}}
    </a>
</li>
<li class="side-nav-item">
    <a href="{{ route('justificatifs.absence.liste') }}" class="side-nav-link">
        <i class="ri-calendar-line"></i>
        <span>Justifiés son absence</span> 
        <span class="menu-arrow"></span>
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