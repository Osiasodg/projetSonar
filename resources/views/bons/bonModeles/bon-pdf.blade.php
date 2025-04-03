<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bons d'Achat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #fff;
        }
        .page {
            width: 96%;
            height: 880px; /* Une page A4 complète */
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            page-break-after: always;
        }
        .container {
            width: 96%;
            height: 48%; /* Deux bons sur une page */
            margin: 0 auto;
            padding: 30px;
            border: 3px solid #333;
            background: #f9f9f9;
            position: relative;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }
        
        /* Positionnement des éléments */
        .logo {
            position: absolute;
            left: 30px;
            top: 20px;
            width: 110px; /* Taille réduite */
        }
        .qrcode {
            position: absolute;
            right: 30px;
            top: 20px;
            width: 100px; /* Taille réduite */
        }
        .header {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            margin: 5px 0;
            text-transform: uppercase;
        }
        .numero-bon {
            text-align: center;
            font-size: 18px;
            margin: 5px 0;
        }
        .montant {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            margin: 15px 0;
            color: green;
        }
        .beneficiaire {
            font-size: 20px;
            margin: 8px 0;
        }
        .contact {
            font-size: 18px;
            margin: 5px 0;
            color: blue;
        }
        .validite {
            font-size: 18px;
            margin: 5px 0;
            color: red;
            font-weight: bold;
        }
        .retour {
            font-size: 18px;
            margin: 10px 0;
            font-style: italic;
        }
        .signature {
            margin-top: auto; /* Pousse la signature vers le bas */
            text-align: right;
            padding-top: 10px;
        }
        .signature-nom {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .signature-poste {
            text-transform: uppercase;
        }
        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    @foreach (array_chunk($bons->toArray(), 2) as $bonPair)
        <div class="page">
            @foreach ($bonPair as $bon)
                <div class="container">
                    <!-- Logo en haut à gauche -->
                    <div class="logo">
                        <img src="{{ $logoPath }}" alt="Logo" onerror="this.style.display='none'">
                    </div>
                    
                    <!-- QR Code en haut à droite -->
                    <div class="qrcode">
                        <img src="{{ $qrCodes[$bon['id']] }}" alt="QR Code">
                    </div>
                    
                    <!-- Titre principal -->
                    <div class="header">CADEAU</div>
                    
                    <!-- Numéro de bon -->
                    <div class="numero-bon">
                        <strong>BON D'ACHAT N° :</strong> <strong>{{ $bon['numero'] }}</strong>
                    </div>
                    
                    <!-- Montant -->
                    <p class="montant">{{ number_format($bon['montant'], 0, ',', ' ') }} FCFA</p>
                    
                    <!-- Informations bénéficiaire -->
                    <div class="beneficiaire"><strong>Bénéficiaire :</strong> {{ $bon['beneficiaire'] }}</div>
                    <p class="contact">Demander {{ $bon['recepteur'] }} - Tel: {{ $bon['telephone'] }}</p>
                    <p class="validite">Valable jusqu'au {{ date('d/m/Y', strtotime($bon['date_validite'])) }}</p>
                    
                    <!-- Instructions de retour -->
                    <div class="retour">
                        A retourner à la <strong>{{ $entite }}</strong> accompagné de la facture pour règlement.
                    </div>
                    
                    <!-- Signature -->
                    <div class="signature">
                        @php
                            $signataire = $signataires[$bon['id']] ?? null;
                        @endphp
                        @if ($signataire)
                            <div class="signature-nom">{{ $signataire['nom'] }} {{ $signataire['prenom'] }}</div>
                            <div class="signature-poste">{{ strtoupper($signataire['poste']) }}</div>
                        @else
                            <div class="signature-nom">Signataire non défini</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</body>
</html>
