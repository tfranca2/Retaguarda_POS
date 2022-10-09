<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('venda_id')->nullable();
            $table->unsignedInteger('cliente_id')->nullable();
            $table->string('transaction_code')->nullable();
            $table->string('status')->nullable();
            $table->double('valor_bruto', 10,2)->nullable();
            $table->double('taxa', 10,2)->nullable();
            $table->double('valor_liquido', 10,2)->nullable();
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
        Schema::dropIfExists('payments');
    }
}
