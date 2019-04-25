<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $hidden = ['created_at', 'deleted_at', 'updated_at'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
