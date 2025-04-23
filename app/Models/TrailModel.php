<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrailModel extends Model
{
    use HasFactory;
    protected $table = 'trails';
    protected $fillable = [
        'user_id',
        'admin_id',
        'activity',
        'created_at',
        'updated_at'
    ];
    public function admin()
    {
        return $this->belongsTo(AdminModel::class, 'admin_id', 'id');
    }
    public function client()
    {
        return $this->belongsTo(ClientModel::class, 'user_id', 'id');
    }
}
