<!-- Journal d'audit - resources/views/admin/audit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="text-center mb-4">Journal d'Audit</h2>

    <!-- Cartes d'informations -->
    <div class="row mb-4">
    <!-- Carte existante : Nombre Total d'Utilisateurs -->
    <div class="col-md-4 col-sm-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h5 class="card-title"><i class="fas fa-users"></i> Nombre Total d'Utilisateurs</h5>
                <h2 class="card-text">{{ $totalUsers }}</h2>
            </div>
        </div>
    </div>

    <!-- Carte existante : Nombre Total de Bons -->
    <div class="col-md-4 col-sm-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5 class="card-title"><i class="fas fa-file-alt"></i> Nombre Total de Bons</h5>
                <h2 class="card-text">{{ $totalBons }}</h2>
            </div>
        </div>
    </div>

    <!-- Carte existante : Montant Total des Bons -->
    <div class="col-md-4 col-sm-12 mb-3">
        <div class="card bg-warning text-dark">
            <div class="card-body text-center">
                <h5 class="card-title"><i class="fas fa-coins"></i> Montant Total des Bons</h5>
                <h2 class="card-text">{{ number_format($montantTotal, 0, ',', ' ') }} FCFA</h2>
            </div>
        </div>
    </div>

    <!-- Nouvelle carte : Nombre de Bons Validés -->
    <div class="col-md-4 col-sm-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h5 class="card-title"><i class="fas fa-check-circle"></i> Bons Validés</h5>
                <h2 class="card-text">{{ $bonsValides }}</h2>
            </div>
        </div>
    </div>

    <!-- Nouvelle carte : Montant des Bons Validés -->
    <div class="col-md-4 col-sm-6 mb-3">
        <div class="card bg-secondary text-white">
            <div class="card-body text-center">
                <h5 class="card-title"><i class="fas fa-check-circle"></i> Montant Validé</h5>
                <h2 class="card-text">{{ number_format($montantValide, 0, ',', ' ') }} FCFA</h2>
            </div>
        </div>
    </div>
</div>

    <!-- Tableau des bons par utilisateur -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title"><i class="fas fa-user-tag"></i> Bons générés par utilisateur</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Nombre de Bons</th>
                            <th>Montant Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bonsParUtilisateur as $item)
                            <tr>
                            <td>{{ $item->user->name . ' ' . $item->user->prenom}}</td>
                                <td>{{ $item->total_bons }}</td>
                                <td>{{ number_format($item->total_montant, 0, ',', ' ') }} FCFA</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Formulaire de filtrage par date -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title"><i class="fas fa-filter"></i> Filtrer par Date</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.audit') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="date_debut">Date de début :</label>
                        <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ request('date_debut') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="date_fin">Date de fin :</label>
                        <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ request('date_fin') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <!-- Tableau des bons tirés -->
    <div class="card" id="tableau-resultats">
        <div class="card-header">
            <h5 class="card-title"><i class="fas fa-list"></i> Liste des Bons</h5>
            <p class="text-muted">Nombre total bons affichés : <strong>{{ $nombreBons }}</strong></p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Numéro du Bon</th>
                            <th>Montant</th>
                            <th>Date de Création</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($bons->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center text-muted">Aucun bon trouvé pour cette période.</td>
                            </tr>
                        @else
                            @foreach($bons as $bon)
                                <tr>
                                    <td>{{ $bon->numero }}</td>
                                    <td>{{ number_format($bon->montant, 0, ',', ' ') }} FCFA</td>
                                    <td>{{ $bon->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($bon->utilise)
                                            <span class="badge bg-success">Utilisé</span>
                                        @else
                                            <span class="badge bg-warning">Non Utilisé</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Vérifie si le formulaire a été soumis (filtrage appliqué)
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('date_debut') || urlParams.has('date_fin')) {
            // Fait défiler la page vers le tableau des résultats
            const tableauResultats = document.getElementById('tableau-resultats');
            if (tableauResultats) {
                tableauResultats.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
</script>
@endsection
