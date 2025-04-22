<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ItemModel;
use App\Models\InventoryModel;
use App\Models\ReceiveModel;
use Illuminate\Support\Carbon;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'Insecticide, aerosol type, net content:600 ml min',
                'category_id' => 1,
                'status_id' => 1,
                'unit_id' => 35,
                'quantity' => 20,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 21,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'ALCOHOL, ethyl, 68%070%, scented, 500ml (05ml)',
                'category_id' => 2,
                'status_id' => 1,
                'unit_id' => 36,
                'quantity' => 30,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 13,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'STAMP PAD INK, purple or violet',
                'category_id' => 3,
                'status_id' => 1,
                'unit_id' => 36,
                'quantity' => 30,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 16,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'TRODAT INK',
                'category_id' => 3,
                'status_id' => 1,
                'unit_id' => 36,
                'quantity' => 30,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 01,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'CARBON FILM, PE, black, size 210mm x 297mm',
                'category_id' => 4,
                'status_id' => 1,
                'unit_id' => 37,
                'quantity' => 10,
                'min_quantity' => 3,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 02,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'CARBON FILM, PE, black, size 216mm x 330mm',
                'category_id' => 4,
                'status_id' => 1,
                'unit_id' => 37,
                'quantity' => 10,
                'min_quantity' => 3,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 03,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'CARTOLINA, assorted colors',
                'category_id' => 5,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 120,
                'min_quantity' => 30,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 04,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'NOTE PAD, stick on, 50mm x 76mm (2" x 3") min',
                'category_id' => 5,
                'status_id' => 1,
                'unit_id' => 39,
                'quantity' => 25,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 05,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'NOTE PAD, stick on, 76mm x 100mm (3" x 4") min',
                'category_id' => 5,
                'status_id' => 1,
                'unit_id' => 39,
                'quantity' => 15,
                'min_quantity' => 5,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 03,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'NOTE PAD, stick on, 76mm x 76mm (3" x 3") min',
                'category_id' => 5,
                'status_id' => 1,
                'unit_id' => 39,
                'quantity' => 50,
                'min_quantity' => 15,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 03,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'NOTE PAD, stick on, 76mm x 120mm (3" x 5") min',
                'category_id' => 5,
                'status_id' => 1,
                'unit_id' => 39,
                'quantity' => 30,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 07,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'NOTEBOOK, STENOGRAPHER, spiral, 40 leaves',
                'category_id' => 5,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 400,
                'min_quantity' => 100,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 06,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'PAPER, MULTICOPY,size: 216mm x 330mm (Long)',
                'category_id' => 5,
                'status_id' => 1,
                'unit_id' => 40,
                'quantity' => 40,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 06,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'PAPER, Multi0Purpose (COPY) (A4)',
                'category_id' => 5,
                'status_id' => 1,
                'unit_id' => 40,
                'quantity' => 113,
                'min_quantity' => 30,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 07,
                'received_month' => 04,
                'received_year' => 2025
            ],
            [
                'name' => 'PAPER, PAD, ruled, size: 216mm x 330mm (± 2mm)',
                'category_id' => 5,
                'status_id' => 1,
                'unit_id' => 39,
                'quantity' => 20,
                'min_quantity' => 5,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 10,
                'received_month' => 04,
                'received_year' => 2025
            ],
            [
                'name' => 'PAPER, PARCHMENT, size: 210 x 297mm, multi0purpose',
                'category_id' => 5,
                'status_id' => 1,
                'unit_id' => 41,
                'quantity' => 70,
                'min_quantity' => 20,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 11,
                'received_month' => 04,
                'received_year' => 2025
            ],
            [
                'name' => 'RECORD BOOK, 300 PAGES, size: 214mm x 278mm min',
                'category_id' => 5,
                'status_id' => 1,
                'unit_id' => 42,
                'quantity' => 15,
                'min_quantity' => 5,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 12,
                'received_month' => 04,
                'received_year' => 2025
            ],
            [
                'name' => 'RECORD BOOK, 500 PAGES, size: 214mm x 278mm min',
                'category_id' => 5,
                'status_id' => 1,
                'unit_id' => 42,
                'quantity' => 25,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 13,
                'received_month' => 04,
                'received_year' => 2025
            ],
            [
                'name' => 'TOILET TISSUE PAPER 20plys sheets, 150 pulls',
                'category_id' => 5,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 41,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 14,
                'received_month' => 04,
                'received_year' => 2025
            ],
            [
                'name' => 'BATTERY, dry cell, AA, 2 pieces per blister pack',
                'category_id' => 6,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 30,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 15,
                'received_month' => 04,
                'received_year' => 2025
            ],
            [
                'name' => 'BATTERY, dry cell, AAA, 2 pieces per blister pack',
                'category_id' => 6,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 0,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 16,
                'received_month' => 04,
                'received_year' => 2025
            ],
            [
                'name' => 'GLUE, all purpose, gross weight: 200 grams min',
                'category_id' => 20,
                'status_id' => 1,
                'unit_id' => 43,
                'quantity' => 13,
                'min_quantity' => 5,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 17,
                'received_month' => 04,
                'received_year' => 2025
            ],

            [
                'name' => 'STAPLE WIRE, for heavy duty staplers, (23/13)',
                'category_id' => 20,
                'status_id' => 1,
                'unit_id' => 37,
                'quantity' => 41,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 18,
                'received_month' => 04,
                'received_year' => 2025
            ],
            [
                'name' => 'STAPLE WIRE, STANDARD, (26/6)',
                'category_id' => 20,
                'status_id' => 1,
                'unit_id' => 37,
                'quantity' => 33,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 19,
                'received_month' => 04,
                'received_year' => 2025 //eto
            ],
            // [
            //     'name' => ' ',
            //     'category_id' => 4,
            //     'status_id' => 1,
            //     'unit_id' => 37,
            //     'quantity' => 10,
            //     'min_quantity' => 3,
            //     'delivery_type' => "Receipt for Stock",
            //     'received_day' => 03,
            //     'received_month' => 04,
            //     'received_year' => 2025
            // ],
            // [
            //     'name' => ' ',
            //     'category_id' => 4,
            //     'status_id' => 1,
            //     'unit_id' => 37,
            //     'quantity' => 10,
            //     'min_quantity' => 3,
            //     'delivery_type' => "Receipt for Stock",
            //     'received_day' => 03,
            //     'received_month' => 04,
            //     'received_year' => 2025
            // ],
            // [
            //     'name' => ' ',
            //     'category_id' => 4,
            //     'status_id' => 1,
            //     'unit_id' => 37,
            //     'quantity' => 10,
            //     'min_quantity' => 3,
            //     'delivery_type' => "Receipt for Stock",
            //     'received_day' => 03,
            //     'received_month' => 04,
            //     'received_year' => 2025
            // ],
            // [
            //     'name' => ' ',
            //     'category_id' => 4,
            //     'status_id' => 1,
            //     'unit_id' => 37,
            //     'quantity' => 10,
            //     'min_quantity' => 3,
            //     'delivery_type' => "Receipt for Stock",
            //     'received_day' => 03,
            //     'received_month' => 04,
            //     'received_year' => 2025
            // ],
            // [
            //     'name' => ' ',
            //     'category_id' => 4,
            //     'status_id' => 1,
            //     'unit_id' => 37,
            //     'quantity' => 10,
            //     'min_quantity' => 3,
            //     'delivery_type' => "Receipt for Stock",
            //     'received_day' => 03,
            //     'received_month' => 04,
            //     'received_year' => 2025
            // ],
            // [
            //     'name' => ' ',
            //     'category_id' => 4,
            //     'status_id' => 1,
            //     'unit_id' => 37,
            //     'quantity' => 10,
            //     'min_quantity' => 3,
            //     'delivery_type' => "Receipt for Stock",
            //     'received_day' => 03,
            //     'received_month' => 04,
            //     'received_year' => 2025
            // ],
            // [
            //     'name' => ' ',
            //     'category_id' => 4,
            //     'status_id' => 1,
            //     'unit_id' => 37,
            //     'quantity' => 10,
            //     'min_quantity' => 3,
            //     'delivery_type' => "Receipt for Stock",
            //     'received_day' => 03,
            //     'received_month' => 04,
            //     'received_year' => 2025
            // ],
            // [
            //     'name' => ' ',
            //     'category_id' => 4,
            //     'status_id' => 1,
            //     'unit_id' => 37,
            //     'quantity' => 10,
            //     'min_quantity' => 3,
            //     'delivery_type' => "Receipt for Stock",
            //     'received_day' => 03,
            //     'received_month' => 04,
            //     'received_year' => 2025
            // ],
            // [
            //     'name' => ' ',
            //     'category_id' => 4,
            //     'status_id' => 1,
            //     'unit_id' => 37,
            //     'quantity' => 10,
            //     'min_quantity' => 3,
            //     'delivery_type' => "Receipt for Stock",
            //     'received_day' => 03,
            //     'received_month' => 04,
            //     'received_year' => 2025
            // ],
            // [
            //     'name' => ' ',
            //     'category_id' => 4,
            //     'status_id' => 1,
            //     'unit_id' => 37,
            //     'quantity' => 10,
            //     'min_quantity' => 3,
            //     'delivery_type' => "Receipt for Stock",
            //     'received_day' => 03,
            //     'received_month' => 04,
            //     'received_year' => 2025
            // ],
            // [
            //     'name' => ' ',
            //     'category_id' => 4,
            //     'status_id' => 1,
            //     'unit_id' => 37,
            //     'quantity' => 10,
            //     'min_quantity' => 3,
            //     'delivery_type' => "Receipt for Stock",
            //     'received_day' => 03,
            //     'received_month' => 04,
            //     'received_year' => 2025
            // ],
            
            
            
        ];
        foreach ($items as $itemData) {
            $controlNumber = $this->generateControlNumber();

            $item = ItemModel::create([
                'name' => $itemData['name'],
                'controlNumber' => $controlNumber,
                'category_id' => $itemData['category_id'],
                'status_id' => $itemData['status_id'],
            ]);

            InventoryModel::create([
                'item_id' => $item->id,
                'unit_id' => $itemData['unit_id'],
                'quantity' => $itemData['quantity'],
                'min_quantity' => $itemData['min_quantity'],
            ]);

            ReceiveModel::create([
                'item_id' => $item->id,
                'delivery_type' => $itemData['delivery_type'],
                'received_quantity' => $itemData['quantity'],
                'received_day' => $itemData['received_day'],
                'received_month' => $itemData['received_month'],
                'received_year' => $itemData['received_year'],
            ]);
        }
    }
    private function generateControlNumber()
    {
        $currentYearAndMonth = Carbon::now()->format('Y-m');

        $lastControlNumber = ItemModel::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->orderBy('controlNumber', 'desc')
            ->pluck('controlNumber')
            ->first();

        if (!$lastControlNumber) {
            return $currentYearAndMonth . '-00001';
        }

        $numberPart = intval(substr($lastControlNumber, -5)) + 1;
        $paddedNumber = str_pad($numberPart, 5, '0', STR_PAD_LEFT);

        return $currentYearAndMonth . '-' . $paddedNumber;
    }
}
