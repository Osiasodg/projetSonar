<!DOCTYPE html>
<html>
<head>
    <title>Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .auth-section { border-right: 2px solid #dee2e6; }
        .form-switcher { cursor: pointer; color: #0d6efd; }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center">Portail SONAR</h3>
                </div>

                <div class="card-body">
                    <!-- Section Vérification -->
                    <div id="verificationSection">
                        <h4 class="mb-4">Vérification de Bon</h4>
                        <form method="POST" action="{{ route('bons.verifier') }}">
                            @csrf
                            <div class="mb-3">
                                <input type="text" class="form-control" name="numero" 
                                       placeholder="Entrez le numéro du bon" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Vérifier</button>
                        </form>
                    </div>

                    <hr class="my-4">

                    <!-- Section Connexion -->
                    <div id="loginSection">
                        <h4 class="mb-4">Connexion Staff</h4>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <input type="email" class="form-control" name="email" 
                                       placeholder="Email professionnel" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" name="password" 
                                       placeholder="Mot de passe" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Se Connecter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>