<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signataire extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'poste',
        'societe_id'
    ];

    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }
}