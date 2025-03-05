@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Tableau de Bord - Administrateur</h2>

    <!-- Onglets -->
    <ul class="nav nav-tabs mt-4" id="adminTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="gestionnaire-tab" data-bs-toggle="tab" href="#gestionnaire" role="tab">Gestionnaires</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="societe-tab" data-bs-toggle="tab" href="#societe" role="tab">Sociétés</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="signataire-tab" data-bs-toggle="tab" href="#signataire" role="tab">Signataires</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="modele-tab" data-bs-toggle="tab" href="#modele" role="tab">Modèles</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="audit-tab" data-bs-toggle="tab" href="#audit" role="tab">Journal d'Audit</a>
        </li>
    </ul>

    <div class="tab-content mt-3" id="adminTabsContent">
        <!-- Gestionnaires -->
        <div class="tab-pane fade show active" id="gestionnaire" role="tabpanel">
            @include('admin.gestionnaires')
        </div>

        <!-- Sociétés -->
        <div class="tab-pane fade" id="societe" role="tabpanel">
            @include('admin.societes')
        </div>

        <!-- Signataires -->
        <div class="tab-pane fade" id="signataire" role="tabpanel">
            @include('admin.signataires')
        </div>

        <!-- Modèles -->
        <div class="tab-pane fade" id="modele" role="tabpanel">
            @include('admin.modeles')
        </div>

        <!-- Journal d'Audit -->
        <div class="tab-pane fade" id="audit" role="tabpanel">
            @include('admin.audit')
        </div>
    </div>
</div>
@endsection
