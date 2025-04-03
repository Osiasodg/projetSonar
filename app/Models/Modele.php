<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modele extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'elements'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modele) {
            if (empty($modele->nom) && !empty($modele->fichier)) {
                $modele->nom = pathinfo($modele->fichier, PATHINFO_FILENAME);
            }
        });

        static::creating(function ($modele) {
            if (empty($modele->description)) {
                $modele->description = $modele->nom;
            }
        });
    }

}