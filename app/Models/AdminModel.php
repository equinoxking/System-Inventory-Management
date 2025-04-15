<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminModel extends Model
{
    use HasFactory;
    protected $table = 'admins';
    protected $fillable = [
        'role_id',
        'full_name',
        'position',
        'created_at',
        'updated_at'
    ];
    public function role()
    {
        return $this->belongsTo(RoleModel::class);
    }
    public function reports()
    {
        return $this->hasMany(ReportModel::class, 'admin_id', 'id');
    }
    public function transaction()
    {
        return $this->hasMany(TransactionModel::class, 'admin_id', 'id');
    }
        public function adminBy()
    {
        return $this->hasMany(TransactionModel::class, 'released_by', 'id');
    }

}
