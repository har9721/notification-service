<?php

namespace Database\Seeders;

use App\Models\Mst_Roles;
use Illuminate\Container\Attributes\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MstRolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin', 'is_active' => 1, 'created_at' => now()],
            ['name' => 'User', 'is_active' => 1, 'created_at' => now()],
            ['name' => 'Guest', 'is_active' => 1, 'created_at' => now()],
        ];

        if(Mst_Roles::count() == 0)
        {
            Mst_Roles::insert($roles);
        }
    }
}
