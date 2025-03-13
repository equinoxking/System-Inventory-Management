<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoryModel;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Office Supplies', 'description' => 'Items used in offices like paper, pens, and folders.'],
            ['name' => 'Cleaning Supplies', 'description' => 'Detergents, disinfectants, and other cleaning materials.'],
            ['name' => 'Electronics', 'description' => 'Computers, printers, and accessories.'],
            ['name' => 'Furniture', 'description' => 'Chairs, desks, cabinets, and other furniture items.'],
            ['name' => 'Medical Supplies', 'description' => 'First aid kits, gloves, and other medical essentials.'],
        ];

        foreach ($categories as $category) {
            CategoryModel::create($category);
        }
    }
}
