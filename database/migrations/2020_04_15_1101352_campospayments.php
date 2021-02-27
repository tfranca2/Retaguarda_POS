<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Campospayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->integer('contratacao_id')->nullable();
            $table->integer('cliente_id')->nullable();
            $table->double('valorBruto', 10,2)->nullable();
            $table->double('taxa', 10,2)->nullable();
            $table->double('valorLiquido', 10,2)->nullable();
            $table->double('valorExtra', 10,2)->nullable();

            $table->dropColumn('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('contratacao_id');
            $table->dropColumn('cliente_id');
            $table->dropColumn('valorBruto');
            $table->dropColumn('taxa');
            $table->dropColumn('valorLiquido');
            $table->dropColumn('valorExtra');
            
            $table->double('amount', 10,2)->nullable();
        });
    }
}
