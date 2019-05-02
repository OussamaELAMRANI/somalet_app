<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use SoftDeletes;
    protected $fillable =['name','color'];

    public $timestamps = false;

    function products()
    {
        return $this->hasMany(Product::class, 'color_id');
    }
}
