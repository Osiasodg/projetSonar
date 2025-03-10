<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        @if(!isset($showNavbar) || $showNavbar) <!-- Afficher la navbar si $showNavbar est true ou non définie -->
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <!-- Logo à gauche -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{ asset('images/Logo Sonar.png') }}" alt="Logo Sonar" style="height: 30px;"> <!-- Ajustez la hauteur selon vos besoins -->
                    </a>

                    <!-- Bouton pour les écrans mobiles -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- Contenu de la navbar -->
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Partie gauche de la navbar (vide pour l'instant) -->
                        <ul class="navbar-nav me-auto">
                            <!-- Vous pouvez ajouter des liens ici si nécessaire -->
                        </ul>

                        <!-- Partie droite de la navbar -->
                        <ul class="navbar-nav ms-auto">
                            <!-- Liens d'authentification -->
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif
                            @else
                                <!-- Nom de l'utilisateur connecté et menu déroulant -->
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }} <!-- Affiche le nom de l'utilisateur connecté -->
                                    </a>

                                    <!-- Menu déroulant -->
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <!-- Option de déconnexion -->
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Déconnexion') }}
                                        </a>

                                        <!-- Formulaire de déconnexion -->
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        @endif

        <!-- Contenu principal -->
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>