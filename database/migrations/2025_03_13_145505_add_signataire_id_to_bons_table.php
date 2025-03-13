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
        Schema::table('bons', function (Blueprint $table) {
            $table->unsignedBigInteger('signataire_id')->nullable();
            $table->foreign('signataire_id')->references('id')->on('signataires');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bons', function (Blueprint $table) {
            Schema::table('bons', function (Blueprint $table) {
                $table->dropForeign(['signataire_id']);
                $table->dropColumn('signataire_id');
            });
        });
    }
};
