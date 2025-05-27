<?php

namespace Database\Seeders;

use App\Models\Security\Permission;
use App\Models\Security\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuMay25Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            ['id' => 700,'code' => null,'name' => 'Eventos','route' => null, 'active' => null,'icon' => 'fab fa-untappd','order' => 5,'menu_id' => null],
            ['id' => 701,'code' => 'CAC','name' => 'Categorías Actividades','route' => 'category-activity', 'active' => 'category-activity*','icon' => 'fas fa-layer-group','order' => 0,'menu_id' => 700],
            ['id' => 702,'code' => 'ACT','name' => 'Actividades','route' => 'meeting', 'active' => 'meeting*','icon' => 'fas fa-beer','order' => 1,'menu_id' => 700],
            ['id' => 703,'code' => 'AGD','name' => 'Agenda','route' => 'timetable', 'active' => 'timetable*','icon' => 'fas fa-calendar-alt','order' => 2,'menu_id' => 700],
            ['id' => 912,'code' => null,'name' => 'Actividades pendientes','route' => 'report-pending-activity', 'active' => null, 'icon' => 'fas fa-chart-pie','order' => 11,'menu_id' => 900],
            ['id' => 913,'code' => null,'name' => 'Avances por responsable','route' => 'report-responsible-activity', 'active' => null, 'icon' => 'fas fa-chart-pie','order' => 12,'menu_id' => 900],
            ['id' => 914,'code' => null,'name' => 'Eventos por categoría','route' => 'report-category-event', 'active' => null, 'icon' => 'fas fa-chart-pie','order' => 13,'menu_id' => 900],
            ['id' => 915,'code' => null,'name' => 'Agenda Eventos','route' => 'report-timetable-event', 'active' => null, 'icon' => 'fas fa-chart-pie','order' => 14,'menu_id' => 900],
        ];

        DB::table('menus')->insert($menus);

        $permission = Permission::where('name', 'Total')->first();
        $role = Role::where('name', 'Super Administrador')->first();

        foreach ($menus as $menu) {
            $role->permissions()->attach($permission, ['menu_id' => $menu['id']]);
        }
    }
}
