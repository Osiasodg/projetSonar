@extends('layouts.app') <!-- Hérite du layout principal -->

@section('content') <!-- Définit la section "content" -->
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
                    <label class="form-label fw-bold text-primary">Fichier Excel (.xlsx, .xls)</label>
                    <input type="file" id="fileInput" name="file" class="form-control" accept=".xlsx,.xls" required>
                    <p id="fileError" class="text-danger mt-2" style="display: none;">Veuillez sélectionner un fichier Excel (.xls ou .xlsx).</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-primary">Logo de l'entreprise</label>
                    <input type="file" name="logo" class="form-control" accept="image/*" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-primary">Societé</label>
                    <select name="entite" class="form-select" required>
                        @foreach($societes as $societe)
                            <option value="{{ $societe->nom }}">{{ $societe->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-primary">Date de validité</label>
                    <input type="date" name="date_validite" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-primary">Récepteur du bon</label>
                    <input type="text" name="recepteur" class="form-control" required>
                </div>
                <div class="mb-3">
                   <label class="form-label fw-bold text-primary">Téléphone du récepteur</label>
                    <input type="text" name="telephone" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-primary">Modèle</label>
                    <select name="modele_nom" class="form-select" required>
                        <option value="">Sélectionnez un modèle</option>
                        @foreach($fichiersModeles as $modele)
                            <option value="{{ $modele->nom }}">{{ $modele->description }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="mb-3">
                    <label class="form-label fw-bold text-primary">Signataire</label>
                    <select name="signataire_id" class="form-select" required>
                        @foreach($signataires as $signataire)
                            <option value="{{ $signataire->id }}">{{ $signataire->nom }} {{ $signataire->prenom }}</option>
                        @endforeach
                    </select>
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
        <div id="bons-section" class="card shadow mt-4">
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

                <!-- Boutons Générer PDF et Actualiser sur la même ligne -->
                <div class="d-flex gap-3 mt-4">
                     <!-- Bouton Visualiser -->
                     <button type="button" class="btn btn-primary btn-lg" id="visualiserBtn" data-bs-toggle="modal" data-bs-target="#previewModal">
                        👁️ Visualiser
                    </button>
                    <!-- Bouton Générer PDF -->
                    <form action="{{ route('generate.pdfs') }}" method="POST">
                        @csrf
                        @foreach(session('bons') as $bon)
                            <input type="hidden" name="bon_ids[]" value="{{ $bon->id }}">
                        @endforeach
                        <button type="submit" class="btn btn-warning btn-lg">
                            🖨️ Générer tous les PDF
                        </button>
                    </form>

                    <!-- Bouton Actualiser -->
                    <form action="{{ route('clear.session') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-lg">
                            🔄 Actualiser
                        </button>
                    </form>
                </div>


            </div>
        </div>

 <!-- Modal pour l'aperçu -->
 <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="previewModalLabel">Aperçu du bon</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="previewContent">
                            <!-- Le contenu de l'aperçu sera chargé ici via AJAX -->
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Chargement...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>

    @else
        <div class="alert alert-info mt-4">
            Aucun bon importé pour le moment.
        </div>
    @endif
</div>

<!-- JavaScript pour le défilement automatique -->
@if(session('bons'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bonsSection = document.getElementById('bons-section');
            if (bonsSection) {
                bonsSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    </script>
@endif

<!-- JavaScript pour la validation du fichier -->
<script>
    document.getElementById('fileInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const allowedExtensions = ['xlsx', 'xls'];
            const fileExtension = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(fileExtension)) {
                document.getElementById('fileError').style.display = 'block';
                this.value = '';
            } else {
                document.getElementById('fileError').style.display = 'none';
            }
        }
    });
</script>
<!-- <script>
document.addEventListener('DOMContentLoaded', function () {
    const visualiserBtn = document.getElementById('visualiserBtn');
    if (visualiserBtn) {
        visualiserBtn.addEventListener('click', function() {
            const content = document.getElementById('previewContent');
            content.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary"></div>
                    <p>Chargement de l'aperçu...</p>
                </div>`;

            const bonIds = Array.from(document.querySelectorAll('input[name="bon_ids[]"]'))
                                .map(input => input.value);

            if (bonIds.length === 0) {
                content.innerHTML = `<div class="alert alert-danger">Aucun bon sélectionné</div>`;
                return;
            }

            fetch("{{ route('preview.bon') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'text/html',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ bon_ids: bonIds })
            })
            .then(response => {
                if (!response.ok) return response.text().then(t => { throw new Error(t) });
                return response.text();
            })
            .then(html => {
                content.innerHTML = html;
                const modal = new bootstrap.Modal(document.getElementById('previewModal'));
                modal.show();
            })
            .catch(error => {
                content.innerHTML = `<div class="alert alert-danger">Erreur lors du chargement :<br>${error.message}</div>`;
            });
        });
    }
});
</script> -->

<script>
document.addEventListener('DOMContentLoaded', function () {
    const visualiserBtn = document.getElementById('visualiserBtn');
    const previewModal = document.getElementById('previewModal');
    const previewContent = document.getElementById('previewContent');

    // Vérification que les éléments existent
    if (!visualiserBtn || !previewModal || !previewContent) return;

    // Lors du clic sur "Visualiser"
    visualiserBtn.addEventListener('click', function () {
        previewContent.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary"></div>
                <p>Chargement de l'aperçu...</p>
            </div>`;

        const bonIds = Array.from(document.querySelectorAll('input[name="bon_ids[]"]'))
                            .map(input => input.value);

        if (bonIds.length === 0) {
            previewContent.innerHTML = `<div class="alert alert-danger">Aucun bon sélectionné</div>`;
            return;
        }

        fetch("{{ route('preview.bon') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'text/html',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ bon_ids: bonIds })
        })
        .then(response => {
            if (!response.ok) return response.text().then(t => { throw new Error(t) });
            return response.text();
        })
        .then(html => {
            previewContent.innerHTML = html;
        })
        .catch(error => {
            previewContent.innerHTML = `<div class="alert alert-danger">Erreur lors du chargement :<br>${error.message}</div>`;
        });
    });

    // Réinitialiser le contenu du modal à la fermeture
    previewModal.addEventListener('hidden.bs.modal', function () {
        previewContent.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary"></div>
                <p>Chargement de l'aperçu...</p>
            </div>`;
    });
});
</script>

@endsection