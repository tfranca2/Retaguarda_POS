<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Filiacaoprestador extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prestador', function (Blueprint $table) {
            $table->string('mae')->nullable();
            $table->string('pai')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->boolean('checkantecedentes')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prestador', function (Blueprint $table) {
            $table->dropColumn('mae');
            $table->dropColumn('pai');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('checkantecedentes');
        });
    }
}
