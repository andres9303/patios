<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $typeFields = [
            ['id' => 1, 'name' => 'Texto', 'description' => 'Campo de texto', 'state' => 1],
            ['id' => 2, 'name' => 'Entero', 'description' => 'Campo numérico entero', 'state' => 1],
            ['id' => 3, 'name' => 'Decimal', 'description' => 'Campo numérico decimal', 'state' => 1],
            ['id' => 4, 'name' => 'Fecha', 'description' => 'Campo de fecha', 'state' => 1],
            ['id' => 5, 'name' => 'Hora', 'description' => 'Campo de hora', 'state' => 1],
            ['id' => 6, 'name' => 'Fecha y Hora', 'description' => 'Campo de fecha y hora', 'state' => 1],
            ['id' => 7, 'name' => 'Booleano', 'description' => 'Campo booleano', 'state' => 1],
        ];

        DB::table('type_fields')->insert($typeFields);
    }
}
