<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable =['name','color'];

    public $timestamps = false;

    function products()
    {
        return $this->hasMany(Product::class, 'color_id');
    }
}
