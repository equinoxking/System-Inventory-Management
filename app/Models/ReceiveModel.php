<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiveModel extends Model
{
    use HasFactory;
    protected $table = 'receivables';
    protected $fillable = [
        'id',
        'item_id',
        'received_quantity',
        'received_day',
        'received_month',
        'received_year',
        'created_at',
        'updated_at'
    ];
    public function item()
    {
        return $this->belongsTo(ItemModel::class, 'item_id');
    }
}
