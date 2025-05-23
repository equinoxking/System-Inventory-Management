<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierModel extends Model
{
    use HasFactory;
    protected $table = 'suppliers';
    protected $fillable = [
        'control_number',
        'name',
        'created_at',
        'updated_at'
    ];
}
