<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Prestadorreferencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prestador_referencia', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('prestador_id');
            $table->string('empresa')->nullable();
            $table->string('contato')->nullable();
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
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
        Schema::dropIfExists('prestador_referencia');
    }
}
