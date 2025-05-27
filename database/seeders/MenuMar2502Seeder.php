<?php

namespace Database\Seeders;

use App\Models\Security\Permission;
use App\Models\Security\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuMar2502Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            ['id' => 403,'code' => 'PPS','name' => 'Programaciones','route' => 'schedule', 'active' => 'schedule*','icon' => 'fas fa-calendar-alt','order' => 3,'menu_id' => 400,],
        ];

        DB::table('menus')->insert($menus);

        $permission = Permission::where('name', 'Total')->first();
        $role = Role::where('name', 'Super Administrador')->first();

        foreach ($menus as $menu) {
            $role->permissions()->attach($permission, ['menu_id' => $menu['id']]);
        }
    }
}
