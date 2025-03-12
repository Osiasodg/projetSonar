<div class="bg-dark text-white vh-100 p-3" style="width: 250px; position: fixed; top: 56px;">
    <h4 class="text-center">Admin</h4>
    <ul class="nav flex-column mt-4">
        <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.gestionnaires') }}">Gestionnaires</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.societes') }}">Sociétés</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.signataires') }}">Signataires</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.modeles') }}">Modèles</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.audit') }}">Journal d'Audit</a></li>
    </ul>
</div>
