<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class Bon extends Model
{
    use HasFactory; 

    /**
     * Ajout des attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'numero',
        'beneficiaire',
        'montant',
        'date_validite',
        'utilise',
        'date_validation',
        'recepteur',
        'telephone',
        'entite',     
        'logo_path', 
        'user_id' ,
        'signataire_id'
    ];
    public function signataire()
    {
        return $this->belongsTo(Signataire::class, 'signataire_id');
    }
}
