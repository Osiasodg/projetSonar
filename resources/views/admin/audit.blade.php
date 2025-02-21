<!-- Journal d'audit - resources/views/admin/audit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Journal d'Audit</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Gestionnaire</th>
                <th>Date</th>
                <th>Montant Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($audits as $audit)
            <tr>
                <td>{{ $audit->gestionnaire->nom }} {{ $audit->gestionnaire->prenom }}</td>
                <td>{{ $audit->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ number_format($audit->montant_total, 2, ',', ' ') }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection