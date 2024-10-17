<?php

namespace Database\Seeders;

use App\Models\Master\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::forceCreate([
            'name' => "Todos",
        ]);

        Company::forceCreate([
            'name' => "0 - Principal",
        ]);
    }
}
