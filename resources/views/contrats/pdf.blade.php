<!doctype html>
<html>
<head><meta charset="utf-8"><style>body{font-family:DejaVu Sans, sansserif;}</style></head>
<body>
<h2>Contrat de travail</h2>
<p>Employé: {{ $contrat->employe->nom }} {{ $contrat->employe->prenom }}</p>
<p>Type: {{ $contrat->type_contrat }}</p>
<p>Période: {{ $contrat->date_debut->format('d/m/Y') }} @if($contrat->date_fin) - {{ $contrat->date_fin->format('d/m/Y') }} @endif</p>
<p>Salaire: {{ number_format($contrat->salaire_base,2) }} FCFA</p>
<h4>Primes</h4>
<ul>
@foreach($contrat->primes as $p)<li>{{ $p->libelle }} —
{{ number_format($p->montant,2) }}</li>@endforeach
</ul>
<p style="margin-top:40px;">Signature employeur: ____________</p>
<p>Signature employé: ____________</p>
</body>
</html>