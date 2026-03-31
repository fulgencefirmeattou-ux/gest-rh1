<?php
return [

    'admin' => [
        ['name' => 'Dashboard', 'route' => 'dashboard'],
        ['name' => 'Gestion des utilisateurs', 'route' => 'users.index'],
        ['name' => 'Toutes les demandes', 'route' => 'conges.index'],
    ],

    'dg' => [
        ['name' => 'Dashboard DG', 'route' => 'dg.dashboard'],
        ['name' => 'Demandes à valider', 'route' => 'conges.dg'],
    ],

    'rh' => [
        ['name' => 'RH Dashboard', 'route' => 'rh.dashboard'],
        ['name' => 'Gestion des congés', 'route' => 'conges.rh'],
    ],

    'responsable_service' => [
        ['name' => 'Mon service', 'route' => 'service.dashboard'],
        ['name' => 'Demandes du service', 'route' => 'conges.service'],
    ],

    'responsable_departement' => [
        ['name' => 'Mon département', 'route' => 'departement.dashboard'],
        ['name' => 'Demandes du département', 'route' => 'conges.departement'],
    ],

    'employe' => [
        ['name' => 'Mon profil', 'route' => 'profile.show'],
        ['name' => 'Mes demandes', 'route' => 'conges.mes'],
    ],

];
?>


