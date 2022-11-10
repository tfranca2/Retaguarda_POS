<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionTableSeeder extends Seeder {
    public function run() {
        DB::table('perfil')->insert([
            [ 
                'id'=> 1, 
                'nome' => 'Administrador', 
            ],
        ]);

        DB::table('permissions')->insert([
            [ 
                'perfil_id' => 1, 
                'role' => 'empresas-listar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'empresas-incluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'empresas-editar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'empresas-excluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'empresas-gerenciar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'usuarios-listar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'usuarios-incluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'usuarios-editar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'usuarios-excluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'usuarios-gerenciar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'perfis-listar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'perfis-incluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'perfis-editar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'perfis-excluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'perfis-gerenciar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'configuracoes-listar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'configuracoes-incluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'configuracoes-editar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'configuracoes-excluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'configuracoes-gerenciar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'bairros-listar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'bairros-incluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'bairros-editar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'bairros-excluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'bairros-gerenciar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'cidades-listar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'cidades-incluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'cidades-editar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'cidades-excluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'cidades-gerenciar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'dispositivos-listar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'dispositivos-incluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'dispositivos-editar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'dispositivos-excluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'dispositivos-gerenciar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'etapas-listar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'etapas-incluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'etapas-editar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'etapas-excluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'etapas-gerenciar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'matrizes-listar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'matrizes-incluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'matrizes-editar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'matrizes-excluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'matrizes-gerenciar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'pontos-listar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'pontos-incluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'pontos-editar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'pontos-excluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'pontos-gerenciar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'premiacao-listar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'premiacao-incluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'premiacao-editar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'premiacao-excluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'premiacao-gerenciar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' => 'premiacao_eletronica-listar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' =>  'premiacao_eletronica-incluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' =>  'premiacao_eletronica-editar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' =>  'premiacao_eletronica-excluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' =>  'premiacao_eletronica-gerenciar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' =>  'vendas-listar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' =>  'vendas-incluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' =>  'vendas-editar', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' =>  'vendas-excluir', 
            ],
            [ 
                'perfil_id' => 1, 
                'role' =>  'vendas-gerenciar', 
            ],
        ]);


    }
}
