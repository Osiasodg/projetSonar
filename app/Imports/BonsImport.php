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
        // Convertit le montant en nombre décimal
        $montant = (float) str_replace(',', '.', $row['montant']); // Utilise 'montant' comme clé
        
        // Génère un numéro de bon unique
        $annee = date('Y');  // Année en cours
        $numero = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT); // 6 chiffres aléatoires
        $numero_bon = $annee . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT) . 'S'; // Format : 20254359S

        return new Bon([
            'numero' => $numero_bon,
            'beneficiaire' => strtoupper($row['nom']) . ' ' . ucfirst(strtolower($row['prenom'])), 
           // 'montant' => (float) str_replace(',', '.', $row['montant']),
            'montant' => $montant,      // Utilise la valeur convertie
            'date_validite' => $this->date_validite,
            'utilise' => false,
            'recepteur' => $this->recepteur,
            'telephone' => $this->telephone,
            'entite' => $this->entite,
            'logo_path' => $this->logo_path
        ]);
    }

//     public function import(Request $request)
// {
//     $request->validate([
//         'file' => 'required|mimes:xlsx,xls|max:2048',
//         'logo' => 'required|image|max:2048',
//         'entite' => 'required|string',
//         'date_validite' => 'required|date',
//         'recepteur' => 'required|string',
//         'telephone' => 'required|string'
//     ]);

//     // Enregistrez le logo et récupérez son chemin
//     $logoPath = $request->file('logo')->store('logos', 'public');

//     // Importez le fichier Excel en passant les arguments nécessaires
//     Excel::import(new BonsImport(
//         $request->entite, // $entite
//         $request->date_validite, // $date_validite
//         $request->recepteur, // $recepteur
//         $request->telephone, // $telephone
//         $logoPath // $logo_path
//     ), $request->file('file'));

//     return back()->with('success', 'Fichier importé avec succès !');
//}

}
