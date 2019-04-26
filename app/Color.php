<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    public $timestamps = false;

    function products()
    {
        return $this->hasMany(Product::class, 'color_id');
    }
}
