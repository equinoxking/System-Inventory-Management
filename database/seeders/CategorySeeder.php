<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoryModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::unprepared('SET IDENTITY_INSERT categories ON');
        $categories = [
            [
                'id' => 1, 'name' => 'Pesticides or Pest Repellents', 'description' => 'Substances to control or repel pests and insects.',
                'sub_category_id' => 1
            ],
            [
                'id' => 2, 'name' => 'Solvents', 'description' => 'Chemical solutions used for cleaning or dissolving substances.',
                'sub_category_id' => 1
            ],
            [
                'id' => 3, 'name' => 'Color Compound and Dispersions', 'description' => 'Pigments and dyes used in coloring various materials.',
                'sub_category_id' => 1
            ],
            [
                'id' => 4, 'name' => 'Films', 'description' => 'Thin, flexible material used for packaging or photography.',
                'sub_category_id' => 1
            ],
            [
                'id' => 5,'name' => 'Paper Material and Products', 'description' => 'Various paper products like sheets, cards, and notebooks.',
                'sub_category_id' => 1
            ],
            [
                'id' => 6, 'name' => 'Batteries, Cells and Accessories', 'description' => 'Power sources and related accessories for electronic devices.',
                'sub_category_id' => 1
            ],
            [
                'id' => 7,'name' => 'Measuring, Observing, and Testing Equipment', 'description' => 'Tools for measurement, observation, and scientific testing.',
                'sub_category_id' => 1
            ],
            [
                'id' => 8, 'name' => 'Cleaning Equipment and Supplies', 'description' => 'Tools and chemicals for cleaning and maintaining spaces.',
                'sub_category_id' => 1
            ],
            [
                'id' => 9,'name' => 'Office Equipment, Accessories and Supplies', 'description' => 'Furniture and tools used in office environments.',
                'sub_category_id' => 1
            ],
            [
                'id' => 10, 'name' => 'Printer or Facsimile or Photocopy Supplies', 'description' => 'Items related to printing, faxing, or photocopying.',
                'sub_category_id' => 1
            ],
            [
                'id' => 11, 'name' => 'Consumer Electronics', 'description' => 'Electronic devices for personal use, like phones and laptops.',
                'sub_category_id' => 1
            ],
            [
                'id' => 12,'name' => 'Arts and Crafts Equipment, Accessories and Supplies', 'description' => 'Materials and tools for creative activities like drawing or painting.',
                'sub_category_id' => 1
                ],
            [
                'id' => 13, 'name' => 'Common Office Supplies', 'description' => 'Everyday items used in office settings like pens, paper, and folders.',
                'sub_category_id' => 2
            ],
            [
                'id' => 14, 'name' => 'Common Janitorial Supplies', 'description' => 'Products used for cleaning and maintaining buildings or offices.',
                'sub_category_id' => 2
            ],
            [
                'id' => 15, 'name' => 'Printed Forms', 'description' => 'Pre-printed documents for use in offices or business operations.',
                'sub_category_id' => 2
            ],
            [
                'id' => 16, 'name' => 'Computer Supplies', 'description' => 'Peripherals and accessories related to computers and IT equipment.',
                'sub_category_id' => 2
            ],
            [
                'id' => 17,'name' => 'Consumables', 'description' => 'Items that are used up and need replacement, like ink and paper.',
                'sub_category_id' => 2
            ],
            [
                'id' => 18,'name' => 'Other New Items', 'description' => 'Miscellaneous new products not falling under specific categories.',
                'sub_category_id' => 2
            ],
            [
                'id' => 20, 'name' => 'Manufacturing Components and Supplies', 'description' => 'Items used for manufacturing.',
                'sub_category_id' => 1
            ],
        ];
        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'id' => $category['id'],
                'sub_category_id' => $category['sub_category_id'],
                'name' => $category['name'],
                'description' => $category['description'],
                'control_number' => $this->generateControlNumber(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // DB::unprepared('SET IDENTITY_INSERT categories OFF');
    }
    private function generateControlNumber() {
        $currentYearAndMonth = Carbon::now()->format('Y-m');
        $controlNumber = CategoryModel::whereYear('created_at', Carbon::now()->year)
                                ->whereMonth('created_at', Carbon::now()->month)
                                ->orderBy('control_number', 'desc')
                                ->pluck('control_number')
                                ->first();

        if (!$controlNumber) {
            return $currentYearAndMonth . '-00001';
        }

        $numberPart = intval(substr($controlNumber, -5)) + 1;
        $paddedNumber = str_pad($numberPart, 5, '0', STR_PAD_LEFT);

        return $currentYearAndMonth . '-' . $paddedNumber;
    }
}
