<div class="modele-container">
    <!-- Formulaire de saisie (nom et description) -->
    <div class="mb-3">
        <label class="form-label">Nom du modèle</label>
        <input type="text" wire:model="nom" class="form-control" placeholder="Entrez le nom">
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea wire:model="description" class="form-control" placeholder="Entrez la description"></textarea>
    </div>

    <!-- Prévisualisation en format A4 -->
    <div id="preview-container" wire:ignore.self style="width: 595px; height: 842px; margin: auto; border: 1px solid #ddd; position: relative;">
        @foreach ($elements as $key => $element)
            <div class="draggable"
                 id="{{ $key }}"
                 style="position: absolute; left: {{ $element['x'] }}px; top: {{ $element['y'] }}px; cursor: move; padding: 5px; border: 1px solid #ccc; background: #fff;"
                 data-id="{{ $key }}">
                {{ $element['label'] }}
            </div>
        @endforeach
    </div>

    <!-- Bouton d'enregistrement -->
    <button wire:click="saveSettings" class="btn btn-success mt-3">
        {{ $modeleId ? 'Mettre à jour' : 'Enregistrer' }}
    </button>
</div>

<!-- Script Interact.js pour le drag-and-drop -->
<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    function enableDrag(element, positionKey) {
        interact(element).draggable({
            modifiers: [
                interact.modifiers.restrictRect({
                    restriction: 'parent',
                    endOnly: false
                })
            ],
            listeners: {
                move: function (event) {
                    let target = event.target;
                    let x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
                    let y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

                    target.style.transform = `translate(${x}px, ${y}px)`;
                    target.setAttribute('data-x', x);
                    target.setAttribute('data-y', y);
                },
                end: function (event) {
                    let target = event.target;
                    let finalX = parseFloat(target.getAttribute('data-x')) || 0;
                    let finalY = parseFloat(target.getAttribute('data-y')) || 0;

                    console.log(`Déplacé : ${positionKey} à x: ${finalX}, y: ${finalY}`);

                    // Vérifier que Livewire est bien chargé avant d'envoyer l'événement
                    if (typeof Livewire !== 'undefined' && Livewire.emit) {
                        Livewire.emit('positionUpdated', positionKey, finalX, finalY);
                    } else {
                        console.error("Livewire n'est pas chargé correctement.");
                    }
                }
            }
        });
    }

    document.querySelectorAll('.draggable').forEach(el => {
        enableDrag(el, el.getAttribute('data-id'));
    });
});
</script>

