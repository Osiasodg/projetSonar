

@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <h2>Bienvenue sur la page de</h2>

    <!-- Ajout du logo -->
    <div class="my-4">
        <img src="{{ asset('images/Logo Sonar.png') }}" class="img-fluid" style="max-width: 250px;">

    </div>

    
    <div class="mt-4 d-grid gap-3 mx-auto" style="max-width: 300px;">
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg mx-2">Se connecter</a>
        <a href="{{ route('verifier.form') }}" class="btn btn-primary btn-lg mx-2">Vérifier bons</a>
    </div>
</div>
@endsection
