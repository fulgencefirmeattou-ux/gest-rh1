<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body  { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #333; }
        h2    { font-size: 13px; margin-bottom: 2px; }
        p     { margin: 0 0 8px; color: #666; font-size: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        thead th { background: #1a1a2e; color: #fff; padding: 5px 3px; text-align: center; font-size: 8px; }
        tbody td { padding: 4px 3px; border-bottom: 1px solid #e0e0e0; text-align: center; font-size: 8px; }
        .row-ok      { background: #d1e7dd; }
        .row-absent  { background: #f8d7da; }
        .row-sup     { background: #cfe2ff; }
        .row-warning { background: #fff3cd; }
        .row-weekend { background: #e9ecef; color: #999; }
        .footer { margin-top: 20px; font-size: 7px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <h2>Calendrier de présence — {{ $employe->nom }} {{ $employe->prenom }}</h2>
    <p>{{ ucfirst($nomMois) }} {{ $annee }} — Généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th style="text-align:left">Jour</th>
                <th>Arrivée</th>
                <th>Départ</th>
                <th>Pause</th>
                <th>Travaillées</th>
                <th>Sup</th>
                <th>Manquantes</th>
                <th>Type</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @for($jour = 1; $jour <= $nbJours; $jour++)
                @php
                    $dateStr   = sprintf('%04d-%02d-%02d', $annee, $mois, $jour);
                    $date      = \Carbon\Carbon::parse($dateStr);
                    $isWeekend = $date->isWeekend();
                    $p         = $pointages->get($dateStr);

                    if ($isWeekend)                             $rowClass = 'row-weekend';
                    elseif (!$p || !$p->heure_arrivee)         $rowClass = 'row-absent';
                    elseif ($p && $p->heures_sup > 0)          $rowClass = 'row-sup';
                    elseif ($p && $p->heures_manquantes > 0)   $rowClass = 'row-warning';
                    elseif ($p && in_array($p->type_jour, ['CONGE','FERIE'])) $rowClass = 'row-warning';
                    else                                        $rowClass = 'row-ok';
                @endphp
                <tr class="{{ $rowClass }}">
                    <td style="text-align:left; font-weight:bold;">
                        {{ $date->locale('fr')->isoFormat('ddd D') }}
                        @if($isWeekend)(WE)@endif
                    </td>
                    <td>{{ $p && $p->heure_arrivee ? \Carbon\Carbon::createFromTimeString($p->heure_arrivee)->format('H:i') : ($isWeekend ? '—' : '✗') }}</td>
                    <td>{{ $p && $p->heure_depart  ? \Carbon\Carbon::createFromTimeString($p->heure_depart)->format('H:i')  : '—' }}</td>
                    <td>
                        @if($p && $p->heure_debut_pause && $p->heure_fin_pause)
                            {{ \Carbon\Carbon::createFromTimeString($p->heure_debut_pause)->format('H:i') }}→{{ \Carbon\Carbon::createFromTimeString($p->heure_fin_pause)->format('H:i') }}
                        @else
                            —
                        @endif
                    </td>
                    <td style="font-weight:bold;">{{ $p && $p->heures_travaillees !== null ? $p->heures_travaillees_format : '—' }}</td>
                    <td>{{ $p && $p->heures_sup > 0 ? '+'.number_format($p->heures_sup,2).'h' : '—' }}</td>
                    <td>
                        @if($p && $p->heures_manquantes > 0)
                            -{{ number_format($p->heures_manquantes,2) }}h
                        @elseif(!$isWeekend && (!$p || !$p->heure_arrivee))
                            -8.00h
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $p ? $p->type_jour : '—' }}</td>
                    <td>{{ $p ? $p->statut : '—' }}</td>
                </tr>
            @endfor
        </tbody>
    </table>

    <div class="footer">
        Document généré automatiquement — Système de Gestion RH
    </div>
</body>
</html>
