<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Clientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('CPF',11)->nullable();
            $table->string('nome',65)->nullable();
            $table->string('email',65)->nullable();
            $table->string('DDD',3)->nullable();
            $table->string('telefone',15)->nullable();
            $table->enum('is_whatsapp', ['sim', 'nao'])->default('nao');
            $table->integer('cidade_id')->nullable();
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
        //
        Schema::dropIfExists('clientes');
    }
}
