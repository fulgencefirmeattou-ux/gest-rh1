<!doctype html>
<html>
<head><meta charset="utf-8"><style>body{font-family:DejaVu Sans, sansserif;
font-size:12px;}table{width:100%;border-collapse:collapse;}
td,th{padding:6px;border-bottom:1px solid #eee;} .right{textalign:
right;}</style></head>
<body>
<h3>Bulletin de paie — {{ $bulletin->mois }}</h3>
<p>Employé: {{ $bulletin->employe->nom }} {{ $bulletin->employe->prenom }}</p>
<table>
<thead><tr><th>Libellé</th><th class="right">Montant (FCFA)</th></
tr></thead>
<tbody>
@foreach($bulletin->items as $it)
<tr><td>{{ $it->libelle }}</td><td
class="right">{{ number_format($it->montant,2) }}</td></tr>
@endforeach
<tr><td class="bold">Net à payer</td><td
class="right"><strong>{{ number_format($bulletin->net_a_payer,2) }}</strong></td></tr>
</tbody>
</table>
</body>
</html>