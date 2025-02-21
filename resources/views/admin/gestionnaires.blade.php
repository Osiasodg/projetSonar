<!-- Gestionnaires - resources/views/admin/gestionnaires.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Gestion des Gestionnaires</h2>

    <!-- Message de confirmation -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Bouton d'ajout -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addGestionnaireModal">Ajouter un Gestionnaire</button>

    <!-- Tableau des gestionnaires -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Service</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gestionnaires as $gestionnaire)
            <tr>
                <td>{{ $gestionnaire->nom }}</td>
                <td>{{ $gestionnaire->prenom }}</td>
                <td>{{ $gestionnaire->email }}</td>
                <td>{{ $gestionnaire->service }}</td>
                <td>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editGestionnaireModal{{ $gestionnaire->id }}">Modifier</button>
                    <form action="{{ route('admin.gestionnaires.delete', $gestionnaire->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                    </form>
                    <form action="{{ route('admin.gestionnaires.reset', $gestionnaire->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-sm">Réinitialiser MDP</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Ajout Gestionnaire -->
<div class="modal fade" id="addGestionnaireModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un Gestionnaire</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.gestionnaires.create') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="prenom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Service</label>
                        <input type="text" name="service" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
