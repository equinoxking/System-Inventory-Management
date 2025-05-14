<?php

namespace Database\Seeders;

use App\Models\SupplierModel;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            ['name' => 'Luckybert Drugmart'],
            ['name' => 'Gabenmik'],
            ['name' => 'PCInet'],
            ['name' => 'PC Enterprise'],
            ['name' => 'Jhamas'],
            ['name' => 'AES Techno World'],
            ['name' => 'Better Homes'],
            ['name' => 'Vizcaya Balita'],
        ];
        foreach ($suppliers as $supplier) {
            SupplierModel::create([
                'control_number' => $this->generateControlNumber(),
                'name' => $supplier['name']
            ]);
        }
    }
    private function generateControlNumber() {
        $currentYearAndMonth = Carbon::now()->format('Y-m');
        $controlNumber = SupplierModel::whereYear('created_at', Carbon::now()->year)
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
