<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bons', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->string('beneficiaire');
            $table->decimal('montant', 10, 2);
            $table->date('date_validite');
            $table->boolean('utilise')->default(false);
            $table->dateTime('date_validation')->nullable();
            $table->string('recepteur');
            $table->string('telephone');
            $table->string('entite');
            $table->string('logo_path');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bons');
    }
};
