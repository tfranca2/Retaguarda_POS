<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEstadoIdToDistribuidoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('distribuidores', function (Blueprint $table) {
            $table->integer('cidade_id')->nullable();
            $table->integer('estado_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('distribuidores', function (Blueprint $table) {
            $table->dropColumn('cidade_id');
            $table->dropColumn('estado_id');
        });
    }
}
