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
        'received_date',
        'created_at',
        'updated_at'
    ];
    public function items()
    {
        return $this->hasMany(ItemModel::class);
    }
}
