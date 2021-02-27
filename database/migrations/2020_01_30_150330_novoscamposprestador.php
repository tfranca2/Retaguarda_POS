<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Novoscamposprestador extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prestador', function (Blueprint $table) {
            $table->string('rg')->nullable();
            $table->string('cep')->nullable();
            $table->string('endereco')->nullable();
            $table->string('numero')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->string('celular')->nullable();
            $table->string('fixo')->nullable();
            $table->text('observacoes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prestador', function (Blueprint $table) {
            $table->dropColumn('rg');
            $table->dropColumn('cep');
            $table->dropColumn('endereco');
            $table->dropColumn('numero');
            $table->dropColumn('bairro');
            $table->dropColumn('cidade');
            $table->dropColumn('estado');
            $table->dropColumn('celular');
            $table->dropColumn('fixo');
            $table->dropColumn('observacoes');
        });
    }
}
