<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryModel extends Model
{
    use HasFactory;
    protected $table = 'inventories';
    protected $fillable = [
        'item_id',
        'unit_id',
        'quantity',
        'min_quantity',
        'unit',
        'created_at',
        'updated_at'
    ];
    public function item(){
        return $this->belongsTo(ItemModel::class, 'item_id', 'id');
    }
    public function unit(){
        return $this->belongsTo(UnitModel::class, 'unit_id');
    }
}
