<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class ClientModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'clients';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'department',
        'username',
        'password',
        'role',
        'status',
        'created_at',
        'updated_at'
    ];
    public function role()
    {
        return $this->belongsTo(RoleModel::class);
    }
}
