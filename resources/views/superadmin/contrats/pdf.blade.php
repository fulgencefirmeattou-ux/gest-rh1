<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{ public_path('assets/css/styles-pdf.css') }}">
</head>
<body>

<h2>Contrat de travail</h2>

<div class="section">
    Entre la société <span class="label">Ma Société SARL</span>
    et le salarié <span class="label">{{ $contrat->employe->nom }} {{ $contrat->employe->prenom }}</span>
</div>

@if($contrat->employe->adresse)
<div class="section">
    <span class="label">Adresse :</span> {{ $contrat->employe->adresse }}
</div>
@endif

<hr>

<div class="section">
    <span class="label">Type de contrat :</span> {{ $contrat->type_contrat }}
</div>

<div class="section">
    <span class="label">Poste :</span> {{ $contrat->employe->poste->name ?? '—' }}
</div>

<div class="section">
    <span class="label">Date de début :</span> {{ \Carbon\Carbon::parse($contrat->date_debut)->format('d/m/Y') }}
</div>

@if($contrat->date_fin)
<div class="section">
    <span class="label">Date de fin :</span> {{ \Carbon\Carbon::parse($contrat->date_fin)->format('d/m/Y') }}
</div>
@endif

<div class="section">
    <span class="label">Salaire mensuel de base :</span>
    {{ number_format($contrat->salaire_base, 0, ',', ' ') }} FCFA
</div>

<div class="section">
    <span class="label">Mode de calcul :</span> {{ ucfirst($contrat->mode_calcul) }}
</div>

<div class="section">
    <span class="label">Heures de travail / semaine :</span> {{ $contrat->heures_par_semaine ?? 40 }} h
</div>

@if($contrat->primes->isNotEmpty())
<hr>
<div class="section">
    <span class="label">Primes :</span>
    <table class="primes-table">
        <thead>
            <tr>
                <th>Libellé</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contrat->primes as $prime)
                <tr>
                    <td>{{ $prime->libelle }}</td>
                    <td>{{ number_format($prime->montant, 0, ',', ' ') }} FCFA</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<hr>

<div class="section">
    Fait le : {{ now()->format('d/m/Y') }}
</div>

<table class="signatures">
    <tr>
        <td>Signature employeur</td>
        <td>Signature employé</td>
    </tr>
</table>

</body>
</html>
