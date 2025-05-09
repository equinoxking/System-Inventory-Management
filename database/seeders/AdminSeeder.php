<?php

namespace Database\Seeders;

use App\Models\AdminModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            ['full_name' => 'Christine Joy C. Bartolome', 'role_id' => '1', 'position' => 'AAIV/ACTING AO', 'client_id' => 6],
        ];
        foreach ($admins as $admin) {
            AdminModel::create($admin);
        }
    }
}
