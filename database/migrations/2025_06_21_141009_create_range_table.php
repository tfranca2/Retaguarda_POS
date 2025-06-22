<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('range', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('etapa_id');
            $table->string('tipo');
            $table->unsignedInteger('chances');
            $table->unsignedInteger('inicio');
            $table->unsignedInteger('final');
            $table->unsignedInteger('intervalo');
            $table->double('valor', 10,2);
            $table->double('comissao', 10,2);
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
        Schema::dropIfExists('range');
    }
}
