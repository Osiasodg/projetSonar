<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord - Gestionnaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <!-- Formulaire d'importation -->
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Gestionnaire - Importer un fichier Excel</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('gestionnaire.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Fichier Excel (.xlsx, .xls)</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Logo de l'entreprise</label>
                        <input type="file" name="logo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Entité</label>
                        <select name="entite" class="form-select" required>
                            <option value="SONAR VIE">SONAR VIE</option>
                            <option value="SONAR-IARD">SONAR-IARD</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date de validité</label>
                        <input type="date" name="date_validite" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Récepteur du bon</label>
                        <input type="text" name="recepteur" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Téléphone du récepteur</label>
                        <input type="text" name="telephone" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success">Importer</button>
                </form>
            </div>
        </div>

        <!-- Messages de succès/erreur -->
        @if(session('success'))
            <div class="alert alert-success mt-4">
                {{ session('success') }}
            </div>
        @endif

       <!-- Aperçu après importation -->
@if(session('bons') && count(session('bons')) > 0)
    <div class="card shadow mt-4">
        <div class="card-header bg-info text-white">
            <h3 class="mb-0">Aperçu des bénéficiaires ({{ count(session('bons')) }})</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>N° Bon</th>
                            <th>Bénéficiaire</th>
                            <th>Montant</th>
                            <th>Date validité</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(session('bons') as $bon)
                            <tr>
                                <td>{{ $bon->numero }}</td>
                                <td>{{ $bon->beneficiaire }}</td>
                                <td>{{ number_format($bon->montant, 0, ',', ' ') }} FCFA</td>
                                <td>{{ date('d/m/Y', strtotime($bon->date_validite)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Bouton Générer PDF -->
            <form action="{{ route('generate.pdfs') }}" method="POST" class="mt-4">
                @csrf
                <!-- Champ caché pour envoyer les IDs des bons importés -->
                @foreach(session('bons') as $bon)
                    <input type="hidden" name="bon_ids[]" value="{{ $bon->id }}">
                @endforeach
                <button type="submit" class="btn btn-warning btn-lg">
                    🖨️ Générer tous les PDF
                </button>
            </form>
        </div>
    </div>
@else
    <div class="alert alert-info mt-4">
        Aucun bon importé pour le moment.
    </div>
@endif
    </div>
</body>
</html>