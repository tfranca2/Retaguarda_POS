<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Cancelamentoprestadorcontratacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratacao', function (Blueprint $table) {
            $table->text('cancelamento_prestador')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratacao', function (Blueprint $table) {
            $table->dropColumn('cancelamento_prestador');
        });
    }
}
