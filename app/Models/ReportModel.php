<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportModel extends Model
{
    use HasFactory;
    protected $table = 'reports';
    protected $fillable = [
        'admin_id',
        'report_type',
        'control_number',
        'report_file',
        'created_at',
        'updated_at'
    ];
    public function admin()
    {
        return $this->belongsTo(AdminModel::class);
    }
}
