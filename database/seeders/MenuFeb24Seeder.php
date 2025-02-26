<?php

namespace Database\Seeders;

use App\Models\Security\Permission;
use App\Models\Security\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuFeb24Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            ['id' => 205, 'code' => 'UNT', 'name' => 'Unidades', 'route' => 'unit', 'active' => 'unit*', 'icon' => 'fas fa-ruler', 'order' => 4, 'menu_id' => 200],
            ['id' => 206, 'code' => 'CPD', 'name' => 'Categorías Productos', 'route' => 'category-product', 'active' => 'category-product*', 'icon' => 'fas fa-grip-horizontal', 'order' => 5, 'menu_id' => 200],
            ['id' => 207, 'code' => 'PRO', 'name' => 'Productos', 'route' => 'product', 'active' => 'product*', 'icon' => 'fab fa-product-hunt', 'order' => 6, 'menu_id' => 200],
            ['id' => 208, 'code' => 'SPC', 'name' => 'Espacios', 'route' => 'space', 'active' => 'space*', 'icon' => 'fas fa-hotel', 'order' => 7, 'menu_id' => 200],
            ['id' => 400, 'code' => null, 'name' => 'Proyectos', 'route' => null, 'active' => null, 'icon' => 'fas fa-pencil-ruler', 'order' => 3, 'menu_id' => null],
            ['id' => 401, 'code' => 'PYT', 'name' => 'Proyectos', 'route' => 'project', 'active' => 'project*', 'icon' => 'fas fa-ruler-combined', 'order' => 0, 'menu_id' => 400],
            ['id' => 402, 'code' => 'APY', 'name' => 'Avances Proyecto', 'route' => 'project', 'active' => 'project*', 'icon' => 'fas fa-ruler', 'order' => 0, 'menu_id' => 400],
            ['id' => 500, 'code' => null, 'name' => 'Costos', 'route' => null, 'active' => null, 'icon' => 'fas fa-search-dollar', 'order' => 4, 'menu_id' => null],
            ['id' => 501, 'code' => 'BIL', 'name' => 'Gastos', 'route' => 'bill', 'active' => 'bill*', 'icon' => 'fas fa-tags', 'order' => 0, 'menu_id' => 500],
            ['id' => 502, 'code' => 'COM', 'name' => 'Compra Directa', 'route' => 'direct-purchase', 'active' => 'direct-purchase*', 'icon' => 'fas fa-shopping-cart', 'order' => 1, 'menu_id' => 500],
            ['id' => 503, 'code' => 'SAL', 'name' => 'Salidas Inventario', 'route' => 'output', 'active' => 'output*', 'icon' => 'fas fa-dolly-flatbed', 'order' => 2, 'menu_id' => 500],
            ['id' => 901, 'code' => null, 'name' => 'Saldo de Inventario', 'route' => 'report-balance-inv', 'active' => null, 'icon' => 'fas fa-chart-pie', 'order' => 0, 'menu_id' => 900],
            ['id' => 902, 'code' => null, 'name' => 'Movimientos de inventarios', 'route' => 'report-movement-inv', 'active' => null, 'icon' => 'fas fa-chart-pie', 'order' => 1, 'menu_id' => 900],
            ['id' => 903, 'code' => null, 'name' => 'Costos por actividad', 'route' => 'report-cost-activity', 'active' => null, 'icon' => 'fas fa-chart-pie', 'order' => 2, 'menu_id' => 900],
            
        ];

        DB::table('menus')->insert($menus);

        $permission = Permission::where('name', 'Total')->first();
        $role = Role::where('name', 'Super Administrador')->first();

        foreach ($menus as $menu) {
            $role->permissions()->attach($permission, ['menu_id' => $menu['id']]);
        }
    }
}
