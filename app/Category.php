<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $hidden = ['created_at', 'deleted_at', 'updated_at'];
//    public $timestamps = false;

    protected $fillable = ['category_name'];

    function sub_categories()
    {
        return $this->hasMany(SubCategory::class, 'category_id');
    }
}
