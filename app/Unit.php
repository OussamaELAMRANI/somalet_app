<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable =['name'];

    public $timestamps = false;

    function products()
    {
        return $this->hasMany(Product::class, 'unit_id');
    }
}
