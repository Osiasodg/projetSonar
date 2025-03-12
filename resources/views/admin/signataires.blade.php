@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Gestion des Signataires</h2>

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

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addSignataireModal">
        <i class="fas fa-plus"></i> Ajouter un Signataire
    </button>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Poste</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($signataires as $signataire)
                <tr>
                    <td>{{ $signataire->nom }}</td>
                    <td>{{ $signataire->prenom }}</td>
                    <td>{{ $signataire->poste }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSignataireModal{{ $signataire->id }}" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('admin.signataires.delete', $signataire->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Modal de modification -->
                <div class="modal fade" id="editSignataireModal{{ $signataire->id }}" tabindex="-1" aria-labelledby="editSignataireModalLabel{{ $signataire->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('admin.signataires.update', $signataire->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editSignataireModalLabel{{ $signataire->id }}">Modifier le signataire</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="nom{{ $signataire->id }}" class="form-label">Nom</label>
                                        <input type="text" name="nom" class="form-control" id="nom{{ $signataire->id }}" value="{{ $signataire->nom }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="prenom{{ $signataire->id }}" class="form-label">Prénom</label>
                                        <input type="text" name="prenom" class="form-control" id="prenom{{ $signataire->id }}" value="{{ $signataire->prenom }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="poste{{ $signataire->id }}" class="form-label">Poste</label>
                                        <input type="text" name="poste" class="form-control" id="poste{{ $signataire->id }}" value="{{ $signataire->poste }}" required>
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
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Ajout -->
<div class="modal fade" id="addSignataireModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un Signataire</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.signataires.create') }}" method="POST">
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
                        <label class="form-label">Poste</label>
                        <input type="text" name="poste" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection