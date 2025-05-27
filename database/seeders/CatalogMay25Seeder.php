<?php

namespace Database\Seeders;

use App\Models\Config\Catalog;
use Illuminate\Database\Seeder;

class CatalogMay25Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Catalog::create([
            'id' => 70001,
            'name' => 'Categorias Actividades (Eventos)',
            'text' => 'Categorias Actividades (Eventos)',
        ]);

        Catalog::create([
            'id' => 70002,
            'name' => 'Actividades (Eventos)',
            'text' => 'Actividades (Eventos)',
        ]);
    }
}
