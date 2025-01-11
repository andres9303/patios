<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['code' => null, 'name' => 'Amazonas', 'text' => 'Amazonas - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Cabo de la Vela', 'text' => 'Cabo De La Vela - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Jaguar', 'text' => 'Jaguar - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Nudo de Paramillo', 'text' => 'Nudo De Paramillo - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Manacacias', 'text' => 'Manacacias - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Leticia', 'text' => 'Leticia - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Nuqui', 'text' => 'Nuqui - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Villavo', 'text' => 'Villavo - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Valle del Cocora', 'text' => 'Valle Del Cocora - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Monkey Island', 'text' => 'Monkey Island - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Baru', 'text' => 'Baru - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Orinoco', 'text' => 'Orinoco - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Puerto Carreño', 'text' => 'Puerto Carreño - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Nevado del Ruiz', 'text' => 'Nevado Del Ruiz - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Gorgona', 'text' => 'Gorgona - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'La Macarena', 'text' => 'La Macarena - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Puerto Nariño', 'text' => 'Puerto Nariño - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Providencia', 'text' => 'Providencia - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Tarapoto', 'text' => 'Tarapoto - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Tayrona', 'text' => 'Tayrona - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Caño Cristales', 'text' => 'Caño Cristales - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Sierra Nevada', 'text' => 'Sierra Nevada - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Cañon del Chicamocha', 'text' => 'Cañon Del Chicamocha - BOUTIQUE', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Huila', 'text' => 'Huila - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Pisba', 'text' => 'Pisba - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Carnaval Blancos Y Negros', 'text' => 'Carnaval Blancos Y Negros - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Sapzurro', 'text' => 'Sapzurro - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Santurban', 'text' => 'Santurban - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Chiribiquete', 'text' => 'Chiribiquete - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Feria De Manizales', 'text' => 'Feria De Manizales - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Bahia Solano', 'text' => 'Bahia Solano - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Sumapaz', 'text' => 'Sumapaz - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Ciudad Perdida', 'text' => 'Ciudad Perdida - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'San Bernardo Del Viento', 'text' => 'San Bernardo Del Viento - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Desierto De La Tatacoa', 'text' => 'Desierto De La Tatacoa - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Carnaval De Barranquilla', 'text' => 'Carnaval De Barranquilla - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Vallenato', 'text' => 'Vallenato - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Islas Del Rosario', 'text' => 'Islas Del Rosario - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Chingaza', 'text' => 'Chingaza - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Feria De Cali', 'text' => 'Feria De Cali - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Cocuy', 'text' => 'Cocuy - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'El Peñol', 'text' => 'El Peñol - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Cumbia', 'text' => 'Cumbia - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'San Agustin', 'text' => 'San Agustin - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Bahia Malaga', 'text' => 'Bahia Malaga - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Feria De Flores', 'text' => 'Feria De Flores - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Magdalena', 'text' => 'Magdalena - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Salsa', 'text' => 'Salsa - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Festival Vallenato', 'text' => 'Festival Vallenato - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Mucura', 'text' => 'Mucura - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Mompox', 'text' => 'Mompox - SUITES', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
        ];

        DB::table('locations')->insert($locations);
    }
}
