<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    use HasFactory;
    protected $table = 'roles';
    protected $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];
    public function clients()
    {
        return $this->hasMany(ClientModel::class);
    }
    public function admin()
    {
        return $this->hasMany(AdminModel::class);
    }
}
