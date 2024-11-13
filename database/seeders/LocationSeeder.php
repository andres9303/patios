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
            ['code' => null, 'name' => 'La Macarena', 'text' => 'Ubicación La Macarena', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Caño Cristales', 'text' => 'Ubicación Caño Cristales', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Nevado del Ruiz', 'text' => 'Ubicación Nevado del Ruiz', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Jaguar', 'text' => 'Ubicación Jaguar', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Villavo', 'text' => 'Ubicación Villavo', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Tarapoto Lake', 'text' => 'Ubicación Tarapoto Lake', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Nudo de Paramillo', 'text' => 'Ubicación Nudo de Paramillo', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Suites', 'text' => 'Ubicación Suites', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Nuquí', 'text' => 'Ubicación Nuquí', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Cañón del Chicamocha', 'text' => 'Ubicación Cañón del Chicamocha', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Mucura', 'text' => 'Ubicación Mucura', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Huila', 'text' => 'Ubicación Huila', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Chingaza', 'text' => 'Ubicación Chingaza', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Mompox', 'text' => 'Ubicación Mompox', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Manacacias', 'text' => 'Ubicación Manacacias', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Puerto Carreño', 'text' => 'Ubicación Puerto Carreño', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'San Agustín', 'text' => 'Ubicación San Agustín', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Bahía Solano', 'text' => 'Ubicación Bahía Solano', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Providencia', 'text' => 'Ubicación Providencia', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Santurban', 'text' => 'Ubicación Santurban', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Sierra Nevada', 'text' => 'Ubicación Sierra Nevada', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Tayrona', 'text' => 'Ubicación Tayrona', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Puerto Nariño', 'text' => 'Ubicación Puerto Nariño', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Orinoco', 'text' => 'Ubicación Orinoco', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Ciudad Perdida', 'text' => 'Ubicación Ciudad Perdida', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'San Bernardo', 'text' => 'Ubicación San Bernardo', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Cocuy', 'text' => 'Ubicación Cocuy', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Magdalena', 'text' => 'Ubicación Magdalena', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Gorgona', 'text' => 'Ubicación Gorgona', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Tobia', 'text' => 'Ubicación Tobia', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Valle del Cocora', 'text' => 'Ubicación Valle del Cocora', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Leticia', 'text' => 'Ubicación Leticia', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Cabo de la Vela', 'text' => 'Ubicación Cabo de la Vela', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Barú', 'text' => 'Ubicación Barú', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Amazonas', 'text' => 'Ubicación Amazonas', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Tatacoa', 'text' => 'Ubicación Tatacoa', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Chiribiquete', 'text' => 'Ubicación Chiribiquete', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Sapzurro', 'text' => 'Ubicación Sapzurro', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
            ['code' => null, 'name' => 'Carnaval de Barranquilla', 'text' => 'Ubicación Carnaval de Barranquilla', 'ref_id' => null, 'company_id' => 2, 'state' => 1],
        ];

        DB::table('locations')->insert($locations);
    }
}
