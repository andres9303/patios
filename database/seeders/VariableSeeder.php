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
            ['cod' => 'TKT_DAYS', 'name' => 'Días por defecto de solucion tickets', 'concept' => 5],
            ['cod' => 'TKT_TYPE2', 'name' => 'ID de la categoría que se clasificará en el tipo 2', 'concept' => 0],
            ['cod' => 'TKT_TYPE3', 'name' => 'ID de la categoría que se clasificará en el tipo 3', 'concept' => 0],
        ];

        DB::table('variables')->insert($variables);
    }
}
