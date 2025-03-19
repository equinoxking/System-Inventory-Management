<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoleModel;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'InventoryAdmin'],
            ['name' => 'HeadAdmin'],
            ['name' => 'CheckerAdmin'],
            ['name' => 'User'],
        ];

        foreach ($roles as $role) {
            RoleModel::create($role);
        }
    }
}
