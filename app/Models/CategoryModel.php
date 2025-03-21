<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = [
        'sub_category_id',
        'name',
        'description',
        'created_at',
        'updated_at'
    ];
    public function items()
    {
        return $this->hasMany(ItemModel::class);
    }
    public function subCategory()
    {
        return $this->belongsTo(SubCategoryModel::class, 'sub_category_id'); // Foreign key is sub_category_id
    }
}    
