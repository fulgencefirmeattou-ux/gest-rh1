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