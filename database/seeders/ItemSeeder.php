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
                'received_month' => 03,
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
                'received_month' => 03,
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
                'received_month' => 03,
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
                'received_month' => 03,
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
                'received_month' => 03,
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
                'received_month' => 03,
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
                'received_month' => 03,
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
                'received_month' => 03,
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
                'received_month' => 03,
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
                'received_month' => 03,
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
                'received_month' => 03,
                'received_year' => 2025 
            ],
            [
                'name' => 'TAPE ADHESIVE',
                'category_id' => 20,
                'status_id' => 1,
                'unit_id' => 44,
                'quantity' => 20,
                'min_quantity' => 5,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 20,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'TAPE, ELECTRICAL, 18mm x 16M min',
                'category_id' => 20,
                'status_id' => 1,
                'unit_id' => 44,
                'quantity' => 3,
                'min_quantity' => 5,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 21,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'TAPE, MASKING, width: 24mm (±1mm)',
                'category_id' => 20,
                'status_id' => 1,
                'unit_id' => 44,
                'quantity' => 48,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 22,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'TAPE, MASKING, width: 48mm (±1mm)',
                'category_id' => 20,
                'status_id' => 1,
                'unit_id' => 44,
                'quantity' => 43,
                'min_quantity' =>10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 23,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'TAPE, PACKAGING, width: 48mm (±1mm)',
                'category_id' => 20,
                'status_id' => 1,
                'unit_id' => 44,
                'quantity' => 19,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 24,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'TAPE, TRANSPARENT, width: 24mm (±1mm)',
                'category_id' => 20,
                'status_id' => 1,
                'unit_id' => 44,
                'quantity' => 64,
                'min_quantity' => 15,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 25,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'TAPE, TRANSPARENT, width: 48mm (±1mm)',
                'category_id' => 20,
                'status_id' => 1,
                'unit_id' => 44,
                'quantity' => 45,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 26,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'TWINE, plastic, one (1) kilo per roll',
                'category_id' => 20,
                'status_id' => 1,
                'unit_id' => 44,
                'quantity' => 2,
                'min_quantity' => 2,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 27,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'RULER, plastic, 450mm (18"), width: 38mm min',
                'category_id' => 7,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 14,
                'min_quantity' => 5,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 28,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'AIR FRESHENER, aerosol, 280ml/150g min',
                'category_id' => 8,
                'status_id' => 1,
                'unit_id' => 35,
                'quantity' => 2,
                'min_quantity' => 2,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 29,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'BROOM, soft (tambo)',
                'category_id' => 8,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 3,
                'min_quantity' => 2,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 30,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'BROOM, STICK (TING0TING), usable length: 760mm min',
                'category_id' => 8,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 5,
                'min_quantity' => 2,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 01,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'CLEANER,TOILET BOWL AND URINAL, 500ml per/can',
                'category_id' => 8,
                'status_id' => 1,
                'unit_id' => 36,
                'quantity' => 24,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 02,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'CLEANSER, SCOURING POWDER, 350g min./can',
                'category_id' => 8,
                'status_id' => 1,
                'unit_id' => 35,
                'quantity' => 24,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 03,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'DETERGENT BAR, 140 grams as packed',
                'category_id' => 8,
                'status_id' => 1,
                'unit_id' => 45,
                'quantity' => 28,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 04,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'DETERGENT POWDER, all purpose, 1kg',
                'category_id' => 8,
                'status_id' => 1,
                'unit_id' => 41,
                'quantity' => 5,
                'min_quantity' => 3,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 05,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'DISINFECTANT SPRAY, aerosol type, 4000550 grams',
                'category_id' => 8,
                'status_id' => 1,
                'unit_id' => 35,
                'quantity' => 4,
                'min_quantity' => 2,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 06,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'DISINFECTANT, Bleach (zonrox)',
                'category_id' => 8,
                'status_id' => 1,
                'unit_id' => 46,
                'quantity' => 16,
                'min_quantity' => 5,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 07,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'DUST PAN, non0rigid plastic, w/ detachable handle',
                'category_id' => 8,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 6,
                'min_quantity' => 3,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 8,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'FLOOR WAX, PASTE, RED',
                'category_id' => 8,
                'status_id' => 1,
                'unit_id' => 35,
                'quantity' => 1,
                'min_quantity' => 2,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 9,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'FURNITURE CLEANER, aerosol type, 300ml min per can(wood polish)',
                'category_id' => 8,
                'status_id' => 1,
                'unit_id' => 35,
                'quantity' => 8,
                'min_quantity' => 5,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 10,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'HOE',
                'category_id' => 8,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 4,
                'min_quantity' => 2,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 11,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'MOP BUCKET, heavy duty, hard plastic',
                'category_id' => 8,
                'status_id' => 1,
                'unit_id' => 47,
                'quantity' => 0,
                'min_quantity' => 2,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 12,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'MOPHANDLE, heavy duty, aluminum, screw type',
                'category_id' => 8,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 0,
                'min_quantity' => 2,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 13,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'MOPHEAD, made of rayon, weight: 400 grams min',
                'category_id' => 8,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 12,
                'min_quantity' => 5,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 14,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'RAGS, all cotton, 32 pieces per kilogram min',
                'category_id' => 8,
                'status_id' => 1,
                'unit_id' => 48,
                'quantity' => 15,
                'min_quantity' => 5,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 15,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'RAKE',
                'category_id' => 8,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 2,
                'min_quantity' => 1,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 16,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'SCOURING PAD, made of synthetic nylon, 140 x 220mm',
                'category_id' => 8,
                'status_id' => 1,
                'unit_id' => 41,
                'quantity' => 54,
                'min_quantity' => 15,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 17,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'ARCHFILE FOLDER, side clip, 2 14x11 legal',
                'category_id' => 9,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 0,
                'min_quantity' => 5,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 18,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'CLIP, BACKFOLD, all metal, clamping: 19mm (01mm) ½ inch',
                'category_id' => 9,
                'status_id' => 1,
                'unit_id' => 37,
                'quantity' => 86,
                'min_quantity' => 25,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 19,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'CLIP, BACKFOLD, all metal, clamping: 25mm (01mm) 1 inch',
                'category_id' => 9,
                'status_id' => 1,
                'unit_id' => 37,
                'quantity' => 36,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 20,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'CLIP, BACKFOLD, all metal, clamping: 32mm (01mm) 1 ½ inch',
                'category_id' => 9,
                'status_id' => 1,
                'unit_id' => 37,
                'quantity' => 34,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 21,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'CLIP, BACKFOLD, all metal, clamping: 50mm (01mm) 2inch',
                'category_id' => 9,
                'status_id' => 1,
                'unit_id' => 37,
                'quantity' => 43,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 22,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'CORRECTION TAPE, film base type, UL 6m min',
                'category_id' => 9,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 39,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 23,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'CUTTER BLADE, for heavy duty cutter',
                'category_id' => 9,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 8,
                'min_quantity' => 5,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 24,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'CUTTER KNIFE, for general purpose',
                'category_id' => 9,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 26,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 25,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'DATA FILE BOX, made of chipboard, with closed ends',
                'category_id' => 9,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 0,
                'min_quantity' => 5,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 26,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'DATA FOLDER, made of chipboard, taglia lock',
                'category_id' => 9,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 0,
                'min_quantity' => 5,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 27,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'ENVELOPE, DOCUMENTARY, for A4 size document (brown envelope)',
                'category_id' => 9,
                'status_id' => 1,
                'unit_id' => 41,
                'quantity' => 13,
                'min_quantity' => 5,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 28,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'ENVELOPE, DOCUMENTARY, for legal size document(brown envelope)',
                'category_id' => 9,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 1595,
                'min_quantity' => 500,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 29,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'ENVELOPE, EXPANDING, KRAFTBOARD,for legal size doc(with garter)',
                'category_id' => 9,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 6,
                'min_quantity' => 5,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 30,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'ENVELOPE, EXPANDING, PLASTIC, 0.50mm thickness min',
                'category_id' => 9,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 41,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 1,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'ENVELOPE, MAILING,white, 80gsm',
                'category_id' => 9,
                'status_id' => 1,
                'unit_id' => 41,
                'quantity' => 51,
                'min_quantity' => 10,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 2,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'ERASER, FELT, for blackboard/whiteboard',
                'category_id' => 9,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 3,
                'min_quantity' => 5,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 3,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'FASTENER, METAL, 70mm between prongs',
                'category_id' => 9,
                'status_id' => 1,
                'unit_id' => 37,
                'quantity' => 19,
                'min_quantity' => 5,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 4,
                'received_month' => 03,
                'received_year' => 2025
            ],
            [
                'name' => 'FILE ORGANIZER, expanding, plastic, 12 pockets',
                'category_id' => 9,
                'status_id' => 1,
                'unit_id' => 38,
                'quantity' => 9,
                'min_quantity' => 5,
                'delivery_type' => "Receipt for Stock",
                'received_day' => 5,
                'received_month' => 03,
                'received_year' => 2025
            ],
            // [
            //     'name' => '',
            //     'category_id' => ,
            //     'status_id' => 1,
            //     'unit_id' => ,
            //     'quantity' => ,
            //     'min_quantity' => ,
            //     'delivery_type' => "Receipt for Stock",
            //     'received_day' => ,
            //     'received_month' => 03,
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
