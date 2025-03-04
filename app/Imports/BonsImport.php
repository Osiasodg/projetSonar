<?php

namespace App\Imports;

use App\Models\Bon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BonsImport implements ToModel, WithHeadingRow
{
    protected $entite;
    protected $date_validite;
    protected $recepteur;
    protected $telephone;
    protected $logo_path;

    public function __construct($entite, $date_validite, $recepteur, $telephone, $logo_path)
    {
        $this->entite = $entite;
        $this->date_validite = $date_validite;
        $this->recepteur = $recepteur;
        $this->telephone = $telephone;
        $this->logo_path = $logo_path;
    }

    public function model(array $row)
{
    return new Bon([
        'numero' => date('Y') . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT) . 'S', // Générer le numéro de bon
        'beneficiaire' => strtoupper($row['nom']) . ' ' . ucfirst(strtolower($row['prenom'])), 
        'montant' => (float) str_replace(',', '.', $row['montant']),
        'date_validite' => $this->date_validite,
        'utilise' => false,
        'recepteur' => $this->recepteur,
        'telephone' => $this->telephone,
        'entite' => $this->entite,
        'logo_path' => $this->logo_path,
        'user_id' => auth()->id() ?? 1, // Ajoute l'ID de l'utilisateur connecté, ou une valeur par défaut
    ]);
}

}
