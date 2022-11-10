<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresaTableSeeder extends Seeder {
    public function run() {
        DB::table('empresa')->insert([
            [ 
                'id' => 1, 
                'nome' => 'Retaguarda POS', 
                'cnpj' => '01234567890107', 
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now(), 
                'deleted_at' => NULL, 
                'main_logo' => 'logo.png', 
                'favicon' => 'logo.png', 
                'menu_logo' => 'logo.png', 
                'contracted_menu_logo' => 'logo.png', 
                'menu_background' => '#53586b', 
                'menu_color' => '#ffffff', 
            ],
        ]);
    }
}
