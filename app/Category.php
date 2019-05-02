<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $hidden = ['created_at', 'deleted_at', 'updated_at'];
//    public $timestamps = false;

    protected $fillable = ['category_name'];

    function sub_categories()
    {
        return $this->hasMany(SubCategory::class, 'category_id');
    }
}
