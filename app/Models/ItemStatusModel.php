<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemStatusModel extends Model
{
    use HasFactory;
    protected $table = 'item_statuses';
    protected $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];
    public function items()
    {
        return $this->hasMany(ItemModel::class);
    }
}
