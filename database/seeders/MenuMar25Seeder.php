<?php

namespace Database\Seeders;

use App\Models\Security\Permission;
use App\Models\Security\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuMar25Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            ['id' => 504,'code' => 'AJT','name' => 'Ajustes Inventario','route' => 'adjustment', 'active' => 'adjustment*','icon' => 'fas fa-warehouse','order' => 3,'menu_id' => 500,],
            ['id' => 904,'code' => null,'name' => 'Kardex','route' => 'report-kardex', 'active' => null,'icon' => 'fas fa-chart-pie','order' => 3,'menu_id' => 900,],

        ];

        DB::table('menus')->insert($menus);

        $permission = Permission::where('name', 'Total')->first();
        $role = Role::where('name', 'Super Administrador')->first();

        foreach ($menus as $menu) {
            $role->permissions()->attach($permission, ['menu_id' => $menu['id']]);
        }
    }
}
