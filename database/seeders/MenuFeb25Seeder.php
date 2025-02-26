<?php

namespace Database\Seeders;

use App\Models\Security\Permission;
use App\Models\Security\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuFeb25Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            ['id' => 9005,'code' => 'NTF','name' => 'Notificaciones','route' => 'notification', 'active' => 'notification*','icon' => 'fas fa-bell','order' => 4,'menu_id' => 9000,],

        ];

        DB::table('menus')->insert($menus);

        $permission = Permission::where('name', 'Total')->first();
        $role = Role::where('name', 'Super Administrador')->first();

        foreach ($menus as $menu) {
            $role->permissions()->attach($permission, ['menu_id' => $menu['id']]);
        }
    }
}
