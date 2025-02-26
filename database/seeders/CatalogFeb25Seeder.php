<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogFeb25Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catalogs = [
            ['id' => 20801, 'name' => 'Categorías Espacios', 'text' => 'Categorías para los espacios'],
            ['id' => 20802, 'name' => 'Clases Espacios', 'text' => 'Clases de espacios'],
            ['id' => 40101, 'name' => 'Clasificación Proyectos', 'text' => 'Clasificación de proyectos']
        ];

        DB::table('catalogs')->insert($catalogs);
    }
}
