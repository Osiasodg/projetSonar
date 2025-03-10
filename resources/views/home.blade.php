@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <!-- Card header avec fond vert -->
                <div class="card-header text-center text-white" style="background-color: #1FA055;">
                    <h5>Bienvenue sur la page de création de bons de</h5>
                </div>

                <div class="card-body text-center">
                    <!-- Ajout du logo -->
                    <div class="my-4">
                        <img src="{{ asset('images/Logo Sonar.png') }}" class="img-fluid" style="max-width: 300px;">
                    </div>

                    <!-- Boutons -->
                    <div class="d-grid gap-3 mx-auto" style="max-width: 300px;">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg mx-2">Se connecter</a>
                        <a href="{{ route('verifier.form') }}" class="btn btn-primary btn-lg mx-2">Vérifier bons</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
