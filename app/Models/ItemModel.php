<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemModel extends Model
{
    use HasFactory;
    protected $table = 'items';
    protected $fillable = [
        'name',
        'symbol',
        'created_at',
        'updated_at'
    ];
}
