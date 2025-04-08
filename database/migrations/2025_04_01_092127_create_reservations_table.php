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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->date('dateDeb');
            $table->date('dateFin');
            $table->decimal('totalPayer');
            $table->decimal('soldePayer');
            $table->unsignedBigInteger('receptionniste_id');
            $table->foreign('receptionniste_id')->references('id')->on('receptionnistes')->onUpdate('cascade');     
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onUpdate('cascade');     
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
