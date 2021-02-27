<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Contratacaoconcluida extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratacao', function (Blueprint $table) {
            $table->dateTime('iniciado')->nullable();
            $table->dateTime('concluido')->nullable();
            $table->text('pos_servico')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratacao', function (Blueprint $table) {
            $table->dropColumn('iniciado');
            $table->dropColumn('concluido');
            $table->dropColumn('pos_servico');
        });
    }
}
