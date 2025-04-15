<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionModel extends Model
{
    use HasFactory;
    protected $table = 'transactions';
    protected $fillable = [
        'user_id',
        'admin_id',
        'item_id',
        'status_id',
        'transaction_number',
        'released_by',
        'released_time',
        'approved_time',
        'approved_date',
        'reason',
        'remark',
        'created_at',
        'updated_at'
    ];
    public function status()
    {
        return $this->belongsTo(TransactionStatusModel::class, 'status_id');
    }
    public function item()
    {
        return $this->belongsTo(ItemModel::class, 'item_id');
    }
    public function client()
    {
        return $this->belongsTo(ClientModel::class, 'user_id' ,'id');
    }
    public function transactionDetail()
    {
        return $this->hasOne(TransactionDetailModel::class, 'transaction_id');
    }
    public function adminBy()
    {
        return $this->belongsTo(AdminModel::class, 'released_by');
    }
    public function admin()
    {
        return $this->belongsTo(AdminModel::class, 'admin_id');
    }
}
