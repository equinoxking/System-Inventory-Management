<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TransactionStatusModel;
class TransactionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Pending'],
            ['name' => 'Accepted'],
            ['name' => 'Rejected'],
        ];

        foreach ($statuses as $status) {
            TransactionStatusModel::create($status);
        }
    }
}
