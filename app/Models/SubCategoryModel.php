<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategoryModel extends Model
{
    use HasFactory;
    protected $table = 'sub_categories';
    protected $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];
    public function categories()
    {
        return $this->hasMany(CategoryModel::class);
    }
}
