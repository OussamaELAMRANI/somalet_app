<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    use SoftDeletes;
    protected $hidden = ['created_at', 'deleted_at', 'updated_at'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
