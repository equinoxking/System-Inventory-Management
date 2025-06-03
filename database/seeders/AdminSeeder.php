<?php

namespace Database\Seeders;

use App\Models\AdminModel;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'full_name' => 'Christine Joy C. Bartolome',
                'role_id' => '1',
                'position' => 'Officer In Charge',
                'client_id' => 1,
                'status' => 'Active',
            ],
        ];

        foreach ($admins as $admin) {
            AdminModel::insert([
                'full_name' => $admin['full_name'],
                'role_id' => $admin['role_id'],
                'position' => $admin['position'],
                'client_id' => $admin['client_id'],
                'status' => $admin['status'],
                'control_number' => '20-095'
            ]);
        }

    }
    private function generateControlNumber() {
        $currentYearAndMonth = Carbon::now()->format('Y-m');
        $controlNumber = AdminModel::whereYear('created_at', Carbon::now()->year)
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
