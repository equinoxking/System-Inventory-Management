<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitModel;
class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'can'],
            ['name' => 'bottle'],
            ['name' => 'box'],
            ['name' => 'piece'],
            ['name' => 'pad'],
            ['name' => 'ream'],
            ['name' => 'packs'],
            ['name' => 'books'],
            ['name' => 'jar'],
            ['name' => 'roll'],
            ['name' => 'bars'],
            ['name' => 'gallon'],
            ['name' => 'unit'],
            ['name' => 'bundle'],
            ['name' => 'set'],
            ['name' => 'cart'],
            ['name' => 'booklet'],
        ];

        foreach ($units as $unit) {
            UnitModel::create($unit);
        }
    }
}
