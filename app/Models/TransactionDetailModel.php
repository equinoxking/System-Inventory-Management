<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetailModel extends Model
{
    use HasFactory;
    protected $table = 'transaction_details';
    protected $fillable = [
        'id',
        'transaction_id',
        'request_quantity',
        'request_item',
        'request_day',
        'request_month',
        'request_year',
        'remark',
        'updated_at'
    ];
    public function transacts()
    {
        return $this->belongsTo(TransactionModel::class);
    }
}
