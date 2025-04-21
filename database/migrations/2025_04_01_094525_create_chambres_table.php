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
        Schema::create('chambres', function (Blueprint $table) {
            $table->id();
            $table->integer('NumCh');
            $table->integer('NumEtg');
            $table->boolean('status')->default(false);
            $table->decimal('prixNuit');
            $table->unsignedBigInteger('categorie_id');
            $table->foreign('categorie_id')->references('id')->on('categories')->onUpdate('cascade');            
            
            $table->unsignedBigInteger('reservation_id')->default(0);

            //cette approche provoque un problème
            //$table->unsignedBigInteger('reservation_id')->nullable();
            //$table->foreign('reservation_id')->references('id')->on('reservations')->onUpdate('cascade')->onDelete('set null');

            //j'ai ajouté un nouveau champ image & capacite dans cette table , voire le fichier add_image_to_chambres_table

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chambres');
    }
};
