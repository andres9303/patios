<?php

namespace Database\Seeders;

use App\Models\Config\Catalog;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CompanySeeder::class,
            UserSeeder::class,
            MenuSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            CatalogSeeder::class,
            VariableSeeder::class,
            LocationSeeder::class,
            CategorySeeder::class,
        ]);
    }
}
