<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ItemStatusModel;
class ItemStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Available'],
            ['name' => 'Out of Stock'],
        ];

        foreach ($statuses as $status) {
            ItemStatusModel::create($status);
        }
    }
}
