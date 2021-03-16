<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEtapas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('etapas', function (Blueprint $table) {
            $table->integer('etapa')->unique();
            $table->integer('tipo')->comment('1=simples|2=dupla|3=tripla|4=simples e dupla|5=simples e tripla');
            $table->integer('range_inicial');
            $table->integer('range_final');
            $table->integer('intervalo')->nullable();
            $table->double('valor_simples',5,2)->nullable();
            $table->double('valor_duplo',5,2)->nullable();
            $table->double('valor_triplo',5,2)->nullable();
            $table->double('v_comissao_simples',5,2)->nullable();
            $table->double('v_comissao_duplo',5,2)->nullable();
            $table->double('v_comissao_triplo',5,2)->nullable();
            $table->integer('ativo')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('etapas', function (Blueprint $table) {
            $table->dropColumn('etapa');
            $table->dropColumn('tipo');
            $table->dropColumn('range_inicial');
            $table->dropColumn('range_final');
            $table->dropColumn('intervalo');
            $table->dropColumn('valor_simples');
            $table->dropColumn('valor_duplo');
            $table->dropColumn('valor_triplo');
            $table->dropColumn('v_comissao_simples');
            $table->dropColumn('v_comissao_duplo');
            $table->dropColumn('v_comissao_triplo');
            $table->dropColumn('ativo');
        });
    }
}
