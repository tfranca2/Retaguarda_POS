<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Configempresa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empresa', function ($table) {
            $table->string('main_logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('menu_logo')->nullable();
            $table->string('contracted_menu_logo')->nullable();
            $table->string('menu_background')->nullable();
            $table->string('menu_color')->nullable();
            $table->string('google_maps_api_key')->nullable();
            $table->string('fcm_server_key')->nullable();
            $table->string('mail_driver')->nullable();
            $table->string('mail_host')->nullable();
            $table->string('mail_port')->nullable();
            $table->string('mail_username')->nullable();
            $table->string('mail_password')->nullable();
            $table->string('mail_encryption')->nullable();
            $table->string('mail_from_name')->nullable();
            $table->string('mail_from_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresa', function ($table) {
            $table->dropColumn('main_logo');
            $table->dropColumn('favicon');
            $table->dropColumn('menu_logo');
            $table->dropColumn('contracted_menu_logo');
            $table->dropColumn('menu_background');
            $table->dropColumn('menu_color');
            $table->dropColumn('google_maps_api_key');
            $table->dropColumn('fcm_server_key');
            $table->dropColumn('mail_driver');
            $table->dropColumn('mail_host');
            $table->dropColumn('mail_port');
            $table->dropColumn('mail_username');
            $table->dropColumn('mail_password');
            $table->dropColumn('mail_encryption');
            $table->dropColumn('mail_from_name');
            $table->dropColumn('mail_from_address');
        });
    }
}
