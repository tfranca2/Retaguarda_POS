<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePremiacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('premiacoes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('seq')->nullable();
            $table->string('premiacao')->nullable();
            $table->string('descricao')->nullable();
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
        Schema::dropIfExists('premiacoes');
    }
}
