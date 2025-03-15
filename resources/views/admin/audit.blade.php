<!-- Journal d'audit - resources/views/admin/audit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="text-center mb-4">Journal d'Audit</h2>

    <!-- Cartes d'informations -->
    <div class="row mb-4">
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="fas fa-users"></i> Nombre Total d'Utilisateurs</h5>
                    <h2 class="card-text">{{ $totalUsers }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="fas fa-file-alt"></i> Nombre Total de Bons</h5>
                    <h2 class="card-text">{{ $totalBons }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="fas fa-coins"></i> Montant Total des Bons</h5>
                    <h2 class="card-text">{{ number_format($montantTotal, 0, ',', ' ') }} FCFA</h2>
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

    <!-- Graphique des bons générés par mois -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title"><i class="fas fa-chart-bar"></i> Bons générés par mois</h5>
        </div>
        <div class="card-body">
            <canvas id="bonsParMoisChart"></canvas>
        </div>
    </div>
</div>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var ctx = document.getElementById('bonsParMoisChart').getContext('2d');
        var chartData = @json($bonsParMois);

        // Vérifier si on a des données avant d'afficher le graphe
        if (chartData.length > 0) {
            var labels = chartData.map(item => `${item.mois}/${item.annee}`);
            var montantData = chartData.map(item => item.total_montant);
            var countData = chartData.map(item => item.total_bons);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Montant Total (FCFA)',
                            data: montantData,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Nombre de Bons',
                            data: countData,
                            backgroundColor: 'rgba(255, 99, 132, 0.6)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        } else {
            document.getElementById('bonsParMoisChart').style.display = 'none';
        }
    });
</script>
@endsection
