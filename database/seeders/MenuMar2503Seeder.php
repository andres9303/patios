<?php

namespace Database\Seeders;

use App\Models\Security\Permission;
use App\Models\Security\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuMar2503Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            ['id' => 209,'code' => 'TPE','name' => 'Tipos de Eventos','route' => 'event-type', 'active' => 'event-type*','icon' => 'fas fa-calendar-alt','order' => 8,'menu_id' => 200,],
            ['id' => 210,'code' => 'TPD','name' => 'Áreas','route' => 'area', 'active' => 'area*','icon' => 'fas fa-layer-group','order' => 9,'menu_id' => 200,],
            ['id' => 306,'code' => 'MNE','name' => 'Mis Novedades','route' => 'me-ticket', 'active' => 'me-ticket*','icon' => 'fas fa-bell','order' => 5,'menu_id' => 300,],
            ['id' => 505,'code' => 'ASI','name' => 'Asignar Inventario','route' => 'assign', 'active' => 'assign*','icon' => 'fas fa-hand-holding-medical','order' => 4,'menu_id' => 500,],
            ['id' => 506,'code' => 'DEV','name' => 'Devolver Inventario','route' => 'return', 'active' => 'return*','icon' => 'fas fa-hand-holding','order' => 5,'menu_id' => 500,],
            ['id' => 507,'code' => 'CON','name' => 'Conteo de Inventario','route' => 'count', 'active' => 'count*','icon' => 'fas fa-calculator','order' => 6,'menu_id' => 500,],
            ['id' => 600,'code' => null,'name' => 'Espacios','route' => null, 'active' => null,'icon' => 'fas fa-building','order' => 5,'menu_id' => null,],
            ['id' => 601,'code' => 'EVT','name' => 'Eventos','route' => 'event', 'active' => 'event*','icon' => 'fas fa-history','order' => 0,'menu_id' => 600,],
            ['id' => 602,'code' => 'ING','name' => 'Ingresos','route' => 'input', 'active' => 'input*','icon' => 'fas fa-money-bill-wave-alt','order' => 1,'menu_id' => 600,],
            ['id' => 603,'code' => 'PLT','name' => 'Plantillas','route' => 'template', 'active' => 'template*','icon' => 'fas fa-file-alt','order' => 2,'menu_id' => 600,],
            ['id' => 604,'code' => 'CHL','name' => 'Check-list','route' => 'checklist', 'active' => 'checklist*','icon' => 'fas fa-list','order' => 3,'menu_id' => 600,],
            ['id' => 905,'code' => null,'name' => 'Historial de tickets','route' => 'report-ticket-history', 'active' => null, 'icon' => 'fas fa-chart-pie','order' => 4,'menu_id' => 900,],
            ['id' => 906,'code' => null,'name' => 'Resumen Costos Mensual','route' => 'report-monthly-cost', 'active' => null, 'icon' => 'fas fa-chart-pie','order' => 5,'menu_id' => 900,],
            ['id' => 907,'code' => null,'name' => 'Inventario en Prestamo','route' => 'report-borrow', 'active' => null, 'icon' => 'fas fa-chart-pie','order' => 6,'menu_id' => 900,],
            ['id' => 908,'code' => null,'name' => 'Balance Proyecto','route' => 'report-balance-project', 'active' => null, 'icon' => 'fas fa-chart-pie','order' => 7,'menu_id' => 900,],
            ['id' => 909,'code' => null,'name' => 'Detalle de Costos','route' => 'report-cost-detail', 'active' => null, 'icon' => 'fas fa-chart-pie','order' => 8,'menu_id' => 900,],
            ['id' => 910,'code' => null,'name' => 'Balance Espacio','route' => 'report-balance-space', 'active' => null, 'icon' => 'fas fa-chart-pie','order' => 9,'menu_id' => 900,],
            ['id' => 911,'code' => null,'name' => 'Detalle Espacio','route' => 'report-space-detail', 'active' => null, 'icon' => 'fas fa-chart-pie','order' => 10,'menu_id' => 900,],
        ];

        DB::table('menus')->insert($menus);

        $permission = Permission::where('name', 'Total')->first();
        $role = Role::where('name', 'Super Administrador')->first();

        foreach ($menus as $menu) {
            $role->permissions()->attach($permission, ['menu_id' => $menu['id']]);
        }
    }
}
