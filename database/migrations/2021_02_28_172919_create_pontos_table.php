<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePontosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pontos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('tipo')->nullable();
            $table->string('nome')->nullable();
            $table->string('responsavel')->nullable();
            $table->string('endereco')->nullable();
            $table->string('telefone')->nullable();
            $table->string('telefone2')->nullable();
            $table->string('cpf_cnpj')->nullable();
            $table->string('rg')->nullable();
            $table->string('funcionamento')->nullable();
            $table->time('encerramento')->nullable();
            $table->string('ponto_referencia')->nullable();
            $table->string('observacao')->nullable();
            $table->integer('distribuidor_id')->nullable();
            $table->integer('cidade_id')->nullable();
            $table->integer('bairro_id')->nullable();
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
        Schema::dropIfExists('pontos');
    }
}
