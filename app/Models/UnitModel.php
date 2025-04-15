<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitModel extends Model
{
    use HasFactory;
    protected $table = 'units';
    protected $fillable = [
        'name',
        'control_number',
        'symbol',
        'description',
        'created_at',
        'updated_at'
    ];
    public function inventory()
    {
        return $this->hasMany(InventoryModel::class);
    }
}
