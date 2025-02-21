<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification des Bons d'Achat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Vérifier un Bon d'Achat</h2>
            </div>
            <div class="card-body">
                <!-- Formulaire de vérification -->
                <form action="{{ route('bons.verifier') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Numéro du bon</label>
                        <input type="text" name="numero" class="form-control" required placeholder="Ex: 20252357S">
                    </div>
                    <button type="submit" class="btn btn-success">Vérifier</button>
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
            </div>
        </div>
    </div>
</body>
</html>