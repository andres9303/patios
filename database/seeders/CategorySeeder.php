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
            ['code' => 'INF', 'name' => 'Información General', 'text' => 'Consultas generales y detalles de información.', 'days' => 1, 'ref_id' => null, 'company_id' => 1, 'state' => 1],
            ['code' => 'MAINT', 'name' => 'Mantenimiento', 'text' => 'Mantenimiento de habitaciones, ventilación y limpieza.', 'days' => 3, 'ref_id' => null, 'company_id' => 1, 'state' => 1],
            ['code' => 'CLEAN', 'name' => 'Limpieza y Saneamiento', 'text' => 'Limpieza por insectos, chinches o suciedad en habitación.', 'days' => 2, 'ref_id' => null, 'company_id' => 1, 'state' => 1],
            ['code' => 'RES', 'name' => 'Reservas', 'text' => 'Problemas o cambios en reservas.', 'days' => 1, 'ref_id' => null, 'company_id' => 1, 'state' => 1],
            ['code' => 'CANCE', 'name' => 'Cancelación de Reserva', 'text' => 'Cancelaciones y devoluciones por cancelación de reservas.', 'days' => 2, 'ref_id' => null, 'company_id' => 1, 'state' => 1],
            ['code' => 'ROOMC', 'name' => 'Cambio de Habitación', 'text' => 'Cambios de habitación por solicitud del huésped o inconvenientes.', 'days' => 1, 'ref_id' => null, 'company_id' => 1, 'state' => 1],
            ['code' => 'REFUN', 'name' => 'Devoluciones y Reembolsos', 'text' => 'Procesos de devolución o reembolso de pagos.', 'days' => 2, 'ref_id' => null, 'company_id' => 1, 'state' => 1],
            ['code' => 'NOISE', 'name' => 'Ruido e Incomodidad', 'text' => 'Inconformidad por ruido de otros huéspedes o eventos.', 'days' => 1, 'ref_id' => null, 'company_id' => 1, 'state' => 1],
            ['code' => 'OBJLO', 'name' => 'Objetos Perdidos', 'text' => 'Reportes de objetos perdidos o encontrados.', 'days' => 3, 'ref_id' => null, 'company_id' => 1, 'state' => 1],
            ['code' => 'COMPL', 'name' => 'Quejas y Reclamos', 'text' => 'Quejas relacionadas con la estancia y servicios.', 'days' => 2, 'ref_id' => null, 'company_id' => 1, 'state' => 1],
            ['code' => 'SECUR', 'name' => 'Seguridad y Pertenencias', 'text' => 'Problemas de seguridad o pertenencias de los huéspedes.', 'days' => 1, 'ref_id' => null, 'company_id' => 1, 'state' => 1],
            ['code' => 'INFOR', 'name' => 'Solicitud de Información', 'text' => 'Consultas específicas sobre estadía o servicios.', 'days' => 1, 'ref_id' => null, 'company_id' => 1, 'state' => 1],
            ['code' => 'ERROR', 'name' => 'Errores en Reserva o Facturación', 'text' => 'Errores en facturación, reservas o pagos.', 'days' => 2, 'ref_id' => null, 'company_id' => 1, 'state' => 1],
            ['code' => 'BAGGA', 'name' => 'Equipaje', 'text' => 'Reclamación y gestión de equipaje en recepción.', 'days' => 1, 'ref_id' => null, 'company_id' => 1, 'state' => 1],
            ['code' => 'PAQUE', 'name' => 'Recepción de Paquetes', 'text' => 'Paquetes y correspondencia recibidos para los huéspedes.', 'days' => 1, 'ref_id' => null, 'company_id' => 1, 'state' => 1],
            ['code' => 'LOSTF', 'name' => 'Objetos Perdidos y Recuperados', 'text' => 'Gestión de objetos olvidados y su recuperación.', 'days' => 3, 'ref_id' => null, 'company_id' => 1, 'state' => 1],
            ['code' => 'HEALT', 'name' => 'Salud', 'text' => 'Asistencia en caso de problemas de salud o alergias.', 'days' => 1, 'ref_id' => null, 'company_id' => 1, 'state' => 1],
            ['code' => 'CHOUT', 'name' => 'Late Check-Out', 'text' => 'Solicitud y gestión de late check-out.', 'days' => 1, 'ref_id' => null, 'company_id' => 1, 'state' => 1],
            ['code' => 'TRANS', 'name' => 'Transporte', 'text' => 'Solicitud de transporte y gestión de movilidad.', 'days' => 1, 'ref_id' => null, 'company_id' => 1, 'state' => 1],
            ['code' => 'INVOI', 'name' => 'Facturación y Documentos', 'text' => 'Solicitudes de facturación y documentos pendientes.', 'days' => 2, 'ref_id' => null, 'company_id' => 1, 'state' => 1],
        ];

        DB::table('categories')->insert($categories);
    }
}
