<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        /* Mode sombre */
        .dark-mode {
            background-color: #121212;
            color: #ffffff;
        }
        .dark-mode .navbar {
            background-color: #1f1f1f !important;
            border-bottom: 1px solid #333;
        }
        .dark-mode .navbar-brand,
        .dark-mode .nav-link,
        .dark-mode .dropdown-menu {
            color: #ffffff !important;
        }
        .dark-mode .dropdown-menu {
            background-color: #1f1f1f;
            border: 1px solid #333;
        }
        .dark-mode .dropdown-item {
            color: #ffffff !important;
        }
        .dark-mode .dropdown-item:hover {
            background-color: #333;
        }
        .dark-mode .table {
            color: #ffffff;
        }
        .dark-mode .table-bordered th,
        .dark-mode .table-bordered td {
            border-color: #444;
        }
        .dark-mode .card {
            background-color: #1f1f1f;
            border-color: #333;
        }
        .dark-mode .card-header {
            background-color: #2c2c2c;
            border-bottom: 1px solid #333;
        }
        .dark-mode .alert {
            background-color: #2c2c2c;
            border-color: #333;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div id="app">
        @if(!isset($showNavbar) || $showNavbar)
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{ asset('images/Logo Sonar.png') }}" alt="Logo Sonar" style="height: 30px;">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto"></ul>
                        <ul class="navbar-nav ms-auto">
                            <!-- Bouton pour changer le mode clair/sombre -->
                            <li class="nav-item">
                                <button id="themeToggle" class="btn btn-link nav-link">
                                    <i id="themeIcon" class="fas fa-moon"></i>
                                </button>
                            </li>
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                        {{ Auth::user()->name }} {{ Auth::user()->prenom }}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Déconnexion') }}
                                        </a>
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
        <div class="d-flex">
            @if(Auth::check() && Auth::user()->isAdmin())
                @include('layouts.admin_sidebar')
            @endif
            <main class="py-4 flex-grow-1" style="margin-top: 70px; margin-left: {{ Auth::check() && Auth::user()->isAdmin() ? '250px' : '0' }};">
                @yield('content')
            </main>
        </div>
    </div>
    <script>
        // Fonction pour basculer entre les modes clair et sombre
        function toggleTheme() {
            const body = document.body;
            const themeIcon = document.getElementById('themeIcon');
            if (body.classList.contains('dark-mode')) {
                body.classList.remove('dark-mode');
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
                localStorage.setItem('theme', 'light');
            } else {
                body.classList.add('dark-mode');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
                localStorage.setItem('theme', 'dark');
            }
        }
        // Appliquer le mode sauvegardé au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme');
            const themeIcon = document.getElementById('themeIcon');
            if (savedTheme === 'dark') {
                document.body.classList.add('dark-mode');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            } else {
                document.body.classList.remove('dark-mode');
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            }
        });
        // Ajouter un écouteur d'événement au bouton
        document.getElementById('themeToggle').addEventListener('click', toggleTheme);
    </script>
</body>
</html>