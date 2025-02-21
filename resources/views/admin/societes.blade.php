<!-- Sociétés - resources/views/admin/societes.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Gestion des Sociétés</h2>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addSocieteModal">Ajouter une Société</button>
    <table class="table table-striped">
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
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSocieteModal{{ $societe->id }}">Modifier</button>
                    <form action="{{ route('admin.societes.delete', $societe->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
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
@endsection
