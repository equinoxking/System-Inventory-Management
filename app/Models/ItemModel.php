<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemModel extends Model
{
    use HasFactory;
    protected $table = 'items';
    protected $fillable = [
        'id',
        'category_id',
        'unit_id',
        'status_id',
        'controlNumber',
        'name',
        'status',
        'created_at',
        'updated_at'
    ];
    public function category()
    {
        return $this->belongsTo(CategoryModel::class);
    }
    public function inventory()
    {
        return $this->hasOne(InventoryModel::class, 'item_id');
    }
    public function status()
    {
        return $this->belongsTo(ItemStatusModel::class);
    }
}