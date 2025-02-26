<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => 'Unidad [und]', 'unit' => 1, 'time' => null, 'mass' => null, 'longitude' => null, 'state' => 1],
            ['name' => 'Segundo [s]', 'unit' => null, 'time' => 1, 'mass' => null, 'longitude' => null, 'state' => 1],
            ['name' => 'Metro [m]', 'unit' => null, 'time' => null, 'mass' => null, 'longitude' => 1, 'state' => 1],
            ['name' => 'Kilogramo [kg]', 'unit' => null, 'time' => null, 'mass' => 1, 'longitude' => null, 'state' => 1],
        ];

        DB::table('units')->insert($items);
    }
}
