<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePremiacaoEletronica extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('premiacoes_eletronica', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('numero');
            $table->string('descricao');
            $table->double('bruto',8,2);
            $table->double('liquido',8,2);
            $table->integer('etapa_id');
            $table->softDeletes();
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
        Schema::dropIfExists('premiacao_eletronica');
    }
}
