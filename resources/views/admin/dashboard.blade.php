@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="text-center mb-4">Tableau de Bord - Administrateur</h2>

    <!-- Cartes d'informations -->
    <div class="row mb-4">
        <!-- Carte 1 : Utilisateurs -->
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-users"></i> Utilisateurs</h5>
                    <p class="card-text">Nombre total d'utilisateurs : 120</p>
                    <a href="#" class="text-white">Voir plus <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Carte 2 : Sociétés -->
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-building"></i> Sociétés</h5>
                    <p class="card-text">Nombre total de sociétés : 45</p>
                    <a href="#" class="text-white">Voir plus <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Carte 3 : Tickets -->
        <div class="col-md-4">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-ticket-alt"></i> Tickets</h5>
                    <p class="card-text">Tickets ouverts : 15</p>
                    <a href="#" class="text-dark">Voir plus <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des dernières activités -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title"><i class="fas fa-history"></i> Dernières Activités</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Utilisateur</th>
                            <th>Action</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>John Doe</td>
                            <td>Connexion</td>
                            <td>2023-10-01 14:30</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Jane Smith</td>
                            <td>Création d'une société</td>
                            <td>2023-10-01 15:00</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Alice Johnson</td>
                            <td>Modification de profil</td>
                            <td>2023-10-01 16:15</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- <div class="container-fluid" style="margin-left: 250px;">
    <h2 class="text-center">Tableau de Bord - Administrateur</h2>

    <p>Bienvenue sur le tableau de bord administrateur.</p>
</div> -->
