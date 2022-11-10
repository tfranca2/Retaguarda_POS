<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EtapaTableSeeder extends Seeder {
    public function run() {
        DB::table('etapas')->insert([
            [
                'id' => 1,
                'descricao' => 'TESTE',
                'data' => Carbon::parse('next sunday')->toDateString(),
                'etapa' => 1,
                'tipo' => 1,
                'range_inicial' => 1,
                'range_final' => 100,
                'ativa' => true,
            ],
        ]);
    }
}
