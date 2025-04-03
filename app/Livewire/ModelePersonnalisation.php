<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Modele;

class ModelePersonnalisation extends Component
{
    // Si $modeleId est null, on est en création ; sinon, modification
    public $modeleId;
    public $nom;
    public $description;
    public $elements = [];

    // Structure par défaut pour un nouveau modèle
    protected $defaultStructure = [
        'logo' => [
            'x' => 50, 'y' => 20,
            'label' => 'Logo',
            'type' => 'logo',
            'style' => 'width: 120px;'
        ],
        'titre' => [
            'x' => 200, 'y' => 20,
            'label' => "Bon d'achat",
            'type' => 'text',
            'style' => 'font-size: 24px; font-weight: bold; color: #007bff;'
        ],
        'numero' => [
            'x' => 50, 'y' => 80,
            'label' => 'Numéro du bon',
            'type' => 'text',
            'style' => 'font-size: 16px;'
        ],
        'montant' => [
            'x' => 50, 'y' => 140,
            'label' => 'Montant',
            'type' => 'text',
            'style' => 'font-size: 30px; color: green; font-weight: bold;'
        ],
        'beneficiaire' => [
            'x' => 50, 'y' => 200,
            'label' => 'Bénéficiaire',
            'type' => 'text',
            'style' => 'font-size: 16px; font-weight: bold;'
        ],
        'recepteur' => [
            'x' => 50, 'y' => 260,
            'label' => 'Récepteur',
            'type' => 'text',
            'style' => 'font-size: 16px; color: blue;'
        ],
        'validite' => [
            'x' => 50, 'y' => 320,
            'label' => 'Validité',
            'type' => 'text',
            'style' => 'font-size: 18px; color: red; font-weight: bold;'
        ],
        'qrcode' => [
            'x' => 400, 'y' => 320,
            'label' => 'QR Code',
            'type' => 'qrcode',
            'style' => 'width: 100px;'
        ],
        'signature' => [
            'x' => 50, 'y' => 380,
            'label' => 'Signature',
            'type' => 'text',
            'style' => 'font-size: 14px; text-align: right;'
        ]
    ];

    // Écoute l'événement "positionUpdated" émis par le JavaScript
    protected $listeners = [
        'positionUpdated' => 'updatePosition'
    ];

    public function mount($modeleId = null)
    {
        $this->modeleId = $modeleId;
        if ($this->modeleId) {
            $modele = Modele::find($this->modeleId);
            if ($modele) {
                $this->nom = $modele->nom;
                $this->description = $modele->description;
                $this->elements = json_decode($modele->elements, true) ?? []; // Assurez-vous que les positions sont bien chargées
              // dd($this->elements); // Debug pour vérifier si les bonnes positions sont chargées
            }
        } else {
            $this->elements = $this->defaultStructure;
        }
    }


    // Méthode appelée lors d'un déplacement pour mettre à jour la position
    public function updatePosition($elementKey, $x, $y)
    {
        if (isset($this->elements[$elementKey])) {
            $this->elements[$elementKey]['x'] = $x;
            $this->elements[$elementKey]['y'] = $y;
        }
        
        dd($this->elements); // Debug : Vérifier si les positions sont bien mises à jour
    }


    // Sauvegarde ou met à jour le modèle dans la base de données
    public function saveSettings()
    {
        
        if (!$this->nom || !$this->description) {
            session()->flash('error', 'Le nom et la description sont obligatoires.');
            return;
        }
    
        if ($this->modeleId) {
            // Mise à jour du modèle existant
            $modele = Modele::find($this->modeleId);
            if ($modele) {
                $modele->update([
                    'nom' => $this->nom,
                    'description' => $this->description,
                    'elements' => json_encode($this->elements), // Assurez-vous que les nouvelles positions sont bien enregistrées
                ]);
            }
        } else {
            // Création d'un nouveau modèle
            Modele::create([
                'nom' => $this->nom,
                'description' => $this->description,
                'elements' => json_encode($this->elements),
            ]);
        }
    
        
        session()->flash('message', 'Modèle enregistré avec succès.');
        return redirect()->route('admin.modeles');
    }
    

    public function render()
    {
        // Vous pouvez également passer la liste des modèles si nécessaire
        return view('livewire.modele-personnalisation', [
            'modeles' => Modele::all()
        ]);
    }
}
