<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('signataires', function (Blueprint $table) {
            $table->id(); // ID auto-incrémenté
            $table->string('nom'); // Nom du signataire
            $table->string('prenom'); // Prénom du signataire
            $table->string('poste'); // Poste du signataire
            $table->timestamps(); // Colonnes created_at et updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('signataires'); // Supprimer la table si la migration est annulée
    }
};
