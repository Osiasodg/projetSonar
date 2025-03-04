<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bon</title>
</head>
<body>
    <h1>Bon</h1>
    <p>Numéro: {{ $bon->numero }}</p>
    <p>Bénéficiaire: {{ $bon->beneficiaire }}</p>
    <p>Montant: {{ $bon->montant }}</p>
    <p>Date de validité: {{ $bon->date_validite }}</p>
    <p>Récepteur: {{ $bon->recepteur }}</p>
    <p>Téléphone: {{ $bon->telephone }}</p>
    <p>Entité: {{ $bon->entite }}</p>
</body>
</html>