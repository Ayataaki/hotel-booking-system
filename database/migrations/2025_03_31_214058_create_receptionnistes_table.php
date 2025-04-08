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
        Schema::create('receptionnistes', function (Blueprint $table) {
            $table->id();
            $table->string('nomRec');
            $table->string('prenomRec');
            $table->string('CIN');
            $table->string('numTel');
            $table->unsignedBigInteger('user_id');//à cause de l'héritage on obtient cette clé étrangère dans cette table
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receptionnistes');
    }
};
