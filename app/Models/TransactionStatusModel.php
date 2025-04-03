<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionStatusModel extends Model
{
    use HasFactory;
    protected $table = 'transaction_statuses';
    protected $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];
    public function transactions()
    {
        return $this->hasMany(TransactionModel::class);
    }

}