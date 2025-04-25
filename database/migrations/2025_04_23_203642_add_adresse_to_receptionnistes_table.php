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
        Schema::table('receptionnistes', function (Blueprint $table) {
            $table->string('adresse')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('receptionnistes', function (Blueprint $table) {
            $table->dropColumn('adresse');
        });
    }
};
