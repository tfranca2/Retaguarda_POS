<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Feedbackclientecontratacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratacao', function (Blueprint $table) {
            $table->integer('rating')->nullable();
            $table->text('justificativa')->nullable();
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
            $table->dropColumn('rating');
            $table->dropColumn('justificativa');
        });
    }
}
