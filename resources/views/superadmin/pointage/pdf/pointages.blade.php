<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #333; }
        h2   { font-size: 14px; margin-bottom: 4px; }
        p    { margin: 0 0 8px; color: #666; font-size: 9px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        thead th { background: #1a1a2e; color: #fff; padding: 6px 4px; text-align: center; font-size: 9px; }
        tbody td { padding: 5px 4px; border-bottom: 1px solid #e0e0e0; text-align: center; font-size: 9px; }
        tbody tr:nth-child(even) { background: #f8f9fa; }
        .badge-success  { background: #198754; color:#fff; padding:2px 5px; border-radius:3px; }
        .badge-danger   { background: #dc3545; color:#fff; padding:2px 5px; border-radius:3px; }
        .badge-primary  { background: #0d6efd; color:#fff; padding:2px 5px; border-radius:3px; }
        .badge-warning  { background: #ffc107; color:#333; padding:2px 5px; border-radius:3px; }
        .badge-secondary{ background: #6c757d; color:#fff; padding:2px 5px; border-radius:3px; }
        .text-right { text-align: right; }
        .footer { margin-top: 20px; font-size: 8px; color: #999; text-align: center; }
        .dot { display:inline-block; width:9px; height:9px; border-radius:50%; }
        .dot-rouge { background:#dc3545; }
        .dot-vert  { background:#198754; }
    </style>
</head>
<body>
    <h2>Rapport de Pointage — {{ ucfirst($nomMois) }} {{ $annee }}</h2>
    <p>Généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</p>

    @if($pointages->isEmpty())
        <p>Aucun pointage pour cette période.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th style="text-align:left">Employé</th>
                    <th>Date</th>
                    <th>Arrivée</th>
                    <th>Départ</th>
                    <th>Dbt Pause</th>
                    <th>Fin Pause</th>
                    <th>Travaillées</th>
                    <th>Sup</th>
                    <th>Manquantes</th>
                    <th>Absent</th>
                    <th>Type</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pointages as $p)
                    @php
                        $statC = $p->statut === 'VALIDE' ? 'badge-success' : ($p->statut === 'REFUSE' ? 'badge-danger' : 'badge-warning');
                        $htC   = $p->heures_sup > 0 ? 'badge-primary' : ($p->heures_manquantes > 0 ? 'badge-danger' : 'badge-success');
                    @endphp
                    <tr>
                        <td style="text-align:left">{{ $p->employe->nom ?? '—' }} {{ $p->employe->prenom ?? '' }}</td>
                        <td>{{ $p->date->format('d/m/Y') }}</td>
                        <td>{{ $p->heure_arrivee ? \Carbon\Carbon::createFromTimeString($p->heure_arrivee)->format('H:i') : '—' }}</td>
                        <td>{{ $p->heure_depart  ? \Carbon\Carbon::createFromTimeString($p->heure_depart)->format('H:i')  : '—' }}</td>
                        <td>{{ $p->heure_debut_pause ? \Carbon\Carbon::createFromTimeString($p->heure_debut_pause)->format('H:i') : '—' }}</td>
                        <td>{{ $p->heure_fin_pause   ? \Carbon\Carbon::createFromTimeString($p->heure_fin_pause)->format('H:i')   : '—' }}</td>
                        <td><span class="{{ $htC }}">{{ $p->heures_travaillees_format }}</span></td>
                        <td>{{ $p->heures_sup > 0 ? '+'.number_format($p->heures_sup,2).'h' : '—' }}</td>
                        <td>{{ $p->heures_manquantes > 0 ? '-'.number_format($p->heures_manquantes,2).'h' : '—' }}</td>
                        <td>
                            @if($p->absent)
                                <span class="dot dot-rouge" title="Absent"></span>
                            @else
                                <span class="dot dot-vert" title="Présent"></span>
                            @endif
                        </td>
                        <td><span class="badge-secondary">{{ $p->type_jour }}</span></td>
                        <td><span class="{{ $statC }}">{{ $p->statut }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totaux --}}
        <table style="margin-top:16px; width:auto; float:right;">
            <tr>
                <td style="padding:4px 8px; font-weight:bold;">Total heures travaillées :</td>
                <td style="padding:4px 8px;">{{ round($pointages->sum('heures_travaillees'), 2) }}h</td>
            </tr>
            <tr>
                <td style="padding:4px 8px; font-weight:bold;">Total heures sup :</td>
                <td style="padding:4px 8px;">+{{ round($pointages->sum('heures_sup'), 2) }}h</td>
            </tr>
            <tr>
                <td style="padding:4px 8px; font-weight:bold;">Total heures manquantes :</td>
                <td style="padding:4px 8px;">-{{ round($pointages->sum('heures_manquantes'), 2) }}h</td>
            </tr>
        </table>
    @endif

    <div class="footer" style="clear:both; padding-top:30px;">
        Document généré automatiquement — Système de Gestion RH
    </div>
</body>
</html>
