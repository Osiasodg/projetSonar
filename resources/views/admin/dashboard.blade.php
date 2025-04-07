@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="text-center mb-4">Tableau de Bord - Administrateur</h2>

    <!-- Cartes d'informations -->
    <div class="row mb-4">
        <!-- Carte 1 : Utilisateurs connectés -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="fas fa-users"></i> Utilisateurs connectés</h5>
                    <h2 class="card-text">{{ count($utilisateursConnectes) }}</h2>
                </div>
            </div>
        </div>

        <!-- Carte 2 : Sociétés -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="fas fa-building"></i> Sociétés</h5>
                    <h2 class="card-text">{{ $totalSocietes }}</h2>
                </div>
            </div>
        </div>

        <!-- Carte 3 : Bons Cadeaux -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="fas fa-gift"></i> Bons Cadeaux</h5>
                    <h2 class="card-text">{{ $totalBons }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des dernières activités des gestionnaires -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title"><i class="fas fa-history"></i> Dernières Activités des Gestionnaires</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Gestionnaire</th>
                            <th>Action</th>
                            <th>Détails</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activites as $activite)
                            <tr>
                                <td>{{ $activite->id }}</td>
                                <td>{{ $activite->user->prenom ?? 'Prénom inconnu' }} {{ $activite->user->name ?? 'Nom inconnu' }}</td>

                                <td>{{ $activite->action }}</td>
                                <td>{{ $activite->details }}</td>
                                <td>{{ $activite->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Aucune activité récente.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
@endsection