<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RgAreaDistribuidor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('distribuidores', function (Blueprint $table) {
            $table->string('rg')->nullable();
            $table->string('area')->nullable();
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
            $table->dropColumn('rg');
            $table->dropColumn('area');
        });
    }
}
