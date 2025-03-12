@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Gestion des gestionnaires</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGestionnaireModal">
            <i class="fas fa-plus"></i> Ajouter
        </button>
    </div>
    <div class="card-body">
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

        <div class="table-responsive">
            <table class="table table-bordered">
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
                        <td>{{ $gestionnaire->name }}</td>
                        <td>{{ $gestionnaire->prenom }}</td>
                        <td>{{ $gestionnaire->email }}</td>
                        <td>{{ $gestionnaire->service }}</td>
                        <td>
                            <!-- Bouton Modifier -->
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                                    data-bs-target="#editGestionnaireModal{{ $gestionnaire->id }}"
                                    data-id="{{ $gestionnaire->id }}"
                                    data-name="{{ $gestionnaire->name }}"
                                    data-prenom="{{ $gestionnaire->prenom }}"
                                    data-email="{{ $gestionnaire->email }}"
                                    data-service="{{ $gestionnaire->service }}" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- Bouton Supprimer -->
                            <form action="{{ route('admin.gestionnaires.delete', $gestionnaire->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer gestionnaire">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                            <!-- Bouton Réinitialiser le mot de passe -->
                            <form action="{{ route('admin.gestionnaires.reset-password', $gestionnaire->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-info" title="Réinitialiser le mot de passe">
                                    <i class="fas fa-key"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Ajout Gestionnaire -->
<div class="modal fade" id="addGestionnaireModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.gestionnaires.create') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un gestionnaire</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nom</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Prénom</label>
                        <input type="text" name="prenom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Service</label>
                        <input type="text" name="service" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal d'édition pour chaque gestionnaire -->
@foreach($gestionnaires as $gestionnaire)
<div class="modal fade" id="editGestionnaireModal{{ $gestionnaire->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.gestionnaires.update', $gestionnaire->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Modifier le gestionnaire</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nom</label>
                        <input type="text" name="name" class="form-control" value="{{ $gestionnaire->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Prénom</label>
                        <input type="text" name="prenom" class="form-control" value="{{ $gestionnaire->prenom }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $gestionnaire->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Service</label>
                        <input type="text" name="service" class="form-control" value="{{ $gestionnaire->service }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
