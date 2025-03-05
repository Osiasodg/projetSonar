<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bon d'Achat</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            border: 5px solid #333;
            border-radius: 10px;
            text-align: center;
            background: #f9f9f9;
        }
        .header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #007bff;
            text-transform: uppercase;
        }
        .montant {
            color: green;
            font-size: 30px;
            font-weight: bold;
            margin: 10px 0;
        }
        .validite {
            color: red;
            font-size: 18px;
            margin-top: 10px;
            font-weight: bold;
        }
        .recepteur {
            font-size: 16px;
            color: blue;
            margin-top: 10px;
        }
        .signature {
            margin-top: 40px;
            font-weight: bold;
            text-align: right;
        }
        .qrcode {
            text-align: center;
            margin-top: 20px;
        }
        .logo {
            margin-bottom: 10px;
            text-align: center;
        }
        .retour {
            margin-top: 20px;
            font-style: italic;
            font-size: 14px;
        }
        img { max-width: 120px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo -->
        <div class="logo">
            <img src="{{ $logoPath }}" alt="Logo">
        </div>

        <!-- Titre -->
        <div class="header">Bon d'achat</div>

        <!-- Numéro de bon -->
        <h2>BON N°: {{ $bon->numero }}</h2>

        <!-- Montant en grand -->
        <p class="montant">{{ number_format($bon->montant, 0, ',', ' ') }} FCFA</p>

        <!-- Informations du bénéficiaire -->
        <h3><strong>Bénéficiaire :</strong> {{ $bon->beneficiaire }}</h3>
        <p class="recepteur">Demander {{ $bon->recepteur }} - Tel: {{ $bon->telephone }}</p>
        <p class="validite">Valable jusqu’au {{ date('d/m/Y', strtotime($bon->date_validite)) }}</p>

        <!-- QR Code -->
        <div class="qrcode">
            <img src="{{ storage_path('app/public/qrcode.png') }}" alt="QR Code">
        </div>

        <!-- Retourner à -->
        <div class="retour">
            A retourner à la <strong>{{ $entite }}</strong> accompagné de la facture pour règlement.
        </div>

        <!-- Signature du Directeur -->
        <div class="signature">
            <p>{{ strtoupper($directeur) }}</p>
            <p><u>DIRECTEUR GÉNÉRAL</u></p>
        </div>
    </div>
</body>
</html>
