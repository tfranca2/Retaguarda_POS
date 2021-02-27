<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Contratacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratacao', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('cliente_id')->nullable();
            $table->integer('servico_id')->nullable();
            $table->integer('prestador_id')->nullable();
            $table->dateTime('inicio')->nullable();
            $table->dateTime('fim')->nullable();
            $table->string('tipo_imovel')->nullable();
            $table->decimal('valor',10,2)->nullable();
            $table->text('observacoes')->nullable();
            $table->text('campos')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratacao');
    }
}
