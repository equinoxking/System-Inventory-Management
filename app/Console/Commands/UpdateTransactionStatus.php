<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TransactionModel;
use Carbon\Carbon;

class UpdateTransactionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
     protected $signature = 'transaction:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically update the transaction status based on the time';

    /**
     * Execute the console command.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $currentTime = Carbon::now('Asia/Manila')->format('H:i');

        $this->info("Current time: $currentTime");


        $transactions = TransactionModel::where('released_time', '<=', $currentTime)  
            ->where('status_id', '2')  
            ->get();

        if ($transactions->isEmpty()) {
            $this->info("No transactions to update.");
        }

        foreach ($transactions as $transaction) {

            $this->info("Updating transaction ID {$transaction->id}.");

            $transaction->remark = 'Completed';
            $transaction->save();

 
            $this->info("Transaction ID {$transaction->id} updated to 'Completed'.");
        }
    }

}
