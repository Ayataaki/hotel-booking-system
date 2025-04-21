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
        Schema::table('chambres', function (Blueprint $table) {
            // Ajouter un champ pour la capacité de la chambre
            $table->integer('capacite')->default(1);  // ou tout autre valeur par défaut selon ton besoin
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chambres', function (Blueprint $table) {
            // Revenir en arrière (supprimer la colonne "capacite")
            $table->dropColumn('capacite');
        });
    }
};
