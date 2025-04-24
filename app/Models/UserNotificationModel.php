<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotificationModel extends Model
{
    use HasFactory;
    protected $table = 'user_notifications';
    protected $fillable = [
        'user_id',
        'admin_id',
        'message',
        'control_number',
        'status',
        'created_at',
        'updated_at'
    ];
    public function client()
    {
        return $this->belongsTo(ClientModel::class, 'user_id', 'id');
    }
    public function admin(){
        return $this->belongsTo(AdminModel::class, 'admin_id', 'id');
    }
}
