<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    public $timestamps = false;

    function products()
    {
        return $this->hasMany(Product::class, 'unit_id');
    }
}
