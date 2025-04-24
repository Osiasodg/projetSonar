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
            background: #f5f5f5;
            color: #222;
        }

        .page {
            width: 96%;
            height: 880px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            page-break-after: always;
            position: relative;
        }

        .container {
            width: 96%;
            height: 48%;
            margin: 0 auto;
            padding: 30px;
            border: 3px solid #004080;
            background: rgba(255, 255, 255, 0.9);
            position: relative;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            overflow: hidden;

            @if(isset($isPreview) && $isPreview)
                min-height: 400px;
                height: auto;
                padding-bottom: 60px;
            @else
                height: 48%; /* Deux bons par page */
            @endif
        }

        /* Filigrane */
        .filigrane {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 80px;
            font-weight: bold;
            color: rgba(0, 64, 128, 0.15);
            text-transform: uppercase;
            white-space: nowrap;
            filter: blur(2px);
            z-index: 0;
        }

        /* Positionnement des éléments */
        .logo {
            position: absolute;
            left: 30px; /* Passe le logo à gauche */
            top: 20px;
            width: 110px;
            z-index: 1;
        }

        .qrcode {
            position: absolute;
            left: 30px;
            bottom: 20px; /* Place le QR code en bas à gauche */
            width: 100px;
            z-index: 1;
        }

        .header {
            text-align: center;
            font-size: 30px;
            font-weight: bold;
            margin: 5px 0;
            color: #004080;
            text-transform: uppercase;
            z-index: 1;
        }

        .numero-bon {
            text-align: center;
            font-size: 18px;
            margin: 5px 0;
            z-index: 1;
        }

        .montant {
            text-align: center;
            font-size: 30px;
            font-weight: bold;
            margin: 15px 0;
            color: #008000;
            z-index: 1;
        }

        .beneficiaire {
            font-size: 20px;
            margin: 8px 0;
            z-index: 1;
        }

        .contact {
            font-size: 18px;
            margin: 5px 0;
            color: #002080;
            z-index: 1;
        }

        .validite {
            font-size: 18px;
            margin: 5px 0;
            color: #b00000;
            font-weight: bold;
            z-index: 1;
        }

        .retour {
            font-size: 18px;
            margin: 10px 0;
            font-style: italic;
            z-index: 1;
        }

        .signature {
            margin-top: auto;
            text-align: right;
            padding-top: 10px;
            z-index: 1;
        }

        .signature-nom {
            font-weight: bold;
            margin-bottom: 5px;
            z-index: 1;
        }

        .signature-poste {
            text-transform: uppercase;
            z-index: 1;
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
                    <!-- Filigrane -->
                    <div class="filigrane">GROUPE SONAR</div>

                    <!-- Logo en haut à gauche -->
                    <div class="logo">
                        <img src="{{ $logoPath }}" alt="Logo" onerror="this.style.display='none'">
                    </div>

                    <!-- QR Code en bas à gauche -->
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
