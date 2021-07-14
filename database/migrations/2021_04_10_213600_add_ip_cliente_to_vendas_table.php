<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIpClienteToVendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendas', function (Blueprint $table) {
            $table->string('matriz_id')->nullable()->after('etapa_id');
            $table->string('nome')->nullable()->after('matriz_id');
            $table->string('cpf')->nullable()->after('nome');
            $table->string('telefone')->nullable()->after('cpf');
            $table->string('ip')->nullable()->after('telefone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendas', function (Blueprint $table) {
            $table->dropColumn('matriz_id');
            $table->dropColumn('nome');
            $table->dropColumn('cpf');
            $table->dropColumn('telefone');
            $table->dropColumn('ip');
        });
    }
}
