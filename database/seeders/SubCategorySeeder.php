<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubCategoryModel;
class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sub_categories = [
            ['name' => 'Available at procurement services stores'],
            ['name' => 'Other items not available at ps but regularly purchased from other sources'],
        ];

        foreach ($sub_categories as $sub_category) {
            SubCategoryModel::create($sub_category);
        }
    }
}
