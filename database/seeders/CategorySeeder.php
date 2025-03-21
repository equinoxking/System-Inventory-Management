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
            [
                'name' => 'Pesticides or Pest Repellents', 'description' => 'Substances to control or repel pests and insects.',
                'sub_category_id' => 1
            ],
            [
                'name' => 'Solvents', 'description' => 'Chemical solutions used for cleaning or dissolving substances.',
                'sub_category_id' => 1
            ],
            [
                'name' => 'Color Compound and Dispersions', 'description' => 'Pigments and dyes used in coloring various materials.',
                'sub_category_id' => 1
            ],
            [
                'name' => 'Films', 'description' => 'Thin, flexible material used for packaging or photography.',
                'sub_category_id' => 1
            ],
            [
                'name' => 'Paper Material and Products', 'description' => 'Various paper products like sheets, cards, and notebooks.',
                'sub_category_id' => 1
            ],
            [
                'name' => 'Batteries, Cells and Accessories', 'description' => 'Power sources and related accessories for electronic devices.',
                'sub_category_id' => 1
            ],
            [
                'name' => 'Measuring, Observing, and Testing Equipment', 'description' => 'Tools for measurement, observation, and scientific testing.',
                'sub_category_id' => 1
            ],
            [
                'name' => 'Cleaning Equipment and Supplies', 'description' => 'Tools and chemicals for cleaning and maintaining spaces.',
                'sub_category_id' => 1
            ],
            [
                'name' => 'Office Equipment, Accessories and Supplies', 'description' => 'Furniture and tools used in office environments.',
                'sub_category_id' => 1
            ],
            [
                'name' => 'Printer or Facsimile or Photocopy Supplies', 'description' => 'Items related to printing, faxing, or photocopying.',
                'sub_category_id' => 1
            ],
            [
                'name' => 'Consumer Electronics', 'description' => 'Electronic devices for personal use, like phones and laptops.',
                'sub_category_id' => 1
            ],
            [
                'name' => 'Arts and Crafts Equipment, Accessories and Supplies', 'description' => 'Materials and tools for creative activities like drawing or painting.',
                'sub_category_id' => 1
                ],
            [
                'name' => 'Common Office Supplies', 'description' => 'Everyday items used in office settings like pens, paper, and folders.',
                'sub_category_id' => 2
            ],
            [
                'name' => 'Common Janitorial Supplies', 'description' => 'Products used for cleaning and maintaining buildings or offices.',
                'sub_category_id' => 2
            ],
            [
                'name' => 'Printed Forms', 'description' => 'Pre-printed documents for use in offices or business operations.',
                'sub_category_id' => 2
            ],
            [
                'name' => 'Computer Supplies', 'description' => 'Peripherals and accessories related to computers and IT equipment.',
                'sub_category_id' => 2
            ],
            [
                'name' => 'Consumables', 'description' => 'Items that are used up and need replacement, like ink and paper.',
                'sub_category_id' => 2
            ],
            [
                'name' => 'Other New Items', 'description' => 'Miscellaneous new products not falling under specific categories.',
                'sub_category_id' => 2
            ],
        ];        
        foreach ($categories as $category) {
            CategoryModel::create($category);
        }
    }
}
