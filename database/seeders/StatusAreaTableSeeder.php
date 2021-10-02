<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StatusAreaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status_area')->insert([
            'description' => 'Grande possibilidade de incêndio',
            'name' => 'Faixa Laranja',
            'color' => '#ff8c00',
        ]);

        DB::table('status_area')->insert([
            'description' => 'Urgência possibilidade de incêndio',
            'name' => 'Faixa Vermelha',
            'color' => '#DC143C',
        ]);

        DB::table('status_area')->insert([
            'description' => 'Tudo bem',
            'name' => 'Faixa Azul',
            'color' => '#1E90FF',
        ]);
    }
}
