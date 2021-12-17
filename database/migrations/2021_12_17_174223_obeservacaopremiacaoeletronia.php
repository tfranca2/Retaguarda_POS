<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Obeservacaopremiacaoeletronia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('premiacoes_eletronica', function (Blueprint $table) {
            $table->string('observacao')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('premiacoes_eletronica', function (Blueprint $table) {
            $table->dropColumn('observacao');
        });
    }
}
