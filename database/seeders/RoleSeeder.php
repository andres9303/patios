<?php

namespace Database\Seeders;

use App\Models\Master\Company;
use App\Models\Security\Menu;
use App\Models\Security\Permission;
use App\Models\Security\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('username', 'admin')->first();
        $company = Company::where('name', 'Todos')->first();
        $role = Role::create([
            'name' => 'Super Administrador',
        ]);

        $permission = Permission::where('name', 'Total')->first();
        $menus = Menu::whereNotNull('menu_id')->get();
        foreach ($menus as $menu) {
            $role->permissions()->attach($permission, ['menu_id' => $menu->id]);
        }

        $company->users()->attach($user, ['role_id' => $role->id]);
    }
}
