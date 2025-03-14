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
            ['name' => 'Pesticides or Pest Repellents', 'description' => 'Substances to control or repel pests and insects.'],
            ['name' => 'Solvents', 'description' => 'Chemical solutions used for cleaning or dissolving substances.'],
            ['name' => 'Color Compound and Dispersions', 'description' => 'Pigments and dyes used in coloring various materials.'],
            ['name' => 'Films', 'description' => 'Thin, flexible material used for packaging or photography.'],
            ['name' => 'Paper Material and Products', 'description' => 'Various paper products like sheets, cards, and notebooks.'],
            ['name' => 'Batteries, Cells and Accessories', 'description' => 'Power sources and related accessories for electronic devices.'],
            ['name' => 'Measuring, Observing, and Testing Equipment', 'description' => 'Tools for measurement, observation, and scientific testing.'],
            ['name' => 'Cleaning Equipment and Supplies', 'description' => 'Tools and chemicals for cleaning and maintaining spaces.'],
            ['name' => 'Office Equipment, Accessories and Supplies', 'description' => 'Furniture and tools used in office environments.'],
            ['name' => 'Printer or Facsimile or Photocopy Supplies', 'description' => 'Items related to printing, faxing, or photocopying.'],
            ['name' => 'Consumer Electronics', 'description' => 'Electronic devices for personal use, like phones and laptops.'],
            ['name' => 'Arts and Crafts Equipment, Accessories and Supplies', 'description' => 'Materials and tools for creative activities like drawing or painting.'],
            ['name' => 'Common Office Supplies', 'description' => 'Everyday items used in office settings like pens, paper, and folders.'],
            ['name' => 'Common Janitorial Supplies', 'description' => 'Products used for cleaning and maintaining buildings or offices.'],
            ['name' => 'Printed Forms', 'description' => 'Pre-printed documents for use in offices or business operations.'],
            ['name' => 'Computer Supplies', 'description' => 'Peripherals and accessories related to computers and IT equipment.'],
            ['name' => 'Consumables', 'description' => 'Items that are used up and need replacement, like ink and paper.'],
            ['name' => 'Other New Items', 'description' => 'Miscellaneous new products not falling under specific categories.'],
        ];        
        foreach ($categories as $category) {
            CategoryModel::create($category);
        }
    }
}
