<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bon d'Achat</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; font-size: 24px; font-weight: bold; }
        .montant { color: green; font-size: 26px; font-weight: bold; }
        .validite { color: red; font-size: 18px; }
        .recepteur { font-size: 16px; color: blue; }
        .signature { margin-top: 40px; font-weight: bold; text-align: right; }
        .qrcode { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">BON D'ACHAT N° {{ $bon->numero }}</div>
    <p class="montant">{{ number_format($bon->montant, 0, ',', ' ') }} FCFA</p>
    <p><strong>Bénéficiaire :</strong> {{ $bon->beneficiaire }}</p>
    <p class="recepteur">Demander {{ $bon->recepteur }}. Tel: {{ $bon->telephone }}</p>
    <p class="validite">Valable jusqu’au {{ date('d/m/Y', strtotime($bon->date_validite)) }}</p>
    
    <div class="qrcode">{!! $qrCode !!}</div>

    <div class="signature">
        <p>{{ strtoupper($bon->directeur) }}</p>
        <p><u>DIRECTEUR GÉNÉRAL</u></p>
    </div>
</body>
</html>
