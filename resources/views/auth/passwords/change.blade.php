@extends('layouts.app')

@section('content')
<style>
    /* Style personnalisé pour le bouton et l'icône */
    .password-toggle-btn {
        border: 1px solid #ced4da !important; /* Couleur identique aux inputs */
        background-color: transparent !important;
        color: #495057 !important; /* Couleur du texte identique aux inputs */
        margin-left: 5px; /* Espacement entre l'input et le bouton */
    }

    /* Style au survol */
    .password-toggle-btn:hover {
        background-color: #e9ecef !important;
    }

    /* Espacement entre les groupes de formulaire */
    .form-group.row {
        margin-bottom: 1.5rem; /* Ajustez la valeur selon vos besoins */
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Changement de mot de passe obligatoire') }}</div>

                <div class="card-body">
                    <!-- Message de succès -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            Mot de passe mis à jour avec succès !
                        </div>
                    @endif

                    <!-- Message d'erreur global -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <!-- Mot de passe actuel -->
                        <div class="form-group row">
                            <label for="current_password" class="col-md-4 col-form-label text-md-right">
                                {{ __('Mot de passe actuel') }}
                            </label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input id="current_password" type="password" 
                                        class="form-control @error('current_password') is-invalid @enderror" 
                                        name="current_password" required autofocus>
                                    <button type="button" class="btn password-toggle-btn" onclick="togglePassword('current_password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Nouveau mot de passe -->
                        <div class="form-group row">
                            <label for="new_password" class="col-md-4 col-form-label text-md-right">
                                {{ __('Nouveau mot de passe') }}
                            </label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input id="new_password" type="password" 
                                        class="form-control @error('new_password') is-invalid @enderror" 
                                        name="new_password" required>
                                    <button type="button" class="btn password-toggle-btn" onclick="togglePassword('new_password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('new_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Confirmation du nouveau mot de passe -->
                        <div class="form-group row">
                            <label for="new_password_confirmation" class="col-md-4 col-form-label text-md-right">
                                {{ __('Confirmer le nouveau mot de passe') }}
                            </label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input id="new_password_confirmation" type="password" 
                                        class="form-control" 
                                        name="new_password_confirmation" required>
                                    <button type="button" class="btn password-toggle-btn" onclick="togglePassword('new_password_confirmation')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Bouton de soumission -->
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Mettre à jour le mot de passe') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script pour afficher/masquer le mot de passe -->
<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.nextElementSibling.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
<!-- Animation et confirmation -->
<style>
    /* Animation pour le symbole de succès (V dans un cercle) */
    @keyframes drawCircle {
        0% { stroke-dashoffset: 283; }
        100% { stroke-dashoffset: 0; }
    }

    @keyframes drawCheck {
        0% { stroke-dashoffset: 100; }
        100% { stroke-dashoffset: 0; }
    }

    .success-popup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        text-align: center;
        z-index: 9999;
        display: none;
    }

    .success-circle {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        display: block;
    }

    .success-circle circle {
        stroke: #28a745;
        stroke-width: 5;
        stroke-dasharray: 283;
        stroke-dashoffset: 283;
        animation: drawCircle 1s ease-in-out forwards;
    }

    .success-circle path {
        stroke: #28a745;
        stroke-width: 5;
        stroke-dasharray: 100;
        stroke-dashoffset: 100;
        animation: drawCheck 0.5s ease-in-out 1s forwards;
    }

    .confirmation-message {
        font-size: 1.2rem;
        color: #28a745;
        margin-top: 15px;
    }
</style>

<!-- Popup de confirmation -->
<div id="successPopup" class="success-popup">
    <svg class="success-circle" viewBox="0 0 100 100">
        <circle cx="50" cy="50" r="45" fill="none" />
        <path d="M30,50 L45,65 L75,35" fill="none" />
    </svg>
    <p class="confirmation-message">Mot de passe mis à jour avec succès !</p>
</div>

<!-- Script pour afficher l'animation et rediriger -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if ({{ session('success') ? 'true' : 'false' }}) {
            let popup = document.getElementById('successPopup');
            popup.style.display = 'block';

            setTimeout(() => {
                window.location.href = "{{ route('gestionnaire.dashboard') }}";
            }, 3000); // Redirection après 3 secondes
        }
    });
</script>

@endsection