<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationModel extends Model
{
    use HasFactory;
    protected $table = 'notifications';
    protected $fillable = [
        'user_id',
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
}
