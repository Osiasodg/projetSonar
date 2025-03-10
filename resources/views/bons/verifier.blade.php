<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification des Bons d'Achat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Animation pour le symbole de succès (V dans un cercle) */
        @keyframes drawCircle {
            0% {
                stroke-dashoffset: 283;
            }
            100% {
                stroke-dashoffset: 0;
            }
        }

        @keyframes drawCheck {
            0% {
                stroke-dashoffset: 100;
            }
            100% {
                stroke-dashoffset: 0;
            }
        }

        .success-circle {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            display: block;
        }

        .success-circle circle {
            stroke: #28a745; /* Couleur du cercle */
            stroke-width: 5;
            stroke-dasharray: 283; /* Circonférence du cercle */
            stroke-dashoffset: 283;
            animation: drawCircle 1s ease-in-out forwards;
        }

        .success-circle path {
            stroke: #28a745; /* Couleur du V */
            stroke-width: 5;
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: drawCheck 0.5s ease-in-out 1s forwards;
        }

        .confirmation-message {
            text-align: center;
            margin-top: 20px;
            font-size: 1.2rem;
            color: #28a745;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Vérifier et Valider un Bon d'Achat</h2>
            </div>
            <div class="card-body">
                <!-- Formulaire de VÉRIFICATION -->
                <form action="{{ route('bons.verifier') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Numéro du bon</label>
                        <input type="text" name="numero" class="form-control" required placeholder="Ex: 20252357S">
                    </div>
                    <button type="submit" class="btn btn-info">Vérifier</button>
                </form>

                <!-- Messages de succès/erreur -->
                @if(session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger mt-3">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Formulaire de VALIDATION (affiché uniquement si le bon est valide) -->
                @if(session('bon'))
                    <hr>
                    <div class="mt-4">
                        <h4>Détails du Bon</h4>
                        <p><strong>Numéro :</strong> {{ session('bon')->numero }}</p>
                        <p><strong>Montant :</strong> {{ session('bon')->montant }} FCFA</p>
                        <p><strong>Date de validité :</strong> {{ session('bon')->date_validite }}</p>
                        <p><strong>Statut :</strong> {{ session('bon')->utilise ? 'Utilisé' : 'Non utilisé' }}</p>

                        <!-- Bouton pour ouvrir le modal de confirmation -->
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmationModal">
                            Valider le Bon
                        </button>
                    </div>

                    <!-- Modal de confirmation -->
                    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmationModalLabel">Confirmation de validation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Contenu initial du modal -->
                                    <div id="confirmationContent">
                                        <p>Voulez-vous vraiment valider ce bon ?</p>
                                        <p><strong>Numéro :</strong> {{ session('bon')->numero }}</p>
                                        <p><strong>Montant :</strong> {{ session('bon')->montant }} FCFA</p>
                                    </div>

                                    <!-- Contenu de confirmation (caché par défaut) -->
                                    <div id="successContent" style="display: none;">
                                        <!-- Animation de succès -->
                                        <svg class="success-circle" viewBox="0 0 100 100">
                                            <circle cx="50" cy="50" r="45" fill="none" />
                                            <path d="M20,50 L40,70 L80,30" fill="none" />
                                        </svg>
                                        <!-- Message de confirmation -->
                                        <div class="confirmation-message">
                                            Bon validé avec succès !
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <!-- Formulaire de validation -->
                                    <form action="{{ route('bons.valider') }}" method="POST" id="validationForm">
                                        @csrf
                                        <input type="hidden" name="numero" value="{{ session('bon')->numero }}">
                                        <button type="submit" class="btn btn-primary">Confirmer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Script Bootstrap (nécessaire pour les modals) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Gestion de la validation du bon
        document.getElementById('validationForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Empêche la soumission du formulaire

            // Masque le contenu initial et affiche le contenu de confirmation
            document.getElementById('confirmationContent').style.display = 'none';
            document.getElementById('successContent').style.display = 'block';

            // Soumet le formulaire après un court délai (pour permettre l'affichage de l'animation)
            setTimeout(() => {
                event.target.submit();
            }, 3000); // 3 secondes de délai
        });
    </script>
</body>
</html>