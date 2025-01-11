<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Mobiliario' => [
                'Cama mal estado',
                'Silla mal estado',
                'Mesa mal estado',
            ],
            'Iluminación' => [
                'Bombillos fundidos',
                'Luces intermitentes',
            ],
            'Aire acondicionado o ventiladores' => [
                'No funcionan correctamente',
                'Hacen ruido',
            ],
            'Puertas y ventanas' => [
                'Cerraduras dañadas',
                'ventanas que no cierran bien',
                'cortinas en mal estado.',
            ],
            'Enchufes y tomacorrientes' => [
                'No funcionan',
            ],
            'Ruido' => [
                'Interno',
                'Externo',
            ],
            'Plomería' => [
                'Grifos que gotean',
                'Poca presión',
                'Baño que no descarga',
            ],
            'Agua caliente' => [
                'Inexistente',
                'Intermitente',
            ],
            'Humedad' => [
                'Paredes',
                'Techos',
            ],
            'Accesorios dañados' => [
                'Portapapeles',
                'Espejos roto',
                'Jaboneras',
            ],
            'Cocina' => [
                'Electrodomésticos dañados (nevera microondas horno)',
                'Gas',
                'Electricidad no funciona',
            ],
            'Zonas de entretenimiento' => [
                'Juegos en mal estado',
                'Mesas en mal estado',
                'Sofás en mal estado',
                'Televisores en mal estado',
            ],
            'Coworking' => [
                'Sillas en mal estado',
                'Enchufes dañados',
                'Internet lento o caido',
            ],
            'Piscina' => [
                'Agua sucia',
                'Bombas en mal estado',
            ],
            'Plagas' => [
                'Chinches',
                'Cucarachas',
            ],
            'Electricidad' => [
                'Cortes interruptores que no funcionan',
            ],
            'Agua' => [
                'Falta de agua',
                'Presión insuficiente',
            ],
            'Wi-Fi' => [
                'Sin conexión',
                'Velocidad muy baja',
            ],
            'Sistemas de seguridad' => [
                'Camarás',
                'Detectores de humo',
                'Extintores en mal estado',
            ],
            'Ascensor' => [
                'En mantenimiento',
                'No funciona',
            ],
            'Informativo' => [
                'Información',
            ],
            'Objetos perdidos' => [
                'Objetos perdidos',
            ],
        ];

        foreach ($categories as $parent => $subcategories) {
            $parentCategory = DB::table('categories')->where('name', $parent)->first();

            if (!$parentCategory) {
                $parentId = DB::table('categories')->insertGetId([
                    'name' => $parent,
                    'text' => $parent,
                    'days' => 5,
                    'company_id' => 1,
                    'state' => 1,
                ]);
            } else {
                $parentId = $parentCategory->id;
            }

            foreach ($subcategories as $subcategory) {
                $subcategoryName = $subcategory;
                $subcategoryText = "$parent - $subcategory";

                DB::table('categories')->updateOrInsert(
                    ['name' => $subcategoryName, 'ref_id' => $parentId],
                    [
                        'text' => $subcategoryText,
                        'days' => 5,
                        'company_id' => 1,
                        'state' => 1,
                    ]
                );
            }
        }
    }
}
