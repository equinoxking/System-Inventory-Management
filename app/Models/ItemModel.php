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
        return $this->belongsTo(CategoryModel::class, 'category_id');
    }
    public function inventory()
    {
        return $this->hasOne(InventoryModel::class, 'item_id');
    }
    public function status()
    {
        return $this->belongsTo(ItemStatusModel::class);
    }
    public function receives()
    {
        return $this->hasMany(ReceiveModel::class, 'item_id');
    }
    public function transacts()
    {
        return $this->hasMany(TransactionModel::class, 'item_id');
    }
    public function receivesUpToMonth()
    {
        return $this->hasMany(ReceiveModel::class, 'item_id', 'id');
    }
    public function receivesInSelectedMonth()
    {
        return $this->hasMany(ReceiveModel::class , 'item_id' ,'id') ;
    }
    public function requestedUpToMonth()
    {
        return $this->hasMany(TransactionDetailModel::class , 'item_id' , 'id');
    }

    // Define the 'receivesInSelectedMonth' method
    public function requestedInSelectedMonth()
    {
        return $this->hasMany(TransactionDetailModel::class , 'item_id') // Replace `Receive::class` with the actual model name if it's different
                    ->where('request_month', '=', request()->month)
                    ->where('request_year', '=', request()->year);
    }
    public function details()
    {
        return $this->hasMany(TransactionDetailModel::class, 'item_id');
    }
}