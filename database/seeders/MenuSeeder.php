<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            ['id' => 100,'code' => null,'name' => 'Seguridad','route' => null, 'active' => null,'icon' => 'fas fa-lock','order' => 0,'menu_id' => null,],
            ['id' => 101,'code' => 'USR','name' => 'Usuarios','route' => 'user', 'active' => 'user*','icon' => 'fas fa-user','order' => 0,'menu_id' => 100,],
            ['id' => 102,'code' => 'GRP','name' => 'Grupos','route' => 'role', 'active' => 'role*','icon' => 'fas fa-users','order' => 1,'menu_id' => 100,],
            ['id' => 103,'code' => 'FRM','name' => 'Formularios','route' => 'menu', 'active' => 'menu*','icon' => 'fas fa-shield-alt','order' => 2,'menu_id' => 100,],
            ['id' => 104,'code' => 'PMS','name' => 'Permisos','route' => 'permission', 'active' => 'permission*','icon' => 'fas fa-user-shield','order' => 3,'menu_id' => 100,],
            ['id' => 105,'code' => 'ACD','name' => 'Accesos Directos','route' => 'shortcut', 'active' => 'shortcut*','icon' => 'fas fa-map-signs','order' => 4,'menu_id' => 100,],
            ['id' => 200,'code' => null,'name' => 'Maestros','route' => null, 'active' => null,'icon' => 'fas fa-archive','order' => 1,'menu_id' => null],
            ['id' => 201,'code' => 'CCO','name' => 'Empresas','route' => 'company', 'active' => 'company*','icon' => 'fas fa-building','order' => 0,'menu_id' => 200],
            ['id' => 202,'code' => 'TER','name' => 'Terceros','route' => 'person', 'active' => 'person*','icon' => 'fas fa-users','order' => 1,'menu_id' => 200],
            ['id' => 203,'code' => 'LOC','name' => 'Locaciones','route' => 'location', 'active' => 'location*','icon' => 'fas fa-map','order' => 2,'menu_id' => 200],
            ['id' => 204,'code' => 'CAT','name' => 'Categorías FrontDesk','route' => 'category', 'active' => 'category*','icon' => 'fas fa-layer-group','order' => 3,'menu_id' => 200],
            ['id' => 300,'code' => null,'name' => 'Mesa de ayuda','route' => null, 'active' => null,'icon' => 'fas fa-ticket-alt','order' => 2,'menu_id' => null],
            ['id' => 301,'code' => 'RTK','name' => 'Registro Novedad','route' => 'ticket', 'active' => 'ticket*','icon' => 'fas fa-pencil-ruler','order' => 0,'menu_id' => 300],
            ['id' => 302,'code' => 'ATK','name' => 'Asignar Novedad','route' => 'manage-ticket', 'active' => 'manage-ticket*','icon' => 'fas fa-puzzle-piece','order' => 1,'menu_id' => 300],
            ['id' => 303,'code' => 'STK','name' => 'Resolver Novedad','route' => 'resolve-ticket', 'active' => 'resolve-ticket*','icon' => 'fas fa-file-signature','order' => 2,'menu_id' => 300],
            ['id' => 900, 'code' => NULL, 'name' => 'Reportes', 'route' => NULL, 'active' => null, 'icon' => 'fas fa-chart-pie', 'order' => 99, 'menu_id' => NULL],
            ['id' => 9000,'code' => NULL,'name' => 'Configuración','route' => NULL, 'active' => null,'icon' => 'fas fa-cog','order' => 9999,'menu_id' => NULL,],
            ['id' => 9003,'code' => 'LST','name' => 'Listas','route' => 'list', 'active' => 'list*','icon' => 'fas fa-list-ol','order' => 3,'menu_id' => 9000,],
            ['id' => 9004,'code' => 'VAR','name' => 'Variables','route' => 'variable', 'active' => 'variable*','icon' => 'fas fa-ruler','order' => 4,'menu_id' => 9000,],
        ];

        DB::table('menus')->insert($menus);
    }
}
