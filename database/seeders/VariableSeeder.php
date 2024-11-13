<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VariableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variables = [
            ['cod' => 'TKT_DAYS', 'name' => 'Días por defecto de solucion tickets', 'concept' => 2],
        ];

        DB::table('variables')->insert($variables);
    }
}
