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
        Schema::table('receptionnistes', function (Blueprint $table) {
            $table->string('email')->nullable();
            $table->string('statut')->default('Actif'); // ou 'inactif' selon besoin
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receptionnistes', function (Blueprint $table) {
            $table->dropColumn(['email', 'statut']);
        });

    }
};
