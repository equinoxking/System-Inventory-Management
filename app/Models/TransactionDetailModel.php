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
        'item_id',
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
        return $this->belongsTo(TransactionModel::class, 'transaction_id');
    }
    public function item()
    {
        return $this->belongsTo(ItemModel::class, 'item_id');
    }
}
