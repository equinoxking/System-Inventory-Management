<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitModel;
use Illuminate\Support\Carbon;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'can',      'description' => 'Metal container for liquids or food',           'symbol' => 'cn'],
            ['name' => 'bottle',   'description' => 'Container with narrow neck for liquids',        'symbol' => 'btl'],
            ['name' => 'box',      'description' => 'Container for storage or packaging',            'symbol' => 'bx'],
            ['name' => 'piece',    'description' => 'Single item or unit',                           'symbol' => 'pc'],
            ['name' => 'pad',      'description' => 'Stack of sheets fastened together',             'symbol' => 'pd'],
            ['name' => 'ream',     'description' => 'Bundle of 500 sheets of paper',                 'symbol' => 'rm'],
            ['name' => 'packs',    'description' => 'Collection of items wrapped together',          'symbol' => 'pk'],
            ['name' => 'books',    'description' => 'Bound set of written or printed pages',         'symbol' => 'bk'],
            ['name' => 'jar',      'description' => 'Glass or plastic container with a lid',         'symbol' => 'jr'],
            ['name' => 'roll',     'description' => 'Item wound into a cylindrical shape',           'symbol' => 'rl'],
            ['name' => 'bars',     'description' => 'Solid blocks, typically of food or metal',      'symbol' => 'br'],
            ['name' => 'gallon',   'description' => 'Unit of liquid capacity (3.785 L in US)',       'symbol' => 'gal'],
            ['name' => 'unit',     'description' => 'Generic single item or quantity',               'symbol' => 'u'],
            ['name' => 'bundle',   'description' => 'Group of items fastened together',              'symbol' => 'bdl'],
            ['name' => 'set',      'description' => 'Collection of related items',                   'symbol' => 'set'],
            ['name' => 'cart',     'description' => 'Wheeled container for transporting items',      'symbol' => 'crt'],
            ['name' => 'booklet',  'description' => 'Small book with few pages',                     'symbol' => 'bkt'],
        ];        
        foreach ($units as $unit) {
            $unit['control_number'] = $this->generateControlNumber();
            $unit['created_at'] = now();
            $unit['updated_at'] = now();
            UnitModel::create($unit);
        }
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
