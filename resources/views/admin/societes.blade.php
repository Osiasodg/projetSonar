<!-- Sociétés - resources/views/admin/societes.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Gestion des Sociétés</h2>

    <!-- Messages de succès ou d'erreur -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Bouton Ajouter une Société -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addSocieteModal">
        <i class="fas fa-plus"></i> Ajouter une Société
    </button>

    <!-- Tableau des sociétés -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($societes as $societe)
                <tr>
                    <td>{{ $societe->nom }}</td>
                    <td>
                        <!-- Bouton Modifier avec icône -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSocieteModal{{ $societe->id }}" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>

                        <!-- Bouton Supprimer avec icône -->
                        <form action="{{ route('admin.societes.delete', $societe->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE') <!-- Ajoutez cette ligne si vous utilisez la méthode DELETE -->
                            <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Ajout Société -->
<div class="modal fade" id="addSocieteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une Société</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.societes.create') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modals de modification pour chaque société -->
@foreach($societes as $societe)
<div class="modal fade" id="editSocieteModal{{ $societe->id }}" tabindex="-1" aria-labelledby="editSocieteModalLabel{{ $societe->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.societes.update', $societe->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editSocieteModalLabel{{ $societe->id }}">Modifier la société</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nom{{ $societe->id }}" class="form-label">Nom</label>
                        <input type="text" name="nom" class="form-control" id="nom{{ $societe->id }}" value="{{ $societe->nom }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection