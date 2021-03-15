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
        Schema::create('etapas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('etapa')->unique();
            $table->string('descricao',45);
            $table->date('data');
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
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('etapas');
    }
}
