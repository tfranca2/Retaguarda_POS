<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMuralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mural', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('tipo')->nullable();
            $table->string('titulo')->nullable();
            $table->text('descricao')->nullable();
            $table->longText('observacao')->nullable();
            $table->string('nome')->nullable();
            $table->string('telefone')->nullable();
            $table->string('email')->nullable();
            $table->longText('fotos')->nullable();
            $table->timestamp('inicio')->nullable();
            $table->timestamp('fim')->nullable();
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
        Schema::dropIfExists('mural');
    }
}
