<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuTableSeeder extends Seeder {
    public function run() {
        DB::table('menus')->insert([
            [
                'id' => 1, 
                'permission' => 'usuarios-listar', 
                'parent' => NULL,
                'ordem' => NULL,
                'icon' => 'fa fa-user-plus', 
                'label' => 'Usuários', 
                'link' => 'usuarios', 
            ],
            [
                'id' => 2, 
                'permission' => 'etapas-listar', 
                'parent' => NULL,
                'ordem' => NULL,
                'icon' => 'fa fa-bars', 
                'label' => 'Etapas', 
                'link' => 'etapas', 
            ],
            [
                'id' => 3, 
                'permission' => 'etapas-listar', 
                'parent' => 2, 
                'ordem' => 0, 
                'icon' => NULL,
                'label' => 'Etapas', 
                'link' => 'etapas', 
            ],
            [
                'id' => 4, 
                'permission' => 'premiacao-listar', 
                'parent' => 2, 
                'ordem' => NULL,
                'icon' => NULL,
                'label' => 'Premiação', 
                'link' => 'premiacao', 
            ],
            [
                'id' => 5, 
                'permission' => 'premiacao_eletronica-listar', 
                'parent' => 2, 
                'ordem' => NULL,
                'icon' => NULL,
                'label' => 'Premiação Eletrônica', 
                'link' => 'premiacaoeletronica', 
            ],
            [
                'id' => 6, 
                'permission' => 'vendas-listar', 
                'parent' => NULL,
                'ordem' => NULL,
                'icon' => 'fa fa-usd', 
                'label' => 'Vendas', 
                'link' => NULL,
            ],
            [
                'id' => 7, 
                'permission' => 'vendas-listar', 
                'parent' => 6, 
                'ordem' => 0, 
                'icon' => NULL,
                'label' => 'Vendas', 
                'link' => 'vendas', 
            ],
            [
                'id' => 8, 
                'permission' => 'vendas-listar', 
                'parent' => 6, 
                'ordem' => NULL,
                'icon' => NULL,
                'label' => 'Leads', 
                'link' => 'leads', 
            ],
        ]);
    }
}
