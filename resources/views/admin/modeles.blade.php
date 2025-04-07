@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header">
        <h5>Gestion des modèles</h5>
    </div>
    <div class="card-body">
        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($modeles as $modele)
                    <tr>
                        <td>{{ $modele->nom }}</td>
                        <td>
                            <span class="desc-text" id="desc-text-{{ $loop->index }}">{{ $modele->description }}</span>
                            <input type="text" class="form-control desc-input d-none" 
                                   id="desc-input-{{ $loop->index }}" 
                                   value="{{ $modele->description }}" 
                                   data-id="{{ $modele->id }}">
                        </td>
                        <td>

                            <button class="btn btn-sm btn-warning edit-btn" data-index="{{ $loop->index }}">
                                <i class="fas fa-edit"></i> Modifier
                            </button>
                            <button class="btn btn-sm btn-success save-btn d-none" data-index="{{ $loop->index }}">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function () {
        let index = this.getAttribute('data-index');
        document.getElementById('desc-text-' + index).classList.add('d-none');
        document.getElementById('desc-input-' + index).classList.remove('d-none');
        document.querySelector('.save-btn[data-index="' + index + '"]').classList.remove('d-none');
        this.classList.add('d-none');
    });
});

document.querySelectorAll('.save-btn').forEach(button => {
    button.addEventListener('click', function () {
        let index = this.getAttribute('data-index');
        let input = document.getElementById('desc-input-' + index);
        let description = input.value;
        let id = input.getAttribute('data-id');

        fetch(`/admin/modeles/update/${id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ description: description })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('desc-text-' + index).textContent = description;
                document.getElementById('desc-text-' + index).classList.remove('d-none');
                input.classList.add('d-none');
                button.classList.add('d-none');
                document.querySelector('.edit-btn[data-index="' + index + '"]').classList.remove('d-none');
            } else {
                alert("Erreur : " + data.error);
            }
        });
    });
});
</script>
@endsection
