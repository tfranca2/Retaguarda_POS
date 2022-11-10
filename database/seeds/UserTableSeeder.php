<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder {
    public function run() {
        DB::table('users')->insert([
            [ 
                'id' => 1, 
                'name' => 'Administrador', 
                'email' => 'pos@runservices.com.br', 
                'api_token' => Str::random(60), 
                'perfil_id' => 1, 
                'cpf' => '12345678909', 
                'imagem' => NULL, 
                'empresa_id' => 1, 
                'password' => Hash::make('#r1Ser2s@'), 
                'remember_token' => NULL, 
                'email_verified_at' => Carbon::now(), 
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now(), 
                'deleted_at' => NULL,  
            ],
        ]);
    }
}
