<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitModel;
use Illuminate\Support\Carbon;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;
use Illuminate\Support\Facades\DB;
class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::unprepared('SET IDENTITY_INSERT units ON');

        $units = [
            ['id' => 35, 'name' => 'can',      'description' => 'Metal container for liquids or food',           'symbol' => 'cn'],
            ['id' => 36, 'name' => 'bottle',   'description' => 'Container with narrow neck for liquids',        'symbol' => 'btl'],
            ['id' => 37, 'name' => 'box',      'description' => 'Container for storage or packaging',            'symbol' => 'bx'],
            ['id' => 38, 'name' => 'piece',    'description' => 'Single item or unit',                           'symbol' => 'pc'],
            ['id' => 39, 'name' => 'pad',      'description' => 'Stack of sheets fastened together',             'symbol' => 'pd'],
            ['id' => 40, 'name' => 'ream',     'description' => 'Bundle of 500 sheets of paper',                 'symbol' => 'rm'],
            ['id' => 41, 'name' => 'pack',     'description' => 'Collection of items wrapped together',          'symbol' => 'pk'],
            ['id' => 42, 'name' => 'book',     'description' => 'Bound set of written or printed pages',         'symbol' => 'bk'],
            ['id' => 43, 'name' => 'jar',      'description' => 'Glass or plastic container with a lid',         'symbol' => 'jr'],
            ['id' => 44, 'name' => 'roll',     'description' => 'Item wound into a cylindrical shape',           'symbol' => 'rl'],
            ['id' => 45, 'name' => 'bar',      'description' => 'Solid blocks, typically of food or metal',      'symbol' => 'br'],
            ['id' => 46, 'name' => 'gallon',   'description' => 'Unit of liquid capacity (3.785 L in US)',       'symbol' => 'gal'],
            ['id' => 47, 'name' => 'unit',     'description' => 'Generic single item or quantity',               'symbol' => 'u'],
            ['id' => 48, 'name' => 'bundle',   'description' => 'Group of items fastened together',              'symbol' => 'bdl'],
            ['id' => 49, 'name' => 'set',      'description' => 'Collection of related items',                   'symbol' => 'set'],
            ['id' => 50, 'name' => 'cart',     'description' => 'Measured by quantity or volume (for ink/toner)','symbol' => 'crt'],
            ['id' => 52, 'name' => 'booklet',  'description' => 'Small book with few pages',                     'symbol' => 'bkt'],
            ['id' => 55, 'name' => 'pair',     'description' => 'Always two as one',                             'symbol' => 'bkt'],
        ];
    
        foreach ($units as $unit) {
            DB::table('units')->insert([
                'id' => $unit['id'],
                'name' => $unit['name'],
                'description' => $unit['description'],
                'symbol' => $unit['symbol'],
                'control_number' => $this->generateControlNumber(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    
        DB::unprepared('SET IDENTITY_INSERT units OFF');
    }
    private function generateControlNumber() {
        $currentYearAndMonth = Carbon::now()->format('Y-m');
        $controlNumber = UnitModel::whereYear('created_at', Carbon::now()->year)
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
