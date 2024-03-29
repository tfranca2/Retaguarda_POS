<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            StateTableSeeder::class,
            CityTableSeeder::class,
            BairroTableSeeder::class,
            EmpresaTableSeeder::class,
            PermissionTableSeeder::class,
            UserTableSeeder::class,
            MenuTableSeeder::class,
            EtapaTableSeeder::class,
        ]);
    }
}