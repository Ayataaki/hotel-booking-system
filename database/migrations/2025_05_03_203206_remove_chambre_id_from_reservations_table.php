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
        Schema::table('reservations', function (Blueprint $table) {
            // Supprimer d'abord la contrainte de clé étrangère
            $table->dropForeign(['chambre_id']);
            // Puis supprimer la colonne
            $table->dropColumn('chambre_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Recréer la colonne
            $table->unsignedBigInteger('chambre_id')->nullable();
            // Recréer la contrainte
            $table->foreign('chambre_id')->references('id')->on('chambres')->onDelete('cascade');
        });
    }
};
