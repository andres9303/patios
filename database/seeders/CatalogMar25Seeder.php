<?php

namespace Database\Seeders;

use App\Models\Config\Catalog;
use App\Models\Config\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogMar25Seeder extends Seeder
{
    public function run(): void
    {
        Catalog::create([
            'id' => 20701,
            'name' => 'Tipos de productos (Áreas)',
            'text' => 'Tipos de productos (Áreas)',
        ]);

        $catalog = Catalog::create([
            'id' => 20803,
            'name' => 'Tipos de Eventos (Espacios)',
            'text' => 'Tipos de Eventos (Espacios)',
        ]);

        $items = [
            ['id' => null, 'catalog_id' => $catalog->id,'name' => 'Inicio Proyecto','text' => 'Inicio Proyecto','order' => 0],
            ['id' => null, 'catalog_id' => $catalog->id,'name' => 'Anula Proyecto','text' => 'Anula Proyecto','order' => 1],
            ['id' => null, 'catalog_id' => $catalog->id,'name' => 'Fin Proyecto','text' => 'Fin Proyecto','order' => 2],
            ['id' => null, 'catalog_id' => $catalog->id,'name' => 'Costo','text' => 'Costo','order' => 3],
            ['id' => null, 'catalog_id' => $catalog->id,'name' => 'Ingreso','text' => 'Ingreso','order' => 4],
            ['id' => null, 'catalog_id' => $catalog->id,'name' => 'CheckList','text' => 'CheckList','order' => 5],
            ['id' => null, 'catalog_id' => $catalog->id,'name' => 'Comentario','text' => 'Comentario','order' => 6],
        ];

        DB::table('items')->insert($items);
    }
}
